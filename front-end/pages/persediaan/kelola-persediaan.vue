<template>
	<div class="gap-4 space-y-4 margin-auth">
		<div class="card">
			<div class="grid grid-cols-12 gap-4">
				<div class="relative col-span-12 my-4 md:col-span-6">
					<input type="search" class="w-full py-2 pl-8 pr-4 text-sm font-medium text-gray-800 border border-gray-200 rounded-lg shadow md:w-3/5 focus:outline-2 focus:outline-blue-100 focus:ring-2 focus:ring-blue-300" placeholder="Search..." v-model="search" />
					<div class="absolute top-0 left-0 inline-flex items-center px-2 py-3">
						<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
							<rect x="0" y="0" width="24" height="24" stroke="none"></rect>
							<circle cx="10" cy="10" r="7" />
							<line x1="21" y1="21" x2="15" y2="15" />
						</svg>
					</div>
				</div>
				<div class="flex items-center justify-between col-span-12 mb-2 space-x-2 md:justify-end md:mb-0 md:col-span-6">
					<div class="">
						<button class="btn-with-icon btn btn-blue btn-sm" @click="modalTambahStok = !modalTambahStok">
							<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
							</svg>
							Tambah Stok
						</button>
					</div>
				</div>
			</div>

			<div class="relative overflow-x-auto overflow-y-auto bg-white rounded-lg shadow">
				<table class="table table-auto table-produk">
					<thead class="bg-gray-100">
						<tr class="text-left text-gray-800 font-title">
							<th class="p-3">
								Id Barang Masuk
							</th>
							<th class="p-3">Tgl Transaksi</th>
							<th class="p-3">User</th>
							<th class="p-3">Jumlah Pesanan</th>
							<th class="p-3">Total Pesanan</th>
							<th class="p-1"></th>
						</tr>
					</thead>
					<tbody class="divide-y-2 divide-gray-100 divide-dotted" v-for="(data, index) in searchData">
						<tr class="text-sm">
							<td class="p-6">{{ data.id_barang_masuk }}</td>
							<td class="p-6">{{ data.tgl_barang_masuk }}</td>
							<td class="p-6">1961</td>
							<td class="p-6">{{ data.detail_barang.length }}</td>
							<td class="p-6">Rp. {{ Intl.NumberFormat().format(data.total_harga_beli) }}</td>
							<td class="p-2">
								<button class="p-1 text-blue-400 border border-blue-600 rounded-full focus:bg-blue-100 hover:text-blue-800" @click.prevent="showRow(index)">
									<svg xmlns="http://www.w3.org/2000/svg" :class="content === index ? 'rotate-90 duration-300' : 'rotate-0 duration-300'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
									</svg>
								</button>
							</td>
						</tr>
						<tr>
							<td class="p-3 bg-sky-100" colspan="6" v-show="content === index">
								<div class="px-4 pb-2 font-semibold">
									Detail Barang Masuk
								</div>
								<div class="grid grid-cols-2" v-for="(data, index) in data.detail_barang" :key="index">
									<div class="col-span-2 md:col-span-1">
										<div class="border-t border-sky-200">
											<dl>
												<div class="px-3 py-4 bg-white sm:grid sm:grid-cols-3 sm:gap-4">
													<dt class="text-xs font-semibold">Id Detail Barang Masuk</dt>
													<dd class="mt-1 text-xs sm:mt-0 sm:col-span-2">{{ data.id_detail_barang_masuk }}</dd>
												</div>
												<div class="px-3 py-4 bg-slate-50 sm:grid sm:grid-cols-3 sm:gap-4">
													<dt class="text-xs font-semibold">Nama Bahan Baku</dt>
													<dd class="mt-1 text-xs capitalize sm:mt-0 sm:col-span-2">{{ data.bahan_baku.nama_bahan_baku }}</dd>
												</div>
											</dl>
										</div>
									</div>
									<div class="col-span-2 md:col-span-1">
										<div class="border-t border-sky-200">
											<dl>
												<div class="px-3 py-4 bg-white sm:grid sm:grid-cols-3 sm:gap-4">
													<dt class="text-xs font-semibold">QTY</dt>
													<dd class="mt-1 text-xs sm:mt-0 sm:col-span-2">{{ data.qty }}</dd>
												</div>
												<div class="px-3 py-4 bg-slate-50 sm:grid sm:grid-cols-3 sm:gap-4">
													<dt class="text-xs font-semibold">Harga</dt>
													<dd class="mt-1 text-xs sm:mt-0 sm:col-span-2">Rp. {{ Intl.NumberFormat().format(data.harga) }}</dd>
												</div>
											</dl>
										</div>
									</div>
									<!-- <div class="flex flex-col justify-between col-span-2 px-5 py-6 bg-sky-100 md:flex-row">
										<dt class="text-sm font-semibold">Subtotal</dt>
										<dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">Rp. 500.000</dd>
									</div> -->
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="grid grid-cols-2 gap-4">
			<div class="col-span-2 md:col-span-1">
				<BahanBaku :materials="materials" :units="units" @get-data="getData"></BahanBaku>
			</div>
			<div class="col-span-2 md:col-span-1"></div>
		</div>
		<Modal size="max-w-2xl" title="Tambah Stok" @close-modal="closeModal" v-show="modalTambahStok">
			<template #content>
				<div class="grid grid-cols-12 col-span-12 gap-2" v-for="(data, index) in stok">
					<div class="col-span-12 sm:col-span-5">
						<label class="label">Nama Bahan Baku</label>
						<select class="form-input form-input-lg" v-model="stok[index].nama_bahan_baku">
							<option value="">Pilih Bahan Baku</option>
							<option :value="material.id_material" v-for="(material, index) in materials">{{ material.nama_bahan_baku }}</option>
						</select>

						<!-- <p class="mt-2 text-xs text-red-500" v-if="validation.nama_kategori">{{ validation.id_satuan[0] }}</p> -->
					</div>
					<div class="col-span-12 sm:col-span-4">
						<label class="label">Harga</label>
						<input class="form-input form-input-lg" placeholder="Rp. ....." v-model.number="stok[index].harga" />
					</div>
					<div class="col-span-12 sm:col-span-3">
						<label class="label">Qty</label>
						<div class="flex gap-2">
							<div>
								<input type="text" class="form-input form-input-lg" v-model.number="stok[index].qty" />
								<!-- <p class="mt-2 text-xs text-red-500" v-if="validation.qty">{{ validation.qty[0] }}</p> -->
							</div>
							<button class="mt-1 btn btn-sm btn-indigo" v-if="(index == 0)" @click="addForm">
								<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
								</svg>
							</button>
							<button class="mt-1 btn btn-sm btn-red" v-else @click="deleteForm(index)">
								<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 rotate-45" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
								</svg>
							</button>
						</div>
					</div>
				</div>
				<div class="flex flex-col justify-between col-span-12 px-5 py-3 rounded-md shadow-md bg-lime-100 md:flex-row">
					<dt class="text-sm font-semibold">Total</dt>
					<dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">Rp. {{ Intl.NumberFormat().format(getSubtotal) }}</dd>
				</div>
				<div class="col-span-12 px-5 py-3 bg-red-100 rounded-md shadow-md" v-if="validation">
					<!-- <dt class="text-sm font-semibold"></dt> -->
					<ul>
						<li class="mt-1 text-xs font-semibold sm:mt-0 sm:col-span-2" v-for="(error, index) in validation">{{ error[0] }}</li>
					</ul>
				</div>
			</template>
			<template #submit>
				<button class="btn btn-lg btn-green" @click.prevent="addStok">
					Submit
				</button>
			</template>
		</Modal>
	</div>
</template>

<script>
import BahanBaku from "../../components/persediaan/DataBahanBaku.vue";

export default {
	name: "KelolaPersediaan",
	layout: "auth",
	components: {BahanBaku},
	auth: false,
	data() {
		return {
			content: null,
			modalTambahStok: false,

			materials: [],
			units: [],
			barangMasuk: [],

			validation: [],

			stok: [
				{
					nama_bahan_baku: "",
					harga: null,
					qty: null,
				},
			],

			search: "",
			// user : this.$auth.user
		};
	},

	mounted() {
		this.getAllData();
	},

	computed: {
		getSubtotal() {
			let price = this.stok;
			let total = 0;

			for (let index = 0; index < price.length; index++) {
				total += price[index].harga * price[index].qty;
			}
			return total;
		},

		searchData() {
			let data = this.barangMasuk;

			if (this.search != "" && this.search) {
				data = data.filter((item) => {
					return item.id_barang_masuk.toUpperCase().includes(this.search.toUpperCase());
				});
			}

			return data;
		},
	},
	methods: {
		async getAllData() {
			this.$axios
				.get("/api/persediaan")
				.then((response) => {
					this.materials = response.data.materials;
					this.units = response.data.satuan;
					this.barangMasuk = response.data.barangMasuk;
				})
				.catch((error) => {
					console.log(error);
				});
		},
		showRow(index) {
			if (this.content !== null) {
				this.content = this.content == index ? null : index;
			} else {
				this.content = index;
			}
		},
		getData() {
			this.getAllData();
		},

		addForm() {
			this.stok.push({
				nama_bahan_baku: "",
				harga: null,
				qty: null,
			});
		},

		deleteForm(index) {
			this.stok.splice(index, 1);
		},

		closeModal() {
			this.modalTambahStok = false;
			this.stok = [
				{
					nama_bahan_baku: "",
					harga: null,
					qty: null,
				},
			];

			this.validation = [];
		},

		async addStok() {
			this.$axios
				.post("/api/addStok", {
					total: this.getSubtotal,
					stok: this.stok,
				})
				.then((response) => {
					console.log(response.data);
					this.closeModal();
					this.getAllData();
					// this.$emit("get-data", response.data);
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
					this.validation = error.response.data;
				});
			// .finally(() => {
			// 	this.isloading = false;
			// })
		},
	},
};
</script>
