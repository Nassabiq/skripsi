<template>
	<div class="gap-4 margin-auth">
		<div class="grid grid-cols-12 gap-4 card">
			<div class="flex flex-col col-span-12 space-y-2 md:col-span-4">
				<label class="label">Kategori Produk</label>
				<select class="form-input form-input-lg" v-model="data_produk">
					<option value="">Pilih Produk</option>
					<option :value="product.id_produk" v-for="product in products">
						{{ product.nama_produk }}
					</option>
				</select>
			</div>
			<div class="flex flex-col col-span-12 space-y-2 md:col-span-3">
				<label class="label">Bulan</label>
				<select class="form-input form-input-lg" v-model="bulan">
					<option value="">Pilih Bulan</option>
					<option value="01">Januari</option>
					<option value="02">Februari</option>
					<option value="03">Maret</option>
					<option value="04">April</option>
					<option value="05">Mei</option>
					<option value="06">Juni</option>
					<option value="07">Juli</option>
					<option value="08">Agustus</option>
					<option value="09">September</option>
					<option value="10">Oktober</option>
					<option value="11">November</option>
					<option value="12">Desember</option>
				</select>
			</div>
			<div class="flex flex-col col-span-12 space-y-2 md:col-span-3">
				<label class="label">Tahun</label>
				<input type="text" class="form-input form-input-lg" v-model.number="tahun" placeholder="Isi Tahun" />
			</div>
			<div class="flex items-end justify-end col-span-12 md:col-span-2">
				<button type="submit" class="btn btn-lg btn-blue" @click.prevent="getDetailHPP">Show Data</button>
			</div>

			<!-- <Modal size="max-w-xl" title="Update Harga Jual Produk" @close-modal="closeModal" v-show="modal">
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
			</Modal> -->
		</div>
		<div class="card">
			<p class="text-lg font-semibold">Harga Pokok Produksi</p>
			<template>
				<!-- <div class="flex justify-between">
					<p class="text-sm font-semibold">Nama Bahan Baku : {{ data.bahan_baku }}</p>
					<p class="text-sm">Bulan : {{ $moment(bulan).format("MMMM") }}</p>
				</div> -->
				<table class="table mt-4 table-auto table-produk">
					<thead class="bg-gray-100">
						<tr class="text-center text-gray-800 font-title">
							<th rowspan="2" class="p-1 border-table">Tgl</th>
							<th colspan="3" class="p-2 border-table">Pembelian</th>
							<th colspan="3" class="p-2 border-table">Produksi</th>
							<th colspan="3" class="p-2 border-table">Persediaan</th>
						</tr>
						<tr class="text-center text-gray-800 font-title">
							<th class="p-2 border-table">Qty</th>
							<th class="p-2 border-table">Harga / Unit</th>
							<th class="p-2 border-table">Total</th>
							<th class="p-2 border-table">Qty</th>
							<th class="p-2 border-table">Harga / Unit</th>
							<th class="p-2 border-table">Total</th>
							<th class="p-2 border-table">Qty</th>
							<th class="p-2 border-table">Harga / Unit</th>
							<th class="p-2 border-table">Total</th>
						</tr>
					</thead>
					<tbody class="divide-y-2 divide-gray-100 divide-dotted">
						<tr class="text-sm text-center">
							<td class="p-2 border-table">1</td>
							<td class="p-2 border-table"></td>
							<td class="p-2 border-table"></td>
							<td class="p-2 border-table"></td>
							<td class="p-2 border-table"></td>
							<td class="p-2 border-table"></td>
							<td class="p-2 border-table"></td>
							<td class="p-2 border-table">{{ persediaan.qty }}</td>
							<td class="p-2 border-table">{{ persediaan.harga }}</td>
							<td class="p-2 border-table">{{ persediaan.qty * persediaan.harga }}</td>
						</tr>
						<template v-for="(data, index) in hasilHPP.data">
							<tr class="text-sm text-center">
								<!-- v-for="result in hasilHPP.result" -->
								<!-- <template v-if="index === result.id"> -->
								<td class="p-1 border-table">{{ $moment(data.tgl).format("DD") }}</td>
								<td class="p-2 border-table">{{ data.id == "pembelian" ? data.qty : "-" }}</td>
								<td class="p-2 border-table">{{ data.id == "pembelian" ? "Rp. " + Intl.NumberFormat().format(data.harga) : "-" }}</td>
								<td class="p-2 border-table">{{ data.id == "pembelian" ? "Rp. " + Intl.NumberFormat().format(data.total) : "-" }}</td>
								<td class="p-2 border-table">{{ data.id == "produksi" ? data.qty : "-" }}</td>
								<td class="p-2 border-table">
									<!-- <ul v-if="Array.isArray(result.harga)">
										<li>
											{{ "Rp. " + Intl.NumberFormat().format(result.harga[0]) }}
										</li>
									</ul> -->
									<!-- <p>{{ "Rp. " + Intl.NumberFormat().format(result.harga) }}</p> -->
								</td>
								<td class="p-2 border-table">
									<!-- {{ data.id != "produksi" ? "-" : Array.isArray(result.harga) ? "Rp. " + Intl.NumberFormat().format(data.qty * result.harga[0]) : "Rp. " + Intl.NumberFormat().format(data.qty * result.harga) }} -->
								</td>

								<!-- <td class="p-2 border-table">
										<ul v-if="Array.isArray(result.qty)">
											<li v-for="qty in result.qty">{{ qty }}</li>
										</ul>
										<p v-else>{{ result.qty }}</p>
									</td>
									<td class="p-2 border-table">
										<ul v-if="Array.isArray(result.harga)">
											<li v-for="harga in result.harga">{{ "Rp. " + Intl.NumberFormat().format(harga) }}</li>
										</ul>
										<p v-else>{{ "Rp. " + Intl.NumberFormat().format(result.harga) }}</p>
									</td>
									<td class="p-2 border-table">
										<ul v-if="Array.isArray(result.harga) && Array.isArray(result.qty)">
											<li v-for="(harga, i) in result.harga">
												<p v-for="(qty, j) in result.qty">
													<span v-if="i === j">
														{{ "Rp. " + Intl.NumberFormat().format(harga * qty) }}
													</span>
												</p>
											</li>
										</ul>
										<p v-else>{{ "Rp. " + Intl.NumberFormat().format(result.harga * result.qty) }}</p>
									</td> -->
								<!-- </template> -->
							</tr>
						</template>
					</tbody>
				</table>
			</template>
		</div>
	</div>
</template>

<script>
// import {number} from "@coders-tm/vue-number-format";
export default {
	name: "AnalisisHPP",
	layout: "auth",
	// components: {number},
	data() {
		return {
			persediaan: {
				qty: 0,
				harga: 0,
			},

			modal: false,

			data_produk: "",
			bulan: "",
			tahun: "",

			products: [],

			hpp: [],

			produksi: [],
			pembelian: [],

			validation: [],
		};
	},
	mounted() {
		this.getAllData();
	},
	computed: {
		concatData() {
			let hpp = this.hpp;
			let result = [];
			if (result.length > 0) result.pop();

			hpp.forEach((index) => {
				for (let i = 0; i < index.pembelian.length; i++) {
					result.push({
						id: "pembelian",
						tgl: this.$moment(index.pembelian[i].barang_masuk.tgl_barang_masuk).format("DD MMMM YYYY hh:mm:ss"),
						qty: index.pembelian[i].qty,
						harga: index.pembelian[i].harga,
						total: index.pembelian[i].qty * index.pembelian[i].harga,
					});
				}
				for (let j = 0; j < index.produksi.length; j++) {
					result.push({
						id: "produksi",
						tgl: this.$moment(index.produksi[j].tgl_produksi).format("DD MMMM YYYY hh:mm:ss"),
						qty: index.produksi[j].transaksi.qty,
						// harga: index.produksi[j].transaksi.subtotal,
						// total: index.produksi[j].transaksi.qty * index.produksi[j].transaksi.subtotal,
					});
				}
			});

			return result.sort((a, b) => (this.$moment(b.tgl).isBefore(this.$moment(a.tgl)) ? 1 : -1));
		},
		hasilHPP() {
			let data = this.concatData;
			let result = [];
			let harga = [];
			let qty = [];
			let id = null;

			for (let i = 0; i < data.length; i++) {
				// for (let j = 0; j < data.length; j++) {
				if (data[i].id == "pembelian") {
					data.reduce((result, item) => {
						// re
					}, []);
					// if (harga.length == 0) {
					// 	id = i;
					// 	harga[0] = data[i].harga;
					// 	qty[0] = data[i].qty;
					// 	result.push({id, harga, qty});
					// }
					// if (harga.length > 0 && harga.indexOf(data[i].harga) > -1) {
					// 	for (let j = 0; j < harga.length; j++) {
					// 		if (harga[j] == data[i].harga) {
					// 			id = i;
					// 			harga[i] = data[i].harga;
					// 			qty[i] = qty[i] + data[i].qty;
					// 			result.push({id, harga, qty});
					// 			// } else {
					// 			// if (harga[j] != data[i].harga) {
					// 			// 	id = i;
					// 			// 	harga = harga.concat(data[i].harga);
					// 			// 	qty = qty.concat(data[i].qty);
					// 			// 	result.push({id, harga, qty});
					// 		}
					// 	}
					// 	// }
					// }
					// else {
					// }
				} else if (data[i].id == "produksi") {
					// if (Array.isArray(harga) && Array.isArray(qty)) {
					// 	if (qty[0] != 0 && qty[0] > data[i].qty) {
					// 		id = i;
					// 		harga = harga[0];
					// 		qty[0] -= data[i].qty;
					// 		result.push({id, harga, qty});
					// 	}
					// } else {
					// 	id = i;
					// 	harga = harga;
					// 	qty -= data[i].qty;
					// 	result.push({id, harga, qty});
					// }
				}
				// }
			}
			return {data, result};
			// return {harga, qty};
		},
	},
	methods: {
		async getAllData() {
			this.$axios
				.get("/api/product")
				.then((response) => {
					this.products = response.data.products;
				})
				.catch((error) => {
					console.log(error);
				});
		},
		async getDetailHPP() {
			if (this.data_produk && this.bulan && this.tahun) {
				this.$axios
					.get("/api/analisis-hpp?id=" + this.data_produk + "&month=" + this.bulan + "&year=" + this.tahun)
					.then((response) => {
						this.produksi = response.data.produksi;
						this.pembelian = response.data.pembelian;
					})
					.then(() => {
						let produksi = this.produksi != null ? this.produksi.material : null;
						let pembelian = this.pembelian != null ? this.pembelian.material : null;
						let data = this.hpp;

						if (data.length > 0) data.pop();
						if (produksi && pembelian) {
							for (let i = 0; i < produksi.length; i++) {
								for (let j = 0; j < pembelian.length; j++) {
									if (produksi[i].id_material == pembelian[j].id_material) {
										data.push({
											bahan_baku: produksi[i].nama_bahan_baku,
											produksi: produksi[i].produksi,
											pembelian: pembelian[j].detail_barang,
										});
										// return data;
									}
								}
							}
						} else if (produksi == null) {
							for (let i = 0; i < pembelian.length; i++) {
								data.push({
									bahan_baku: pembelian[i].nama_bahan_baku,
									pembelian: pembelian[i].detail_barang,
								});
								// return data;
							}
						} else if (pembelian == null) {
							for (let j = 0; j < produksi.length; j++) {
								data.push({bahan_baku: produksi[i].nama_bahan_baku, produksi: produksi[j].produksi});
								// return data;
							}
						} else if (!pembelian && !produksi) {
							data == null;
						}
					})
					.finally(() => {
						this.data_produk = "";
						this.bulan = "";
						this.tahun = "";
					})
					.catch((error) => {
						console.log(error);
					});
			}
		},
		// closeModal() {
		// 	this.modal = false;
		// 	this.produk = {
		// 		id_produk: "",
		// 		harga: "",
		// 	};
		// },
		// showSatuan(id) {
		// 	let products = this.products;

		// 	products = products.filter((data) => {
		// 		return data.id_produk.includes(id);
		// 	});
		// 	if (id) {
		// 		if (!this.produk.satuan) {
		// 			this.produk.satuan = products[0].categories.satuan.nama_satuan;
		// 		} else {
		// 			this.produk.satuan = null;
		// 			this.produk.satuan = products[0].categories.satuan.nama_satuan;
		// 		}
		// 	} else {
		// 		this.produk.satuan = null;
		// 	}
		// },
	},
};
</script>
