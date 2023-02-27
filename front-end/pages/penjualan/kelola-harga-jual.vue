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
									<option :value="product.id_produk" v-for="product in products">
										{{ product.nama_produk }}
									</option>
								</select>
							</div>
						</div>
						<div class="col-span-1">
							<div class="flex flex-col space-y-2">
								<label class="label">Pilih Bahan Baku</label>
								<select class="form-input form-input-lg" v-model="id_bahan_baku">
									<option value="">Pilih Bahan Baku</option>
									<option :value="data.bahan_baku.id_bahan_baku" v-for="data in bahan_baku">
										{{ data.bahan_baku.nama_bahan_baku }}
									</option>
								</select>
							</div>
						</div>
					</div>
					<div class="flex justify-end gap-4">
						<button type="submit" @click="generateData" class="btn btn-sm btn-blue">Generate Data</button>
						<button type="submit" @click="modal = !modal" v-if="data_harga.harga" class="btn btn-sm btn-green">Update Harga</button>
					</div>

					<Modal size="max-w-lg" title="Update Harga Jual Produk" @close-modal="closeModal" v-show="modal">
						<template #content>
							<div class="col-span-12">
								<label class="label">Harga terbaru</label>
								<div class="flex items-end gap-2">
									<number placeholder="Harga Terbaru.." class="form-input form-input-lg" v-model="harga_jual.harga" v-bind="number"></number>
								</div>
								<!-- <input type="hidden" v-model="produk.id_produk" :value="id_produk" />
								<input type="hidden" v-model="produk.id_bahan_baku" :value="id_bahan_baku" /> -->
							</div>
						</template>
						<template #submit>
							<button class="btn btn-lg btn-green" @click="updateHarga">Submit</button>
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
							<tbody class="divide-y-2 divide-gray-100 divide-dotted">
								<template v-if="data_harga.harga">
									<tr class="text-sm" v-for="(data, index) in data_harga.harga">
										<td class="p-3">{{ index + 1 }}</td>
										<td class="p-3">Rp. {{ Intl.NumberFormat().format(data.harga_produk) }}</td>
										<td class="p-3">{{ $moment(data.tgl_diubah).format("DD MMMM YYYY") }}</td>
									</tr>
								</template>
								<template v-else>
									<tr>
										<td colspan="3">
											<div class="flex items-center justify-center gap-4 py-20 font-semibold text-center">
												<svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
													<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
												</svg>
												<span class="text-2xl"> Pilih Data </span>
											</div>
										</td>
									</tr>
								</template>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-span-12 md:col-span-6">
				<div class="space-y-4 card">
					<p class="text-lg font-semibold">Hasil Analisis HPP dan Harga Jual</p>
					<div>
						<div v-if="data_harga.hpp">
							<p class="text-xs font-semibold">Harga Sebelumnya</p>
							Rp. {{ Intl.NumberFormat().format(data_harga.harga[data_harga.harga.length - 1].harga_produk) }}
						</div>
						<!-- <div v-for="data in data_harga.hpp" class="flex justify-between px-4 py-2 bg-white border rounded-md">
							<p class="text-xs font-semibold">{{ $moment(data.tgl_analisa).format("DD MMMM YYYY") }}</p>
							<p class="text-xs">Rp. {{ Intl.NumberFormat().format(data.nilai_hpp) }}</p>
						</div> -->
					</div>
					<div v-if="data_harga">
						<p class="text-xs font-semibold" v-if="data_harga.hpp">Nilai Analisis HPP Terbaru</p>
						<div v-for="data in data_harga.hpp" class="flex justify-between px-4 py-2 bg-white border rounded-md">
							<p class="text-xs font-semibold">{{ $moment(data.tgl_analisa).format("DD MMMM YYYY") }}</p>
							<p class="text-xs">Rp. {{ Intl.NumberFormat().format(data.nilai_hpp) }}</p>
						</div>
					</div>
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
	middleware: "role/manager-penjualan",
	data() {
		return {
			modal: false,

			products: [],
			bahan_baku: [],

			id_produk: "",
			id_bahan_baku: "",

			harga_jual: {
				harga: "",
			},

			data_harga: [],
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
				.get("/api/harga?id_produk=" + this.id_produk + "&id_bahan_baku=" + this.id_bahan_baku)
				.then((response) => {
					this.data_harga = response.data;
				})
				.catch((error) => {
					console.log(error);
				});
		},
		async updateHarga() {
			this.$axios
				.post("/api/harga", {
					sku: this.data_harga.id_sku,
					harga: this.harga_jual.harga,
				})
				.then((response) => {
					this.closeModal();
					this.generateData();
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
			this.harga_jual.harga = "";
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
