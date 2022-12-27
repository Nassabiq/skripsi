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
				<button type="submit" class="btn btn-lg btn-blue" @click.prevent="getDetailHPP">Generate Data</button>
			</div>
		</div>
		<div class="card" v-if="hpp && hpp.length > 0">
			<!-- {{ hpp }} -->
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
				<tbody class="divide-y-2 divide-gray-100 divide-dotted" v-if="hpp">
					<tr class="text-sm text-center" v-for="data in hpp[0]">
						<td class="p-2 border-table">{{ data.tanggal }}</td>
						<td class="p-2 border-table" v-text="data.pembelian.qty > 0 ? data.pembelian.qty : '-'"></td>
						<td class="p-2 border-table" v-text="data.pembelian.harga > 0 ? data.pembelian.harga : '-'"></td>
						<td class="p-2 border-table" v-text="data.pembelian.jumlah > 0 ? data.pembelian.jumlah : '-'"></td>
						<td class="p-2 border-table">
							<ul v-if="data.hpp != null">
								<li v-for="data in qtyResult(data.hpp)" v-text="data"></li>
							</ul>
							<span v-else>-</span>
						</td>
						<td class="p-2 border-table">
							<ul v-if="data.hpp != null">
								<li v-for="data in hargaResult(data.hpp)">{{ "Rp. " + Intl.NumberFormat().format(data) }}</li>
							</ul>
							<span v-else>-</span>
						</td>
						<td class="p-2 border-table">
							<ul v-if="data.hpp != null">
								<li v-for="data in totalResult(data.hpp)">{{ "Rp. " + Intl.NumberFormat().format(data) }}</li>
							</ul>
							<span v-else>-</span>
						</td>

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
					<tr v-if="hpp[1]" class="text-sm text-center bg-gray-50">
						<td class="p-2 font-semibold border-table">Total</td>
						<td class="p-2 border-table">{{ hpp[1].pembelian.qty }}</td>
						<td class="p-2 border-table"></td>
						<td class="p-2 border-table">{{ "Rp. " + Intl.NumberFormat().format(hpp[1].pembelian.jumlah) }}</td>
						<td class="p-2 border-table">{{ hpp[1].penjualan.qty }}</td>
						<td class="p-2 border-table"></td>
						<td class="p-2 border-table">{{ "Rp. " + Intl.NumberFormat().format(hpp[1].penjualan.jumlah) }}</td>
						<td class="p-2 border-table">
							<ul v-if="qtyResult(hpp[1].persediaan).length > 0">
								<li v-for="data in qtyResult(hpp[1].persediaan)" v-text="data"></li>
							</ul>
							<span v-else>{{ qtyResult(hpp[1].persediaan) }}</span>
						</td>
						<td class="p-2 border-table">
							<ul v-if="hargaResult(hpp[1].persediaan).length > 0">
								<li v-for="data in hargaResult(hpp[1].persediaan)">{{ "Rp. " + Intl.NumberFormat().format(data) }}</li>
							</ul>
							<span v-else>{{ "Rp. " + Intl.NumberFormat().format(hargaResult(hpp[1].persediaan)) }}</span>
						</td>
						<td class="p-2 border-table">
							<ul v-if="totalResult(hpp[1].persediaan).length > 0">
								<li v-for="data in totalResult(hpp[1].persediaan)">{{ "Rp. " + Intl.NumberFormat().format(data) }}</li>
							</ul>
							<span v-else>{{ "Rp. " + Intl.NumberFormat().format(totalResult(hpp[1].persediaan)) }}</span>
						</td>
					</tr>
				</tbody>
			</table>
			<div class="grid grid-cols-12 gap-4">
				<div class="flex flex-col items-center justify-between col-span-12 px-4 py-2 mx-2 mt-4 bg-white border-2 rounded-lg shadow sm:flex-row border-slate-100 md:col-span-10">
					<p class="font-semibold">Total Biaya Bahan Baku</p>
					<p class="font-semibold">Rp. {{ Intl.NumberFormat().format(hpp[1].penjualan.jumlah) }}</p>
				</div>
				<div class="flex justify-end col-span-12 py-2 mx-2 md:mt-4 md:px-4 md:justify-start md:col-span-2">
					<button @click="openModal" class="btn btn-sm btn-indigo" v-if="hpp[0]">Buat Analisis</button>
				</div>
			</div>

			<Modal size="max-w-xl" title="Input Biaya Overhead" @close-modal="closeModal" v-show="modal">
				<template #content>
					<div class="col-span-12 space-y-4">
						<div class="my-2">
							<label class="label">Biaya Tenaga Kerja</label>
							<!-- <input type="text" class="form-input form-input-lg" placeholder="No Resi ... " v-model="no_resi" /> -->
							<!-- <p class="mt-2 text-xs text-red-500" v-if="validation.resi">{{ validation.resi[0] }}</p> -->
						</div>
						<div class="my-2">
							<label class="label">Biaya Mesin</label>
							<!-- <input type="text" class="form-input form-input-lg" placeholder="No Resi ... " v-model="no_resi" /> -->
							<!-- <p class="mt-2 text-xs text-red-500" v-if="validation.resi">{{ validation.resi[0] }}</p> -->
						</div>
					</div>
				</template>
				<template #submit>
					<!-- <button class="btn btn-lg btn-green" @click.prevent="inputResi(modalData.id_transaksi)">Submit</button> -->
				</template>
			</Modal>
		</div>
	</div>
</template>

<script>
// import {number} from "@coders-tm/vue-number-format";
export default {
	name: "AnalisisHPP",
	layout: "auth",
	// components: {number, },
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

			biaya_produksi: {
				tenaga_kerja: "",
			},

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
		hppQty(data) {
			const result = [];
			Object.keys(data).forEach((k) => result.push(data[k].qty));

			return result;
		},
		totalResult(data) {
			const result = [];
			Object.keys(data).forEach((k) => result.push(data[k].jumlah));

			return result;
		},
		openModal() {
			this.modal = !this.modal;
		},
		closeModal() {
			this.modal = false;
			// this.produk = {
			// 	id_produk: "",
			// 	harga: "",
			// };
		},
	},
};
</script>
