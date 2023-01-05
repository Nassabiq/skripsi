<template>
	<!-- <div class="py-10 bg-lime-500"> -->
	<!-- <div class="block p-5 mx-auto bg-white print:block drop-shadow-lg paper"> -->
	<div>
		<client-only>
			<div class="relative">
				<button class="fixed bottom-0 right-0 m-5 btn btn-lg btn-with-icon btn-indigo" @click.prevent="generateReport">
					<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
						<path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
					</svg>
					Download
				</button>
			</div>
			<vue-html2pdf :show-layout="true" :float-layout="false" :enable-download="true" :preview-modal="false" :paginate-elements-by-height="1400" :pdf-quality="2" :manual-pagination="false" pdf-format="a4" :filename="transaksi.id_transaksi" pdf-orientation="portrait" pdf-content-width="800px" ref="html2Pdf">
				<!-- @progress="onProgress($event)" @hasStartedGeneration="hasStartedGeneration()" @hasGenerated="hasGenerated($event)" -->
				<section slot="pdf-content" class="bg-white" v-if="transaksi">
					<div class="grid grid-cols-12 p-4">
						<div class="col-span-12">
							<p class="flex justify-center my-8 text-2xl font-semibold text-cemter">Order Quotation</p>
						</div>
						<div class="col-span-6">
							<div class="px-2 py-3 bg-white sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
								<dt class="text-sm font-medium text-gray-500">Nomor Transaksi</dt>
								<dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">: {{ transaksi.id_transaksi }}</dd>
							</div>
							<div class="px-2 py-3 bg-white sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
								<dt class="text-sm font-medium text-gray-500">Nama Pemesan</dt>
								<dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2" v-text="transaksi.pelanggan.nama_pelanggan" v-if="transaksi.pelanggan"></dd>
							</div>
						</div>
						<div class="col-span-6">
							<div class="px-2 py-3 bg-white sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
								<dt class="text-sm font-medium text-gray-500">No. Telp</dt>
								<dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2" v-if="transaksi.pelanggan">: {{ transaksi.pelanggan.no_telp }}</dd>
							</div>
							<div class="px-2 py-3 bg-white sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
								<dt class="text-sm font-medium text-gray-500">Tgl Transaksi</dt>
								<dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">: {{ $moment(transaksi.tgl_transaksi).format("DD MMMM YYYY") }}</dd>
							</div>
						</div>
						<div class="col-span-12">
							<div class="flex gap-4 px-2 py-3 bg-white sm:px-6">
								<dt class="text-sm font-medium text-gray-500">Catatan</dt>
								<dd class="mt-1 text-sm text-gray-900 sm:mt-0" v-if="transaksi.catatan">: {{ transaksi.catatan }}</dd>
							</div>
						</div>
						<div class="col-span-12 px-4 mt-6">
							<div class="overflow-x-auto overflow-y-auto bg-white border-2 rounded-lg shadow-md border-slate-400">
								<table class="table table-auto table-produk">
									<thead class="bg-gray-100">
										<tr class="text-left text-gray-800 font-title">
											<th class="p-3 text-sm border border-slate-300">No</th>
											<th class="p-3 text-sm border border-slate-300">Produk</th>
											<th class="p-3 text-sm border border-slate-300">Qty</th>
											<th class="p-3 text-sm border border-slate-300">Harga</th>
											<th class="p-3 text-sm border border-slate-300">Ukuran</th>
											<th class="p-3 text-sm border border-slate-300">Jenis Bahan</th>
											<th class="p-3 text-sm border border-slate-300">Subtotal</th>
										</tr>
									</thead>

									<tbody class="">
										<template v-for="(data, index) in transaksi.detail_transaksi">
											<tr class="text-gray-800 font-title">
												<td class="p-3 text-sm border border-slate-300">{{ index + 1 }}</td>
												<td class="p-3 text-sm border border-slate-300">{{ data.sku.produk.nama_produk }}</td>
												<td class="p-3 text-sm border border-slate-300">{{ data.qty_produk }}{{ data.sku.produk.satuan_produk }}</td>
												<td class="p-3 text-sm border border-slate-300">Rp. {{ Intl.NumberFormat().format(data.sku.harga[data.sku.harga.length - 1].harga_produk) }}</td>
												<td class="p-3 text-sm border border-slate-300" v-text="data.ukuran ? data.ukuran + data.sku.produk.satuan_produk : '-'">{{ data.ukuran }}</td>
												<td class="p-3 text-sm border border-slate-300">{{ data.sku.bahan_baku.nama_bahan_baku }}</td>
												<!-- <td class="p-3 text-sm border border-slate-300">-</td> -->
												<td class="p-3 text-sm border border-slate-300">Rp. {{ Intl.NumberFormat().format(data.sku.harga[data.sku.harga.length - 1].harga_produk * data.qty_produk) }}</td>
											</tr>
											<tr>
												<td colspan="7" class="p-1 border border-slate-300">
													<p class="text-xs">Note:</p>
													<div class="grid grid-cols-1 gap-4 md:grid-cols-2">
														<div class="flex col-span-1 p-3">
															<p class="text-xs">Finishing :</p>
															<p class="pl-4 text-xs">{{ data.finishing.nama_finishing }}</p>
														</div>
														<!-- <div class="flex col-span-1 p-3">
															<p class="text-xs">Laminasi :</p>
															<p class="pl-4 text-xs">{{ data.laminasi }}</p>
														</div> -->
													</div>
												</td>
											</tr>
										</template>
										<tr class="text-gray-800 bg-gray-100 font-title">
											<td colspan="6" class="p-3 text-lg font-semibold border border-slate-300">Total</td>
											<td class="p-3 font-semibold border border-slate-300">Rp. {{ Intl.NumberFormat().format(transaksi.total_harga) }}</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="flex justify-end px-4 m-4">
						<div class="space-y-4 text-sm text-center">
							<p>Bogor, {{ $moment().format("DD MMMM YYYY") }}</p>
							<qrcode-vue :value="transaksi.id_transaksi" size="100" level="H" class="flex justify-center" />
							<p>UQI Media Digital Printing</p>
						</div>
					</div>
				</section>
			</vue-html2pdf>
		</client-only>
	</div>
	<!-- </div> -->
	<!-- </div> -->
</template>

<script>
import QrcodeVue from "qrcode.vue";
export default {
	components: {QrcodeVue},
	data() {
		return {
			// transaksi: [],
		};
	},
	computed: {
		transaksi() {
			return this.$store.state.transaksi.detailData;
		},
	},
	created() {
		this.getData();
	},
	methods: {
		getData() {
			this.$store.dispatch("transaksi/fetchDetailTransaksi", this.$route.params.id);
		},
		generateReport() {
			this.$refs.html2Pdf.generatePdf();
		},
	},
};
</script>

<style scoped>
.paper {
	width: 21cm;
	height: 29.7cm;
}
</style>
