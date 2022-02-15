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
			</div>

			<div class="overflow-x-auto overflow-y-hidden bg-white rounded-lg shadow">
				<table class="table table-auto table-produk">
					<thead class="bg-gray-100">
						<tr class="text-left text-gray-800 font-title">
							<th class="p-3">Id Transaksi</th>
							<th class="p-3">Tgl Transaksi</th>
							<th class="p-3">Customer</th>
							<th class="p-3">Jumlah Pesanan</th>
							<th class="p-3">Total Pesanan</th>
							<th class="p-1"></th>
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
							<td class="p-3 w-48">
								<div class="flex flex-wrap gap-2">
									<button class="btn btn-with-icon btn-sm btn-indigo">
										<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
											<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
										</svg>
										<span>Proses</span>
									</button>
									<button class="btn btn-with-icon btn-sm btn-indigo">
										<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
											<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
										</svg>
										<span>Ubah Status</span>
									</button>
									<button class="btn btn-with-icon btn-sm btn-indigo">
										<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
											<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
										</svg>
										<span>Selesaikan</span>
									</button>
								</div>
							</td>
							<td class="p-2">
								<button class="p-1 text-blue-400 border border-blue-600 rounded-full focus:bg-blue-100 hover:text-blue-800" @click.prevent="showRow">
									<svg xmlns="http://www.w3.org/2000/svg" :class="content ? 'rotate-90 duration-300' : 'rotate-0 duration-300'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
									</svg>
								</button>
							</td>
						</tr>
						<tr>
							<td class="p-3 bg-sky-50" colspan="7" v-show="content">
								<div class="px-4 pb-2 font-semibold">
									Detail Transaksi
								</div>
								<div class="grid grid-cols-2">
									<div class="col-span-2 md:col-span-1">
										<div class="border-t border-gray-200">
											<dl>
												<div class="px-3 py-4 bg-white sm:grid sm:grid-cols-3 sm:gap-4">
													<dt class="text-xs font-semibold">Id Detail Transaksi</dt>
													<dd class="mt-1 text-xs sm:mt-0 sm:col-span-2">PJJ-000011111</dd>
												</div>
												<div class="px-3 py-4 bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4">
													<dt class="text-xs font-semibold">Nama Produk</dt>
													<dd class="mt-1 text-xs sm:mt-0 sm:col-span-2">Jagung</dd>
												</div>
												<div class="px-3 py-4 bg-white sm:grid sm:grid-cols-3 sm:gap-4">
													<dt class="text-xs font-semibold">QTY</dt>
													<dd class="mt-1 text-xs sm:mt-0 sm:col-span-2">2 Pcs</dd>
												</div>
												<div class="px-3 py-4 bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4">
													<dt class="text-xs font-semibold">Status</dt>
													<dd class="mt-1 text-xs sm:mt-0 sm:col-span-2">
														<span class="px-2 py-1 text-xs text-gray-800 bg-yellow-200 border-2 border-yellow-500 rounded-full">Belum disetujui</span>
													</dd>
												</div>
											</dl>
										</div>
									</div>
									<div class="col-span-2 md:col-span-1">
										<div class="border-t border-gray-200">
											<dl>
												<div class="px-3 py-4 bg-white sm:grid sm:grid-cols-3 sm:gap-4">
													<dt class="text-xs font-semibold">Ukuran</dt>
													<dd class="mt-1 text-xs sm:mt-0 sm:col-span-2">20x20</dd>
												</div>
												<div class="px-3 py-4 bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4">
													<dt class="text-xs font-semibold">Jenis Bahan</dt>
													<dd class="mt-1 text-xs sm:mt-0 sm:col-span-2">Kain</dd>
												</div>
												<div class="px-3 py-4 bg-white sm:grid sm:grid-cols-3 sm:gap-4">
													<dt class="text-xs font-semibold">Finishing</dt>
													<dd class="mt-1 text-xs sm:mt-0 sm:col-span-2">Abis</dd>
												</div>
												<div class="px-3 py-4 bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4">
													<dt class="text-xs font-semibold">Laminasi</dt>
													<dd class="mt-1 text-xs sm:mt-0 sm:col-span-2">-</dd>
												</div>
											</dl>
										</div>
									</div>
									<div class="col-span-2">
										<dl>
											<div class="px-5 py-6 bg-blue-100 sm:grid sm:grid-cols-3 sm:gap-4">
												<dt class="text-sm font-semibold">Subtotal</dt>
												<dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">Rp. 500.000</dd>
											</div>
										</dl>
									</div>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	name: "KelolaPemesanan",
	layout: "auth",
	auth: false,
	data() {
		return {
			content: false,
		};
	},
	methods: {
		showRow() {
			this.content = !this.content;
		},
	},
};
</script>
