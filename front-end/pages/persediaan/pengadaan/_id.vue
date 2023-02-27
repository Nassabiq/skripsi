<template>
	<div>
		<client-only>
			<button class="fixed bottom-0 right-0 m-5 btn btn-lg btn-with-icon btn-indigo" @click.prevent="generateReport">
				<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
					<path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
				</svg>
				Download
			</button>
			<vue-html2pdf :show-layout="true" :float-layout="false" :enable-download="true" :preview-modal="false" :paginate-elements-by-height="1400" :pdf-quality="2" :manual-pagination="false" pdf-format="a4" :filename="pengadaan.nama_pengadaan" pdf-orientation="portrait" pdf-content-width="800px" ref="html2Pdf">
				<!-- @progress="onProgress($event)" @hasStartedGeneration="hasStartedGeneration()" @hasGenerated="hasGenerated($event)" -->
				<section slot="pdf-content" class="bg-white">
					<div class="grid grid-cols-12 p-4">
						<div class="col-span-12">
							<p class="flex justify-center my-8 text-2xl font-semibold text-cemter">Dokumen Pengadaan Barang</p>
						</div>
						<div class="col-span-6">
							<div class="px-2 py-3 bg-white sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
								<dt class="text-xs font-medium text-gray-500">Nomor Pengadaan</dt>
								<dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">: {{ pengadaan.id_pengadaan }}</dd>
							</div>
							<div class="px-2 py-3 bg-white sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
								<dt class="text-xs font-medium text-gray-500">Nama Pengadaan</dt>
								<dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">: {{ pengadaan.nama_pengadaan }}</dd>
							</div>
						</div>
						<div class="col-span-6">
							<div class="px-2 py-3 bg-white sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
								<dt class="text-xs font-medium text-gray-500">Status</dt>
								<dd class="mt-1 text-xs text-gray-900 sm:mt-0 sm:col-span-2">: {{ pengadaan.status_pengadaan == 1 ? "Disetujui" : "Belum disetujui" }}</dd>
							</div>
							<div class="px-2 py-3 bg-white sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
								<dt class="text-xs font-medium text-gray-500">Tgl Disetujui</dt>
								<dd class="mt-1 text-xs text-gray-900 sm:mt-0 sm:col-span-2">: {{ pengadaan.tgl_disetujui ? $moment(pengadaan.tgl_disetujui).format("DD MMMM YYYY") : "Belum disetujui" }}</dd>
							</div>
						</div>
						<div class="col-span-12 px-4 mt-6">
							<div class="overflow-x-auto overflow-y-auto bg-white border-2 rounded-lg shadow-md border-slate-400">
								<table class="table table-auto table-produk">
									<thead class="bg-gray-100">
										<tr class="text-left text-gray-800 font-title">
											<th class="p-3 text-sm border border-slate-300">No. Detail</th>
											<th class="p-3 text-sm border border-slate-300">Bahan Baku</th>
											<th class="p-3 text-sm border border-slate-300">Jumlah</th>
										</tr>
									</thead>
									<tbody>
										<tr class="text-gray-800 font-title" v-for="(data, index) in pengadaan.detail_pengadaan">
											<td class="p-3 text-sm border border-slate-300">{{ data.id_detail_pengadaan }}</td>
											<td class="p-3 text-sm border border-slate-300">{{ data.bahan_baku.nama_bahan_baku }}</td>
											<td class="p-3 text-sm border border-slate-300">{{ data.jumlah_barang }}</td>
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
	middleware: "role/operator-inventory",
	data() {
		return {
			pengadaan: [],
		};
	},
	mounted() {
		this.getData();
	},
	methods: {
		async getData() {
			const pengadaan = await this.$axios.$get("/api/pengadaan/" + this.$route.params.id);
			this.pengadaan = pengadaan;
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
