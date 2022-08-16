<template>
	<div class="margin-auth">
		<div class="grid grid-cols-12 gap-4">
			<div class="col-span-12 md:col-span-6">
				<div class="space-y-4 card">
					<div class="flex flex-col space-y-2">
						<label class="label">Kategori Produk</label>
						<select class="form-input form-input-lg" v-model="data_produk" @change="showData">
							<option value="">Pilih Produk</option>
							<option :value="product.id_produk" v-for="product in products">
								{{ product.nama_produk }}
							</option>
						</select>
					</div>
					<!-- {{ sortedItems }} -->
					<div class="flex flex-col space-y-2">
						<label class="label">Rentang Tanggal</label>
						<div class="grid grid-cols-2 gap-4">
							<div class="col-span-1">
								<client-only><date-picker placeholder="Start Date" style="width: 100%;" :format="'DD-MM-YYYY'" valueType="format" v-model="start_date" /></client-only>
							</div>
							<div class="col-span-1">
								<client-only><date-picker placeholder="End Date" style="width: 100%;" :format="'DD-MM-YYYY'" valueType="format" v-model="end_date" /></client-only>
							</div>
						</div>
					</div>
					<div class="flex justify-between">
						<p class="font-semibold">Riwayat Harga</p>
						<button type="submit" class="btn btn-sm btn-blue" @click="modal = !modal">Update Harga</button>
					</div>

					<Modal size="max-w-xl" title="Update Harga Jual Produk" @close-modal="closeModal" v-show="modal">
						<template #content>
							<div class="col-span-12 md:col-span-7">
								<label class="label">Kategori Produk</label>
								<select class="form-input form-input-lg" v-model="produk.id_produk" @change="showSatuan(produk.id_produk)">
									<option value="">Pilih Produk</option>
									<option :value="product.id_produk" v-for="product in products">
										{{ product.nama_produk }}
									</option>
								</select>
								<p class="mt-2 text-xs text-red-500" v-if="validation.id_produk">{{ validation.id_produk[0] }}</p>
							</div>
							<div class="col-span-12 md:col-span-5">
								<label class="label">Harga terbaru</label>
								<div class="flex items-end gap-2">
									<number placeholder="Harga Terbaru.." class="form-input form-input-lg" v-model="produk.harga" v-bind="number"></number>
									<p class="text-sm font-semibold">/{{ produk.satuan }}</p>
								</div>
							</div>
						</template>
						<template #submit>
							<button class="btn btn-lg btn-green" @click="updateHarga">
								Submit
							</button>
						</template>
					</Modal>

					<div class="relative overflow-x-auto overflow-y-auto bg-white rounded-lg shadow">
						<table class="table table-auto table-produk">
							<thead class="bg-gray-100">
								<tr class="text-left text-gray-800 font-title">
									<th class="p-3"></th>
									<th class="p-3">Tgl Perubahan</th>
									<th class="p-3">Harga</th>
									<th class="p-1"></th>
								</tr>
							</thead>
							<tbody class="divide-y-2 divide-gray-100 divide-dotted">
								<tr class="text-sm" v-for="(data, index) in sortedItems">
									<td class="p-3">{{ index + 1 }}</td>
									<td class="p-3">{{ $moment(data.tgl_diubah).format("DD MMMM YYYY") }}</td>
									<td class="p-3">Rp. {{ Intl.NumberFormat().format(data.harga) }}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-span-12 md:col-span-6">
				<div class="card">
					<p class="text-lg font-semibold">Hasil Analisis HPP dan Harga Jual</p>
					{{ sortedItems }}
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import {number} from "@coders-tm/vue-number-format";
export default {
	name: "KelolaHargaJual",
	layout: "auth",
	auth: false,
	components: {number},
	data() {
		return {
			start_date: null,
			end_date: null,
			modal: false,

			data_produk: "",

			produk: {
				id_produk: "",
				harga: "",
				satuan: "",
			},
			products: [],
			data_harga: null,
			validation: [],

			number: {
				decimal: ",",
				separator: ".",
				prefix: "Rp. ",
				precision: 2,
				masked: false,
			},
		};
	},
	mounted() {
		this.getAllData();
	},
	computed: {
		sortedItems() {
			let data = this.data_harga;
			if (data) {
				return data.sort((a, b) => (this.$moment(a.tgl_diubah).isBefore(this.$moment(b.tgl_diubah)) ? 1 : -1));
			}
		},
	},
	methods: {
		async getAllData() {
			this.$axios
				.get("/api/product")
				.then((response) => {
					this.products = response.data;
				})
				.catch((error) => {
					console.log(error);
				});
		},
		async updateHarga() {
			this.$axios
				.post("/api/updateHarga", this.produk)
				.then((response) => {
					this.closeModal();
					this.getAllData();
				})
				.then(() => {
					this.$swal.fire({
						icon: "success",
						title: "Success...",
						text: "Data Berhasil ditambahkan!",
						showConfirmButton: false,
						timer: 1500,
						timerProgressBar: true,
					});
				})
				.catch((error) => {
					console.log(error.response.data);
					this.validation = error.response.data;
				});
		},
		closeModal() {
			this.modal = false;
			this.produk.id_produk = "";
			this.produk.harga = "";
			this.produk.satuan = "";
		},
		showSatuan(id) {
			let products = this.products;

			products = products.filter((data) => {
				return data.id_produk.includes(id);
			});
			if (id) {
				if (!this.produk.satuan) {
					this.produk.satuan = products[0].categories.satuan.nama_satuan;
				} else {
					this.produk.satuan = null;
					this.produk.satuan = products[0].categories.satuan.nama_satuan;
				}
			} else {
				this.produk.satuan = null;
			}
		},
		showData() {
			let products = this.products;
			let selectedProduct = this.data_produk;

			products = products.filter((data) => {
				return data.id_produk.includes(selectedProduct);
			});

			if (selectedProduct) {
				if (!this.data_harga) {
					this.data_harga = products[0].harga;
				} else {
					this.data_harga = null;
					this.data_harga = products[0].harga;
				}
			} else {
				this.data_harga = null;
			}
		},
		// async logout(){
		//   await this.$auth.logout();
		//   this.$router.push('/login');
		// }
	},
};
</script>
