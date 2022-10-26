<template>
	<div class="gap-4 space-y-4 margin-auth">
		<div class="card">
			<div class="grid grid-cols-12 gap-4 my-2">
				<div class="flex flex-col col-span-12 col lg:col-span-8 sm:col-span-6">
					<span class="mb-2 text-sm font-medium"> Search : </span>
					<input class="block w-full mt-1 text-xs border border-gray-300 rounded shadow sm:w-3/5 lg:w-2/5 form-input-lg focus:border-green-200 focus:outline-none focus:ring-2 focus:ring-green-300" placeholder="Cari Bahan Baku..." v-model="search" @keyup="searchData" />
				</div>
				<div class="flex items-center justify-end col-span-12 sm:col-span-6 lg:col-span-4">
					<button class="btn-with-icon btn btn-blue btn-sm" @click="modalBahanBaku = !modalBahanBaku">
						<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
						</svg>
						Tambah Bahan Baku
					</button>
				</div>
			</div>
			<div class="my-4 overflow-x-auto overflow-y-auto bg-white rounded-lg shadow">
				<table class="table table-auto table-produk">
					<thead class="bg-gray-100">
						<tr class="text-left text-gray-800 font-title">
							<th class="p-3">Nama Bahan Baku</th>
							<th class="p-3">Produk</th>
							<th class="p-3">Stok</th>
							<th class="p-3">Satuan</th>
							<th class="p-3"></th>
						</tr>
					</thead>
					<tbody class="divide-y-2 divide-gray-100 divide-dotted">
						<template v-if="bahanBaku.length > 0">
							<tr class="text-sm" v-for="(data, index) in bahanBaku">
								<td class="p-3">{{ data.nama_bahan_baku }}</td>
								<td class="p-3" v-for="item in data.stok">{{ item.produk.nama_produk }}</td>
								<td class="p-3" v-for="item in data.stok">{{ item.jml_stok }}</td>
								<td class="p-3">{{ data.satuan_bahan_baku }}</td>
								<td class="p-2">
									<button class="btn btn-sm btn-indigo" @click.prevent="editBahanBaku(data)">
										<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
											<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
										</svg>
									</button>
									<button class="btn btn-sm btn-red" @click.prevent="deleteBahanBaku(data.id_bahan_baku)">
										<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
											<path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
										</svg>
									</button>
								</td>
							</tr>
						</template>
						<template v-else>
							<tr class="text-sm">
								<td colspan="4">
									<div class="flex items-center justify-center gap-4 py-20 font-semibold text-center">
										<svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
											<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
										</svg>
										<span class="text-2xl"> Tidak Ada Data </span>
									</div>
								</td>
							</tr>
						</template>
					</tbody>
				</table>
			</div>
		</div>
		<Modal size="max-w-xl" :title="updateMode ? 'Edit Bahan Baku' : 'Tambah Bahan Baku'" @close-modal="closeModal" v-show="modalBahanBaku">
			<template #content>
				<div class="col-span-12 sm:col-span-9">
					<label class="label">Nama Bahan Baku</label>
					<input type="text" class="form-input form-input-lg" placeholder="Nama Bahan Baku ... " v-model="material.nama_bahan_baku" />
					<p class="mt-2 text-xs text-red-500" v-if="validation.nama_kategori">{{ validation.nama_bahan_baku[0] }}</p>
				</div>
				<div class="col-span-12 sm:col-span-3">
					<label class="label">Satuan</label>
					<input type="text" class="form-input form-input-lg" placeholder="Satuan ... " v-model="material.satuan_bahan_baku" />
					<p class="mt-2 text-xs text-red-500" v-if="validation.satuan_bahan_baku">{{ validation.satuan_bahan_baku[0] }}</p>
				</div>
				<div class="col-span-12 sm:col-span-9">
					<label class="label">Bahan Baku untuk : </label>
					<select class="form-input form-input-lg" v-model="material.id_produk">
						<option value="">Pilih Produk</option>
						<option :value="data.id_produk" v-for="(data, index) in produk">{{ data.nama_produk }}</option>
					</select>
					<p class="mt-2 text-xs text-red-500" v-if="validation.id_produk">{{ validation.id_produk[0] }}</p>
				</div>
			</template>
			<template #submit>
				<button class="btn btn-lg btn-green" @click.prevent="addBahanBaku" v-if="!updateMode">Submit</button>
				<button class="btn btn-lg btn-green" @click.prevent="updateBahanBaku" v-else>Update</button>
			</template>
		</Modal>
		<Spinner v-show="isloading"></Spinner>
	</div>
</template>

<script>
import Spinner from "../../components/SpinnerLoading.vue";
import Modal from "../../components/ModalComponent.vue";

export default {
	layout: "auth",
	components: {Spinner, Modal},
	data() {
		return {
			modalBahanBaku: false,

			bahanBaku: [],
			produk: [],

			id_bahan_baku: "",
			material: {
				nama_bahan_baku: "",
				satuan_bahan_baku: "",
				id_produk: "",
			},
			validation: [],
			isloading: false,
			updateMode: false,

			search: "",
		};
	},
	mounted() {
		this.getData();
	},
	methods: {
		async getData() {
			this.getBahanBaku();
			this.getProduk();
		},
		async getBahanBaku() {
			this.$axios
				.get("/api/bahan-baku")
				.then((response) => {
					this.bahanBaku = response.data;
				})
				.catch((error) => {
					console.log(error);
				});
		},
		async getProduk() {
			this.$axios
				.get("/api/produk")
				.then((response) => {
					this.produk = response.data;
				})
				.catch((error) => {
					console.log(error);
				});
		},
		closeModal() {
			this.material.satuan_bahan_baku = "";
			this.material.id_produk = "";
			this.material.nama_bahan_baku = "";
			this.validation = [];
			this.modalBahanBaku = false;
		},

		searchData() {
			this.$emit("search-data", this.search);
		},
		async addBahanBaku() {
			this.isloading = true;
			this.$axios
				.post("/api/addBahanBaku", {
					nama_bahan_baku: this.material.nama_bahan_baku,
					satuan_bahan_baku: this.material.satuan_bahan_baku,
					id_produk: this.material.id_produk,
				})
				.then(() => {
					this.closeModal();
					this.getData();
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
					this.validation = error.response.data;
				})
				.finally(() => {
					this.isloading = false;
				});
		},
		editBahanBaku(data) {
			this.updateMode = true;
			this.modalBahanBaku = true;

			this.id_bahan_baku = data.id_bahan_baku;
			this.material.nama_bahan_baku = data.nama_bahan_baku;
			this.material.satuan_bahan_baku = data.satuan_bahan_baku;
			this.material.id_produk = data.stok[0].id_produk;
		},

		async updateBahanBaku() {
			this.isloading = true;
			this.$axios
				.post("/api/editBahanBaku/" + this.id_bahan_baku, {
					nama_bahan_baku: this.material.nama_bahan_baku,
					satuan_bahan_baku: this.material.satuan_bahan_baku,
					id_produk: this.material.id_produk,
				})
				.then(() => {
					this.closeModal();
					this.getData();
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
					this.validation = error.response.data;
				})
				.finally(() => {
					this.isloading = false;
				});
		},

		async deleteBahanBaku(id) {
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
						this.isloading = !this.isloading;
						setTimeout(() => {
							this.$axios
								.post("api/deleteBahanBaku/" + id)
								.then(() => {
									this.$swal.fire("Deleted!", "Data Berhasil dihapus.", "success");
								})
								.then(() => {
									this.getData();
								})
								.catch((error) => {
									console.log(error);
								})
								.finally(() => {
									this.isloading = !this.isloading;
								});
						}, 500);
					}
				});
		},
	},
};
</script>

<style></style>
