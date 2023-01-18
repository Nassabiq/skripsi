<template>
	<div class="gap-4 margin-auth space-y-4">
		<div class="grid grid-cols-12 gap-4">
			<div class="col-span-12 md:col-span-4">
				<div class="item">
					<img v-if="product.image_produk" :src="imageUrl + product.id_produk + '/' + currentImage" class="object-cover w-full border-2 aspect-square border-slate-200 h-80" />
					<Skeleton v-else />
				</div>
				<div class="grid grid-cols-4 gap-4 mt-4 thumbnails">
					<div class="col-span-1 cursor-pointer" v-for="(img, index) in product.image_produk" :key="index" @click.prevent="activateImage(index)">
						<img :src="imageUrl + product.id_produk + '/' + img.filename" alt="" :class="activeImage == index ? 'outline outline-offset-2 outline-4 outline-sky-500 ring-2 ring-red-300' : ''" class="object-cover w-full rounded-md aspect-square" />
					</div>
				</div>
			</div>
			<div class="col-span-12 md:col-span-8">
				<div class="grid grid-cols-12 py-2 border-b-2 border-slate-200">
					<div class="col-span-10 item">
						<p v-if="product.nama_produk" class="text-4xl font-semibold text-gray-700 capitalize font-title">{{ product.nama_produk }}</p>
						<!-- <p v-if="product.kategori" class="text-sm text-gray-600">{{ product.kategori.nama_kategori }}</p> -->
						<Skeleton v-else height="50px" />
					</div>
				</div>
				<div class="py-4 border-b-2 border-slate-200">
					<p class="pb-2 text-xl font-bold text-gray-600 capitalize">Deskripsi</p>
					<p class="text-xs text-justify text-slate-500" v-html="product.deskripsi_produk"></p>
				</div>
				<div class="py-4 border-b-2 border-slate-200">
					<p class="pb-2 text-xl font-bold text-gray-600 capitalize">Informasi Pemesanan</p>
					<p class="text-xs text-justify text-slate-500" v-html="product.informasi_pemesanan"></p>
				</div>
				<div class="py-4 border-b-2 border-slate-200">
					<p class="pb-2 text-lg font-bold text-gray-600 capitalize">Jenis Bahan</p>
					<div v-if="product.stok !== undefined && product.stok.length > 0" class="flex flex-wrap gap-4">
						<div v-for="data in product.stok" class="text-sm text-justify text-slate-500 rounded-lg border-green-500 border px-3 py-2">
							<p class="text-xs text-gray-700">{{ data.bahan_baku.nama_bahan_baku }}</p>
							<p class="text-xs font-semibold text-gray-700">Rp. {{ Intl.NumberFormat().format(product.stok[0].harga[0].harga_produk) }}</p>
						</div>
					</div>
					<p v-else class="text-sm text-justify text-slate-500">Tidak Ada Data</p>
					<!-- <Skeleton :count="5" v-else /> -->
				</div>
				<div class="py-4 border-b-2 border-slate-200 space-y-4">
					<div class="grid grid-cols-2 gap-4">
						<div class="col-span-2 md:col-span-1">
							<p class="pb-2 text-lg font-bold text-gray-600 capitalize">Jenis Finishing</p>
							<div class="flex flex-wrap gap-4">
								<div class="text-xs text-gray-700 border border-green-500 px-3 py-2 rounded-lg" v-for="data in product.finishing">{{ data.nama_finishing }}</div>
							</div>
						</div>
						<div class="col-span-2 md:col-span-1" v-if="product.satuan_produk == 'm2'">
							<div class="flex justify-between gap-4">
								<div>
									<label class="text-xs font-semibold text-gray-700">Panjang (meter)</label>
									<input type="text" class="form-input form-input-lg" v-model="cart.panjang" placeholder="dalam Meter ... " />
								</div>
								<div>
									<label class="text-xs font-semibold text-gray-700">Lebar (meter)</label>
									<input type="text" class="form-input form-input-lg" v-model="cart.lebar" placeholder="dalam Meter ... " />
								</div>
							</div>
						</div>
					</div>
					<div class="flex gap-4 justify-between">
						<div>
							<div class="flex gap-2">
								<button class="mt-1 btn btn-sm btn-green" @click.prevent="cart.qty_cart--" :disabled="cart.qty_cart <= 0">
									<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
										<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
									</svg>
								</button>
								<input type="text" class="grow-0 block px-3 py-2 mt-1 text-xs border border-gray-300 rounded shadow" v-model="cart.qty_cart" placeholder="QTY ... " disabled />
								<button class="mt-1 btn btn-sm btn-green" @click.prevent="cart.qty_cart++">
									<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
									</svg>
								</button>
							</div>
							<p class="mt-2 text-xs text-red-500" v-if="validation.qty_cart">{{ validation.qty_cart[0] }}</p>
						</div>
						<div>
							<button class="btn btn-sm btn-with-icon border focus:ring focus:ring-green-300 border-green-500 focus:bg-green-500 hover:bg-green-500 hover:text-white text-green-700 focus:text-white">
								<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
									<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
								</svg>
								Add to Cart
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
// import {Skeleton} from "vue-loading-skeleton";

export default {
	auth: false,
	layout: "user",
	// components: {Skeleton},
	data() {
		return {
			product: [],

			previewImage: null,
			activeImage: 0,
			imageUrl: "http://localhost:8000/storage/image_produk/",

			cart: {
				bahan_baku: "",
				qty_cart: 0,
				finishing: "",
				panjang: "",
				lebar: "",
			},

			validation: [],
		};
	},
	mounted() {
		this.getData();
	},
	computed: {
		currentImage() {
			return this.product.image_produk[this.activeImage].filename;
		},
	},
	methods: {
		async getData() {
			const product = await this.$axios.$get("/api/katalog/" + this.$route.params.id);
			// const categories = await this.$axios.$get("/api/kategori");
			// const bahanBaku = await this.$axios.$get("/api/bahan-baku");

			this.product = product;
			this.product.image_produk = JSON.parse(this.product.image_produk);
			this.uploadedImage.allImages = [...this.product.image_produk];

			// this.categories = categories;
			// this.bahanBaku = bahanBaku;
		},
		activateImage(imageIndex) {
			this.activeImage = imageIndex;
		},
	},
};
</script>

<style></style>
