<template>
	<div class="gap-4 space-y-4 margin-auth">
		<div class="card">
			<div class="grid grid-cols-12 gap-4">
				<div class="relative col-span-12 my-4 md:col-span-6">
					<input type="search" class="w-full py-2 pl-8 pr-4 text-sm font-medium text-gray-800 border border-gray-200 rounded-lg shadow md:w-3/5 focus:outline-2 focus:outline-blue-100 focus:ring-2 focus:ring-blue-300" placeholder="Search..." />
					<div class="absolute top-0 left-0 inline-flex items-center p-2">
						<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
							<rect x="0" y="0" width="24" height="24" stroke="none"></rect>
							<circle cx="10" cy="10" r="7" />
							<line x1="21" y1="21" x2="15" y2="15" />
						</svg>
					</div>
				</div>
				<div class="flex items-center justify-between col-span-12 mb-2 space-x-2 md:justify-end md:mb-0 md:col-span-6">
					<div class="">
						<button class="btn-with-icon btn btn-blue">
							<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
							</svg>
							Tambah Bahan Baku
						</button>
					</div>
				</div>
			</div>

			<div class="relative overflow-x-auto overflow-y-auto bg-white rounded-lg shadow">
				<table class="table table-auto table-produk">
					<thead class="bg-gray-100">
						<tr class="text-left text-gray-800 font-title">
							<th class="p-3">Nama Bahan Baku</th>
							<th class="p-3">Satuan</th>
							<th class="p-3">Stok</th>
							<th class="p-1"></th>
						</tr>
					</thead>
					<tbody class="divide-y-2 divide-gray-100 divide-dotted">
						<tr class="text-sm">
							<td class="p-3">Malcolm Lockyer</td>
							<td class="p-3">1961</td>
							<td class="p-3">1961</td>
							<td class="p-1">
								<button class="p-1 text-blue-400 border border-blue-600 rounded-full focus:bg-blue-100 hover:text-blue-800" @click.prevent="showRow">
									<svg xmlns="http://www.w3.org/2000/svg" :class="content ? 'rotate-90 duration-300' : 'rotate-0 duration-300'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
									</svg>
								</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="grid grid-cols-2 gap-4">
			<div class="col-span-2 card md:col-span-1">
				<div class="my-4">
					<button class="btn-with-icon btn btn-blue">
						<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
						</svg>
						Data Barang Masuk
					</button>
				</div>
				<div class="my-4 overflow-x-auto overflow-y-auto bg-white rounded-lg shadow">
					<table class="table table-auto table-kategori-produk">
						<thead class="bg-gray-100">
							<tr class="text-left text-gray-800 font-title">
								<th class="p-3">No</th>
								<th class="p-3">Nama Kategori</th>
								<th class="p-3">Satuan</th>
							</tr>
						</thead>
						<tbody class="divide-y-2 divide-gray-100 divide-dotted">
							<tr class="text-sm">
								<td class="p-3">1.</td>
								<td class="p-3">Malcolm Lockyer</td>
								<td class="p-3">1961</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-span-2 card md:col-span-1">
				<div class="my-4">
					<button class="btn-with-icon btn btn-blue">
						<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
						</svg>
						Tambah Jenis Satuan
					</button>
				</div>
				<div class="my-4 overflow-x-auto overflow-y-auto bg-white rounded-lg shadow">
					<table class="table table-auto table-kategori-produk">
						<thead class="bg-gray-100">
							<tr class="text-left text-gray-800 font-title">
								<th class="p-3">No.</th>
								<th class="p-3">Nama Satuan</th>
							</tr>
						</thead>
						<tbody class="divide-y-2 divide-gray-100 divide-dotted">
							<tr class="text-sm">
								<td class="p-3">1.</td>
								<td class="p-3">Malcolm Lockyer</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	name: "KelolaPersediaan",
	layout: "auth",
	auth: false,
	data() {
		return {};
	},
	methods: {},
};
</script>
