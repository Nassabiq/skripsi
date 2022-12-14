<template>
	<div class="margin-auth">
		<div class="grid grid-cols-12 gap-4">
			<div class="col-span-12 md:col-span-6">
				<div class="space-y-4 card">
					<div class="grid grid-cols-2 gap-4">
						<div class="col-span-1">
							<div class="flex flex-col space-y-2">
								<label class="label">Produk</label>
								<select class="form-input form-input-lg" v-model="id_produk" @change="getBahanBaku">
									<option value="">Pilih Produk</option>
									<!-- <option :value="product.id_produk" v-for="product in products">
										{{ product.nama_produk }}
									</option> -->
								</select>
							</div>
						</div>
						<div class="col-span-1">
							<div class="flex flex-col space-y-2">
								<label class="label">Pilih Bahan Baku</label>
								<select class="form-input form-input-lg" v-model="id_bahan_baku">
									<option value="">Pilih Bahan Baku</option>
									<!-- <option :value="data.bahan_baku.id_bahan_baku" v-for="data in bahan_baku">
										{{ data.bahan_baku.nama_bahan_baku }}
									</option> -->
								</select>
							</div>
						</div>
					</div>
					<div class="flex justify-end">
						<button type="submit" @click.prevent="generateData" class="btn btn-sm btn-blue">Generate Data</button>
					</div>

					<Modal size="max-w-xl" title="Update Harga Jual Produk" @close-modal="closeModal" v-show="modal">
						<template #content>
							<div class="col-span-12 md:col-span-7">
								<label class="label">Kategori Produk</label>
								<!-- <select class="form-input form-input-lg" v-model="produk.id_produk" @change="showSatuan(produk.id_produk)">
									<option value="">Pilih Produk</option>
									<option :value="product.id_produk" v-for="product in products">
										{{ product.nama_produk }}
									</option>
								</select> -->
								<!-- <p class="mt-2 text-xs text-red-500" v-if="validation.id_produk">{{ validation.id_produk[0] }}</p> -->
							</div>
							<div class="col-span-12 md:col-span-5">
								<label class="label">Harga terbaru</label>
								<div class="flex items-end gap-2">
									<!-- <number placeholder="Harga Terbaru.." class="form-input form-input-lg" v-model="produk.harga" v-bind="number"></number>
									<p class="text-sm font-semibold">/{{ produk.satuan }}</p> -->
								</div>
							</div>
						</template>
						<template #submit>
							<!-- <button class="btn btn-lg btn-green" @click="updateHarga">Submit</button> -->
						</template>
					</Modal>

					<div class="relative overflow-x-auto overflow-y-auto bg-white rounded-lg shadow">
						<table class="table table-auto table-produk">
							<thead class="bg-gray-100">
								<tr class="text-left text-gray-800 font-title">
									<th class="p-3"></th>
									<th class="p-3">Harga</th>
									<th class="p-3">Tgl Diubah</th>
									<th class="p-1"></th>
								</tr>
							</thead>
							<!-- <tbody class="divide-y-2 divide-gray-100 divide-dotted">
								<tr class="text-sm" v-for="(data, index) in sortedItems">
									<td class="p-3">{{ index + 1 }}</td>
									<td class="p-3">{{ $moment(data.tgl_diubah).format("DD MMMM YYYY") }}</td>
									<td class="p-3">Rp. {{ Intl.NumberFormat().format(data.harga) }}</td>
								</tr>
							</tbody> -->
						</table>
					</div>
				</div>
			</div>
			<div class="col-span-12 md:col-span-6">
				<div class="card">
					<p class="text-lg font-semibold">Hasil Analisis HPP dan Harga Jual</p>
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
	components: {number},
	data() {
		return {
			modal: false,

			products: [],
			bahan_baku: [],

			id_produk: "",
			id_bahan_baku: "",

			produk: {
				id_produk: "",
				harga: "",
				satuan: "",
			},

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
		this.getProduk();
	},
	methods: {
		async getProduk() {
			this.$axios
				.get("/api/produk")
				.then((response) => {
					this.products = response.data;
				})
				.catch((error) => {
					console.log(error);
				});
		},
		async getBahanBaku() {
			await this.$axios
				.get("/api/sku/bahan-baku?id_produk=" + this.id_produk)
				.then((response) => {
					this.bahan_baku = response.data;
				})
				.catch((error) => {
					console.log(error);
				});
		},
		async generateData() {
			this.$axios
				.get("/api/produk/harga-produk?id_produk=" + this.id_produk + "&id_bahan_baku=" + this.id_bahan_baku)
				.then((response) => {
					this.data_harga = response.data;
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
					this.getProduk();
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
	},
};
</script>
