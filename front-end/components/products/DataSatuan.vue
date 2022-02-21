<template>
	<div>
		<div class="my-4">
			<button class="btn-with-icon btn btn-sm btn-blue" @click="modalSatuan = !modalSatuan">
				<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
				</svg>
				Tambah Jenis Satuan
			</button>
		</div>
		<div class="my-4 overflow-x-auto overflow-y-auto bg-white rounded-lg shadow">
			<table class="table table-auto table-kategori-produk">
				<thead class="bg-gray-100">
					<tr class="text-left text-gray-800 font-title">
						<th class="p-3">No.</th>
						<th class="p-3">Nama Satuan</th>
						<th class="p-1"></th>
					</tr>
				</thead>
				<tbody class="divide-y-2 divide-gray-100 divide-dotted">
					<tr class="text-sm" v-for="(unit, index) in units">
						<td class="p-3">{{ index + 1 }}</td>
						<td class="p-3">{{ unit.nama_satuan }}</td>
						<td class="p-2">
							<button class="btn btn-sm btn-indigo" @click="editSatuan(unit, index)">
								<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
									<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
								</svg>
							</button>
							<button class="btn btn-sm btn-red" @click="deleteSatuan(unit, index)">
								<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
									<path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
								</svg>
							</button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<!-- Modal Tambah Satuan -->
		<Modal size="max-w-md" :title="updateMode ? 'Edit Satuan' : 'Tambah Satuan'" @close-modal="closeModalUnit" v-show="modalSatuan" ref="modalSatuan">
			<!-- <form @submit="addSatuan"> -->
			<template #content>
				<div class="col-span-12">
					<label class="label">Nama Satuan</label>
					<input v-model="satuan.nama_satuan" type="text" class="form-input form-input-lg" placeholder="Nama Satuan ... " />
					<p class="mt-2 text-xs text-red-500" v-if="validation.nama_satuan">{{ validation.nama_satuan[0] }}</p>
				</div>
			</template>
			<template #submit>
				<button @click="addSatuan" v-if="!updateMode" class="btn btn-lg btn-green">
					Submit
				</button>
				<button @click="updateSatuan" v-else class="btn btn-lg btn-indigo">
					Update
				</button>
			</template>
			<!-- </form> -->
		</Modal>
		<Spinner v-show="isloading"></Spinner>
	</div>
</template>
<script>
import Modal from "../ModalComponent.vue";
import Spinner from "../SpinnerLoading.vue";
export default {
	name: "DataSatuan",
	props: ["units"],
	components: {
		Modal,
		Spinner,
	},
	data() {
		return {
			modalSatuan: false,

			isloading: false,
			updateMode: false,

			satuan: {
				id_satuan: "",
				nama_satuan: "",
			},

			validation: [],
		};
	},
	methods: {
		closeModalUnit() {
			if ((this.updateMode = true)) {
				this.updateMode = false;
			}

			this.modalSatuan = false;
			this.satuan.id_satuan = "";
			this.satuan.nama_satuan = "";
			this.validation = [];
		},

		// Satuan
		async addSatuan() {
			this.isloading = true;
			setTimeout(() => {
				this.$axios
					.post("/api/addSatuan", {
						nama_satuan: this.satuan.nama_satuan,
					})
					.then((res) => {
						this.closeModalUnit();
						this.units.push(res.data);
						console.log(res);
					})
					.catch((error) => {
						this.validation = error.response.data;
					})
					.finally(() => {
						this.isloading = false;
					});
			}, 500);
		},

		editSatuan(unit) {
			this.updateMode = true;
			this.modalSatuan = true;
			this.satuan.nama_satuan = unit.nama_satuan;
			this.satuan.id_satuan = unit.id_satuan;
		},

		async updateSatuan() {
			this.isloading = true;
			setTimeout(() => {
				this.$axios
					.post(`/api/updateSatuan/${this.satuan.id_satuan}`, {
						nama_satuan: this.satuan.nama_satuan,
					})
					.then((res) => {
						this.closeModalUnit();
						this.$emit("get-data", res.data);
					})
					.catch((error) => {
						console.log(error);
						this.validation = error.response.data;
					})
					.finally(() => {
						this.isloading = false;
					});
			}, 500);
		},

		async deleteSatuan(unit, index) {
			// console.log(unit.index);
			this.isloading = true;
			setTimeout(() => {
				this.$axios
					.post("api/deleteSatuan/" + unit.id_satuan)
					.then(() => {
						this.units.splice(index, 1);
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
