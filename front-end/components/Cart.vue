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
																<p class="ml-4 text-sm" v-text="harga(data)"></p>
															</div>
															<p class="mt-1 text-sm text-gray-500">{{ data.sku.bahan_baku.nama_bahan_baku }}</p>
														</div>
														<div class="flex items-end justify-between flex-1 text-sm">
															<p class="text-gray-500">{{ data.qty_produk }} {{ data.sku.produk.satuan_produk }}</p>

															<div class="flex">
																<button @click.prevent="removeCart(data.id_cart)" type="button" class="font-medium text-indigo-600 hover:text-indigo-400">Remove</button>
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
								<div class="flex justify-center my-4 btn btn-indigo btn-lg">
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
			cart: [],
		};
	},
	mounted() {
		this.getCart();
	},
	methods: {
		async getCart() {
			const cart = await this.$axios.$get("/api/cart");
			this.cart = cart;
		},
		harga(data) {
			let price = data.sku.harga[data.sku.harga.length - 1].harga_produk * data.qty_produk;
			return "Rp. " + Intl.NumberFormat().format(price);
		},
		async removeCart(id) {
			await this.$swal
				.fire({
					icon: "question",
					title: "Hapus data ini?",
					text: "Data yang sudah dihapus tidak dapat dikembalikan",
					width: 600,
					showCloseButton: true,
					showCancelButton: true,
					cancelButtonColor: "#d33",
					confirmButtonText: "Remove",
				})
				.then((result) => {
					/* Read more about isConfirmed, isDenied below */
					if (result.isConfirmed) {
						this.$axios
							.delete("api/cart/" + id)
							.then(() => this.$swal.fire("Deleted!", "Your file has been deleted.", "success"))
							.then(() => this.getCart());
					}
				});
		},
	},
};
</script>

<style></style>
