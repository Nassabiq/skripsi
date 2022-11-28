<template>
	<div>
		<div class="absolute inset-0 overflow-hidden">
			<div class="fixed inset-y-0 right-0 flex max-w-full pl-10 pointer-events-none">
				<div class="relative w-screen max-w-md pointer-events-auto">
					<div class="flex flex-col h-full py-6 overflow-y-scroll bg-white border-l-2 border-green-600 shadow-xl">
						<div class="px-4 sm:px-6">
							<div class="flex justify-between">
								<h2 class="text-lg font-semibold text-gray-900" id="slide-over-title">Cart</h2>
								<button @click.prevent="$emit('close-cart')" type="button" class="text-gray-700 rounded-md hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-white">
									<span class="sr-only">Close panel</span>
									<!-- Heroicon name: outline/x-mark -->
									<svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
										<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
									</svg>
								</button>
							</div>
						</div>
						<div class="relative flex-1 px-4 mt-6 sm:px-6">
							<!-- Replace with your content -->
							<div class="absolute inset-0 px-4 sm:px-6">
								<div class="flex-col overflow-y-auto border-2 border-gray-200 border-dashed h-[90%]" aria-hidden="true">
									<div class="flow-root p-4">
										<ul role="list" class="-my-6 divide-y divide-gray-200">
											<template v-if="$auth.loggedIn">
												<li class="flex py-6 border-b-2 border-gray-200" v-for="data in cart">
													<div class="flex-shrink-0 w-24 h-24 overflow-hidden border border-gray-200 rounded-md">
														<img :src="'http://localhost:8000/storage/image_produk/' + data.sku.produk.id_produk + '/' + JSON.parse(data.sku.produk.image)[0].filename" alt="Salmon orange fabric pouch with match zipper, gray zipper pull, and adjustable hip belt." class="object-cover object-center w-full h-full" />
													</div>

													<div class="flex flex-col flex-1 ml-4">
														<div>
															<div class="flex justify-between text-base font-medium text-gray-900">
																<h3>
																	<a class="font-semibold" href="#">{{ data.sku.produk.nama_produk }}</a>
																</h3>
																<!-- <p class="ml-4 text-sm whitespace-nowrap" v-text="harga(data)"></p> -->
																<button @click.prevent="$store.dispatch('cart/removeCart', data.id_cart)" type="button" class="text-xs font-medium">
																	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 cursor-pointer hover:text-red-500">
																		<path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z" clip-rule="evenodd" />
																	</svg>
																</button>
															</div>
															<p class="mt-1 text-xs text-gray-500">{{ data.sku.bahan_baku.nama_bahan_baku }}</p>
														</div>
														<div class="flex items-end justify-between flex-1 text-sm">
															<p class="text-gray-500" v-text="qty(data)"></p>

															<div class="flex">
																<p class="ml-4 text-sm whitespace-nowrap" v-text="harga(data)"></p>
															</div>
														</div>
													</div>
												</li>
											</template>
											<template v-else>
												<li class="flex py-6 text-center">Silahkan Login Terlebih Dahulu</li>
											</template>
										</ul>
									</div>
									<!-- {{ cart }} -->
								</div>
								<div class="flex justify-center my-4 btn btn-indigo btn-lg" @click.prevent="checkout">
									<button class="btn-with-icon" type="submit">
										<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
											<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
										</svg>
										Checkout
									</button>
								</div>
							</div>
							<!-- /End replace -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	data() {
		return {
			// cart: [],
		};
	},
	mounted() {
		this.fetch();
	},
	computed: {
		cart() {
			return this.$store.state.cart.data;
		},
	},
	methods: {
		fetch() {
			this.$store.dispatch("cart/fetchCarts");
		},
		harga(data) {
			if (data.sku.produk.satuan_produk == "m2") {
				let ukuran = JSON.parse(data.ukuran).panjang * JSON.parse(data.ukuran).lebar;
				let price = data.sku.harga[data.sku.harga.length - 1].harga_produk * ukuran * data.qty_produk;
				return "Rp. " + Intl.NumberFormat().format(price);
			} else {
				let price = data.sku.harga[data.sku.harga.length - 1].harga_produk * data.qty_produk;
				return "Rp. " + Intl.NumberFormat().format(price);
			}
		},
		qty(data) {
			if (data.sku.produk.satuan_produk == "m2") {
				let ukuran = JSON.parse(data.ukuran);
				return ukuran.panjang * ukuran.lebar + " " + data.sku.produk.satuan_produk + " - " + data.qty_produk + " pcs";
			} else return data.qty_produk + data.sku.produk.satuan_produk;
		},
		checkout() {
			if (this.$auth.loggedIn) {
				if (this.cart.length > 0) {
					this.$router.push({name: "summary"});
				} else {
					this.$swal.fire({
						icon: "error",
						title: "Oops...",
						text: "Tidak Ada Data!",
						timer: 1500,
						timerProgressBar: true,
					});
				}
			} else {
				this.$swal
					.fire({
						icon: "error",
						title: "Oops...",
						text: "Anda harus login terlebih dahulu!",
						showCloseButton: true,
						showCancelButton: true,
						cancelButtonColor: "#d33",
						confirmButtonText: "Login",
					})
					.then((result) => {
						/* Read more about isConfirmed, isDenied below */
						if (result.isConfirmed) {
							this.$router.push("/login");
						}
					});
			}
		},
	},
};
</script>

<style></style>
