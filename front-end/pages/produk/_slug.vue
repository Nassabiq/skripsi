<template>
	<div class="gap-4 space-y-4 margin-auth">
		<div class="card">
			<div class="grid grid-cols-12 gap-4">
				<div class="col-span-5">
					<img :src="'http://localhost:8000/storage/image_produk/' + product.slug + '/' + image[0]" alt="" class="object-cover w-full border-2 border-slate-200 h-80" />

					<div class="flex items-center justify-end mt-2">
						<button class="btn btn-sm btn-with-icon btn-indigo">
							<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
							</svg>
							<span>Update Gambar</span>
						</button>
					</div>
				</div>
				<div class="col-span-7">
					<p class="text-4xl font-semibold text-gray-700 capitalize font-title">{{ product.nama_produk }}</p>
					<div class="flex items-center justify-between py-4 mt-2 border-b-2 border-slate-200">
						<p class="text-xl font-bold text-gray-500 capitalize">Rp. 40,000</p>
						<p class="text-xs font-semibold text-blue-500 capitalize">Lihat Histori Harga</p>
					</div>
					<div class="py-4 border-b-2 border-slate-200">
						<p class="pb-2 text-xl font-bold text-gray-600 capitalize">Deskripsi</p>
						<p class="text-sm text-justify text-slate-500">{{ product.description }}</p>
					</div>
					<div class="py-4 border-b-2 border-slate-200">
						<p class="pb-2 text-xl font-bold text-gray-600 capitalize">Data Transaksi Terbaru</p>
						<p class="text-sm text-justify text-slate-500">{{ product.description }}</p>
					</div>
					<div class="flex items-center justify-end gap-4 mt-4">
						<button class="btn btn-sm btn-with-icon btn-green" @click="openModal">
							<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
								<path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
								<path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
							</svg>
							<span>Update Data Produk</span>
						</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal Tambah Produk -->
		<Modal size="max-w-xl" title="Edit Data Produk" @close-modal="modal = !modal" v-show="modal">
			<template #content>
				<div class="col-span-12">
					<label class="label">Nama Produk</label>
					<input type="text" class="form-input form-input-lg" placeholder="Nama Produk ... " v-model="product.nama_produk" />
					<p class="mt-2 text-xs text-red-500" v-if="validation.nama_produk">{{ validation.nama_produk[0] }}</p>
				</div>
				<div class="col-span-12">
					<label class="label">Deskripsi</label>
					<div class="mt-1">
						<textarea id="about" name="about" rows="8" class="form-input form-input-sm" placeholder="you@example.com" v-model="product.description"></textarea>
						<p class="mt-2 text-xs text-red-500" v-if="validation.description">{{ validation.description[0] }}</p>
					</div>
				</div>
			</template>
			<template #submit>
				<button class="btn btn-lg btn-indigo" @click="updateProduk">
					Update
				</button>
			</template>
		</Modal>
		<Spinner v-show="isloading"></Spinner>
	</div>
</template>

<script>
import Modal from "../../components/ModalComponent.vue";
import Spinner from "../../components/SpinnerLoading.vue";

export default {
	auth: false,
	layout: "auth",
	components: {Modal, Spinner},
	data() {
		return {
			product: [],
			image: {},

			modal: false,
			isloading: false,
			validation: [],
		};
	},

	mounted() {
		this.getData();
	},
	methods: {
		async getData() {
			await this.$axios
				.get("/api/product/" + this.$route.params.slug)
				.then((response) => {
					this.product = response.data;
					this.image = JSON.parse(response.data.image);
				})
				.catch((error) => {
					console.log(error);
				});
		},

		openModal() {
			this.modal = !this.modal;
		},

		updateProduk() {
			this.isloading = true;
			setTimeout(() => {
				this.$axios
					.post("/api/updateProduk/" + this.product.id_produk, {
						nama_produk: this.product.nama_produk,
						description: this.product.description,
					})
					.then(() => {
						this.modal = false;
						this.getData();
					})
					.catch((error) => {
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
