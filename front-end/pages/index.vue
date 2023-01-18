<template>
	<div class="margin-auth space-y-4">
		<div class="max-w-7xl bg-green-100 border-2 border-green-500 h-60 mb-4 rounded-lg">
			<div class="flex justify-center items-center h-full">
				<p class="text-2xl font-semibold text-center text-green-700">UQI Media Digital Printing</p>
			</div>
		</div>
		<!-- MAIN DATA PRODUK -->
		<div class="flex sm:flex-row gap-4 flex-col justify-between items-center">
			<p class="text-xl md:basis-1/2 lg:basis-3/4 font-semibold text-gray-600">Koleksi Produk</p>
			<div class="relative md:basis-1/2 lg:basis-1/4">
				<!-- v-model="search" @keyup="searchData" -->
				<input type="search" class="w-full py-2 pl-8 pr-4 text-sm font-medium text-gray-800 border border-gray-200 rounded-lg shadow focus:outline-2 focus:outline-green-100 focus:ring-2 focus:ring-green-300" placeholder="Search..." />
				<div class="absolute top-0 left-0 inline-flex items-center px-2 py-3">
					<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
						<rect x="0" y="0" width="24" height="24" stroke="none"></rect>
						<circle cx="10" cy="10" r="7" />
						<line x1="21" y1="21" x2="15" y2="15" />
					</svg>
				</div>
			</div>
			<!-- <p class="text-xl font-semibold text-gray-600">Sort</p> -->
		</div>
		<div class="col-span-4 md:col-span-3 lg:col-span-4">
			<div class="max-w-2xl mx-auto sm:py-2 lg:max-w-7xl">
				<div v-if="products.length > 0">
					<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
						<a href="#" class="px-3 py-2 space-y-2 hover:bg-green-100 hover:rounded-lg" v-for="product in products">
							<NuxtLink :to="{name: 'id-slug', params: {id: product.id_produk, slug: product.slug_produk}}">
								<div class="overflow-hidden bg-gray-200 border-2 border-green-400 rounded-lg aspect-square">
									<img :src="'http://localhost:8000/storage/image_produk/' + product.id_produk + '/' + JSON.parse(product.image_produk)[0].filename" alt="" class="object-cover w-full h-full rounded-md" />
								</div>
								<div>
									<p class="mt-1 font-semibold tracking-tight text-gray-900">{{ product.nama_produk }}</p>
									<p class="text-[11px] font-medium text-gray-900" v-if="product.stok[0]">
										<span v-text="harga(product.stok)"></span>
										<span class="mt-4 text-xs text-gray-600"> / {{ product.satuan_produk }}</span>
									</p>
								</div>
							</NuxtLink>
						</a>
						<!-- More products... -->
					</div>
				</div>
				<div v-else>
					<div class="max-w-2xl px-2 mx-auto sm:py-2 sm:px-4 lg:max-w-7xl">
						<div class="flex items-center justify-center gap-4 py-40 font-semibold text-center border-2 border-gray-300 rounded-lg">
							<svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
								<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
							</svg>
							<span class="text-3xl"> Tidak Ada Data </span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	name: "IndexPage",
	layout: "user",
	auth: false,
	data() {
		return {
			user: this.$auth.user,
			products: [],
		};
	},
	mounted() {
		this.getProducts();
	},
	methods: {
		async getProducts() {
			const products = await this.$axios.$get("/api/katalog");
			this.products = products;
		},
		harga(data) {
			let result = [];
			data.forEach((element) => {
				element.harga.forEach((element) => {
					result.push(element.harga_produk);
				});
			});
			let number = result.sort(function (a, b) {
				return a - b;
			});
			let smallest = number[0];
			let highest = number[number.length - 1];

			let price = "Rp. " + Intl.NumberFormat().format(smallest) + " - Rp. " + Intl.NumberFormat().format(highest);
			return price;
		},
	},
};
</script>
