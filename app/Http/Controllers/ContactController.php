<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ContactController extends Controller
{
	public function index()
	{
		return view('contacts');
	}

	public function list(Request $request)
	{
		$perPage = (int) $request->integer('per_page', 10);
		$contacts = Contact::query()
			->orderByDesc('id')
			->paginate($perPage);

		return response()->json($contacts);
	}

	public function import(Request $request)
	{
		$request->validate([
			'file' => ['required', 'file', 'mimes:csv,txt'],
		]);

		/** @var UploadedFile $file */
		$file = $request->file('file');

		$summary = [
			'total_rows' => 0,
			'successfully_imported' => 0,
			'ignored_duplicates' => 0,
			'ignored_invalid' => 0,
		];

		$handle = fopen($file->getRealPath(), 'r');
		if ($handle === false) {
			return response()->json(['message' => 'Unable to open uploaded file'], 422);
		}

		// Handle BOM
		$firstLine = fgets($handle);
		if ($firstLine === false) {
			fclose($handle);
			return response()->json(['message' => 'Empty file'], 422);
		}
		$firstLine = ltrim($firstLine, "\xEF\xBB\xBF");
		rewind($handle);

		// Try to detect delimiter by reading first line
		$firstLine = fgets($handle);
		rewind($handle);
		
		$delimiter = ',';
		if (strpos($firstLine, ';') !== false) {
			$delimiter = ';';
		}
		
		$header = fgetcsv($handle, 0, $delimiter);
		if (!$header) {
			fclose($handle);
			return response()->json(['message' => 'Invalid CSV header'], 422);
		}

		$headerMap = $this->buildHeaderMap($header);
		if (!isset($headerMap['name'], $headerMap['email'], $headerMap['phone'], $headerMap['birthdate'])) {
			fclose($handle);
			return response()->json(['message' => 'CSV must include name, email, phone, birthdate columns'], 422);
		}

		$rows = [];
		$fileSeenEmails = [];

		while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
			if ($this->isRowEmpty($row)) {
				continue;
			}
			$summary['total_rows']++;

			$name = trim((string)($row[$headerMap['name']] ?? ''));
			$email = strtolower(trim((string)($row[$headerMap['email']] ?? '')));
			$phone = trim((string)($row[$headerMap['phone']] ?? ''));
			$birthdateRaw = trim((string)($row[$headerMap['birthdate']] ?? ''));

			$isValid = $name !== '' && filter_var($email, FILTER_VALIDATE_EMAIL);
			$birthdate = null;
			if ($birthdateRaw !== '') {
				$birthdate = $this->parseDate($birthdateRaw);
				if ($birthdate === null) {
					$isValid = false;
				}
			}

			if (!$isValid) {
				$summary['ignored_invalid']++;
				continue;
			}

			if (isset($fileSeenEmails[$email])) {
				$summary['ignored_duplicates']++;
				continue;
			}
			$fileSeenEmails[$email] = true;

			$rows[] = [
				'name' => $name,
				'email' => $email,
				'phone' => $phone,
				'birthdate' => $birthdate?->format('Y-m-d'),
				'created_at' => now(),
				'updated_at' => now(),
			];
		}

		fclose($handle);

		if (count($rows) === 0) {
			return response()->json(['summary' => $summary, 'message' => 'No valid rows to import'], 200);
		}

		$emails = array_column($rows, 'email');
		$existingEmails = Contact::query()->whereIn('email', $emails)->pluck('email')->all();
		$existingSet = array_fill_keys(array_map('strtolower', $existingEmails), true);

		$toInsert = [];
		foreach ($rows as $row) {
			if (isset($existingSet[$row['email']])) {
				$summary['ignored_duplicates']++;
				continue;
			}
			$toInsert[] = $row;
		}

		if (count($toInsert) > 0) {
			// Chunk inserts for performance
			foreach (array_chunk($toInsert, 500) as $chunk) {
				try {
					Contact::insert($chunk);
					$summary['successfully_imported'] += count($chunk);
				} catch (\Throwable $e) {
					// In case of rare race conditions on unique index, recalc per row
					foreach ($chunk as $row) {
						try {
							Contact::create($row);
							$summary['successfully_imported']++;
						} catch (\Throwable $inner) {
							$summary['ignored_duplicates']++;
						}
					}
				}
			}
		}

		return response()->json(['summary' => $summary]);
	}

	private function buildHeaderMap(array $header): array
	{
		$map = [];
		foreach ($header as $index => $col) {
			$key = strtolower(trim((string)$col));
			if (in_array($key, ['name', 'full name', 'fullname'])) {
				$map['name'] = $index;
			} elseif (in_array($key, ['email', 'e-mail'])) {
				$map['email'] = $index;
			} elseif (in_array($key, ['phone', 'phone number', 'telephone'])) {
				$map['phone'] = $index;
			} elseif (in_array($key, ['birthdate', 'birth date', 'date of birth', 'dob', 'birthday'])) {
				$map['birthdate'] = $index;
			}
		}
		return $map;
	}

	private function parseDate(string $value): ?Carbon
	{
		$formats = ['Y-m-d', 'd/m/Y', 'd-m-Y', 'm/d/Y', 'm-d-Y', 'd.m.Y'];
		foreach ($formats as $format) {
			try {
				$dt = Carbon::createFromFormat($format, $value);
				if ($dt !== false) {
					return $dt;
				}
			} catch (\Throwable $e) {
				// ignore
			}
		}
		try {
			return Carbon::parse($value);
		} catch (\Throwable $e) {
			return null;
		}
	}

	private function isRowEmpty(array $row): bool
	{
		foreach ($row as $cell) {
			if (trim((string)$cell) !== '') {
				return false;
			}
		}
		return true;
	}
} 