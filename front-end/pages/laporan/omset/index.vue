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
			<div class="my-4 overflow-x-auto overflow-y-hidden">
				<div v-for="data in reports" class="space-y-4">
					<p class="px-3 py-2 text-base font-bold text-gray-800 border-gray-300 border-y">
						{{ data.nama_produk }}
					</p>
					<ul v-for="item in data.stok" class="list-inside">
						<li class="px-3 py-2 ml-5 text-sm font-semibold text-gray-800">{{ item.bahan_baku.nama_bahan_baku }}</li>
						<li class="flex justify-between px-3 py-2 ml-10 text-sm text-gray-700">
							<span>Omset Pendapatan = </span>
							Rp. {{ Intl.NumberFormat().format(pendapatan_per_item(item.transaksi)) }}
						</li>
						<li class="px-3 py-2 ml-10 text-sm text-gray-700">
							<div class="flex justify-between">
								<span>Nilai HPP Terakhir = </span>
								Rp. {{ Intl.NumberFormat().format(nilai_hpp(item.hpp)) }}
							</div>
							<NuxtLink to="/penjualan/analisis-hpp">
								<span v-if="nilai_hpp(item.hpp) == 0" class="text-xs text-blue-500 hover:text-blue-600"> tambahkan analisis hpp disini </span>
							</NuxtLink>
						</li>
						<li class="flex justify-between px-3 py-2 ml-10 font-semibold text-gray-700">
							<span>Total Pendapatan = </span>
							Rp. {{ Intl.NumberFormat().format(pendapatan_per_item(item.transaksi) - nilai_hpp(item.hpp)) }}
						</li>
					</ul>
				</div>
				<div class="px-3 py-4 mt-4 bg-white border border-green-500 rounded-lg" v-if="reports && reports.length > 0">
					<ul>
						<li class="flex justify-between px-3 py-2 text-sm font-semibold text-gray-800">
							<span>Total Omset Keseluruhan</span>
							Rp. {{ Intl.NumberFormat().format(total_omset) }}
						</li>
						<li class="flex justify-between px-3 py-2 text-sm font-semibold text-gray-800">
							<span>Total Nilai HPP</span>
							Rp. {{ Intl.NumberFormat().format(total_hpp) }}
						</li>
						<li class="flex justify-between px-3 py-2 font-semibold text-gray-700">
							<span>Total Pendapatan Bersih = </span>
							Rp. {{ Intl.NumberFormat().format(total_omset - total_hpp) }}
						</li>
					</ul>
					<div class="flex justify-end py-2 mx-2 md:mt-4 md:px-4">
						<!-- :to="{name: 'laporan-omset-print', query: {dateStart: this.dateStart, dateEnd: this.dateEnd}}" -->
						<NuxtLink target="_blank" :to="{name: 'laporan-omset-print', query: {dateStart: this.dateStart, dateEnd: this.dateEnd}}">
							<button class="px-4 py-2 text-white bg-green-500 rounded-lg shadow-md btn-with-icon hover:bg-green-600">
								<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
								</svg>
								Print
							</button>
						</NuxtLink>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	name: "LaporanOmset",
	layout: "auth",
	middleware: "role/multiple-manager",
	data() {
		return {
			dateStart: null,
			dateEnd: null,

			reports: [],
			total_omset: 0,
			total_hpp: 0,
		};
	},
	created() {
		this.getReports();
	},
	computed: {},
	methods: {
		async getProduk() {
			const produk = await this.$axios.$get("api/produk");
			this.produk = produk;
		},
		async getReports() {
			const data = await this.$axios.$get("/api/laporan/omset?from=" + this.dateStart + "&to=" + this.dateEnd);
			this.reports = data.reports;
			this.total_omset = data.total_omset;
			this.total_hpp = data.total_hpp;
		},
		getDataInRange() {
			this.getReports();
		},
		nilai_hpp(data) {
			return data.length > 0 ? data[data.length - 1].nilai_hpp : 0;
		},
		pendapatan_per_item(data) {
			const result = data.reduce((accumulator, currentValue) => accumulator + currentValue.transaksi.total_harga, 0);
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
