<template>
	<div>
		<div class="my-4">
			<button class="btn-with-icon btn btn-sm btn-blue" @click="modalKategori = !modalKategori">
				<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
				</svg>
				Tambah Kategori Produk
			</button>
		</div>
		<div class="my-4 overflow-x-auto overflow-y-auto bg-white rounded-lg shadow">
			<table class="table table-auto table-kategori-produk">
				<thead class="bg-gray-100">
					<tr class="text-left text-gray-800 font-title">
						<th class="p-3">No</th>
						<th class="p-3">Nama Kategori</th>
						<th class="p-1"></th>
					</tr>
				</thead>
				<tbody class="divide-y-2 divide-gray-100 divide-dotted" v-if="categories.length > 0">
					<template v-for="(category, index) in categories">
						<tr class="text-sm">
							<td class="p-3">{{ index + 1 }}</td>
							<td class="p-3">{{ category.nama_kategori }}</td>
							<td class="p-2">
								<button class="btn btn-sm btn-indigo" @click="editKategori(category)">
									<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
										<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
									</svg>
								</button>
								<button class="btn btn-sm btn-red" @click="deleteKategori(category, index)">
									<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
										<path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
									</svg>
								</button>
							</td>
						</tr>
					</template>
				</tbody>
				<tbody v-else>
					<tr>
						<td colspan="4">
							<div class="flex items-center justify-center gap-4 font-semibold text-center py-14">
								<svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-red-500" viewBox="0 0 20 20" fill="currentColor">
									<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
								</svg>
								<span class="text-2xl"> Tidak Ada Data </span>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<Modal size="max-w-lg" :title="updateMode ? 'Edit Kategori' : 'Tambah Kategori'" @close-modal="closeModal" v-if="modalKategori">
			<template #content>
				<div class="col-span-12 sm:col-span-9">
					<label class="text-xs font-semibold text-gray-700">Nama Kategori</label>
					<input type="text" class="form-input form-input-lg" placeholder="Nama Produk ... " v-model="kategori.nama_kategori" />
					<p class="mt-2 text-xs text-red-500" v-if="validation.nama_kategori">{{ validation.nama_kategori[0] }}</p>
				</div>
				<div class="col-span-12 sm:col-span-3">
					<label class="label">Satuan</label>
					<select class="form-input form-input-lg" v-model="kategori.id_satuan">
						<option :value="{}" v-if="!updateMode">Pilih Kategori</option>
						<!-- <option :value="kategori.id_satuan" v-else>{{ kategori }}</option> -->
						<option :value="unit.id_satuan" :key="unit.id_satuan" v-for="unit in units">{{ unit.nama_satuan }}</option>
					</select>
					<p class="mt-2 text-xs text-red-500" v-if="validation.id_satuan">{{ validation.id_satuan[0] }}</p>
				</div>
			</template>
			<template #submit>
				<button class="btn btn-lg btn-green" v-if="!updateMode" @click="addKategori">Submit</button>
				<button class="btn btn-lg btn-indigo" v-else @click="updateKategori">Update</button>
			</template>
		</Modal>
		<Spinner v-show="isloading"></Spinner>
	</div>
</template>
<script>
import Modal from "../ModalComponent.vue";
import Spinner from "../SpinnerLoading.vue";
import Swal from "sweetalert2";

export default {
	name: "DataKategori",
	components: {Modal, Spinner},
	props: ["categories", "units"],
	data() {
		return {
			kategori: {
				id_kategori_produk: "",
				nama_kategori: "",
				id_satuan: {},
			},
			modalKategori: false,
			updateMode: false,
			isloading: false,

			validation: [],
		};
	},
	methods: {
		closeModal() {
			if ((this.updateMode = true)) {
				this.updateMode = false;
			}

			this.kategori.id_satuan = {};
			this.kategori.nama_kategori = "";

			this.validation = [];

			this.modalKategori = false;
		},

		// Kategori Produk
		async addKategori() {
			this.isloading = true;
			setTimeout(() => {
				this.$axios
					.post("/api/addKategori", {
						nama_kategori: this.kategori.nama_kategori,
						id_satuan: this.kategori.id_satuan,
					})
					.then((response) => {
						this.closeModal();
						this.$emit("get-data", response.data);
					})
					.then(() => {
						this.$swal.fire({
							icon: "success",
							title: "Success...",
							text: "Data Berhasil ditambah!",
							showConfirmButton: false,
							timer: 3000,
							timerProgressBar: true,
						});
					})
					.catch((error) => {
						console.log(error.response.data);
						this.validation = error.response.data;
					})
					.finally(() => {
						this.isloading = false;
					});
			}, 500);
		},

		editKategori(category) {
			this.updateMode = true;
			this.modalKategori = true;
			this.kategori.id_kategori_produk = category.id_kategori_produk;
			this.kategori.nama_kategori = category.nama_kategori;
			this.kategori.id_satuan = category.id_kategori_produk;
		},

		async updateKategori() {
			this.isloading = true;
			setTimeout(() => {
				this.$axios
					.post("/api/updateKategori/" + this.kategori.id_kategori_produk, {
						nama_kategori: this.kategori.nama_kategori,
						id_satuan: this.kategori.id_satuan,
					})
					.then((response) => {
						this.closeModal();
						this.$emit("get-data", response.data);
					})
					.then(() => {
						this.$swal.fire({
							icon: "success",
							title: "Success...",
							text: "Data Berhasil diubah!",
							showConfirmButton: false,
							timer: 3000,
							timerProgressBar: true,
						});
					})
					.catch((error) => {
						console.log(error.response.data);
						this.validation = error.response.data;
					})
					.finally(() => {
						this.isloading = false;
					});
			}, 500);
		},

		async deleteKategori(category, index) {
			this.isloading = true;
			setTimeout(() => {
				this.$axios
					.post("api/deleteKategori/" + category.id_kategori_produk)
					.then(() => {
						this.categories.splice(index, 1);
					})
					.then(() => {
						this.$swal.fire({
							icon: "success",
							title: "Success...",
							text: "Data Berhasil dihapus!",
							showConfirmButton: false,
							timer: 3000,
							timerProgressBar: true,
						});
					})
					.catch((error) => {
						console.log(error);
					})
					.finally(() => {
						this.isloading = false;
					});
			}, 500);
		},
	},
};
</script>
