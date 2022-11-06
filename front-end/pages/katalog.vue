<template>
	<div class="margin-auth">
		<div class="grid grid-cols-12 gap-4">
			<div class="col-span-12 space-y-4 md:col-span-4 card">
				<p class="text-2xl font-semibold text-gray-800">Katalog</p>
				<ul class="space-y-1">
					<li @click.prevent="clear" :class="{'bg-green-600 text-white': id_kategori == ''}" class="px-4 py-2 text-sm font-medium text-gray-800 rounded-md cursor-pointer hover:bg-green-600 hover:text-white">All Data</li>
					<li @click.prevent="getKatalog(data.id_kategori_produk)" :class="{'bg-green-600 text-white': id_kategori == data.id_kategori_produk}" class="px-4 py-2 text-sm font-medium text-gray-800 rounded-md cursor-pointer hover:bg-green-600 hover:text-white" v-for="(data, index) in categories">{{ data.nama_kategori }}</li>
				</ul>
			</div>
			<div class="col-span-12 md:col-span-8">
				<div v-if="products.length > 0">
					<div class="max-w-2xl px-2 mx-auto sm:py-2 sm:px-4 lg:max-w-7xl">
						<div class="grid grid-cols-1 gap-y-6 gap-x-6 sm:grid-cols-2 lg:grid-cols-3 xl:gap-x-8">
							<a href="#" class="px-3 py-2 bg-gray-100 rounded-lg shadow-lg group shadow-gray-200" v-for="product in products">
								<div class="w-full overflow-hidden bg-gray-200 rounded-lg aspect-w-1 aspect-h-1">
									<img :src="'http://localhost:8000/storage/image_produk/' + product.id_produk + '/' + JSON.parse(product.image)[0].filename" alt="" class="object-cover w-full h-full border-2 rounded-md border-slate-200" />
									<!-- <img :src="product.image" class="object-cover object-center w-full h-full group-hover:opacity-75" /> -->
								</div>
								<p class="mt-1 font-semibold text-gray-700">{{ product.nama_produk }}</p>
								<p class="text-sm font-medium text-gray-900">
									Rp. 48<span class="mt-4 text-xs text-gray-600">/ {{ product.satuan_produk }}</span>
								</p>
								<class class="flex items-center justify-end gap-2 hover:text-green-600">
									<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
										<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
									</svg>

									<p class="text-xs font-semibold" @click.prevent="modal = !modal">Add to Cart</p>
								</class>
							</a>
							<!-- More products... -->
						</div>
					</div>
				</div>
				<div v-else>Tidak Ada Data</div>
			</div>
		</div>
		<!-- Modal Tambah Produk -->
		<Modal size="max-w-xl" title="Add to Cart" @close-modal="closeModal" v-show="modal">
			<template #content>
				<div class="col-span-12 md:col-span-6">
					<label class="label">QTY</label>
					<input type="text" class="form-input form-input-lg" placeholder="QTY ... " />
					<!-- <p class="mt-2 text-xs text-red-500" v-if="validation.nama_produk">{{ validation.nama_produk[0] }}</p> -->
				</div>
				<div class="col-span-12 md:col-span-6">
					<label class="label">Jenis Bahan</label>
					<select class="form-input form-input-lg">
						<option value="">Pilih Bahan</option>
						<!-- <option :value="category.id_kategori_produk" v-for="category in categories">
							{{ category.nama_kategori }}
						</option> -->
					</select>
					<!-- <p class="mt-2 text-xs text-red-500" v-if="validation.id_kategori_produk">{{ validation.id_kategori_produk[0] }}</p> -->
				</div>
				<div class="col-span-12 md:col-span-6">
					<label class="text-xs font-semibold text-gray-700">Ukuran</label>
				</div>
				<div class="col-span-12 md:col-span-6">
					<label class="text-xs font-semibold text-gray-700">Harga</label>
				</div>
				<div class="col-span-12">
					<label class="label">Finishing</label>
					<div class="mt-1">
						<section>
							<client-only>
								<quill-editor ref="editor" :options="editorOption" />
							</client-only>
						</section>
						<!-- <p class="mt-2 text-xs text-red-500" v-if="validation.description">{{ validation.description[0] }}</p> -->
					</div>
				</div>
				<div class="col-span-12">
					<label class="label">Catatan</label>
					<div class="mt-1">
						<section>
							<client-only>
								<quill-editor ref="editor" :options="editorOption" />
							</client-only>
						</section>
						<!-- <p class="mt-2 text-xs text-red-500" v-if="validation.informasi_pemesanan">{{ validation.informasi_pemesanan[0] }}</p> -->
					</div>
				</div>
			</template>
			<template #submit>
				<!-- <button class="btn btn-lg btn-green" @click="addProduk">Submit</button> -->
			</template>
		</Modal>
	</div>
</template>

<script>
import Modal from "../components/ModalComponent.vue";
export default {
	name: "Katalog",
	layout: "user",
	auth: false,
	components: {Modal},
	data() {
		return {
			// user : this.$auth.user
			products: [],
			categories: [],
			id_kategori: "",

			modal: false,

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
	mounted() {
		this.getProducts();
		this.getCategories();
	},
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
		closeModal() {
			this.modal = !this.modal;
		},
	},
};
</script>
