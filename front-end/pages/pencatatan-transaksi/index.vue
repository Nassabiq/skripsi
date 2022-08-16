<template>
	<div class="margin-auth">
		<div class="grid grid-cols-12 gap-4">
			<div class="col-span-12 md:col-span-7">
				<div class="grid grid-cols-12 gap-4 card">
					<div class="col-span-12 sm:col-span-6">
						<label class="label">Nama Pemesan</label>
						<input type="text" class="form-input form-input-sm" v-model="transaksi.nama_pemesan" placeholder="Nama Pemesan" />
						<p class="mt-2 text-xs text-red-500" v-if="validation.nama_pemesan">{{ validation.nama_pemesan[0] }}</p>
					</div>
					<div class="col-span-12 sm:col-span-6">
						<label class="label">No. Telp</label>
						<input type="text" class="form-input form-input-sm" placeholder="No. Telp" v-model.number="transaksi.no_telp" />
						<p class="mt-2 text-xs text-red-500" v-if="validation.no_telp">{{ validation.no_telp[0] }}</p>
					</div>
					<div class="col-span-12 sm:col-span-12">
						<label for="about" class="label"> Alamat </label>
						<div class="mt-1">
							<textarea id="about" v-model="transaksi.alamat" rows="3" class="form-input form-input-sm" placeholder="you@example.com"></textarea>
							<p class="mt-2 text-xs text-red-500" v-if="validation.alamat">{{ validation.alamat[0] }}</p>
						</div>
					</div>
				</div>
				<div class="grid grid-cols-12 gap-2 card">
					<div class="col-span-12 md:col-span-8">
						<label class="label">Produk</label>
						<select class="form-input form-input-sm" v-model="details.id_produk" @change="selectMaterial">
							<option value="">Pilih Produk</option>
							<option v-for="(data, index) in produk" :value="data.id_produk">{{ data.nama_produk }}</option>
						</select>
					</div>
					<div class="col-span-12 sm:col-span-4">
						<label class="label">Qty</label>
						<input type="text" class="form-input form-input-sm" placeholder="QTY" v-model.number="details.qty" />
					</div>
					<!-- <div class="col-span-12 md:col-span-6">
						<label class="label">Ukuran</label>
						<input type="text" class="form-input form-input-sm" placeholder="Ukuran" v-model="details.ukuran" />
					</div> -->
					<div class="col-span-12 md:col-span-6">
						<label class="label">Jenis Bahan</label>
						<select class="form-input form-input-sm" v-model="details.jenis_bahan">
							<option value="">Select Bahan</option>
							<option v-for="(data, index) in bahan_baku" :value="data.nama_bahan_baku">{{ data.nama_bahan_baku }}</option>
						</select>
					</div>
					<div class="col-span-12 md:col-span-6">
						<label class="label">Finishing</label>
						<input type="text" class="form-input form-input-sm" placeholder="Finishing" v-model="details.finishing" />
					</div>
					<div class="col-span-12 md:col-span-6">
						<label class="label">Laminasi</label>
						<input type="text" class="form-input form-input-sm" placeholder="Laminasi" v-model="details.laminasi" />
					</div>
					<div class="flex items-center col-span-12" :class="error ? 'justify-between' : 'justify-end'">
						<p v-if="error" class="text-sm font-semibold tracking-wider text-red-500 animate-bounce">{{ error }}</p>
						<button class="btn btn-with-icon btn-sm" @click.prevent="addToCart" :class="updateMode ? 'btn-indigo' : 'btn-blue'">
							<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" v-if="updateMode">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
							</svg>
							<svg xmlns="http://www.w3.org/2000/svg" v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
							</svg>
							<p class="text-xs">{{ updateMode ? "Edit" : "Tambah" }}</p>
						</button>
					</div>
				</div>
			</div>
			<!-- <div class="col-span-12 md:col-span-7"></div> -->

			<div class="col-span-12 p-0 md:col-span-5">
				<div class="grid grid-flow-col grid-rows-3 card">
					<div class="row-span-2">
						<p class="font-semibold">Detail Pesanan</p>
						<div class="overflow-y-auto overscroll-contain h-96">
							<div class="flex items-center justify-between gap-2" v-for="(data, index) in pesanan">
								<div class="p-2 my-2 border-2 border-green-400 rounded-md shadow-md bg-green-50 basis-11/12">
									<div class="flex items-center justify-between space-x-2">
										<div class="w-1/5 basis-1/5">
											<img :src="'http://localhost:8000/storage/' + data.image[0].fileUrl" alt="" class="object-cover border-2 aspect-square border-slate-200" />
										</div>
										<div class="detail-pesanan basis-2/5">
											<p class="font-semibold">{{ data.nama_produk }}</p>
											<p class="text-xs font-light tracking-wider">
												{{ data.jenis_bahan }}
											</p>
											<p class="text-xs font-light">Rp. {{ Intl.NumberFormat().format(data.harga) }}</p>
										</div>
										<div class="text-right price justify-items-end basis-2/5">
											<p class="text-xs font-light tracking-wider">{{ data.qty }}{{ data.unit }}</p>
											<p class="text-xs font-semibold">Rp. {{ Intl.NumberFormat().format(data.harga * data.qty) }}</p>
										</div>
									</div>
								</div>
								<div class="space-y-2 basis-1/12">
									<button class="hover:animate-bounce" @click="editFromCart(index)">
										<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 hover:text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
										</svg>
									</button>
									<div class="hover:animate-bounce" @click="deleteFromCart(index)">
										<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
										</svg>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="content-end m-2">
						<div class="">
							<label class="flex justify-end label">Jumlah Bayar</label>
							<div><number placeholder="Jumlah Bayar.." class="form-input form-input-lg" v-model.number="transaksi.total_pembayaran" v-bind="number"></number></div>
						</div>
						<div class="p-2 my-2 border-2 border-green-400 rounded-md shadow-md bg-green-50">
							<div class="flex items-center justify-between">
								<p class="text-xs tracking-wider">Total</p>
								<p class="text-sm font-semibold">Rp. {{ Intl.NumberFormat().format(total) }}</p>
							</div>
							<hr class="my-1 bg-green-600" />
							<div class="flex items-center justify-between">
								<p class="text-xs tracking-wider">Kembalian</p>
								<p class="text-sm font-semibold">Rp. {{ kembalian <= 0 ? 0 : Intl.NumberFormat().format(kembalian) }}</p>
							</div>
						</div>

						<div class="flex justify-end px-2">
							<p class="mt-2 text-xs text-red-500" v-if="errorStock">{{ errorStock }}</p>
						</div>
						<div class="flex justify-end p-2">
							<button class="btn btn-green btn-sm" @click="submitTransaksi">
								<p class="text-xs">Proses Pesanan</p>
							</button>
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
	name: "PencatatanTransaksi",
	layout: "auth",
	components: {number},
	auth: false,
	data() {
		return {
			produk: [],
			bahan_baku: null,
			transaksi: {
				nama_pemesan: "",
				no_telp: "",
				alamat: "",
				total_pembayaran: null,
			},
			details: {
				id_produk: "",
				nama_produk: "",
				qty: "",
				harga: null,
				image: "",
				unit: "",
				jenis_bahan: "",
				finishing: "",
				laminasi: "",
			},
			pesanan: [],
			error: "",

			updateMode: false,

			number: {
				decimal: ",",
				separator: ".",
				prefix: "Rp. ",
				precision: 2,
				masked: false,
			},

			validation: [],
			errorStock: "",
		};
	},
	mounted() {
		this.getData();
	},
	computed: {
		total() {
			let pesanan = this.pesanan;
			let total = 0;

			pesanan.forEach((item) => {
				total += item.harga * item.qty;
			});
			return total;
		},
		kembalian() {
			return this.transaksi.total_pembayaran - this.total;
		},
	},
	methods: {
		async getData() {
			this.$axios
				.get("/api/transaksi")
				.then((response) => {
					this.produk = response.data;
					this.produk.image = JSON.parse(this.produk.image);
				})
				.catch((error) => {
					console.log(error);
				});
		},
		selectMaterial() {
			let produk = this.produk;
			let selectedProduk = this.details.id_produk;

			produk = produk.filter((data) => {
				return data.id_produk.includes(selectedProduk);
			});
			if (selectedProduk) {
				if (!this.bahan_baku) {
					this.bahan_baku = produk[0].material;
					this.details.nama_produk = produk[0].nama_produk;
					this.details.unit = produk[0].categories.satuan.nama_satuan;
					this.details.image = JSON.parse(produk[0].image);
					this.details.harga = produk[0].harga[produk[0].harga.length - 1].harga;
				} else {
					this.bahan_baku = null;
					this.bahan_baku = produk[0].material;
					this.details.nama_produk = produk[0].nama_produk;
					this.details.unit = produk[0].categories.satuan.nama_satuan;
					this.details.image = JSON.parse(produk[0].image);
					this.details.harga = produk[0].harga[produk[0].harga.length - 1].harga;
				}
			} else {
				this.bahan_baku = null;
				this.details.nama_produk = "";
				this.details.unit = "";
				this.details.image = "";
				this.details.harga = null;
			}
		},
		async addToCart() {
			if (this.details.id_produk && this.details.qty && this.details.jenis_bahan && this.details.qty) this.pesanan.push(this.details);
			else {
				this.error = "Form harus diisi";
				setTimeout(() => {
					this.error = "";
				}, 10000);
			}

			this.details = {
				id_produk: "",
				qty: "",
				harga: null,
				image: "",
				unit: "",
				jenis_bahan: "",
				finishing: "",
				laminasi: "",
			};

			if (this.updateMode) {
				this.updateMode = false;
			}
		},
		editFromCart(index) {
			this.updateMode = true;
			this.details = {
				id_produk: this.pesanan[index].id_produk,
				qty: this.pesanan[index].qty,
				harga: this.pesanan[index].harga,
				image: this.pesanan[index].image,
				unit: this.pesanan[index].unit,
				jenis_bahan: this.pesanan[index].jenis_bahan,
				finishing: this.pesanan[index].finishing,
				laminasi: this.pesanan[index].laminasi,
			};
			this.pesanan.splice(index, 1);
		},
		deleteFromCart(index) {
			this.pesanan.splice(index, 1);
		},

		async submitTransaksi() {
			this.$axios
				.post("/api/submitTransaksi", {
					nama_pemesan: this.transaksi.nama_pemesan,
					no_telp: this.transaksi.no_telp,
					alamat: this.transaksi.alamat,
					total_pembayaran: this.transaksi.total_pembayaran,
					total: this.total,

					pesanan: this.pesanan,
				})
				.then(() => {
					this.$router.push({name: "pemesanan"});
				})
				.then(() => {
					this.$swal.fire({
						icon: "success",
						title: "Success...",
						text: "Transaksi Berhasil ditambahkan!",
						showConfirmButton: false,
						timer: 1500,
						timerProgressBar: true,
					});
				})
				.catch((error) => {
					this.errorStock = error.response.data;
				});
		},
	},
};
</script>
