<template>
	<div class="gap-4 space-y-4 margin-auth">
		<div class="card">
			<div class="grid grid-cols-12 gap-4 mt-6 mb-2">
				<div class="flex flex-col col-span-12 col lg:col-span-8 sm:col-span-6">
					<div class="relative col-span-12 my-4 md:col-span-6">
						<input type="search" class="w-full py-2 pl-8 pr-4 text-sm font-medium text-gray-800 border border-gray-200 rounded-lg shadow md:w-3/5 lg:w-2/5 focus:outline-2 focus:outline-blue-100 focus:ring-2 focus:ring-blue-300" placeholder="Search..." v-model="search" @keyup="searchData" />
						<div class="absolute top-0 left-0 inline-flex items-center px-2 py-3">
							<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
								<rect x="0" y="0" width="24" height="24" stroke="none"></rect>
								<circle cx="10" cy="10" r="7" />
								<line x1="21" y1="21" x2="15" y2="15" />
							</svg>
						</div>
					</div>
					<!-- <span class="mb-2 text-sm font-medium"> Search : </span> -->
					<!-- <input class="block w-full mt-1 text-xs border border-gray-300 rounded shadow md:w-3/5 lg:w-2/5 form-input-lg focus:border-green-200 focus:outline-none focus:ring-2 focus:ring-green-300" placeholder="Search..." v-model="search" @keyup="searchData" /> -->
				</div>
				<div class="grid grid-cols-12 col-span-12 space-x-4 sm:col-span-6 lg:col-span-4">
					<div class="flex flex-col col-span-6">
						<span class="mb-2 text-xs font-semibold md:text-right"> Items Per Page : </span>
						<select @change="onChangeRecordsPerPage" v-model="productSize" class="px-2 py-2 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg shadow focus:outline-2 focus:outline-blue-100 focus:ring-2 focus:ring-blue-300">
							<option :value="8">8</option>
							<option :value="10">10</option>
							<option :value="25">25</option>
							<option :value="50">50</option>
						</select>
					</div>
					<div class="col-span-6 place-self-center">
						<button class="btn-with-icon btn btn-sm btn-blue" @click="modal = !modal">
							<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
							</svg>
							Tambah Produk
						</button>
					</div>
				</div>
			</div>
			<div class="max-w-2xl px-2 mx-auto sm:py-2 sm:px-4 lg:max-w-7xl">
				<div class="grid grid-cols-1 gap-y-6 gap-x-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
					<template v-if="products.data && products.data.length > 0">
						<a href="#" class="px-3 py-2 bg-gray-100 rounded-lg shadow-lg group shadow-gray-200" v-for="product in products.data">
							<NuxtLink :to="{name: 'produk-id', params: {id: product.id_produk}}">
								<div class="w-full overflow-hidden bg-gray-200 rounded-lg aspect-square">
									<img :src="'http://localhost:8000/storage/image_produk/' + product.id_produk + '/' + JSON.parse(product.image_produk)[0].filename" alt="" class="object-cover w-full h-full border-2 rounded-md border-slate-200" />
									<!-- <img :src="product.image" class="object-cover object-center w-full h-full group-hover:opacity-75" /> -->
								</div>
								<p class="mt-4 text-gray-700">{{ product.nama_produk }}</p>
								<p class="mt-1 text-sm font-medium text-gray-900">
									<span class="text-xs text-gray-600" v-text="product.stok.length > 0 ? 'Rp. ' + Intl.NumberFormat().format(product.stok[0].harga[0].harga_produk) : 'Data Harga Belum ada'"></span> <span class="mt-4 text-xs text-gray-600">/{{ product.satuan_produk }}</span>
								</p>
							</NuxtLink>
						</a>
					</template>
					<!-- More products... -->
				</div>

				<div class="py-2 mt-2 mb-6">
					<Pagination v-if="products.data && products.per_page < products.total" :total-pages="products.links.length - 2" :per-page="productSize" :current-page="products.current_page" @pagechanged="onPageChange"></Pagination>
				</div>
			</div>
		</div>
		<!-- Modal Tambah Produk -->
		<Modal size="max-w-6xl" title="Tambah Produk" @close-modal="closeModal" v-show="modal">
			<template #content>
				<div class="col-span-12 sm:col-span-4">
					<div class="my-2">
						<label class="label">Nama Produk</label>
						<input type="text" class="form-input form-input-lg" placeholder="Nama Produk ... " v-model="produk.nama_produk" />
						<p class="mt-2 text-xs text-red-500" v-if="validation.nama_produk">{{ validation.nama_produk[0] }}</p>
					</div>
					<div class="grid grid-cols-12 gap-4">
						<div class="col-span-12 sm:col-span-8">
							<label class="label">Kategori Produk</label>
							<select class="form-input form-input-lg" v-model="produk.id_kategori_produk">
								<option value="">Pilih Kategori</option>
								<option :value="category.id_kategori_produk" v-for="category in categories">
									{{ category.nama_kategori }}
								</option>
							</select>
							<p class="mt-2 text-xs text-red-500" v-if="validation.id_kategori_produk">{{ validation.id_kategori_produk[0] }}</p>
						</div>
						<div class="col-span-12 sm:col-span-4">
							<label class="label">Satuan</label>
							<input type="text" class="form-input form-input-lg" placeholder="Satuan Produk ... " v-model="produk.satuan_produk" />
							<p class="mt-2 text-xs text-red-500" v-if="validation.satuan_produk">{{ validation.satuan_produk[0] }}</p>
						</div>

						<div class="col-span-12">
							<label class="text-xs font-semibold text-gray-700">Foto Produk</label>
							<p class="mt-2 text-xs text-red-500" v-if="validation.image_produk">{{ validation.image[0] }}</p>

							<div class="flex flex-col justify-center px-6 pt-5 pb-6 mt-1 border-2 border-gray-300 border-dashed rounded-md">
								<div class="space-y-1 text-center">
									<svg class="w-12 h-12 mx-auto text-gray-400" v-if="produk.image_produk.length == 0" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
										<path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
									</svg>
									<div class="flex justify-center text-sm text-gray-600">
										<label for="file-upload" class="relative font-medium text-indigo-500 bg-white rounded-md cursor-pointer hover:text-indigo-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
											<span>{{ produk.image_produk.length ? produk.image_produk.length + " Gambar terpilih (Add More)" : "Pilih Gambar" }}</span>
											<input id="file-upload" name="file-upload" ref="imageInput" type="file" class="sr-only" @change="handleSelectedImage" multiple accept="image/*" />
										</label>
									</div>
									<p class="text-xs text-gray-500">PNG, JPG up to 10MB</p>
								</div>
								<ul class="px-2 py-1 mt-2 bg-green-200 border-2 border-green-500 divide-y divide-green-500 rounded-md" v-if="produk.image_produk.length > 0">
									<li v-for="(image, key) in produk.image_produk" class="p-2 text-xs">
										<div class="flex items-center justify-between">
											<p>{{ image.name }}</p>
											<p class="font-semibold">{{ formatBytes(image.size) }}</p>
											<button class="btn btn-sm btn-red" @click="removeImage(key)">
												<svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor">
													<path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
												</svg>
											</button>
										</div>
										<span class="text-xs text-red-500" v-if="validation[`image_produk.` + key]">Image must not be greater than 5000 kilobytes.</span>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="col-span-12 mt-2 space-y-4 sm:col-span-8">
					<div class="grid grid-cols-12 gap-4">
						<div class="col-span-12 md:col-span-6">
							<label class="label">Finishing</label>
							<div class="flex gap-4 mb-2" v-for="(data, index) in produk.finishing">
								<input type="text" class="form-input form-input-lg" placeholder="Finishing" v-model="produk.finishing[index].data" />
								<button class="mt-1 btn btn-sm btn-indigo" v-if="index == 0" @click="addForm">
									<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
									</svg>
								</button>
								<button class="mt-1 btn btn-sm btn-red" v-else @click="deleteForm(index)">
									<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 rotate-45" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
									</svg>
								</button>
							</div>
						</div>
						<div class="col-span-12 md:col-span-6">
							<div class="grid grid-cols-12 gap-4" v-for="(data, index) in produk.jenisBahan">
								<div class="col-span-6">
									<label class="label">Jenis Bahan</label>
									<select class="form-input form-input-lg" v-model="produk.jenisBahan[index].id">
										<option value="">Pilih Jenis Bahan</option>
										<option :hidden="produk.jenisBahan.find((item) => item.id == data.id_bahan_baku)" :value="data.id_bahan_baku" v-for="data in bahanBaku">
											<!-- <template v-if="produk.jenisBahan[index].id !== data.id_bahan_baku">
											</template> -->
											{{ data.nama_bahan_baku }}
										</option>
									</select>
									<p class="mt-2 text-xs text-red-500" v-if="validation.id_kategori_produk">{{ validation.id_kategori_produk[0] }}</p>
								</div>
								<div class="col-span-6">
									<div>
										<label class="label">Harga Awal</label>
										<div class="flex justify-between gap-2">
											<div><number placeholder="Harga.." class="form-input form-input-lg" v-model="produk.jenisBahan[index].harga" v-bind="number"></number></div>
											<button class="mt-1 btn btn-sm btn-indigo" v-if="index == 0" @click="addFormBahan">
												<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
												</svg>
											</button>
											<button class="mt-1 btn btn-sm btn-red" v-else @click="deleteFormBahan(index)">
												<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 rotate-45" fill="none" viewBox="0 0 24 24" stroke="currentColor">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
												</svg>
											</button>
											<p class="mt-2 text-xs text-red-500" v-if="validation.nama_produk">{{ validation.harga[0] }}</p>
										</div>
										<!-- <input type="text" class="form-input form-input-lg" placeholder="Harga Awal ... " v-model="jenisBahan.harga" /> -->
									</div>
								</div>
							</div>
						</div>
					</div>
					<div>
						<label class="label">Deskripsi</label>
						<div class="mt-1">
							<section>
								<client-only>
									<quill-editor ref="editor" v-model="produk.deskripsi_produk" :options="editorOption" />
								</client-only>
							</section>
							<p class="mt-2 text-xs text-red-500" v-if="validation.deskripsi_produk">{{ validation.deskripsi_produk[0] }}</p>
						</div>
					</div>
					<div>
						<label class="label">Informasi Pemesanan</label>
						<div class="mt-1">
							<section>
								<client-only>
									<quill-editor ref="editor" v-model="produk.informasi_pemesanan" :options="editorOption" />
								</client-only>
							</section>
							<p class="mt-2 text-xs text-red-500" v-if="validation.informasi_pemesanan">{{ validation.informasi_pemesanan[0] }}</p>
						</div>
					</div>
				</div>
			</template>
			<template #submit>
				<button class="btn btn-lg btn-green" @click="addProduk">Submit</button>
			</template>
		</Modal>
		<Spinner v-show="isloading"></Spinner>
		<!-- <div class="grid grid-cols-2 gap-4">
			<div class="col-span-2 md:col-span-1">
				<div class="card">
					<DataKategori :categories="categories" :units="units" @get-data="getData"></DataKategori>
				</div>
			</div>
		</div> -->
	</div>
</template>

<script>
import Modal from "../../components/ModalComponent.vue";
import Spinner from "../../components/SpinnerLoading.vue";
import Pagination from "../../components/Pagination.vue";
import {number} from "@coders-tm/vue-number-format";

export default {
	name: "IndexPage",
	components: {
		Modal,
		Spinner,
		Pagination,
		number,
	},
	layout: "auth",
	middleware: "role/pelanggan",
	data() {
		return {
			products: [],
			categories: [],
			bahanBaku: [],

			produk: {
				nama_produk: "",
				id_kategori_produk: "",
				satuan_produk: "",
				deskripsi_produk: "",
				informasi_pemesanan: "",
				image_produk: [],
				finishing: [{data: ""}],
				jenisBahan: [
					{
						id: "",
						harga: "",
					},
				],
			},

			modal: false,
			isloading: false,
			validation: [],

			search: "",
			productSize: 8,
			productPage: 1,

			editorOption: {
				// Some Quill options...
				theme: "snow",
				modules: {
					toolbar: [
						["bold", "italic", "underline", "strike"],
						["blockquote", "code-block"],
						[{"color": []}, {"background": []}], // dropdown with defaults from theme
						[{"font": []}],
						[{"align": []}],
					],
				},
			},
		};
	},
	computed: {},

	mounted() {
		this.getProducts();
		this.getCategories();
	},
	methods: {
		async getProducts() {
			const products = await this.$axios.$get("/api/produk?page=" + this.productPage + "&show=" + parseInt(this.productSize) + "&search=" + this.search);
			this.products = products;
		},
		async getCategories() {
			const categories = await this.$axios.$get("/api/kategori");
			const bahanBaku = await this.$axios.$get("/api/bahan-baku");
			this.categories = categories;
			this.bahanBaku = bahanBaku;
		},
		async addProduk() {
			let file = this.produk.image_produk;
			let finishing = this.produk.finishing;
			let bahan = this.produk.jenisBahan;
			let imageData = new FormData();

			file.forEach((img, index) => imageData.append("image_produk[" + index + "]", file[index]));

			imageData.append("nama_produk", this.produk.nama_produk);
			imageData.append("id_kategori_produk", this.produk.id_kategori_produk);
			imageData.append("deskripsi_produk", this.produk.deskripsi_produk);
			imageData.append("informasi_pemesanan", this.produk.informasi_pemesanan);
			imageData.append("satuan_produk", this.produk.satuan_produk);
			finishing.forEach((element, index) => imageData.append("finishing[" + index + "]", finishing[index].data));
			bahan.forEach((element, index) => {
				imageData.append("bahan[" + index + "]", JSON.stringify(bahan[index]));
			});

			this.isloading = true;
			setTimeout(() => {
				this.$axios
					.post("/api/produk", imageData)
					.then(() => {
						this.closeModal();
						this.getProducts();
					})
					.then(() => {
						this.$swal.fire({
							icon: "success",
							title: "Success...",
							text: "Data Berhasil ditambahkan!",
							showConfirmButton: false,
							timer: 1500,
							timerProgressBar: true,
						});
					})
					.catch((error) => {
						console.log(error.response.data);
						this.validation = error.response.data;
					})
					.finally(() => (this.isloading = false));
			}, 500);
		},

		formatBytes(bytes, decimals = 2) {
			if (!+bytes) return "0 Bytes";

			const k = 1024;
			const dm = decimals < 0 ? 0 : decimals;
			const sizes = ["Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"];

			const i = Math.floor(Math.log(bytes) / Math.log(k));

			return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`;
		},

		closeModal() {
			this.produk.nama_produk = "";
			this.produk.id_kategori_produk = "";
			this.produk.satuan_produk = "";
			this.produk.deskripsi_produk = "";
			this.produk.informasi_pemesanan = "";
			this.produk.image_produk = [];
			this.produk.finishing = [{data: ""}];
			this.produk.jenisBahan = [{id: "", harga: ""}];

			this.validation = [];

			this.modal = false;
		},

		handleSelectedImage() {
			let selectedImage = this.$refs.imageInput.files;

			for (let i = 0; i < selectedImage.length; i++) {
				let isFileExists = this.produk.image_produk.find((file) => file.type === selectedImage[i].type && file.name === selectedImage[i].name && file.size === selectedImage[i].size && file.lastModified === selectedImage[i].lastModified);

				if (!isFileExists) this.produk.image_produk.push(selectedImage[i]);
			}
		},
		removeImage(key) {
			this.produk.image_produk.splice(key, 1);
		},

		searchData() {
			this.getProducts();
		},
		onChangeRecordsPerPage() {
			this.getProducts();
		},
		onPageChange(page) {
			this.productPage = page;
			this.getProducts();
		},

		addForm() {
			this.produk.finishing.push({data: ""});
		},
		deleteForm(index) {
			this.produk.finishing.splice(index, 1);
		},
		addFormBahan() {
			this.produk.jenisBahan.push({id: "", harga: ""});
		},
		deleteFormBahan(index) {
			this.produk.jenisBahan.splice(index, 1);
		},
	},
};
</script>
