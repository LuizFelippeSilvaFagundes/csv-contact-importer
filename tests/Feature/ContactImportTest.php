<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ContactImportTest extends TestCase
{
	use RefreshDatabase;

	public function test_imports_contacts_and_returns_summary(): void
	{
		$csv = "name,email,phone,birthdate\n" .
			"Alice,alice@example.com,123,1990-01-01\n" .
			"Bob,bob@example.com,456,01/02/1991\n" .
			"Invalid,invalid-email,789,1992-03-04\n" .
			"Alice Dup,alice@example.com,000,1990-01-01\n";

		$file = UploadedFile::fake()->createWithContent('contacts.csv', $csv);
		
		$response = $this->post('/contacts/import', [ 'file' => $file ]);
		$response->assertStatus(200);
		$response->assertJsonStructure(['summary' => [
			'total_rows', 'successfully_imported', 'ignored_duplicates', 'ignored_invalid'
		]]);

		$summary = $response->json('summary');
		$this->assertSame(4, $summary['total_rows']);
		$this->assertSame(2, $summary['successfully_imported']);
		$this->assertSame(1, $summary['ignored_duplicates']);
		$this->assertSame(1, $summary['ignored_invalid']);

		$this->assertDatabaseHas('contacts', ['email' => 'alice@example.com']);
		$this->assertDatabaseHas('contacts', ['email' => 'bob@example.com']);
	}
}
