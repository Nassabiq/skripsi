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
				<div class="flex flex-col col-span-12 mb-2 space-y-2 sm:space-y-0 sm:space-x-2 sm:flex-row md:justify-end md:mb-0 md:col-span-6">
					<div class="flex flex-col">
						<label class="text-xs text-gray-700">Tampilkan data dari:</label>
						<select class="px-2 py-2 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg shadow focus:outline-2 focus:outline-blue-100 focus:ring-2 focus:ring-blue-300">
							<option>Data Produk</option>
							<option>Data Kategori Produk</option>
						</select>
					</div>
					<div class="flex flex-col">
						<label class="text-xs text-gray-700">sampai : </label>
						<select class="px-2 py-2 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg shadow focus:outline-2 focus:outline-blue-100 focus:ring-2 focus:ring-blue-300">
							<option>Data Produk</option>
							<option>Data Kategori Produk</option>
						</select>
					</div>
				</div>
			</div>

			<div class="overflow-x-auto overflow-y-hidden bg-white rounded-lg shadow">
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
			<div class="grid grid-cols-12 gap-4">
				<div class="flex flex-col items-center justify-between col-span-12 px-4 py-2 mx-2 mt-4 bg-white border-2 rounded-lg shadow sm:flex-row border-slate-100 md:col-span-10">
					<p class="font-semibold">Total Pengeluaran</p>
					<p class="font-semibold">Rp. 30.000</p>
				</div>
				<div class="flex justify-end col-span-12 py-2 mx-2 md:mt-4 md:px-4 md:justify-start md:col-span-2">
					<button class="px-4 py-2 text-white bg-green-500 rounded-lg shadow-md btn-with-icon hover:bg-green-600">
						<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
						</svg>
						Print
					</button>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	name: "LaporanPersediaan",
	layout: "auth",
	auth: false,
	data() {
		return {
			content: false,
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
