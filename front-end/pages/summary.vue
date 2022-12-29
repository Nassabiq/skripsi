<template>
	<div class="min-h-screen py-6 mx-auto bg-gray-100 max-w-screen-2xl sm:px-6 lg:px-8">
		<div class="grid h-screen grid-cols-1 gap-4 my-4 space-x-4 md:grid-cols-2">
			<div class="space-y-4 md:col-span-1">
				<span class="text-xl font-semibold text-gray-800">Informasi Kontak</span>
				<div class="flex items-center px-2">
					<input type="checkbox" v-model="existedPelanggan" class="w-4 h-4 text-indigo-600 border-gray-300 rounded-md focus:ring focus:ring-offset-2 focus:ring-indigo-500" />
					<label for="remember-me" class="block ml-2 text-sm text-gray-900">Gunakan data pelanggan yang sudah ada</label>
				</div>
				<div class="px-8 py-6 space-y-4 bg-white border-2 border-gray-200 rounded-md" v-if="!existedPelanggan">
					<div class="flex justify-between gap-6">
						<div class="w-full">
							<label class="text-sm font-semibold text-gray-700">Nama Pelanggan</label>
							<input type="text" class="form-input form-input-lg" v-model="customer.nama_pelanggan" placeholder="Nama Pelanggan ... " />
							<p class="mt-2 text-xs text-red-500" v-if="validation.nama_pelanggan">{{ validation.nama_pelanggan[0] }}</p>
						</div>
						<div class="w-full">
							<label class="text-sm font-semibold text-gray-700">No Telepon</label>
							<input type="text" class="form-input form-input-lg" v-model="customer.no_telp" placeholder="No Telepon ... " />
							<p class="mt-2 text-xs text-red-500" v-if="validation.no_telp">{{ validation.no_telp[0] }}</p>
						</div>
					</div>
					<div class="w-full">
						<label class="text-sm font-semibold text-gray-700">Alamat</label>
						<div class="mt-1">
							<textarea id="about" name="about" rows="8" class="form-input form-input-lg" v-model="customer.alamat" placeholder="you@example.com"></textarea>
							<p class="mt-2 text-xs text-red-500" v-if="validation.alamat">{{ validation.alamat[0] }}</p>
						</div>
					</div>
				</div>
				<div class="px-8 py-6 space-y-4 bg-white border-2 border-gray-200 rounded-md" v-else>
					<span class="font-semibold text-gray-800"> Data Pelanggan </span>

					<div v-if="pelanggan.length > 0" class="flex flex-col items-center lg:flex-row">
						<!-- Active: "ring-2 ring-indigo-500" -->
						<label v-for="data in pelanggan" :class="selectedUser == data.id_pelanggan ? 'ring ring-indigo-200' : ''" class="relative flex items-center justify-center px-4 py-3 bg-white border rounded-md shadow-sm cursor-pointer group hover:bg-gray-50 focus:outline-none sm:flex-1 active:ring-2 ring-indigo-500">
							<input type="radio" name="size-choice" class="sr-only" :value="data.id_pelanggan" v-model="selectedUser" />
							<div class="flex flex-col space-y-2">
								<span class="text-lg font-semibold text-gray-700 capitalize">{{ data.nama_pelanggan }}</span>
								<span class="text-sm text-gray-800">{{ data.no_telp }}</span>
								<span class="text-xs text-gray-800">{{ data.alamat.slice(0, 100) + "..." }}</span>
							</div>
							<!--
                            Active: "border", Not Active: "border-2"     
                            Checked: "border-indigo-500", Not Checked: "border-transparent"
						-->
							<span :class="selectedUser == data.id_pelanggan ? 'border border-indigo-500' : 'border-2 border-transparent'" class="absolute border-2 border-transparent rounded-md pointer-events-none -inset-px active:border active:border-indigo-500" aria-hidden="true"></span>
						</label>
					</div>
					<div v-else class="py-12 border-2 border-gray-400 border-dashed rounded-md">
						<div class="flex items-center justify-center w-full gap-4">
							<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-yellow-500">
								<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
							</svg>
							<span class="font-semibold text-gray-700">Tidak Ada Data</span>
						</div>
					</div>
				</div>
			</div>
			<div class="space-y-4 md:col-span-1">
				<span class="text-xl font-semibold text-gray-800">Order Summary</span>
				<ul role="list" class="px-4 -my-6 bg-white border-2 divide-y divide-gray-200 rounded-md">
					<li class="flex py-6 border-b-2 border-gray-200" v-for="data in cart">
						<div class="flex-shrink-0 w-24 h-24 overflow-hidden border border-gray-200 rounded-md">
							<img :src="'http://localhost:8000/storage/image_produk/' + data.sku.produk.id_produk + '/' + JSON.parse(data.sku.produk.image_produk)[0].filename" alt="Salmon orange fabric pouch with match zipper, gray zipper pull, and adjustable hip belt." class="object-cover object-center w-full h-full" />
						</div>

						<div class="flex flex-col flex-1 ml-4">
							<div>
								<div class="flex justify-between text-base font-medium text-gray-700">
									<h3>
										<a class="font-semibold" href="#">{{ data.sku.produk.nama_produk }}</a>
									</h3>
									<div @click.prevent="$store.dispatch('cart/removeCart', data.id_cart)">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 cursor-pointer hover:text-red-500">
											<path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z" clip-rule="evenodd" />
										</svg>
									</div>
								</div>
								<p class="mt-1 text-sm text-gray-500">{{ data.sku.bahan_baku.nama_bahan_baku }}</p>
							</div>
							<div class="flex items-end justify-between flex-1 text-sm">
								<!-- <p class="text-gray-500" v-text="qty(data)"></p> -->
								<ul>
									<template v-if="data.sku.produk.satuan_produk == 'm2'">
										<li class="text-xs text-gray-600">Panjang: {{ JSON.parse(data.ukuran).panjang }} meter, Lebar: {{ JSON.parse(data.ukuran).lebar }} meter</li>
										<li class="text-xs text-gray-600">QTY: {{ data.qty_cart + " pcs" }}</li>
									</template>
									<template v-else>
										<li class="text-xs text-gray-600">QTY: {{ data.qty_cart + "" + data.sku.produk.satuan_produk }}</li>
									</template>
								</ul>
								<p class="ml-4 text-sm font-semibold text-gray-800" v-text="harga(data)"></p>
							</div>
						</div>
					</li>
					<li class="py-4 border-b-2 border-gray-200">
						<label class="text-sm font-semibold text-gray-700">Pilih Pengiriman</label>
						<div class="flex flex-col gap-4 mt-1 md:flex-row">
							<label class="relative flex items-center justify-center px-4 py-3 text-sm font-medium text-gray-900 bg-white border rounded-md shadow-sm cursor-pointer group hover:bg-gray-50 focus:outline-none sm:flex-1" :class="shipment == 0 ? 'ring-2 ring-indigo-500' : ''">
								<input type="radio" v-model="shipment" value="0" class="sr-only" aria-labelledby="size-choice-0-label" />
								<span id="size-choice-0-label">Jasa Kirim (Kurir)</span>
								<span class="absolute rounded-md pointer-events-none -inset-px" :class="shipment == 0 ? 'border border-indigo-500' : 'border-2 border-transparent'" aria-hidden="true"></span>
							</label>

							<label class="relative flex items-center justify-center px-4 py-3 text-sm font-medium text-gray-900 bg-white border rounded-md shadow-sm cursor-pointer group hover:bg-gray-50 focus:outline-none sm:flex-1" :class="shipment == 1 ? 'ring-2 ring-indigo-500' : ''">
								<input type="radio" v-model="shipment" value="1" class="sr-only" aria-labelledby="size-choice-1-label" />
								<span id="size-choice-1-label">Diambil di Toko</span>
								<span class="absolute rounded-md pointer-events-none -inset-px" :class="shipment == 1 ? 'border border-indigo-500' : 'border-2 border-transparent'" aria-hidden="true"></span>
							</label>
						</div>
						<p class="mt-2 text-xs text-red-500" v-if="validation.shipment">{{ validation.shipment[0] }}</p>
					</li>
					<li class="py-4 border-b-2 border-gray-200">
						<label class="text-sm font-semibold text-gray-700">Catatan Pesanan</label>
						<div class="mt-1">
							<textarea id="about" name="about" rows="4" class="form-input form-input-lg" v-model="catatan" placeholder="you@example.com"></textarea>
							<!-- <p class="mt-2 text-xs text-red-500" v-if="validation.alamat">{{ validation.alamat[0] }}</p> -->
						</div>
					</li>
					<li class="flex justify-between py-6 border-b-2 border-gray-200">
						<div class="font-semibold text-gray-900">Total</div>
						<div class="font-semibold text-gray-700">Rp. {{ Intl.NumberFormat().format(total) }}</div>
					</li>
					<li class="flex justify-center py-6">
						<button class="btn-with-icon btn btn-lg btn-indigo" @click="payment">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-white">
								<path fill-rule="evenodd" d="M7.5 6v.75H5.513c-.96 0-1.764.724-1.865 1.679l-1.263 12A1.875 1.875 0 004.25 22.5h15.5a1.875 1.875 0 001.865-2.071l-1.263-12a1.875 1.875 0 00-1.865-1.679H16.5V6a4.5 4.5 0 10-9 0zM12 3a3 3 0 00-3 3v.75h6V6a3 3 0 00-3-3zm-3 8.25a3 3 0 106 0v-.75a.75.75 0 011.5 0v.75a4.5 4.5 0 11-9 0v-.75a.75.75 0 011.5 0v.75z" clip-rule="evenodd" />
							</svg>
							<span>Lanjutkan Pembayaran</span>
						</button>
					</li>
				</ul>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	name: "Summary",
	// layout: "user",
	data() {
		return {
			catatan: "",
			customer: {
				nama_pelanggan: "",
				no_telp: "",
				alamat: "",
			},
			shipment: null,

			validation: [],
			existedPelanggan: false,
			selectedUser: false,
		};
	},
	created() {
		this.fetch();
	},
	computed: {
		cart() {
			return this.$store.state.cart.data;
		},
		total() {
			let data = this.cart;
			let result = data.reduce((accumulator, currentValue) => {
				let price = currentValue.sku.harga[currentValue.sku.harga.length - 1].harga_produk;

				let satuanMeterPersegi = currentValue.sku.produk.satuan_produk == "m2";
				let ukuran = satuanMeterPersegi ? JSON.parse(currentValue.ukuran).panjang * JSON.parse(currentValue.ukuran).lebar : null;

				let totalPrice = satuanMeterPersegi ? price * ukuran : price;
				// let sumCart = ;
				return accumulator + totalPrice * currentValue.qty_cart;
			}, 0);
			// return "Rp. " + Intl.NumberFormat().format(result);
			return result;
		},
		pelanggan() {
			return this.$auth.user.pelanggan;
		},
	},
	methods: {
		harga(data) {
			let price = data.sku.harga[data.sku.harga.length - 1].harga_produk;
			let satuanMeterPersegi = data.sku.produk.satuan_produk == "m2";
			let ukuran = satuanMeterPersegi ? JSON.parse(data.ukuran).panjang * JSON.parse(data.ukuran).lebar : null;
			let totalPrice = satuanMeterPersegi ? price * ukuran : price;

			let result = totalPrice * data.qty_cart;
			return "Rp. " + Intl.NumberFormat().format(result);
		},
		fetch() {
			this.$store.dispatch("cart/fetchCarts");
		},
		payment() {
			this.$axios
				.post("/api/transaksi", {
					pelanggan: this.existedPelanggan == true ? this.selectedUser : this.customer,
					user: this.$auth.user.id_user,
					shipment: this.shipment,
					catatan: this.catatan,
					transaksi: JSON.stringify(this.cart),
					total: this.total,
				})
				.then(() => {
					this.$router.push("/transaksi");
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
				.catch((error) => (this.validation = error.response.data));
		},
	},
};
</script>

<style></style>
