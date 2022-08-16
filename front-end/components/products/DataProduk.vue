<template>
	<div>
		<div class="grid grid-cols-12 gap-4 mt-6 mb-2">
			<div class="flex flex-col col-span-12 col lg:col-span-8 sm:col-span-6">
				<span class="mb-2 text-xs font-semibold">
					Search :
				</span>
				<input class="block w-2/5 mt-1 text-xs border border-gray-300 rounded shadow form-input-lg focus:border-green-200 focus:outline-none focus:ring-2 focus:ring-green-300" placeholder="Cari Produk..." v-model="search" @keyup="searchData" />
			</div>
			<div class="grid grid-cols-12 col-span-12 space-x-4 align-middle sm:col-span-6 lg:col-span-4">
				<div class="flex flex-col col-span-6">
					<span class="mb-2 text-xs font-semibold md:text-right">
						Items Per Page :
					</span>
					<select @change="onChangeRecordsPerPage" v-model="recordsPerPage" class="px-2 py-2 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg shadow focus:outline-2 focus:outline-blue-100 focus:ring-2 focus:ring-blue-300">
						<option :value="5">5</option>
						<option :value="10">10</option>
						<option :value="25">25</option>
						<option :value="50">50</option>
					</select>
				</div>
				<div class="flex flex-col items-center col-span-6">
					<button class="btn-with-icon btn btn-sm btn-blue" @click="modal = !modal">
						<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
						</svg>
						Tambah Produk
					</button>
				</div>
			</div>
		</div>

		<div class="relative overflow-x-auto overflow-y-auto bg-white rounded-lg shadow">
			<table class="table table-auto table-produk border-table">
				<thead class="bg-gray-100">
					<tr class="text-left text-gray-800 font-title">
						<th class="p-3"></th>
						<th class="p-3">Nama Produk</th>
						<th class="p-3">Kategori</th>
						<th class="p-3">Harga</th>
						<th class="p-1"></th>
					</tr>
				</thead>
				<tbody class="divide-y-2 divide-gray-100 divide-dotted" v-if="products.data && products.data.length > 0">
					<template v-for="product in products.data">
						<tr class="text-sm">
							<td class="w-32 p-3">
								<img :src="'http://localhost:8000/storage/image_produk/' + product.slug + '/' + JSON.parse(product.image)[0].filename" alt="" class="object-cover border-2 rounded-md w-28 h-28 border-slate-200" />
							</td>
							<td class="p-3">{{ product.nama_produk }}</td>
							<td class="p-3">{{ product.categories.nama_kategori }}</td>
							<td class="p-3">1961</td>
							<td class="p-1">
								<NuxtLink :to="{name: 'produk-slug', params: {slug: product.slug}}">
									<button class="p-1 text-blue-400 border border-blue-600 rounded-full focus:bg-blue-100 hover:text-blue-800">
										<svg xmlns="http://www.w3.org/2000/svg" :class="content ? 'rotate-90 duration-300' : 'rotate-0 duration-300'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
										</svg>
									</button>
								</NuxtLink>
							</td>
						</tr>
					</template>
				</tbody>
				<tbody v-else>
					<tr>
						<td colspan="5">
							<div class="flex items-center justify-center gap-4 py-20 font-semibold text-center">
								<svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-red-500" viewBox="0 0 20 20" fill="currentColor">
									<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
								</svg>
								<span class="text-2xl">
									Tidak Ada Data
								</span>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
			<div>
				<Pagination v-if="products.data && products.per_page < products.total" :total-pages="products.links.length - 2" :per-page="recordsPerPage" :current-page="products.current_page" @pagechanged="onPageChange"></Pagination>
			</div>
		</div>

		<!-- Modal Tambah Produk -->
		<Modal size="max-w-3xl" title="Tambah Produk" @close-modal="closeModal" v-show="modal">
			<template #content>
				<div class="col-span-12 sm:col-span-9">
					<label class="label">Nama Produk</label>
					<input type="text" class="form-input form-input-lg" placeholder="Nama Produk ... " v-model="produk.nama_produk" />
					<p class="mt-2 text-xs text-red-500" v-if="validation.nama_produk">{{ validation.nama_produk[0] }}</p>
				</div>
				<div class="col-span-12 sm:col-span-3">
					<label class="label">Kategori Produk</label>
					<select class="form-input form-input-lg" v-model="produk.id_kategori_produk">
						<option value="">Pilih Kategori</option>
						<option :value="category.id_kategori_produk" v-for="category in categories">
							{{ category.nama_kategori }}
						</option>
					</select>
					<p class="mt-2 text-xs text-red-500" v-if="validation.id_kategori_produk">{{ validation.id_kategori_produk[0] }}</p>
				</div>
				<div class="col-span-12 sm:col-span-6">
					<label class="label">Deskripsi</label>
					<div class="mt-1">
						<textarea id="about" name="about" rows="6" class="form-input form-input-sm" placeholder="you@example.com" v-model="produk.description"></textarea>
						<p class="mt-2 text-xs text-red-500" v-if="validation.description">{{ validation.description[0] }}</p>
					</div>
				</div>
				<div class="col-span-12 sm:col-span-6">
					<label class="text-xs font-semibold text-gray-700">Foto Produk</label>
					<p class="mt-2 text-xs text-red-500" v-if="validation.image">{{ validation.image[0] }}</p>
					<div class="flex flex-col justify-center px-6 pt-5 pb-6 mt-1 border-2 border-gray-300 border-dashed rounded-md">
						<div class="space-y-1 text-center">
							<svg class="w-12 h-12 mx-auto text-gray-400" v-if="produk.image.length == 0" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
								<path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
							</svg>
							<div class="flex justify-center text-sm text-gray-600">
								<label for="file-upload" class="relative font-medium text-indigo-500 bg-white rounded-md cursor-pointer hover:text-indigo-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
									<span>{{ produk.image.length ? produk.image.length + " Gambar terpilih (Add More)" : "Pilih Gambar" }}</span>
									<input id="file-upload" name="file-upload" ref="imageInput" type="file" class="sr-only" @change="handleSelectedImage" multiple accept="image/*" />
								</label>
								<!-- <p class="pl-1">or drag and drop</p> -->
							</div>
							<p class="text-xs text-gray-500">PNG, JPG up to 10MB</p>
						</div>
						<ul class="px-2 py-1 mt-2 bg-green-200 border-2 border-green-500 divide-y divide-green-500 rounded-md" v-if="produk.image.length > 0">
							<li v-for="(image, key) in produk.image" class="p-2 text-xs">
								<div class="flex items-center justify-between">
									<p>{{ image.name }}</p>
									<button class="btn btn-sm btn-red" @click="removeImage(key)">
										<svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor">
											<path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
										</svg>
									</button>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</template>
			<template #submit>
				<button class="btn btn-lg btn-green" @click="addProduk">
					Submit
				</button>
			</template>
		</Modal>
		<Spinner v-show="isloading"></Spinner>
	</div>
</template>

<script>
import Modal from "../ModalComponent.vue";
import Spinner from "../SpinnerLoading.vue";
import Pagination from "../../components/Pagination.vue";

export default {
	name: "DataProduk",
	components: {Modal, Spinner, Pagination},
	props: ["products", "categories"],
	data() {
		return {
			produk: {
				nama_produk: "",
				id_kategori_produk: "",
				description: "",
				image: [],
			},
			modal: false,
			isloading: false,
			validation: [],
			recordsPerPage: 5,

			search: "",
		};
	},

	computed: {},

	methods: {
		onChangeRecordsPerPage() {
			this.$emit("load-data", this.recordsPerPage);
		},
		onPageChange(page) {
			this.products.current_page = page;
			this.$emit("load-page", this.products.current_page);
		},
		searchData() {
			this.$emit("search-data", this.search);
		},

		closeModal() {
			this.produk.nama_produk = "";
			this.produk.id_kategori_produk = "";
			this.produk.description = "";
			this.produk.image = [];

			this.validation = [];

			this.modal = false;
		},
		handleSelectedImage() {
			let selectedImage = this.$refs.imageInput.files;

			for (let i = 0; i < selectedImage.length; i++) {
				let isFileExists = this.produk.image.find((file) => file.type === selectedImage[i].type && file.name === selectedImage[i].name && file.size === selectedImage[i].size && file.lastModified === selectedImage[i].lastModified);

				if (!isFileExists) {
					this.produk.image.push(selectedImage[i]);
				}
			}
		},
		removeImage(key) {
			this.produk.image.splice(key, 1);
		},
		async addProduk() {
			let file = this.produk.image;
			let imageData = new FormData();

			file.forEach((img, index) => {
				imageData.append("image[" + index + "]", file[index]);
			});
			imageData.append("nama_produk", this.produk.nama_produk);
			imageData.append("id_kategori_produk", this.produk.id_kategori_produk);
			imageData.append("description", this.produk.description);

			this.isloading = true;
			setTimeout(() => {
				this.$axios
					.post("/api/addProduk", imageData)
					.then((response) => {
						this.closeModal();
						this.$emit("get-data", response.data);
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
					.finally(() => {
						this.isloading = false;
					});
			}, 500);
		},
	},
};
</script>
