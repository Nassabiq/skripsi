<template>
	<div class="auth">
		<div class="grid grid-cols-5 gap-4">
			<div class="col-span-5 space-y-4 md:col-span-2 lg:col-span-1 card">
				<p class="text-2xl font-semibold text-gray-800">Katalog</p>
				<ul class="space-y-1">
					<li @click.prevent="clear" :class="{'bg-green-600 text-white': id_kategori == ''}" class="px-4 py-2 text-sm font-medium text-gray-800 rounded-md cursor-pointer hover:bg-green-600 hover:text-white">All Data</li>
					<li @click.prevent="getKatalog(data.id_kategori_produk)" :class="{'bg-green-600 text-white': id_kategori == data.id_kategori_produk}" class="px-4 py-2 text-sm font-medium text-gray-800 rounded-md cursor-pointer hover:bg-green-600 hover:text-white" v-for="(data, index) in categories">{{ data.nama_kategori }}</li>
				</ul>
			</div>
			<div class="col-span-5 md:col-span-3 lg:col-span-4">
				<div v-if="products.length > 0">
					<div class="max-w-2xl px-2 mx-auto sm:py-2 sm:px-4 lg:max-w-7xl">
						<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
							<a href="#" class="px-3 py-2 bg-gray-100 rounded-lg shadow-lg group shadow-gray-200" v-for="product in products">
								<div class="w-full overflow-hidden bg-gray-200 rounded-lg aspect-square">
									<img :src="'http://localhost:8000/storage/image_produk/' + product.id_produk + '/' + JSON.parse(product.image)[0].filename" alt="" class="object-cover w-full h-full border-2 rounded-md border-slate-200" />
									<!-- <img :src="product.image" class="object-cover object-center w-full h-full group-hover:opacity-75" /> -->
								</div>
								<p class="mt-1 font-semibold text-gray-700">{{ product.nama_produk }}</p>
								<p class="text-xs font-medium text-gray-900" v-if="product.stok[0]">
									<span v-text="harga(product.stok)"></span>
									<!-- Rp. {{ Intl.NumberFormat().format(product.stok[0].harga[0].harga_produk) }} -->
									<span class="mt-4 text-xs text-gray-600"> / {{ product.satuan_produk }}</span>
								</p>
								<div class="flex items-center justify-end gap-2 hover:text-green-600">
									<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
										<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
									</svg>
									<p class="text-xs font-semibold" @click.prevent="showModal(product)">Add to Cart</p>
								</div>
							</a>
							<!-- More products... -->
						</div>
					</div>
				</div>
				<div v-else>
					<div class="max-w-full bg-gray-100 rounded-md sm:py-2">
						<div class="px-8 py-6 mx-auto text-center">Tidak Ada Data</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Modal Tambah Produk -->
		<Modal size="max-w-5xl" title="Add to Cart" @close-modal="closeModal" v-show="modal" :data="modalData">
			<template #content v-if="modalData">
				<div class="col-span-12 space-y-4 md:col-span-6">
					<p class="text-2xl font-semibold">{{ modalData.nama_produk }}</p>
					<div>
						<label class="label">Informasi Produk</label>
						<p class="text-sm" v-html="modalData.description"></p>
					</div>
					<div>
						<label class="label">Informasi Pemesanan</label>
						<p class="text-sm" v-html="modalData.informasi_pemesanan"></p>
					</div>
				</div>
				<div class="col-span-12 md:col-span-6">
					<div class="grid grid-cols-12 gap-4">
						<div class="col-span-12 md:col-span-6">
							<label class="label">Jenis Bahan</label>
							<select class="form-input form-input-lg" v-model="cart.bahan_baku">
								<option value="">Pilih Bahan</option>
								<option :value="{id: data.id_sku, price: data.harga[data.harga.length - 1].harga_produk}" v-for="data in modalData.stok">
									{{ data.bahan_baku.nama_bahan_baku }}
								</option>
							</select>
							<p class="mt-2 text-xs text-red-500" v-if="validation.id_sku">{{ validation.id_sku[0] }}</p>

							<!-- <p class="mt-2 text-xs text-red-500" v-if="validation.id_kategori_produk">{{ validation.id_kategori_produk[0] }}</p> -->
						</div>
						<div class="col-span-12 md:col-span-6">
							<label class="label">Jumlah Order (Dalam Pcs)</label>
							<div class="flex justify-between gap-2">
								<button class="mt-1 btn btn-sm btn-indigo" @click.prevent="cart.qty_produk--" :disabled="cart.qty_produk <= 0">
									<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
										<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
									</svg>
								</button>
								<input type="text" class="form-input form-input-lg" v-model="cart.qty_produk" placeholder="QTY ... " disabled />
								<button class="mt-1 btn btn-sm btn-indigo" @click.prevent="cart.qty_produk++">
									<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
									</svg>
								</button>
							</div>
							<p class="mt-2 text-xs text-red-500" v-if="validation.qty_produk">{{ validation.qty_produk[0] }}</p>

							<!-- <p class="mt-2 text-xs text-red-500" v-if="validation.nama_produk">{{ validation.nama_produk[0] }}</p> -->
						</div>

						<div class="col-span-12 md:col-span-8" v-if="modalData.satuan_produk == 'm2'">
							<label class="text-xs font-semibold text-gray-700">Ukuran Pesanan</label>
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
						<div class="col-span-12 md:col-span-4 place-self-center" v-if="cart.bahan_baku.price">
							<label class="text-xs font-semibold text-gray-700">Harga</label>
							<p class="text-lg text-gray-700">Rp. {{ Intl.NumberFormat().format(cart.bahan_baku.price) }}</p>
						</div>
						<div class="col-span-12">
							<div>
								<label class="label">Finishing</label>
								<select class="form-input form-input-lg" v-model="cart.finishing">
									<option value="">Pilih Jenis Finishing</option>
									<option :value="data.id_finishing" v-for="data in modalData.finishing">
										{{ data.nama_finishing }}
									</option>
								</select>
								<p class="mt-2 text-xs text-red-500" v-if="validation.finishing">{{ validation.finishing[0] }}</p>
							</div>
						</div>
						<div class="flex justify-end col-span-12" v-if="cart.bahan_baku.price">
							<div>
								<label class="text-xs font-semibold text-gray-700">Subtotal</label>
								<p class="text-lg text-gray-700">Rp. {{ Intl.NumberFormat().format(subtotal(modalData)) }}</p>
							</div>
						</div>
					</div>
				</div>
			</template>
			<template #submit>
				<button class="btn btn-lg btn-green" @click="addToCart">Submit</button>
			</template>
		</Modal>
	</div>
</template>

<script>
import Modal from "../components/ModalComponent.vue";
export default {
	name: "katalog",
	layout: "user",
	auth: false,
	components: {Modal},
	data() {
		return {
			// user : this.$auth.user
			products: [],
			categories: [],
			id_kategori: "",

			modalData: null,

			cart: {
				bahan_baku: "",
				qty_produk: 0,
				finishing: "",
				panjang: "",
				lebar: "",
			},
			validation: [],
			modal: false,

			editorOption: {
				// Some Quill options...
				theme: "snow",
				modules: {
					toolbar: [["bold", "italic", "underline", "strike"]],
				},
			},
		};
	},
	mounted() {
		this.getProducts();
		this.getCategories();
	},
	computed: {},
	methods: {
		async getProducts() {
			const products = await this.$axios.$get("/api/katalog?kategori=" + this.id_kategori);
			this.products = products;
		},
		async getCategories() {
			const categories = await this.$axios.$get("/api/kategori");
			this.categories = categories;
		},
		getKatalog(id) {
			this.id_kategori = id;
			this.getProducts();
		},
		clear() {
			this.id_kategori = "";
			this.getProducts();
		},
		showModal(data) {
			if (this.$auth.loggedIn) {
				this.modalData = data;
				this.modal = !this.modal;
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
		closeModal() {
			this.modalData = null;
			this.cart = {
				bahan_baku: "",
				qty: 0,
				finishing: "",
				panjang: "",
				lebar: "",
			};
			this.modal = !this.modal;
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

		subtotal(data) {
			return data.satuan_produk == "m2" ? this.cart.bahan_baku.price * (this.cart.panjang * this.cart.lebar) * this.cart.qty_produk : this.cart.bahan_baku.price * this.cart.qty_produk;
		},

		addToCart() {
			this.$axios
				.post("/api/cart", {
					id_user: this.$auth.user.id_user,
					id_sku: this.cart.bahan_baku.id,
					finishing: this.cart.finishing,
					ukuran: this.cart.panjang * this.cart.lebar,
					qty_produk: this.cart.qty_produk,
				})
				.then((response) => {
					this.closeModal();
					this.getProducts();
					this.$store.dispatch("cart/fetchCarts");
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
					console.log(error);
					this.validation = error.response.data;
				});
		},
	},
};
</script>
