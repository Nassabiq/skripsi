<template>
	<div class="gap-4 space-y-4 margin-auth">
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
						<button class="btn-with-icon btn btn-blue btn-sm" @click="modalTambahStok = !modalTambahStok">
							<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
							</svg>
							Tambah Stok
						</button>
					</div>
				</div>
			</div>

			<div class="relative overflow-x-auto overflow-y-auto bg-white rounded-lg shadow">
				<table class="table table-auto table-produk">
					<thead class="bg-gray-100">
						<tr class="text-left text-gray-800 font-title">
							<th class="p-3">Id Barang Masuk</th>
							<th class="p-3">Tgl Transaksi</th>
							<th class="p-3">User</th>
							<th class="p-3">Jumlah Pesanan</th>
							<th class="p-3">Total Pesanan</th>
							<th class="p-1"></th>
						</tr>
					</thead>
					<tbody class="divide-y-2 divide-gray-100 divide-dotted">
						<tr class="text-sm">
							<td class="w-32 p-3"></td>
							<td class="p-3">Malcolm Lockyer</td>
							<td class="p-3">1961</td>
							<td class="p-3">1961</td>
							<td class="p-3">1961</td>
							<td class="p-2">
								<button class="p-1 text-blue-400 border border-blue-600 rounded-full focus:bg-blue-100 hover:text-blue-800" @click.prevent="showRow">
									<svg xmlns="http://www.w3.org/2000/svg" :class="content ? 'rotate-90 duration-300' : 'rotate-0 duration-300'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
									</svg>
								</button>
							</td>
						</tr>
						<tr>
							<td class="p-3 bg-slate-50" colspan="6" v-show="content">
								<div class="px-4 pb-2 font-semibold">
									Detail Barang Masuk
								</div>
								<div class="grid grid-cols-2">
									<div class="col-span-2 md:col-span-1">
										<div class="border-t border-gray-200">
											<dl>
												<div class="px-3 py-4 bg-white sm:grid sm:grid-cols-3 sm:gap-4">
													<dt class="text-xs font-semibold">Id Detail Barang Masuk</dt>
													<dd class="mt-1 text-xs sm:mt-0 sm:col-span-2">PJJ-000011111</dd>
												</div>
												<div class="px-3 py-4 bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4">
													<dt class="text-xs font-semibold">Nama Bahan Baku</dt>
													<dd class="mt-1 text-xs sm:mt-0 sm:col-span-2">Jagung</dd>
												</div>
											</dl>
										</div>
									</div>
									<div class="col-span-2 md:col-span-1">
										<div class="border-t border-gray-200">
											<dl>
												<div class="px-3 py-4 bg-white sm:grid sm:grid-cols-3 sm:gap-4">
													<dt class="text-xs font-semibold">QTY</dt>
													<dd class="mt-1 text-xs sm:mt-0 sm:col-span-2">2 Pcs</dd>
												</div>
												<div class="px-3 py-4 bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4">
													<dt class="text-xs font-semibold">Harga</dt>
													<dd class="mt-1 text-xs sm:mt-0 sm:col-span-2">25.000</dd>
												</div>
											</dl>
										</div>
									</div>
									<div class="px-5 py-6 bg-sky-100 col-span-2 flex md:flex-row flex-col justify-between">
										<dt class="text-sm font-semibold">Subtotal</dt>
										<dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">Rp. 500.000</dd>
									</div>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="grid grid-cols-2 gap-4">
			<div class="col-span-2 card md:col-span-1">
				<div class="my-4">
					<button class="btn-with-icon btn btn-blue btn-sm" @click="modalBahanBaku = !modalBahanBaku">
						<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
						</svg>
						Tambah Data Bahan Baku
					</button>
				</div>
				<div class="my-4 overflow-x-auto overflow-y-auto bg-white rounded-lg shadow">
					<table class="table table-auto table-produk">
						<thead class="bg-gray-100">
							<tr class="text-left text-gray-800 font-title">
								<th class="p-3">Nama Bahan Baku</th>
								<th class="p-3">Satuan</th>
								<th class="p-3">Stok</th>
							</tr>
						</thead>
						<tbody class="divide-y-2 divide-gray-100 divide-dotted">
							<tr class="text-sm">
								<td class="p-3">Malcolm Lockyer</td>
								<td class="p-3">1961</td>
								<td class="p-3">1961</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-span-2 md:col-span-1"></div>
		</div>
		<Modal size="max-w-2xl" title="Tambah Stok" @close-modal="modalTambahStok = false" v-show="modalTambahStok">
			<template #content>
				<div class="col-span-12 sm:col-span-5">
					<label class="label">Nama Bahan Baku</label>
					<select class="form-input form-input-lg">
						<option value="">Baju</option>
						<option value="">Celan</option>
						<option value="">Dll</option>
					</select>
				</div>
				<div class="col-span-12 sm:col-span-4">
					<label class="label">Harga</label>
					<input class="form-input form-input-lg" placeholder="Rp. ....." />
				</div>
				<div class="col-span-12 sm:col-span-3">
					<label class="label">Qty</label>
					<div class="flex gap-2">
						<input type="text" class="form-input form-input-lg" placeholder="Qty" />
						<button class="btn btn-sm btn-indigo mt-1">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
							</svg>
						</button>
					</div>
				</div>
				<div class="rounded-md shadow-md px-5 py-3 bg-lime-100 col-span-12 flex md:flex-row flex-col justify-between">
					<dt class="text-sm font-semibold">Subtotal</dt>
					<dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">Rp. 500.000</dd>
				</div>
			</template>
			<template #submit>
				<button class="btn btn-lg btn-green">
					Submit
				</button>
			</template>
		</Modal>
		<Modal size="max-w-lg" title="Tambah Data Bahan Baku" @close-modal="modalBahanBaku = false" v-show="modalBahanBaku">
			<template #content>
				<div class="col-span-12 sm:col-span-9">
					<label class="label">Nama Bahan Baku</label>
					<input type="text" class="form-input form-input-lg" placeholder="Nama Bahan Baku ... " />
				</div>
				<div class="col-span-12 sm:col-span-3">
					<label class="label">Satuan</label>
					<select class="form-input form-input-lg">
						<option value="">Cm</option>
						<option value="">Pcs</option>
						<option value="">Rim</option>
					</select>
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
	name: "KelolaPersediaan",
	layout: "auth",
	components: {Modal},
	auth: false,
	data() {
		return {
			content: false,
			modalTambahStok: false,
			modalBahanBaku: false,
			// user : this.$auth.user
		};
	},
	methods: {
		showRow() {
			this.content = !this.content;
		},
	},
};
</script>
