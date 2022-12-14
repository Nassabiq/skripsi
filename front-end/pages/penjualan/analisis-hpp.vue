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
			<!-- {{ hpp }} -->
			<template>
				<table class="table mt-4 table-auto table-produk">
					<thead class="bg-gray-100">
						<tr class="text-center text-gray-800 font-title">
							<th rowspan="2" class="p-1 border-table">Tgl</th>
							<th colspan="3" class="p-2 border-table">Pembelian</th>
							<th colspan="3" class="p-2 border-table">Penjualan</th>
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
						<tr class="text-sm text-center" v-for="data in hpp">
							<td class="p-2 border-table">{{ data.tanggal }}</td>
							<td class="p-2 border-table" v-text="data.pembelian.qty > 0 ? data.pembelian.qty : '-'"></td>
							<td class="p-2 border-table" v-text="data.pembelian.harga > 0 ? data.pembelian.harga : '-'"></td>
							<td class="p-2 border-table" v-text="data.pembelian.jumlah > 0 ? data.pembelian.jumlah : '-'"></td>
							<td class="p-2 border-table" v-text="data.hpp.qty > 0 ? data.hpp.qty : '-'"></td>
							<td class="p-2 border-table" v-text="data.hpp.harga > 0 ? data.hpp.harga : '-'"></td>
							<td class="p-2 border-table" v-text="data.hpp.jumlah > 0 ? data.hpp.jumlah : '-'"></td>
							<td class="p-2 border-table">
								<ul v-if="qtyResult(data.persediaan).length > 0">
									<li v-for="data in qtyResult(data.persediaan)" v-text="data"></li>
								</ul>
								<span v-else>{{ qtyResult(data.persediaan)[0] }}</span>
							</td>
							<td class="p-2 border-table">
								<ul v-if="hargaResult(data.persediaan).length > 0">
									<li v-for="data in hargaResult(data.persediaan)">{{ "Rp. " + Intl.NumberFormat().format(data) }}</li>
								</ul>
								<span v-else>{{ "Rp. " + Intl.NumberFormat().format(hargaResult(data.persediaan)[0]) }}</span>
							</td>
							<td class="p-2 border-table">
								<ul v-if="totalResult(data.persediaan).length > 0">
									<li v-for="data in totalResult(data.persediaan)">{{ "Rp. " + Intl.NumberFormat().format(data) }}</li>
								</ul>
								<span v-else>{{ "Rp. " + Intl.NumberFormat().format(totalResult(data.persediaan)[0]) }}</span>
							</td>
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
					this.hpp = response.data;
				})
				.catch((error) => {
					console.log(error);
				});
		},

		qtyResult(data) {
			const result = [];
			Object.keys(data).forEach((k) => result.push(data[k].qty));
			return result;
		},
		hargaResult(data) {
			const result = [];
			Object.keys(data).forEach((k) => result.push(data[k].harga));

			return result;
		},
		totalResult(data) {
			const result = [];
			Object.keys(data).forEach((k) => result.push(data[k].jumlah));

			return result;
		},
		// closeModal() {
		// 	this.modal = false;
		// 	this.produk = {
		// 		id_produk: "",
		// 		harga: "",
		// 	};
		// },
	},
};
</script>
