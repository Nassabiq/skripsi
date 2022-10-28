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
						<span class="mb-2 text-xs font-semibold md:text-right"> Items Per Page : </span>
						<select @change="onChangeRecordsPerPage" v-model="show" class="px-2 py-2 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg shadow focus:outline-2 focus:outline-blue-100 focus:ring-2 focus:ring-blue-300">
							<option :value="5">5</option>
							<option :value="10">10</option>
							<option :value="25">25</option>
							<option :value="50">50</option>
						</select>
					</div>
					<div class="flex flex-col col-span-6">
						<span class="mb-2 text-xs font-semibold md:text-right"> Status : </span>
						<select class="px-2 py-2 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg shadow focus:outline-2 focus:outline-blue-100 focus:ring-2 focus:ring-blue-300" v-model="selectedStatus" @change="onChangeStatus">
							<option value="">Status Pengadaan</option>
							<option :value="0">Belum Disetujui</option>
							<option :value="1">Disetujui</option>
						</select>
					</div>
					<div class="">
						<button class="btn-with-icon btn btn-blue btn-sm" @click="modalPengadaan = !modalPengadaan">
							<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
							</svg>
							Buat Pengadaan
						</button>
					</div>
				</div>
			</div>

			<div class="overflow-x-auto overflow-y-auto bg-white rounded-lg shadow">
				<table class="table table-auto table-produk">
					<thead class="bg-gray-100">
						<tr class="text-left text-gray-800 font-title">
							<th class="p-3">No. Pengadaan</th>
							<th class="p-3">Nama Pengadaan</th>
							<th class="p-3">Status</th>
							<th class="p-3">Tgl Dibuat</th>
							<th class="p-1"></th>
							<th class="p-1"></th>
						</tr>
					</thead>
					<tbody class="divide-y-2 divide-gray-100 divide-dotted" v-if="pengadaan_barang.data && pengadaan_barang.data.length > 0">
						<template v-for="(data, index) in pengadaan_barang.data">
							<tr class="text-sm">
								<td class="p-3">{{ data.id_pengadaan }}</td>
								<td class="p-3">{{ data.nama_pengadaan }}</td>
								<td class="p-3 text-xs">
									<span class="px-2 py-1 text-xs text-gray-800 bg-yellow-200 border-2 border-yellow-500 rounded-full whitespace-nowrap" v-if="data.status_pengadaan == 0">Belum disetujui</span>
									<span class="px-2 py-1 text-xs text-gray-800 bg-green-200 border-2 border-green-500 rounded-full whitespace-nowrap" v-else>Sudah disetujui</span>
								</td>
								<td class="p-3">{{ $moment(data.tgl_dibuat).format("DD-MMMM-YYYY") }}</td>
								<td class="w-48 p-1 py-3">
									<div class="flex flex-wrap gap-1">
										<NuxtLink target="_blank" :to="{name: 'persediaan-pengadaan-id', params: {id: data.id_pengadaan}}">
											<button class="btn btn-sm btn-with-icon btn-teal">
												<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
													<path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
												</svg>
												<!-- <span>Print</span> -->
											</button>
										</NuxtLink>
										<button class="btn btn-sm btn-with-icon btn-indigo" v-if="data.status_pengadaan == 0" @click="validasiPengadaan(data.id_pengadaan)">
											<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
												<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
											</svg>
											<!-- <span>Validasi</span> -->
										</button>
										<button class="btn btn-sm btn-with-icon btn-green" v-if="data.status_pengadaan == 0" @click="editPengadaan(data)">
											<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
												<path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
												<path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
											</svg>
											<!-- <span>Edit</span> -->
										</button>
										<button class="btn btn-sm btn-with-icon btn-red" v-if="data.status_pengadaan == 0" @click="deletePengadaan(data.id_pengadaan, index)">
											<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
												<path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
											</svg>
											<!-- <span>Delete</span> -->
										</button>
									</div>
								</td>
								<td class="p-2">
									<button class="p-1 text-blue-400 border border-blue-600 rounded-full focus:bg-blue-100 hover:text-blue-800" @click.prevent="showRow(index)">
										<svg xmlns="http://www.w3.org/2000/svg" :class="content === index ? 'rotate-90 duration-300' : 'rotate-0 duration-300'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
										</svg>
									</button>
								</td>
							</tr>
							<tr>
								<td colspan="7" class="p-3 bg-sky-100" v-show="content === index">
									<div class="px-4 pb-2 font-semibold">Detail Pengadaan</div>
									<div class="grid grid-cols-3 gap-4">
										<div class="col-span-3 md:col-span-1" v-for="(data, index) in data.detail_pengadaan" :key="index">
											<div class="flex justify-between p-4 bg-white rounded-md shadow-md">
												<div>
													<p class="text-xs">{{ data.id_detail_pengadaan }}</p>
													<p class="text-lg font-semibold tracking-wider">{{ data.bahan_baku.nama_bahan_baku }}</p>
												</div>
												<div class="place-self-center">
													<p class="font-semibold">
														{{ data.jumlah_barang }} <span class="text-xs">{{ data.bahan_baku.satuan_bahan_baku }}</span>
													</p>
												</div>
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
									<span class="text-2xl"> Tidak Ada Data </span>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
				<div>
					<Pagination v-if="pengadaan_barang.data && pengadaan_barang.per_page < pengadaan_barang.total" :total-pages="pengadaan_barang.links.length - 2" :per-page="show" :current-page="pengadaan_barang.current_page" @pagechanged="onPageChange"></Pagination>
				</div>
			</div>
		</div>
		<Modal size="max-w-xl" :title="updateMode ? 'Edit Pengadaan Persediaan' : 'Buat Pengadaan Persediaan'" @close-modal="closeModal" v-show="modalPengadaan">
			<template #content>
				<div class="col-span-12 sm:col-span-8">
					<label class="label">Nama Pengadaan</label>
					<input type="text" class="form-input form-input-lg" placeholder="Nama Pengadaan ... " v-model="nama_pengadaan" />
					<!-- <p class="mt-2 text-xs text-red-500" v-if="validation.nama_pengadaan">{{ validation.nama_pengadaan[0] }}</p> -->
				</div>
				<div class="grid grid-cols-12 col-span-12 gap-4" v-for="(data, index) in pengadaan">
					<div class="col-span-12 sm:col-span-8">
						<label class="label">Nama Bahan Baku</label>
						<select class="form-input form-input-lg" v-model="pengadaan[index].id_bahan_baku">
							<!-- <option v-if="updateMode" :value="pengadaan[index].id_material">{{ pengadaan[index].id_material }}</option> -->
							<option value="">Pilih Bahan Baku</option>
							<option :value="data.id_bahan_baku" v-for="data in bahan_baku">{{ data.nama_bahan_baku }}</option>
						</select>
					</div>
					<div class="col-span-12 sm:col-span-4">
						<label class="label">Qty</label>
						<div class="flex gap-2">
							<input type="text" class="form-input form-input-lg" placeholder="Qty" v-model="pengadaan[index].jumlah_barang" />
							<button class="mt-1 btn btn-sm btn-indigo" v-if="index == 0" @click="addForm">
								<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
								</svg>
							</button>
							<button class="mt-1 btn btn-sm btn-red" v-else @click="deleteForm(index)">
								<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 rotate-45" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
								</svg>
							</button>
						</div>
					</div>
				</div>
				<div class="col-span-12" v-if="validation">
					<ul>
						<li class="px-4 py-3 my-2 text-xs bg-red-100 rounded-md shadow-md sm:col-span-2" v-for="(error, index) in validation">{{ error[0] }}</li>
					</ul>
				</div>
			</template>
			<template #submit>
				<button class="btn btn-lg btn-green" @click.prevent="addPengadaan" v-if="!updateMode">Submit</button>
				<button class="btn btn-lg btn-green" @click.prevent="updatePengadaan" v-else>Update</button>
			</template>
		</Modal>
	</div>
</template>

<script>
import {number} from "@coders-tm/vue-number-format";
import Pagination from "../../../components/Pagination.vue";

export default {
	name: "PengadaanBarang",
	layout: "auth",
	components: {number, Pagination},
	auth: false,
	data() {
		return {
			updateMode: false,

			search: "",
			selectedStatus: "",
			show: 5,
			page: 1,

			content: null,
			modalPengadaan: false,

			bahan_baku: [],
			pengadaan_barang: "",

			id_pengadaan: "",
			nama_pengadaan: "",
			pengadaan: [
				{
					id_bahan_baku: "",
					jumlah_barang: null,
				},
			],

			number: {
				decimal: ",",
				separator: ".",
				prefix: "Rp. ",
				precision: 2,
				masked: false,
			},

			validation: [],
		};
	},

	mounted() {
		this.getPengadaan();
		this.getBahanBaku();
	},
	computed: {},
	methods: {
		async getPengadaan() {
			const pengadaan_barang = await this.$axios.$get("/api/pengadaan?page=" + this.page + "&show=" + this.show + "&search=" + this.search + "&status=" + this.selectedStatus);
			this.pengadaan_barang = pengadaan_barang;
		},
		async getBahanBaku() {
			const bahan_baku = await this.$axios.$get("/api/bahan-baku");
			this.bahan_baku = bahan_baku;
		},

		searchData() {
			this.getPengadaan();
		},
		onChangeRecordsPerPage() {
			this.getPengadaan();
		},
		onChangeStatus() {
			this.getPengadaan();
		},

		onPageChange(page) {
			this.page = page;
			this.getPengadaan();
		},

		addForm() {
			this.pengadaan.push({
				id_bahan_baku: "",
				jumlah_barang: null,
			});
		},
		deleteForm(index) {
			this.pengadaan.splice(index, 1);
		},
		closeModal() {
			this.modalPengadaan = false;
			this.id_pengadaan = "";
			this.nama_pengadaan = "";
			this.pengadaan = [
				{
					id_bahan_baku: "",
					jumlah_barang: null,
				},
			];

			if (this.updateMode) this.updateMode = false;

			this.validation = [];
		},

		showRow(index) {
			if (this.content !== null) this.content = this.content == index ? null : index;
			else this.content = index;
		},

		async addPengadaan() {
			this.$axios
				.post("/api/pengadaan", {
					nama_pengadaan: this.nama_pengadaan,
					pengadaan: this.pengadaan,
				})
				.then((response) => {
					this.closeModal();
					this.getPengadaan();
				})
				.then(() => {
					this.$swal.fire({
						icon: "success",
						title: "Success...",
						text: "Data Berhasil ditambah!",
						showConfirmButton: false,
						timer: 1500,
						timerProgressBar: true,
					});
				})
				.catch((error) => {
					console.log(error);
					this.validation = error.response.data;
				});
		},

		editPengadaan(data) {
			this.updateMode = true;
			this.modalPengadaan = true;

			this.id_pengadaan = data.id_pengadaan;
			this.nama_pengadaan = data.nama_pengadaan;

			this.pengadaan = [];

			for (let index = 0; index < data.detail_pengadaan.length; index++) {
				const pengadaan = data.detail_pengadaan[index];
				this.pengadaan.push({
					id_detail_pengadaan: pengadaan.id_detail_pengadaan,
					id_bahan_baku: pengadaan.bahan_baku.id_bahan_baku,
					jumlah_barang: pengadaan.jumlah_barang,
				});
			}
		},
		async updatePengadaan() {
			this.$axios
				.patch("/api/pengadaan/" + this.id_pengadaan, {
					nama_pengadaan: this.nama_pengadaan,
					pengadaan: this.pengadaan,
				})
				.then((response) => {
					this.closeModal();
					this.getPengadaan();
					this.getBahanBaku();
				})
				.then(() => {
					this.$swal.fire({
						icon: "success",
						title: "Success...",
						text: "Data Berhasil diubah!",
						showConfirmButton: false,
						timer: 1500,
						timerProgressBar: true,
					});
				})
				.catch((error) => (this.validation = error.response.data));
		},
		async validasiPengadaan(data) {
			this.$swal
				.fire({
					title: "Validasi Pengadaan?",
					text: "data tidak bisa diubah kembali atau dihapus jika sudah divalidasi!",
					icon: "question",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Validasi!",
				})
				.then((result) => {
					if (result.isConfirmed) {
						this.$axios
							.post("api/pengadaan/validasi/" + data)
							.then(() => {
								// this.pengadaan_barang.splice(index, 1);
								this.$swal.fire("Success!", "Data Berhasil divalidasi.", "success");
							})
							.then(() => {
								this.getPengadaan();
								this.getBahanBaku();
							})
							.catch((error) => {
								console.log(error);
							});
					}
				});
		},

		async deletePengadaan(data, index) {
			this.$swal
				.fire({
					title: "Are you sure?",
					text: "Data yang dihapus tidak dapat dikembalikan!",
					icon: "warning",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Yes, delete it!",
				})
				.then((result) => {
					if (result.isConfirmed) {
						this.$axios
							.delete("api/pengadaan/" + data)
							.then(() => {
								this.pengadaan_barang.data.splice(index, 1);
							})
							.then(() => {
								this.$swal.fire("Deleted!", "Data Berhasil dihapus.", "success");
							})
							.catch((error) => {
								console.log(error);
							});
					}
				});
		},
	},
};
</script>
