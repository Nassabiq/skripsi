<template>
	<div>
		<client-only>
			<button class="fixed bottom-0 right-0 m-5 btn btn-lg btn-with-icon btn-indigo" @click.prevent="generateReport">
				<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
					<path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
				</svg>
				Download
			</button>
			<vue-html2pdf :show-layout="true" :float-layout="false" :enable-download="true" :preview-modal="false" :paginate-elements-by-height="1000" :pdf-quality="2" :manual-pagination="false" pdf-format="a4" :margin="[0, 2]" pdf-orientation="portrait" pdf-content-width="794px" ref="html2Pdf" :filename="filename">
				<!-- @progress="onProgress($event)" @hasStartedGeneration="hasStartedGeneration()" @hasGenerated="hasGenerated($event)" -->
				<section slot="pdf-content" class="bg-white">
					<div class="grid grid-cols-12 p-4">
						<div class="col-span-12">
							<p class="flex justify-center my-4 text-2xl font-semibold text-cemter">Laporan Omset</p>
						</div>
						<div class="col-span-6">
							<div class="px-2 py-3 bg-white sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
								<dt class="text-xs font-medium text-gray-500">Dari</dt>
								<dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">: {{ $moment(dateStart).format("DD MMMM YYYY") }}</dd>
							</div>
						</div>
						<div class="col-span-6">
							<div class="px-2 py-3 bg-white sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
								<dt class="text-xs font-medium text-gray-500">Sampai</dt>
								<dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">: {{ $moment(dateEnd).format("DD MMMM YYYY") }}</dd>
							</div>
						</div>
						<div class="col-span-12 px-4 mt-6">
							<div class="overflow-x-auto overflow-y-auto bg-white border-2 rounded-lg shadow-md border-slate-400">
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
								</div>
							</div>
						</div>
					</div>
				</section>
			</vue-html2pdf>
		</client-only>
	</div>
</template>

<script>
export default {
	middleware: "role/multiple-manager",
	data() {
		return {
			dateStart: this.$route.query.dateStart,
			dateEnd: this.$route.query.dateEnd,

			reports: [],
			total_omset: 0,
			total_hpp: 0,

			filename: "Laporan-Omset-Bulan-" + this.$moment(this.dateEnd).format("MMMM-YYYY"),
		};
	},
	created() {
		this.getData();
	},
	methods: {
		async getData() {
			const data = await this.$axios.$get("/api/laporan/omset?from=" + this.dateStart + "&to=" + this.dateEnd);
			this.reports = data.reports;
			this.total_omset = data.total_omset;
			this.total_hpp = data.total_hpp;
		},
		nilai_hpp(data) {
			return data.length > 0 ? data[data.length - 1].nilai_hpp : 0;
		},
		pendapatan_per_item(data) {
			const result = data.reduce((accumulator, currentValue) => accumulator + currentValue.transaksi.total_harga, 0);
			return result;
		},

		generateReport() {
			this.$refs.html2Pdf.generatePdf();
		},
	},
};
</script>

<style></style>
