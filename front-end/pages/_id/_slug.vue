<template>
	<div class="gap-4 space-y-4 margin-auth">
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
						<div class="flex items-center gap-4 p-2 overflow-x-scroll">
							<label v-for="data in product.stok" class="relative px-4 py-3 mt-2 text-xs font-medium text-gray-900 bg-white border rounded-md shadow-sm cursor-pointer group hover:bg-gray-50 focus:outline-none" :class="[cart.bahan_baku.id == data.id_sku ? 'ring-2 ring-green-500' : '', data.bahan_baku.jml_stok < 1 ? 'ring-2 ring-red-500' : '']">
								<input type="radio" v-model="cart.bahan_baku" :value="{id: data.id_sku, price: data.harga[data.harga.length - 1].harga_produk}" class="sr-only" aria-labelledby="size-choice-0-label" :disabled="data.jml_stok < 1" />
								<div class="flex flex-col">
									<span id="size-choice-0-label" class="whitespace-nowrap" v-text="data.bahan_baku.nama_bahan_baku"></span>
									<span class="text-xs font-semibold text-gray-700">Rp. {{ Intl.NumberFormat().format(data.harga[data.harga.length - 1].harga_produk) }}</span>
								</div>

								<span v-if="data.bahan_baku.jml_stok < 1" aria-hidden="true" class="absolute border-2 border-gray-200 rounded-md pointer-events-none -inset-px">
									<svg class="absolute inset-0 w-full h-full text-red-500 stroke-2" viewBox="0 0 100 100" preserveAspectRatio="none" stroke="currentColor">
										<line x1="0" y1="100" x2="100" y2="0" vector-effect="non-scaling-stroke" />
									</svg>
								</span>
							</label>
						</div>
					</div>
					<p v-else class="text-sm text-justify text-slate-500">Tidak Ada Data</p>
					<p class="mt-2 text-xs text-red-500" v-if="validation.id_sku">{{ validation.id_sku[0] }}</p>
				</div>
				<div class="py-4 space-y-4 border-b-2 border-slate-200">
					<p class="pb-2 text-lg font-bold text-gray-600 capitalize">Jenis Finishing</p>
					<div class="grid grid-cols-2 gap-2">
						<div class="col-span-2 xl:col-span-1">
							<div class="flex flex-wrap gap-4" v-if="product.finishing !== undefined && product.finishing.length > 0">
								<div class="flex items-center gap-4 p-2 overflow-x-scroll">
									<label v-for="data in product.finishing" class="relative px-4 py-3 mt-2 text-xs font-medium text-gray-900 bg-white border rounded-md shadow-sm cursor-pointer group hover:bg-gray-50 focus:outline-none" :class="cart.finishing == data.id_finishing ? 'ring-2 ring-green-500' : ''">
										<input type="radio" v-model="cart.finishing" :value="data.id_finishing" class="sr-only" aria-labelledby="size-choice-0-label" />
										<span id="size-choice-0-label" class="whitespace-nowrap" v-text="data.nama_finishing"></span>
									</label>
								</div>
							</div>
							<p class="mt-2 text-xs text-red-500" v-if="validation.finishing">{{ validation.finishing[0] }}</p>
						</div>
						<div class="col-span-2 xl:col-span-1" v-if="product.satuan_produk == 'm2'">
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
					<div class="grid grid-cols-2 gap-4">
						<div class="col-span-2 md:col-span-1">
							<p class="pb-2 text-lg font-bold text-gray-600 capitalize">QTY</p>
							<div class="flex gap-2">
								<button class="mt-1 btn btn-sm btn-green" @click.prevent="cart.qty_cart--" :disabled="cart.qty_cart <= 0">
									<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
										<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
									</svg>
								</button>
								<input type="text" class="block px-3 py-2 mt-1 text-xs border border-gray-300 rounded shadow grow-0" v-model="cart.qty_cart" placeholder="QTY ... " disabled />
								<button class="mt-1 btn btn-sm btn-green" @click.prevent="cart.qty_cart++">
									<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
									</svg>
								</button>
							</div>
							<p class="mt-2 text-xs text-red-500" v-if="validation.qty_cart">{{ validation.qty_cart[0] }}</p>
						</div>
						<div class="col-span-2 md:col-span-1 place-self-end">
							<button @click.prevent="addToCart" class="text-green-700 border border-green-500 btn btn-sm btn-with-icon focus:ring focus:ring-green-300 focus:bg-green-500 hover:bg-green-500 hover:text-white focus:text-white">
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

			this.product = product;
			this.product.image_produk = JSON.parse(this.product.image_produk);
			this.uploadedImage.allImages = [...this.product.image_produk];
		},
		activateImage(imageIndex) {
			this.activeImage = imageIndex;
		},

		addToCart() {
			this.$axios
				.post("/api/cart", {
					id_user: this.$auth.user.id_user,
					id_sku: this.cart.bahan_baku.id,
					finishing: this.cart.finishing,
					ukuran: (this.cart.panjang || this.cart.lebar) == 0 ? null : JSON.stringify({panjang: parseInt(this.cart.panjang), lebar: parseInt(this.cart.lebar)}),
					qty_cart: this.cart.qty_cart,
				})
				.then(() => {
					this.getData();
					this.$store.dispatch("cart/fetchCarts");
					this.cart = {
						bahan_baku: "",
						qty_cart: 0,
						finishing: "",
						panjang: "",
						lebar: "",
					};
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
		},
	},
};
</script>

<style></style>
