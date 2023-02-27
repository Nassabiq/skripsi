<template>
	<div class="auth">
		<div class="grid grid-cols-4 gap-4">
			<div class="col-span-4 md:col-span-3 lg:col-span-4">
				<div class="max-w-2xl px-2 mx-auto sm:py-2 sm:px-4 lg:max-w-5xl">
					<div class="flex items-center justify-between mb-4">
						<p class="px-4 mb-4 text-2xl font-semibold text-gray-800">Katalog</p>
						<input v-model="search" @keyup="searchData" type="search" class="w-1/4 py-2 pl-8 pr-4 text-sm font-medium text-gray-800 border border-gray-200 rounded-lg shadow focus:outline-2 focus:outline-green-100 focus:ring-2 focus:ring-green-300" placeholder="Search..." />
					</div>
					<div v-if="products.length > 0">
						<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
							<a href="#" class="px-3 py-2 space-y-2" v-for="product in products">
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
								<div class="flex justify-end">
									<button class="flex items-center gap-2 px-3 py-2 text-green-700 border border-green-700 rounded-full btn-sm hover:text-white hover:bg-green-700" @click="showModal(product)">
										<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
											<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
										</svg>
										<p class="text-xs font-semibold">Add to Cart</p>
									</button>
								</div>
							</a>
							<!-- More products... -->
						</div>
					</div>
					<div v-else>
						<div class="max-w-2xl px-2 mx-auto sm:py-2 sm:px-4 lg:max-w-5xl">
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
		<!-- Modal Tambah ke Cart -->
		<Modal size="max-w-6xl" title="Add to Cart" @close-modal="closeModal" v-show="modal" :data="modalData">
			<template #content v-if="modalData">
				<div class="col-span-12 space-y-4 md:col-span-6">
					<p class="text-2xl font-semibold">{{ modalData.nama_produk }}</p>
					<div>
						<label class="label">Informasi Produk</label>
						<p class="text-sm" v-html="modalData.deskripsi_produk"></p>
					</div>
					<div>
						<label class="label">Informasi Pemesanan</label>
						<p class="text-sm" v-html="modalData.informasi_pemesanan"></p>
					</div>
				</div>
				<div class="col-span-12 md:col-span-6">
					<div class="grid grid-cols-12 gap-4">
						<div class="col-span-12 md:col-span-6">
							<label class="text-xs font-semibold text-gray-700">Harga</label>
							<p class="text-lg text-gray-700" v-if="cart.bahan_baku.price">Rp. {{ Intl.NumberFormat().format(cart.bahan_baku.price) }}</p>
							<p class="text-lg text-gray-700" v-else>-</p>
						</div>
						<div class="col-span-12 md:col-span-6">
							<label class="label">Jumlah Order (Dalam Pcs)</label>
							<div class="flex justify-between gap-2">
								<button class="mt-1 btn btn-sm btn-indigo" @click.prevent="cart.qty_cart--" :disabled="cart.qty_cart <= 0">
									<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
										<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
									</svg>
								</button>
								<input type="text" class="form-input form-input-lg" v-model="cart.qty_cart" placeholder="QTY ... " disabled />
								<button class="mt-1 btn btn-sm btn-indigo" @click.prevent="cart.qty_cart++">
									<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
									</svg>
								</button>
							</div>
							<p class="mt-2 text-xs text-red-500" v-if="validation.qty_cart">{{ validation.qty_cart[0] }}</p>
						</div>
						<div class="col-span-12">
							<label class="label">Jenis Bahan</label>
							<p class="mt-2 text-xs text-red-500" v-if="validation.id_sku">{{ validation.id_sku[0] }}</p>
							<div class="flex items-center gap-4 p-2 overflow-x-scroll">
								<label v-for="data in modalData.stok" class="relative flex items-center justify-center px-4 py-3 mt-2 text-xs font-medium text-gray-900 bg-white border rounded-md shadow-sm cursor-pointer group hover:bg-gray-50 focus:outline-none" :class="[cart.bahan_baku.id == data.id_sku ? 'ring-2 ring-blue-500' : '', data.bahan_baku.jml_stok < 1 ? 'ring-2 ring-red-500' : '']">
									<input type="radio" v-model="cart.bahan_baku" :value="{id: data.id_sku, price: data.harga[data.harga.length - 1].harga_produk}" class="sr-only" aria-labelledby="size-choice-0-label" :disabled="data.jml_stok < 1" />
									<span id="size-choice-0-label" class="whitespace-nowrap" v-text="data.bahan_baku.nama_bahan_baku"></span>

									<span v-if="data.bahan_baku.jml_stok < 1" aria-hidden="true" class="absolute border-2 border-gray-200 rounded-md pointer-events-none -inset-px">
										<svg class="absolute inset-0 w-full h-full text-red-500 stroke-2" viewBox="0 0 100 100" preserveAspectRatio="none" stroke="currentColor">
											<line x1="0" y1="100" x2="100" y2="0" vector-effect="non-scaling-stroke" />
										</svg>
									</span>
								</label>
							</div>
							<!-- <p class="mt-2 text-xs text-red-500" v-if="validation.id_kategori_produk">{{ validation.id_kategori_produk[0] }}</p> -->
						</div>

						<div class="col-span-12" v-if="modalData.satuan_produk == 'm2'">
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
						<div class="col-span-12">
							<div>
								<label class="label">Upload File</label>
								<div class="flex flex-col justify-center px-6 py-4 mt-1 border-2 border-green-500 border-dashed rounded-md">
									<div class="space-y-2">
										<div class="flex items-center gap-4 mx-auto">
											<svg xmlns="http://www.w3.org/2000/svg" v-if="cart.file_upload.length == 0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400">
												<path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13" />
											</svg>
											<label for="file-upload" class="relative font-medium text-green-500 bg-white rounded-md cursor-pointer hover:text-green-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
												<span class="font-semibold">{{ cart.file_upload.length ? cart.file_upload.length + " File terpilih (Add More)" : "Pilih File" }}</span>
												<input id="file-upload" type="file" ref="fileUpload" multiple name="file-upload" @change="handleSelectedFile" class="sr-only" />
												<p class="mt-2 text-xs text-gray-500">File Max 20MB, PDF, JPEG, JPG or PNG</p>
											</label>
										</div>
										<ul class="px-2 py-1 mt-2 bg-green-200 border-2 border-green-500 divide-y divide-green-500 rounded-md" v-if="cart.file_upload.length > 0">
											<li v-for="(file, key) in cart.file_upload" class="p-2 text-xs">
												<div class="flex items-center justify-between">
													<p>{{ file.name }}</p>
													<p class="font-semibold">{{ formatBytes(file.size) }}</p>
													<button class="btn btn-sm btn-red" @click="removeImage(key)">
														<svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor">
															<path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
														</svg>
													</button>
												</div>
												<!-- <span class="text-xs text-red-500" v-if="validation[`image_produk.` + key]">Image must not be greater than 5000 kilobytes.</span> -->
											</li>
										</ul>
									</div>
								</div>
								<p class="mt-2 text-xs text-red-500" v-if="validation.file_upload">{{ validation.file_upload[0] }}</p>
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

			search: "",

			cart: {
				bahan_baku: "",
				qty_cart: 0,
				finishing: "",
				panjang: "",
				lebar: "",
				file_upload: [],
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
			const products = await this.$axios.$get("/api/katalog?search=" + this.search);
			this.products = products;
		},
		async getCategories() {
			const categories = await this.$axios.$get("/api/kategori");
			this.categories = categories;
		},
		searchData() {
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
				qty_cart: 0,
				finishing: "",
				panjang: "",
				lebar: "",
				file_upload: [],
			};
			this.validation = [];
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
			return data.satuan_produk == "m2" ? this.cart.bahan_baku.price * (this.cart.panjang * this.cart.lebar) * this.cart.qty_cart : this.cart.bahan_baku.price * this.cart.qty_cart;
		},
		formatBytes(bytes, decimals = 2) {
			if (!+bytes) return "0 Bytes";

			const k = 1024;
			const dm = decimals < 0 ? 0 : decimals;
			const sizes = ["Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"];

			const i = Math.floor(Math.log(bytes) / Math.log(k));

			return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`;
		},
		handleSelectedFile() {
			let selectedFile = this.$refs.fileUpload.files;

			for (let i = 0; i < selectedFile.length; i++) {
				let isFileExists = this.cart.file_upload.find((file) => file.type === selectedFile[i].type && file.name === selectedFile[i].name && file.size === selectedFile[i].size && file.lastModified === selectedFile[i].lastModified);

				if (!isFileExists) this.cart.file_upload.push(selectedFile[i]);
			}
		},
		removeImage(key) {
			this.cart.file_upload.splice(key, 1);
		},

		addToCart() {
			let data = new FormData();
			let file = this.cart.file_upload;

			file.forEach((item, index) => data.append("file_upload[" + index + "]", file[index]));
			data.append("id_user", this.$auth.user.id_user);
			data.append("id_sku", this.cart.bahan_baku.id);
			data.append("finishing", this.cart.finishing);
			data.append("ukuran", (this.cart.panjang || this.cart.lebar) == 0 ? null : JSON.stringify({panjang: parseInt(this.cart.panjang), lebar: parseInt(this.cart.lebar)}));
			data.append("qty_cart", this.cart.qty_cart);

			this.$axios
				.post("/api/cart", data)
				.then(() => {
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
