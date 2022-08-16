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
							<th class="p-3">untuk produk</th>
							<th class="p-3">Satuan</th>
							<th class="p-3">Stok</th>
							<th class="p-3"></th>
						</tr>
					</thead>
					<tbody class="divide-y-2 divide-gray-100 divide-dotted">
						<tr class="text-sm" v-for="(material, index) in materials">
							<td class="p-3">{{ material.nama_bahan_baku }}</td>
							<td class="p-3" v-for="data in material.produk">{{ data.nama_produk }}</td>
							<td class="p-3">{{ material.satuan.nama_satuan }}</td>
							<td class="p-3">{{ material.stok }}</td>
							<td class="p-2">
								<button class="btn btn-sm btn-indigo">
									<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
										<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
									</svg>
								</button>
								<button class="btn btn-sm btn-red">
									<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
										<path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
									</svg>
								</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<Modal size="max-w-xl" title="Tambah Data Bahan Baku" @close-modal="closeModal" v-show="modalBahanBaku">
			<template #content>
				<div class="col-span-12 sm:col-span-9">
					<label class="label">Nama Bahan Baku</label>
					<input type="text" class="form-input form-input-lg" placeholder="Nama Bahan Baku ... " v-model="material.nama_bahan_baku" />
					<p class="mt-2 text-xs text-red-500" v-if="validation.nama_kategori">{{ validation.nama_bahan_baku[0] }}</p>
				</div>
				<div class="col-span-12 sm:col-span-3">
					<label class="label">Satuan</label>
					<select class="form-input form-input-lg" v-model="material.id_satuan">
						<option value="">Pilih Kategori</option>
						<option :value="unit.id_satuan" v-for="(unit, index) in units">{{ unit.nama_satuan }}</option>
					</select>
					<p class="mt-2 text-xs text-red-500" v-if="validation.id_satuan">{{ validation.id_satuan[0] }}</p>
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
				<button class="btn btn-lg btn-green" @click.prevent="addBahanBaku">
					Submit
				</button>
			</template>
		</Modal>
		<Spinner v-show="isloading"></Spinner>
	</div>
</template>

<script>
import Spinner from "../SpinnerLoading.vue";

export default {
	components: {Spinner},
	props: ["materials", "units", "produk"],
	data() {
		return {
			modalBahanBaku: false,

			material: {
				nama_bahan_baku: "",
				id_satuan: "",
				id_produk: "",
			},
			validation: [],
			isloading: false,
		};
	},

	methods: {
		closeModal() {
			this.material.id_satuan = "";
			this.material.id_produk = "";
			this.material.nama_bahan_baku = "";

			this.validation = [];

			this.modalBahanBaku = false;
		},
		async addBahanBaku() {
			this.isloading = true;
			this.$axios
				.post("/api/addBahanBaku", {
					nama_bahan_baku: this.material.nama_bahan_baku,
					id_satuan: this.material.id_satuan,
					id_produk: this.material.id_produk,
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
		},
	},
};
</script>

<style></style>
