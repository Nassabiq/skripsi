<template>
	<div>
		<client-only>
			<button class="fixed bottom-0 right-0 m-5 btn btn-lg btn-with-icon btn-indigo" @click.prevent="generateReport">
				<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
					<path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
				</svg>
				Download
			</button>
			<vue-html2pdf :show-layout="true" :float-layout="false" :enable-download="true" :preview-modal="false" :paginate-elements-by-height="794" :pdf-quality="2" :manual-pagination="false" pdf-format="a4" pdf-orientation="landscape" pdf-content-width="1123px" ref="html2Pdf" :filename="filename">
				<!-- @progress="onProgress($event)" @hasStartedGeneration="hasStartedGeneration()" @hasGenerated="hasGenerated($event)" -->
				<section slot="pdf-content" class="bg-white">
					<div class="grid grid-cols-12 p-4">
						<div class="col-span-12">
							<p class="flex justify-center my-4 text-2xl font-semibold text-cemter">Laporan Penjualan</p>
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
								<table class="table table-auto table-produk">
									<thead class="bg-gray-100">
										<tr class="text-left text-gray-800 font-title">
											<th class="p-3 text-sm border border-slate-300">Id Transaksi</th>
											<th class="p-3 text-sm border border-slate-300">Tgl Transaksi</th>
											<th class="p-3 text-sm border border-slate-300">Customer</th>
											<th class="p-3 text-sm border border-slate-300">Nama Barang</th>
											<th class="p-3 text-sm border border-slate-300">Qty</th>
											<th class="p-3 text-sm border border-slate-300">Total</th>
										</tr>
									</thead>
									<tbody>
										<template v-for="(data, index) in penjualan">
											<tr class="text-gray-800 font-title" v-for="item in data.detail_transaksi">
												<td class="p-3 text-sm border border-slate-300">{{ data.id_transaksi }}</td>
												<td class="p-3 text-sm border border-slate-300">{{ $moment(data.tgl_transaksi).format("DD MMMM YYYY") }}</td>
												<td class="p-3 text-sm border border-slate-300">{{ data.pelanggan.nama_pelanggan }}</td>
												<td class="p-3 text-sm border border-slate-300">{{ item.sku.produk.nama_produk }}</td>
												<td class="p-3 text-sm border border-slate-300" v-text="qty(item)"></td>
												<td class="p-3 text-sm border border-slate-300">Rp. {{ Intl.NumberFormat().format(item.subtotal) }}</td>
											</tr>
										</template>
										<tr>
											<td colspan="5" class="p-4 font-semibold border border-slate-300">Total</td>
											<td class="p-4 font-semibold border border-slate-300">Rp. {{ Intl.NumberFormat().format(revenue) }}</td>
										</tr>
									</tbody>
								</table>
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
	name: "printLaporan",
	middleware: "role/multiple-manager",
	data() {
		return {
			// content: false,
			penjualan: [],
			dateStart: this.$route.query.dateStart,
			dateEnd: this.$route.query.dateEnd,

			filename: "Laporan-Penjualan-Bulan-" + this.$moment(this.dateEnd).format("MMMM-YYYY"),
			// user : this.$auth.user
		};
	},
	created() {
		this.getDataPenjualan();
	},

	computed: {
		revenue() {
			let data = this.penjualan;

			let result = data.reduce((total, item) => total + item.total_harga, 0);
			return result;
		},
	},
	methods: {
		async getDataPenjualan() {
			const penjualan = await this.$axios.$get("/api/laporan/penjualan?from=" + this.dateStart + "&to=" + this.dateEnd);
			this.penjualan = penjualan;
		},

		generateReport() {
			this.$refs.html2Pdf.generatePdf();
		},

		qty(data) {
			if (data.sku.produk.satuan_produk == "m2") {
				let ukuran = JSON.parse(data.ukuran);
				return ukuran.panjang * ukuran.lebar + " " + data.sku.produk.satuan_produk + " - " + data.qty_produk + " pcs";
			} else return data.qty_produk + data.sku.produk.satuan_produk;
		},
	},
};
</script>
