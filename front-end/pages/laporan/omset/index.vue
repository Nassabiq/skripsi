<template>
	<div class="margin-auth">
		<div class="card">
			<div class="grid grid-cols-12 gap-4">
				<div class="relative col-span-12 my-4 md:col-span-6"></div>
				<div class="flex flex-col col-span-12 mb-2 space-y-2 sm:space-y-0 sm:space-x-2 sm:flex-row md:justify-end md:mb-0 md:col-span-6">
					<div class="flex flex-col">
						<label class="text-xs font-semibold text-gray-700">Tampilkan data dari:</label>
						<input type="date" class="px-6 py-2 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg shadow focus:outline-2 focus:outline-blue-100 focus:ring-2 focus:ring-blue-300" v-model="dateStart" @change="getDataInRange" />
					</div>
					<div class="flex flex-col">
						<label class="text-xs font-semibold text-gray-700">sampai : </label>
						<input type="date" class="px-6 py-2 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg shadow focus:outline-2 focus:outline-blue-100 focus:ring-2 focus:ring-blue-300" v-model="dateEnd" @change="getDataInRange" />
					</div>
				</div>
			</div>
			<div class="overflow-x-auto overflow-y-hidden bg-white rounded-lg shadow">
				<div v-for="data in reports">
					<p class="px-3 py-2 text-base font-bold text-gray-800 border-b border-gray-100">
						{{ data.nama_produk }}
					</p>
					<ul v-for="item in data.stok" class="list-inside">
						<li class="px-3 py-2 ml-5 text-sm font-semibold text-gray-800">{{ item.bahan_baku.nama_bahan_baku }}</li>
						<li class="px-3 py-2 ml-10 text-sm text-gray-700">Omset Pendapatan = Rp. {{ Intl.NumberFormat().format(pendapatan_per_item(item.transaksi)) }}</li>
						<li class="px-3 py-2 ml-10 text-sm text-gray-700" v-if="item.hpp.length > 0">Nilai HPP Terakhir = Rp. {{ Intl.NumberFormat().format(item.hpp[item.hpp.length - 1].nilai_hpp) }}</li>
						<li class="px-3 py-2 ml-10 text-sm text-gray-700">Rp. {{ Intl.NumberFormat().format(pendapatan_per_item(item.transaksi) - item.hpp[item.hpp.length - 1].nilai_hpp) }}</li>
					</ul>
				</div>
				<!-- <table class="table table-auto table-produk">
					<thead class="bg-gray-100">
						<tr class="text-left text-gray-800 font-title">
							<th class="p-3">Id Transaksi</th>
							<th class="p-3">Detail Transaksi</th>
							<th class="p-3">Total Pesanan</th>
						</tr>
					</thead>
					<tbody class="" v-if="transaksi && transaksi.length > 0">
						<tr class="text-sm border-b-2 border-gray-200" v-for="(data, index) in transaksi">
							<td class="p-3">{{ data.id_transaksi }}</td>
							<td class="p-3">
								<ul>
									<li v-for="detail in data.detail_transaksi">
										<div class="flex items-center justify-between p-3">
											<p>{{ detail.sku.produk.nama_produk }}</p>
											<p v-text="price(detail)"></p>
										</div>
									</li>
									<li v-for="detail in data.detail_transaksi" v-text="detail"></li>
								</ul>
							</td>
							<td class="p-3">Rp. {{ Intl.NumberFormat().format(data.total_harga) }}</td>
						</tr>
						<tr class="bg-gray-100">
							<td class="p-3 font-semibold" colspan="2">Total Pendapatan</td>
							<td class="p-3 font-semibold">Rp. {{ Intl.NumberFormat().format(total_transaksi) }}</td>
						</tr>
					</tbody>
					<tbody v-else-if="!dateStart || !dateEnd">
						<tr>
							<td colspan="3">
								<div class="flex items-center justify-center gap-4 py-20 font-semibold text-center">
									<svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
										<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
									</svg>
									<span class="text-2xl"> Choose Data First </span>
								</div>
							</td>
						</tr>
					</tbody>
					<tbody v-else>
						<tr>
							<td colspan="3">
								<div class="flex items-center justify-center gap-4 py-20 font-semibold text-center">
									<svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-red-500" viewBox="0 0 20 20" fill="currentColor">
										<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
									</svg>
									<span class="text-2xl"> Tidak Ada </span>
								</div>
							</td>
						</tr>
					</tbody>
				</table> -->
			</div>
		</div>
	</div>
</template>

<script>
export default {
	name: "LaporanOmset",
	layout: "auth",
	data() {
		return {
			dateStart: null,
			dateEnd: null,

			reports: [],
		};
	},
	created() {
		this.getReports();
	},
	computed: {
		// total_transaksi() {
		// 	const transaksi = this.transaksi;
		// 	const initValue = 0;
		// 	const result = transaksi.reduce((accumulator, value) => accumulator + value.total_harga, initValue);
		// 	return result;
		// },
	},
	methods: {
		async getProduk() {
			const produk = await this.$axios.$get("api/produk");
			this.produk = produk;
		},
		async getReports() {
			const reports = await this.$axios.$get("/api/laporan/omset?from=" + this.dateStart + "&to=" + this.dateEnd);
			this.reports = reports;
		},
		getDataInRange() {
			this.getReports();
		},
		pendapatan_per_item(data) {
			const result = data.reduce((accumulator, currentValue) => accumulator + currentValue.transaksi.total_harga, 0);
			// return result;
			return result;
		},
		price(data) {
			if (data.ukuran != null) {
				const ukuran = JSON.parse(data.ukuran);
				const result = data.subtotal / (ukuran.panjang * ukuran.lebar * data.qty_produk);
				return "Rp." + Intl.NumberFormat().format(result);
			} else return "Rp." + Intl.NumberFormat().format(data.subtotal / data.qty_produk);
		},
	},
};
</script>
