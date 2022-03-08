<template>
	<div>
		<div class="card">
			<div class="my-4">
				<button class="btn-with-icon btn btn-blue btn-sm" @click="modalBahanBaku = !modalBahanBaku">
					<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
					</svg>
					Tambah Data Bahan Baku
				</button>
			</div>
			<div class="my-4 overflow-x-auto overflow-y-auto bg-white rounded-lg shadow">
				<table class="table table-auto table-produk">
					<thead class="bg-gray-100">
						<tr class="text-left text-gray-800 font-title">
							<th class="p-3">Nama Bahan Baku</th>
							<th class="p-3">Satuan</th>
							<th class="p-3">Stok</th>
						</tr>
					</thead>
					<tbody class="divide-y-2 divide-gray-100 divide-dotted">
						<tr class="text-sm" v-for="(material, index) in materials">
							<td class="p-3">{{ material.nama_bahan_baku }}</td>
							<td class="p-3">{{ material.satuan.nama_satuan }}</td>
							<td class="p-3">{{ material.stok }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<Modal size="max-w-lg" title="Tambah Data Bahan Baku" @close-modal="modalBahanBaku = false" v-show="modalBahanBaku">
			<template #content>
				<div class="col-span-12 sm:col-span-9">
					<label class="label">Nama Bahan Baku</label>
					<input type="text" class="form-input form-input-lg" placeholder="Nama Bahan Baku ... " v-model="material.nama_bahan_baku" />
					<p class="mt-2 text-xs text-red-500" v-if="validation.nama_kategori">{{ validation.nama_bahan_baku[0] }}</p>
				</div>
				<div class="col-span-12 sm:col-span-3">
					<label class="label">Satuan</label>
					<select class="form-input form-input-lg" v-model="material.id_satuan">
						<option :value="{}">Pilih Kategori</option>
						<option :value="unit.id_satuan" v-for="(unit, index) in units">{{ unit.nama_satuan }}</option>
					</select>
					<p class="mt-2 text-xs text-red-500" v-if="validation.nama_kategori">{{ validation.id_satuan[0] }}</p>
				</div>
			</template>
			<template #submit>
				<button class="btn btn-lg btn-green" @click.prevent="addBahanBaku">
					Submit
				</button>
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
	components: {Modal, Spinner},
	props: ["materials", "units"],
	data() {
		return {
			modalBahanBaku: false,

			material: {
				nama_bahan_baku: "",
				id_satuan: "",
			},
			validation: [],
			isloading: false,
		};
	},

	methods: {
		closeModal() {
			this.material.id_satuan = {};
			this.material.nama_bahan_baku = "";

			this.validation = [];

			this.modalBahanBaku = false;
		},
		async addBahanBaku() {
			this.isloading = true;
			setTimeout(() => {
				this.$axios
					.post("/api/addBahanBaku", {
						nama_bahan_baku: this.material.nama_bahan_baku,
						id_satuan: this.material.id_satuan,
					})
					.then((response) => {
						this.closeModal();
						this.$emit("get-data", response.data);
					})
					.then(() => {
						Swal.fire({
							icon: "success",
							title: "Success...",
							text: "Data Berhasil ditambah!",
							showConfirmButton: false,
							timer: 1500,
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
	},
};
</script>

<style></style>
