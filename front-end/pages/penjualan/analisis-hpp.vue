<template>
	<div class="gap-4 margin-auth">
		<div class="grid grid-cols-12 gap-4 card">
			<div class="flex flex-col col-span-12 space-y-2 md:col-span-4">
				<label class="label">Produk</label>
				<select class="form-input form-input-lg" @change="getBahanBaku" v-model="id_produk">
					<option value="">Pilih Produk</option>
					<option :value="product.id_produk" v-for="product in products">
						{{ product.nama_produk }}
					</option>
				</select>
			</div>
			<div class="flex flex-col col-span-12 space-y-2 md:col-span-4">
				<label class="label">Bahan Baku</label>
				<select class="form-input form-input-lg" v-model="id_bahan_baku">
					<option value="">Pilih Bahan Baku</option>
					<option :value="data.bahan_baku.id_bahan_baku" v-for="data in bahan_baku">
						{{ data.bahan_baku.nama_bahan_baku }}
					</option>
				</select>
			</div>
			<div class="flex flex-col col-span-12 space-y-2 md:col-span-2">
				<label class="text-xs font-semibold text-gray-700">Tampilkan data dari:</label>
				<input type="date" class="px-6 py-2 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg shadow focus:outline-2 focus:outline-blue-100 focus:ring-2 focus:ring-blue-300" v-model="from" />
			</div>
			<div class="flex flex-col col-span-12 space-y-2 md:col-span-2">
				<label class="text-xs font-semibold text-gray-700">sampai : </label>
				<input type="date" class="px-6 py-2 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg shadow focus:outline-2 focus:outline-blue-100 focus:ring-2 focus:ring-blue-300" v-model="to" />
			</div>
			<div class="flex items-end justify-end col-span-12">
				<button type="submit" class="btn btn-lg btn-blue" @click.prevent="getDetailHPP">Analyze</button>
			</div>
		</div>
		<div class="card">
			<p class="text-lg font-semibold">Harga Pokok Produksi</p>
			<ul>
				<li v-for="data in hpp">{{ data }}</li>
			</ul>
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

			id_produk: "",
			id_bahan_baku: "",
			from: "",
			to: "",

			products: [],
			bahan_baku: [],

			hpp: [],

			produksi: [],
			pembelian: [],

			validation: [],
		};
	},
	mounted() {
		this.getAllData();
	},
	computed: {},
	methods: {
		async getAllData() {
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
		async getDetailHPP() {
			this.$axios
				.get("/api/analisis-hpp?id_produk=" + this.id_produk + "&id_bahan_baku=" + this.id_bahan_baku + "&from=" + this.from + "&to=" + this.to)
				.then((response) => {
					console.log(JSON.parse(response.data));
					this.hpp = response.data;
				})
				.then(() => {
					// let produksi = this.produksi != null ? this.produksi.material : null;
					// let pembelian = this.pembelian != null ? this.pembelian.material : null;
					// let data = this.hpp;
					// if (data.length > 0) data.pop();
					// if (produksi && pembelian) {
					// 	for (let i = 0; i < produksi.length; i++) {
					// 		for (let j = 0; j < pembelian.length; j++) {
					// 			if (produksi[i].id_material == pembelian[j].id_material) {
					// 				data.push({
					// 					bahan_baku: produksi[i].nama_bahan_baku,
					// 					produksi: produksi[i].produksi,
					// 					pembelian: pembelian[j].detail_barang,
					// 				});
					// 				// return data;
					// 			}
					// 		}
					// 	}
					// } else if (produksi == null) {
					// 	for (let i = 0; i < pembelian.length; i++) {
					// 		data.push({
					// 			bahan_baku: pembelian[i].nama_bahan_baku,
					// 			pembelian: pembelian[i].detail_barang,
					// 		});
					// 		// return data;
					// 	}
					// } else if (pembelian == null) {
					// 	for (let j = 0; j < produksi.length; j++) {
					// 		data.push({bahan_baku: produksi[i].nama_bahan_baku, produksi: produksi[j].produksi});
					// 		// return data;
					// 	}
					// } else if (!pembelian && !produksi) {
					// 	data == null;
					// }
				})
				.catch((error) => {
					console.log(error);
				});
			// if (this.data_produk && this.bulan && this.tahun) {
			// }
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
