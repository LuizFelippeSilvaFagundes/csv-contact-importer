<template>
	<div class="mx-auto max-w-4xl p-6">
		<h1 class="text-2xl font-semibold mb-4">CSV Contact Importer</h1>

		<div class="rounded-sm border p-4 mb-6 bg-white">
			<form @submit.prevent="onSubmit" class="flex items-center gap-3">
				<input type="file" accept=".csv,text/csv" @change="onFileChange" />
				<button type="submit" :disabled="!file || loading" class="px-4 py-2 bg-black text-white rounded-sm">
					{{ loading ? 'Uploading…' : 'Import CSV' }}
				</button>
			</form>
			<p v-if="error" class="text-red-600 mt-2">{{ error }}</p>
			<div v-if="summary" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
				<div class="p-3 border rounded-sm">
					<div class="text-gray-500">Total rows</div>
					<div class="text-lg font-medium">{{ summary.total_rows }}</div>
				</div>
				<div class="p-3 border rounded-sm">
					<div class="text-gray-500">Imported</div>
					<div class="text-lg font-medium">{{ summary.successfully_imported }}</div>
				</div>
				<div class="p-3 border rounded-sm">
					<div class="text-gray-500">Duplicates</div>
					<div class="text-lg font-medium">{{ summary.ignored_duplicates }}</div>
				</div>
				<div class="p-3 border rounded-sm">
					<div class="text-gray-500">Invalid</div>
					<div class="text-lg font-medium">{{ summary.ignored_invalid }}</div>
				</div>
			</div>
		</div>

		<div class="rounded-sm border p-4 bg-white">
			<div class="flex items-center justify-between mb-3">
				<h2 class="text-xl font-semibold">Contacts</h2>
				<select v-model.number="perPage" class="border p-1 rounded-sm" @change="fetchContacts(1)">
					<option :value="10">10</option>
					<option :value="25">25</option>
					<option :value="50">50</option>
				</select>
			</div>
			<table class="w-full text-sm border-collapse">
				<thead>
					<tr class="text-left border-b">
						<th class="py-2">Name</th>
						<th class="py-2">Email</th>
						<th class="py-2">Phone</th>
						<th class="py-2">Birthdate</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="c in contacts.data" :key="c.id" class="border-b">
						<td class="py-2">{{ c.name }}</td>
						<td class="py-2">{{ c.email }}</td>
						<td class="py-2">{{ c.phone ?? '-' }}</td>
						<td class="py-2">{{ c.birthdate ?? '-' }}</td>
					</tr>
					<tr v-if="!contacts.data.length">
						<td colspan="4" class="py-4 text-center text-gray-500">No contacts yet</td>
					</tr>
				</tbody>
			</table>
			<div class="flex items-center justify-between mt-3 text-sm">
				<div>
					Page {{ contacts.current_page }} of {{ contacts.last_page }} — {{ contacts.total }} total
				</div>
				<div class="space-x-2">
					<button class="px-3 py-1 border rounded-sm" :disabled="contacts.current_page<=1" @click="fetchContacts(contacts.current_page-1)">Prev</button>
					<button class="px-3 py-1 border rounded-sm" :disabled="contacts.current_page>=contacts.last_page" @click="fetchContacts(contacts.current_page+1)">Next</button>
				</div>
			</div>
		</div>
	</div>
</template>

<script setup>
import axios from 'axios';
import { reactive, ref, onMounted } from 'vue';

const file = ref(null);
const loading = ref(false);
const error = ref('');
const summary = ref(null);
const perPage = ref(10);

const contacts = reactive({ data: [], current_page: 1, last_page: 1, total: 0 });

function onFileChange(e) {
	const f = e.target.files?.[0];
	file.value = f || null;
	error.value = '';
}

async function onSubmit() {
	if (!file.value) return;
	loading.value = true;
	error.value = '';
	try {
		const form = new FormData();
		form.append('file', file.value);
		const resp = await axios.post('/contacts/import', form, {
			headers: { 'Content-Type': 'multipart/form-data' },
		});
		summary.value = resp.data.summary;
		await fetchContacts(1);
	} catch (e) {
		error.value = e?.response?.data?.message || 'Upload failed';
	} finally {
		loading.value = false;
	}
}

async function fetchContacts(page = 1) {
	const resp = await axios.get('/contacts/list', { params: { page, per_page: perPage.value } });
	Object.assign(contacts, resp.data);
}

onMounted(() => {
	fetchContacts(1);
});
</script>

<style scoped>
</style> 