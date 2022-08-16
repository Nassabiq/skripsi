<template>
	<div class="margin-auth">
		<div class="card">
			<div class="grid grid-cols-12 gap-4">
				<div class="relative col-span-12 my-4 md:col-span-6">
					<input type="search" class="w-full py-2 pl-8 pr-4 text-sm font-medium text-gray-800 border border-gray-200 rounded-lg shadow md:w-3/5 focus:outline-2 focus:outline-blue-100 focus:ring-2 focus:ring-blue-300" placeholder="Search..." v-model="search" @keyup="searchData" />
					<div class="absolute top-0 left-0 inline-flex items-center px-2 py-3">
						<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
							<rect x="0" y="0" width="24" height="24" stroke="none"></rect>
							<circle cx="10" cy="10" r="7" />
							<line x1="21" y1="21" x2="15" y2="15" />
						</svg>
					</div>
				</div>
				<div class="flex items-center justify-between col-span-12 mb-2 space-x-2 md:justify-end md:mb-0 md:col-span-6">
					<div class="flex flex-col col-span-6">
						<span class="mb-2 text-xs font-semibold md:text-right">
							Items Per Page :
						</span>
						<select @change="onChangeRecordsPerPage" v-model="show" class="px-2 py-2 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg shadow focus:outline-2 focus:outline-blue-100 focus:ring-2 focus:ring-blue-300">
							<option :value="5">5</option>
							<option :value="10">10</option>
							<option :value="25">25</option>
							<option :value="50">50</option>
						</select>
					</div>
					<div class="flex flex-col col-span-6">
						<span class="mb-2 text-xs font-semibold md:text-right">
							Status :
						</span>
						<select class="px-2 py-2 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg shadow focus:outline-2 focus:outline-blue-100 focus:ring-2 focus:ring-blue-300" v-model="selectedStatus" @change="onChangeStatus">
							<option value="">Status Pesanan</option>
							<option :value="0">Belum Diproses</option>
							<option :value="1">Sedang Diproses</option>
							<option :value="2">Sudah Diproses</option>
							<option :value="3">Selesai</option>
						</select>
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
							<th class="p-3">Status</th>
							<th class="p-1"></th>
							<th class="p-1"></th>
						</tr>
					</thead>

					<tbody class="divide-y-2 divide-gray-100 divide-dotted" v-if="transaksi.data">
						<template v-for="(data, index) in transaksi.data">
							<!-- {{ data }} -->
							<tr class="text-sm">
								<td class="w-32 p-3">{{ data.id_transaksi }}</td>
								<td class="p-3">{{ $moment(data.tgl_transaksi).format("DD MMMM YYYY") }}</td>
								<td class="p-3">{{ data.nama_pemesan }}</td>
								<td class="p-3">{{ data.detail_transaksi.length }}</td>
								<td class="p-3">Rp. {{ Intl.NumberFormat().format(data.total_harga) }}</td>
								<td class="p-1 whitespace-nowrap">
									<span class="px-2 py-1 text-xs text-gray-800 border-2 rounded-full" :class="data.status_pesanan == 0 ? 'bg-red-200 border-red-500' : data.status_pesanan == 1 ? 'bg-yellow-200 border-yellow-500' : data.status_pesanan == 2 ? 'bg-green-200 border-green-500' : 'bg-indigo-200 border-indigo-500'">
										{{ data.status_pesanan == 0 ? "Belum diproses" : data.status_pesanan == 1 ? "Sedang diproses" : data.status_pesanan == 2 ? "Sudah Diproses" : "Selesai" }}
									</span>
								</td>
								<td class="p-3 w-36">
									<div class="flex flex-wrap gap-2">
										<button class="btn btn-with-icon btn-sm btn-indigo" @click="changeStatus(data.id_transaksi, index)" v-if="data.status_pesanan !== 3">
											<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
												<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
											</svg>
											<span>{{ data.status_pesanan == 0 ? "Proses" : data.status_pesanan == 1 ? "Ubah Status" : "Selesaikan" }}</span>
										</button>
										<NuxtLink target="_blank" :to="{name: 'pencatatan-transaksi-id', params: {id: data.id_transaksi}}">
											<button class="btn btn-sm btn-with-icon btn-teal">
												<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
													<path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
												</svg>
												<span>Print</span>
											</button>
										</NuxtLink>
									</div>
								</td>
								<td class="p-1">
									<button class="p-1 text-blue-400 border border-blue-600 rounded-full focus:bg-blue-100 hover:text-blue-800" @click.prevent="showRow(index)">
										<svg xmlns="http://www.w3.org/2000/svg" :class="content === index ? 'rotate-90 duration-300' : 'rotate-0 duration-300'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
										</svg>
									</button>
								</td>
							</tr>
							<tr>
								<td class="p-3 bg-sky-50" colspan="8" v-show="content === index">
									<div class="px-4 pb-2 font-semibold">
										Detail Transaksi
									</div>
									<div class="grid grid-cols-2" v-for="(data, index) in data.detail_transaksi">
										<div class="col-span-2 md:col-span-1">
											<div class="border-t border-gray-200">
												<dl>
													<div class="px-3 py-4 bg-white sm:grid sm:grid-cols-3 sm:gap-4">
														<dt class="text-xs font-semibold">Id Detail Transaksi</dt>
														<dd class="mt-1 text-xs sm:mt-0 sm:col-span-2">{{ data.id_detail_transaksi }}</dd>
													</div>
													<div class="px-3 py-4 bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4">
														<dt class="text-xs font-semibold">Nama Produk</dt>
														<dd class="mt-1 text-xs sm:mt-0 sm:col-span-2">{{ data.produk.nama_produk }}</dd>
													</div>
													<div class="px-3 py-4 bg-white sm:grid sm:grid-cols-3 sm:gap-4">
														<dt class="text-xs font-semibold">QTY</dt>
														<dd class="mt-1 text-xs sm:mt-0 sm:col-span-2">{{ data.qty }} {{ data.produk.categories.satuan.nama_satuan }}</dd>
													</div>
													<div class="px-3 py-4 bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4">
														<dt class="text-xs font-semibold">Subtotal</dt>
														<dd class="mt-1 text-xs sm:mt-0 sm:col-span-2">Rp. {{ Intl.NumberFormat().format(data.produk.harga[data.produk.harga.length - 1].harga * data.qty) }}</dd>
													</div>
												</dl>
											</div>
										</div>
										<div class="col-span-2 md:col-span-1">
											<div class="border-t border-gray-200">
												<dl>
													<div class="px-3 py-4 bg-white sm:grid sm:grid-cols-3 sm:gap-4">
														<dt class="text-xs font-semibold">Ukuran</dt>
														<dd class="mt-1 text-xs sm:mt-0 sm:col-span-2">{{ data.ukuran }}</dd>
													</div>
													<div class="px-3 py-4 bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4">
														<dt class="text-xs font-semibold">Jenis Bahan</dt>
														<dd class="mt-1 text-xs sm:mt-0 sm:col-span-2">{{ data.jenis_bahan }}</dd>
													</div>
													<div class="px-3 py-4 bg-white sm:grid sm:grid-cols-3 sm:gap-4">
														<dt class="text-xs font-semibold">Finishing</dt>
														<dd class="mt-1 text-xs sm:mt-0 sm:col-span-2">{{ data.finishing }}</dd>
													</div>
													<div class="px-3 py-4 bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4">
														<dt class="text-xs font-semibold">Laminasi</dt>
														<dd class="mt-1 text-xs sm:mt-0 sm:col-span-2">{{ data.laminasi }}</dd>
													</div>
												</dl>
											</div>
										</div>
									</div>
								</td>
							</tr>
						</template>
					</tbody>
					<tbody v-else>
						<tr>
							<td colspan="8">
								<div class="flex items-center justify-center gap-4 py-20 font-semibold text-center">
									<svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
										<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
									</svg>
									<span class="text-2xl">
										Tidak Ada Data
									</span>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
				<div>
					<Pagination v-if="transaksi.data && transaksi.per_page < transaksi.total" :total-pages="transaksi.links.length - 2" :per-page="show" :current-page="transaksi.current_page" @pagechanged="onPageChange"></Pagination>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import Pagination from "../../components/Pagination.vue";
export default {
	name: "KelolaPemesanan",
	layout: "auth",
	auth: false,
	components: {Pagination},
	data() {
		return {
			content: null,
			transaksi: [],

			search: "",
			selectedStatus: "",
			show: 5,
			page: 1,
		};
	},
	mounted() {
		this.getDataTransaksi();
	},
	computed: {},
	methods: {
		showRow(index) {
			if (this.content !== null) this.content = this.content == index ? null : index;
			else this.content = index;
		},
		getDataTransaksi() {
			this.$axios
				.get("/api/dataTransaksi?page=" + this.page + "&show=" + this.show + "&search=" + this.search + "&status=" + this.selectedStatus)
				.then((response) => {
					this.transaksi = response.data;
				})
				.catch((error) => {
					console.log(error);
				});
		},
		async changeStatus(id, index) {
			let data = this.transaksi[index].status_pesanan;
			let formData = new FormData();

			formData.append("status_pesanan", data);

			this.$swal
				.fire({
					title: "Ubah Status Transaksi?",
					text: "Data yang diubah tidak dapat dikembalikan!",
					icon: "question",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Yes!",
				})
				.then((result) => {
					if (result.isConfirmed) {
						this.$axios
							.post("/api/changeStatus/" + id, formData)
							.then((response) => {
								this.getAllData();
							})
							.then(() => {
								this.$swal.fire("Success!", "Status Pesanan Berhasil diubah.", "success");
							})
							.catch((error) => {
								console.log(error);
							});
					}
				});
		},

		onChangeStatus() {
			this.getDataTransaksi();
		},
		onChangeRecordsPerPage() {
			this.getDataTransaksi();
		},
		searchData() {
			this.getDataTransaksi();
		},
		onPageChange(page) {
			this.page = page;
			this.getDataTransaksi();
		},
	},
};
</script>
