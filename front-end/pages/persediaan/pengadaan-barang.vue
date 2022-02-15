<template>
	<div class="margin-auth">
		<div class="card">
			<div class="grid grid-cols-12 gap-4">
				<div class="relative col-span-12 my-4 md:col-span-6">
					<input type="search" class="w-full py-2 pl-8 pr-4 text-sm font-medium text-gray-800 border border-gray-200 rounded-lg shadow md:w-3/5 focus:outline-2 focus:outline-blue-100 focus:ring-2 focus:ring-blue-300" placeholder="Search..." />
					<div class="absolute top-0 left-0 inline-flex items-center px-2 py-3">
						<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
							<rect x="0" y="0" width="24" height="24" stroke="none"></rect>
							<circle cx="10" cy="10" r="7" />
							<line x1="21" y1="21" x2="15" y2="15" />
						</svg>
					</div>
				</div>
				<div class="flex items-center justify-between col-span-12 mb-2 space-x-2 md:justify-end md:mb-0 md:col-span-6">
					<div class="">
						<select class="px-2 py-2 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg shadow focus:outline-2 focus:outline-blue-100 focus:ring-2 focus:ring-blue-300">
							<option>Status Pengadaan</option>
							<option>Belum Disetujui</option>
							<option>Disetujui</option>
						</select>
					</div>
					<div class="">
						<button class="btn-with-icon btn btn-blue btn-sm" @click="modalPengadaan = !modalPengadaan">
							<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
							</svg>
							Buat Pengadaan
						</button>
					</div>
				</div>
			</div>

			<div class="overflow-x-auto overflow-y-auto bg-white rounded-lg shadow">
				<table class="table table-auto table-produk">
					<thead class="bg-gray-100">
						<tr class="text-left text-gray-800 font-title">
							<th class="p-3">No. Pengadaan</th>
							<th class="p-3">Nama Bahan Baku</th>
							<th class="p-3">Jumlah</th>
							<th class="p-3">Status</th>
							<th class="p-3">Tgl Dibuat</th>
							<th class="p-1"></th>
						</tr>
					</thead>
					<tbody class="divide-y-2 divide-gray-100 divide-dotted">
						<tr class="text-sm">
							<td class="p-3"></td>
							<td class="p-3">Malcolm Lockyer</td>
							<td class="p-3">1212</td>
							<td class="p-3">
								<span class="px-2 py-1 text-xs text-gray-800 bg-yellow-200 border-2 border-yellow-500 rounded-full">Belum disetujui</span>
							</td>
							<td class="p-3">1961</td>
							<td class="w-48 p-1 py-3">
								<div class="flex flex-wrap gap-2">
									<button class="btn btn-sm btn-with-icon btn-green">
										<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
											<path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
											<path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
										</svg>
										<span>Edit</span>
									</button>
									<button class="btn btn-sm btn-with-icon btn-red">
										<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
											<path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
										</svg>
										<span>Delete</span>
									</button>
									<button class="btn btn-sm btn-with-icon btn-indigo">
										<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
											<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
										</svg>
										<span>Validasi</span>
									</button>
									<button class="btn btn-sm btn-with-icon btn-teal">
										<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
											<path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
										</svg>
										<span>Print</span>
									</button>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<Modal size="max-w-xl" title="Buat Pengadaan Barang" @close-modal="modalPengadaan = false" v-show="modalPengadaan">
			<template #content>
				<div class="col-span-12 sm:col-span-8">
					<label class="label">Nama Bahan Baku</label>
					<select class="form-input form-input-lg">
						<option value="">Baju</option>
						<option value="">Celan</option>
						<option value="">Dll</option>
					</select>
				</div>
				<div class="col-span-12 sm:col-span-4">
					<label class="label">Qty</label>
					<div class="flex gap-2">
						<input type="text" class="form-input form-input-lg" placeholder="Qty" />
						<button class="btn btn-sm btn-red mt-1">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
							</svg>
						</button>
					</div>
				</div>
			</template>
			<template #submit>
				<button class="btn btn-lg btn-green">
					Submit
				</button>
			</template>
		</Modal>
	</div>
</template>

<script>
import Modal from "../../components/ModalComponent.vue";
export default {
	name: "PengadaanBarang",
	layout: "auth",
	auth: false,
	components: {Modal},
	data() {
		return {
			modalPengadaan: false,
		};
	},
	methods: {},
};
</script>
