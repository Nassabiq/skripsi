<template>
	<div class="min-h-screen py-6 mx-auto bg-gray-100 max-w-screen-2xl sm:px-6 lg:px-8">
		<div class="grid h-screen grid-cols-1 gap-4 my-4 space-x-4 md:grid-cols-2">
			<div class="space-y-4 md:col-span-1">
				<span class="text-xl font-semibold text-gray-800">Informasi Kontak</span>
				<div class="flex items-center px-2">
					<input id="remember-me" name="remember-me" type="checkbox" v-model="existedPelanggan" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring focus:ring-offset-2 focus:ring-indigo-500" />
					<label for="remember-me" class="block ml-2 text-sm text-gray-900">Gunakan data pelanggan yang sudah ada</label>
				</div>
				<div class="px-8 py-6 space-y-4 bg-white border-2 border-gray-200 rounded-md">
					<div class="flex justify-between gap-6">
						<div class="w-full">
							<label class="text-sm font-semibold text-gray-700">Nama Pelanggan</label>
							<input type="text" class="form-input form-input-lg" v-model="pelanggan.nama_pelanggan" placeholder="Nama Pelanggan ... " />
						</div>
						<div class="w-full">
							<label class="text-sm font-semibold text-gray-700">No Telepon</label>
							<input type="text" class="form-input form-input-lg" v-model="pelanggan.no_telp" placeholder="No Telepon ... " />
						</div>
					</div>
					<div class="w-full">
						<label class="text-sm font-semibold text-gray-700">Alamat</label>
						<div class="mt-1">
							<textarea id="about" name="about" rows="8" class="form-input form-input-lg" v-model="pelanggan.no_telp" placeholder="you@example.com"></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="space-y-4 md:col-span-1">
				<span class="text-xl font-semibold text-gray-800">Order Summary</span>
				<ul role="list" class="px-4 -my-6 bg-white border-2 divide-y divide-gray-200 rounded-md">
					<li class="flex py-6 border-b-2 border-gray-200" v-for="data in cart">
						<div class="flex-shrink-0 w-24 h-24 overflow-hidden border border-gray-200 rounded-md">
							<img :src="'http://localhost:8000/storage/image_produk/' + data.sku.produk.id_produk + '/' + JSON.parse(data.sku.produk.image)[0].filename" alt="Salmon orange fabric pouch with match zipper, gray zipper pull, and adjustable hip belt." class="object-cover object-center w-full h-full" />
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
								<p class="text-gray-500">{{ data.qty_produk }} {{ data.sku.produk.satuan_produk }}</p>
								<p class="ml-4 text-sm font-semibold text-gray-800" v-text="harga(data)"></p>
							</div>
						</div>
					</li>
					<li class="flex justify-between py-6 border-b-2 border-gray-200">
						<div class="font-semibold text-gray-900">Total</div>
						<div class="font-semibold text-gray-700" v-text="total"></div>
					</li>
				</ul>
			</div>
			<button class="fixed bottom-0 right-0 m-4 btn-with-icon btn btn-lg btn-indigo">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-white">
					<path fill-rule="evenodd" d="M7.5 6v.75H5.513c-.96 0-1.764.724-1.865 1.679l-1.263 12A1.875 1.875 0 004.25 22.5h15.5a1.875 1.875 0 001.865-2.071l-1.263-12a1.875 1.875 0 00-1.865-1.679H16.5V6a4.5 4.5 0 10-9 0zM12 3a3 3 0 00-3 3v.75h6V6a3 3 0 00-3-3zm-3 8.25a3 3 0 106 0v-.75a.75.75 0 011.5 0v.75a4.5 4.5 0 11-9 0v-.75a.75.75 0 011.5 0v.75z" clip-rule="evenodd" />
				</svg>
				<span>Lanjutkan Pembayaran</span>
			</button>
		</div>
	</div>
</template>

<script>
export default {
	name: "Summary",
	// layout: "user",
	data() {
		return {
			pelanggan: {
				nama_pelanggan: "",
				no_telepon: "",
				alamat: "",
			},
			existedPelanggan: false,
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
			// data.forEach((element) => {});
			const first = 0;
			let result = data.reduce((accumulator, currentValue) => accumulator + currentValue.sku.harga[currentValue.sku.harga.length - 1].harga_produk * currentValue.qty_produk, first);
			return "Rp. " + Intl.NumberFormat().format(result);
		},
	},
	methods: {
		harga(data) {
			let price = data.sku.harga[data.sku.harga.length - 1].harga_produk * data.qty_produk;
			return "Rp. " + Intl.NumberFormat().format(price);
		},
		fetch() {
			this.$store.dispatch("cart/fetchCarts");
		},
	},
};
</script>

<style></style>
