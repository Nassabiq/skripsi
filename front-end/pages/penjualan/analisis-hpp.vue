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
				<p class="mt-2 text-xs text-red-500" v-if="validation.id_produk">{{ validation.id_produk[0] }}</p>
			</div>
			<div class="flex flex-col col-span-12 space-y-2 md:col-span-4">
				<label class="label">Bahan Baku</label>
				<select class="form-input form-input-lg" v-model="id_bahan_baku">
					<option value="">Pilih Bahan Baku</option>
					<option :value="data.bahan_baku.id_bahan_baku" v-for="data in bahan_baku">
						{{ data.bahan_baku.nama_bahan_baku }}
					</option>
				</select>
				<p class="mt-2 text-xs text-red-500" v-if="validation.id_bahan_baku">{{ validation.id_bahan_baku[0] }}</p>
			</div>
			<!-- <div class="flex flex-col col-span-12 space-y-2 md:col-span-2">
				<label class="text-xs font-semibold text-gray-700">Tampilkan data dari:</label>
				<input type="date" class="px-6 py-2 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg shadow focus:outline-2 focus:outline-blue-100 focus:ring-2 focus:ring-blue-300" v-model="from" />
				<p class="mt-2 text-xs text-red-500" v-if="validation.from">{{ validation.from[0] }}</p>
			</div> -->
			<div class="flex flex-col col-span-12 space-y-2 md:col-span-2">
				<label class="text-xs font-semibold text-gray-700">Bulan : </label>
				<date-picker type="month" v-model="month" format="MMMM-YYYY" placeholder="Masukan Bulan" value-type="format"></date-picker>
				<!-- <input type="date" class="px-6 py-2 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg shadow focus:outline-2 focus:outline-blue-100 focus:ring-2 focus:ring-blue-300" v-model="to" /> -->
				<p class="mt-2 text-xs text-red-500" v-if="validation.month">{{ validation.month[0] }}</p>
			</div>
			<div class="flex items-end justify-end col-span-12">
				<button type="submit" class="btn btn-lg btn-blue" @click.prevent="getDetailHPP">Generate Data</button>
			</div>
		</div>
		<div class="card" v-if="validation.error">
			<div class="flex items-center justify-center w-full py-10 space-x-4">
				<svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
					<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
				</svg>
				<p class="text-xl font-semibold">
					{{ validation.error }}
				</p>
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
						<!-- Pembelian -->
						<td class="p-2 border-table" v-text="data.pembelian.qty > 0 ? data.pembelian.qty : '-'"></td>
						<td class="p-2 border-table" v-text="data.pembelian.harga > 0 ? 'Rp. ' + Intl.NumberFormat().format(data.pembelian.harga) : '-'"></td>
						<td class="p-2 border-table" v-text="data.pembelian.jumlah > 0 ? 'Rp. ' + Intl.NumberFormat().format(data.pembelian.jumlah) : '-'"></td>
						<!-- HPP -->
						<td class="p-2 border-table">
							<ul v-if="data.hpp.qty != 0">
								<li v-for="data in qtyResult(data.hpp)" v-text="data"></li>
							</ul>
							<span v-else>-</span>
						</td>
						<td class="p-2 border-table">
							<ul v-if="data.hpp.harga != 0">
								<li v-for="data in hargaResult(data.hpp)">{{ "Rp. " + Intl.NumberFormat().format(data) }}</li>
							</ul>
							<span v-else>-</span>
						</td>
						<td class="p-2 border-table">
							<ul v-if="data.hpp.jumlah != 0">
								<li v-for="data in totalResult(data.hpp)">{{ "Rp. " + Intl.NumberFormat().format(data) }}</li>
							</ul>
							<span v-else>-</span>
						</td>

						<!-- Persediaan -->
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
					<p class="font-semibold">Total Bahan Baku Digunakan</p>
					<p class="font-semibold">Rp. {{ Intl.NumberFormat().format(hpp[1].penjualan.jumlah) }}</p>
				</div>
				<div class="flex justify-end col-span-12 py-2 mx-2 md:mt-4 md:px-4 md:justify-start md:col-span-2">
					<button @click="openModal(hpp[1])" class="btn btn-sm btn-indigo" v-if="hpp[0]">Buat Analisis</button>
				</div>
			</div>

			<Modal size="max-w-xl" title="Input Biaya Overhead Produksi" :data="modalData" @close-modal="closeModal" v-show="modal">
				<template #content>
					<div class="col-span-12 space-y-4" v-if="hpp[0]">
						<div class="flex flex-col items-center justify-between col-span-12 p-4 mt-4 bg-green-200 border-2 rounded-lg shadow sm:flex-row border-slate-100 md:col-span-10">
							<p class="font-semibold">Total Bahan Baku Digunakan</p>
							<!-- <p class="font-semibold">Rp. {{ Intl.NumberFormat().format(modalData.penjualan.jumlah) }}</p> -->
							<p class="font-semibold" v-text="modalData ? 'Rp. ' + Intl.NumberFormat().format(modalData.penjualan.jumlah) : ''"></p>
						</div>
						<!-- {{  }} -->
						<div class="my-2">
							<label class="label">Biaya Tenaga Kerja</label>
							<!-- <input type="text" class="form-input form-input-lg" placeholder="No Resi ... " v-model="overhead.tenaga_kerja" /> -->
							<div><number class="form-input form-input-lg" v-model="overhead.tenaga_kerja" v-bind="number"></number></div>
							<!-- <p class="mt-2 text-xs text-red-500" v-if="validation.resi">{{ validation.resi[0] }}</p> -->
						</div>
						<div class="my-2">
							<label class="label">Biaya Produksi Tambahan (Jika Ada)</label>
							<div><number class="form-input form-input-lg" v-model="overhead.produksi_tambahan" v-bind="number"></number></div>
							<!-- <input type="text" class="form-input form-input-lg" placeholder="No Resi ... " v-model="overhead.produksi_tambahan" /> -->
							<!-- <p class="mt-2 text-xs text-red-500" v-if="validation.resi">{{ validation.resi[0] }}</p> -->
						</div>
						<div class="my-2">
							<label class="label">Biaya Mesin</label>
							<div><number class="form-input form-input-lg" v-model="overhead.mesin" v-bind="number"></number></div>
							<!-- <input type="text" class="form-input form-input-lg" placeholder="No Resi ... " v-model="overhead.mesin" /> -->
							<!-- <p class="mt-2 text-xs text-red-500" v-if="validation.resi">{{ validation.resi[0] }}</p> -->
						</div>
						<div class="my-2">
							<label class="label">Biaya Maintenance(Air, Listrik dll)</label>
							<div><number class="form-input form-input-lg" v-model="overhead.maintenance" v-bind="number"></number></div>

							<!-- <input type="text" class="form-input form-input-lg" placeholder="No Resi ... " v-model="overhead.maintenance" /> -->
							<!-- <p class="mt-2 text-xs text-red-500" v-if="validation.resi">{{ validation.resi[0] }}</p> -->
						</div>
						<div class="flex flex-col items-center justify-between col-span-12 p-4 mt-4 bg-green-200 border-2 rounded-lg shadow sm:flex-row border-slate-100 md:col-span-10">
							<p class="font-semibold">Harga Pokok Produksi</p>
							<!-- <p class="font-semibold">Rp. {{ Intl.NumberFormat().format(modalData.penjualan.jumlah) }}</p> -->
							<p class="font-semibold" v-text="modalData ? 'Rp. ' + Intl.NumberFormat().format(hargaPokokProduksi(modalData, overhead)) : ''"></p>
						</div>
					</div>
				</template>
				<template #submit>
					<button class="btn btn-lg btn-green" @click.prevent="saveResult(modalData)">Save Result</button>
				</template>
			</Modal>
		</div>
	</div>
</template>

<script>
import {number} from "@coders-tm/vue-number-format";
export default {
	name: "AnalisisHPP",
	layout: "auth",
	components: {number},
	middleware: "role/manager-penjualan",
	data() {
		return {
			persediaan: {
				qty: 0,
				harga: 0,
			},

			modal: false,
			modalData: "",

			id_produk: "",
			id_bahan_baku: "",
			month: "",

			products: [],
			bahan_baku: [],

			hpp: [],

			// produksi: [],
			// pembelian: [],

			overhead: {
				produksi_tambahan: 0,
				tenaga_kerja: 0,
				mesin: 0,
				maintenance: 0,
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
				.get("/api/analisis-hpp?id_produk=" + this.id_produk + "&id_bahan_baku=" + this.id_bahan_baku + "&month=" + this.month)
				.then((response) => {
					this.validation = [];
					this.hpp = response.data;
				})
				.catch((error) => {
					this.validation = error.response.data;
					console.log(error.response);
				});
		},

		hargaPokokProduksi(modalData, overhead) {
			if (this.hpp && this.hpp.length > 0) {
				const biaya_produksi = parseInt(modalData.penjualan.jumlah);

				const produksi_tambahan = parseInt(overhead.produksi_tambahan);
				const tenaga_kerja = parseInt(overhead.tenaga_kerja);
				const mesin = parseInt(overhead.mesin);
				const maintenance = parseInt(overhead.maintenance);

				return biaya_produksi + (produksi_tambahan + tenaga_kerja + mesin + maintenance);
				// return biaya_produksi + produksi_tambahan;
			}
		},

		async saveResult(modalData) {
			this.$axios
				.post("api/analisis-hpp", {
					biaya_produksi: parseInt(modalData.penjualan.jumlah),
					overhead: this.overhead,
					id_bahan_baku: this.id_bahan_baku,
					id_produk: this.id_produk,
					hpp: this.hargaPokokProduksi(modalData, this.overhead),
				})
				.then(() => this.closeModal())
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
					console.log(error);
					this.validation = error.response.data;
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
		openModal(hpp) {
			this.modal = !this.modal;
			this.modalData = hpp;
		},
		closeModal() {
			this.modal = false;
			this.overhead = {
				produksi_tambahan: 0,
				tenaga_kerja: 0,
				mesin: 0,
				maintenance: 0,
			};

			this.validation = [];
		},
	},
};
</script>
