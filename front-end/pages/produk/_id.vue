<template>
	<div class="gap-4 space-y-4 margin-auth">
		<div class="card">
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

					<div class="flex items-center justify-end mt-4">
						<button class="btn btn-sm btn-with-icon btn-indigo" @click.prevent="openModalImage">
							<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
							</svg>
							<span>Update Gambar</span>
						</button>
					</div>
				</div>
				<div class="col-span-12 md:col-span-8">
					<div class="grid grid-cols-12 py-2 border-b-2 border-slate-200">
						<div class="col-span-10 item">
							<p v-if="product.nama_produk" class="text-4xl font-semibold text-gray-700 capitalize font-title">{{ product.nama_produk }}</p>
						</div>
						<div class="flex items-center justify-end col-span-2 gap-2">
							<button class="btn btn-sm btn-green" @click.prevent="openModal">
								<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
									<path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
									<path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
								</svg>
							</button>
							<button class="btn btn-sm btn-red" @click="deleteProduk">
								<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
									<path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
								</svg>
							</button>
						</div>
					</div>
					<div class="py-4 border-b-2 border-slate-200">
						<div class="grid grid-cols-2 gap-4 space-x-4">
							<div class="col-span-2 md:col-span-1">
								<p class="pb-2 text-lg font-bold text-gray-600 capitalize">Jenis Bahan</p>
								<div v-if="product.stok !== undefined && product.stok.length > 0" class="text-sm text-justify text-slate-500">
									<ul>
										<li v-for="data in product.stok" class="space-y-4">
											<div class="flex justify-between">
												<p class="text-sm text-gray-700">{{ data.bahan_baku.nama_bahan_baku }}</p>
												<p class="text-base font-semibold text-gray-700">Rp. {{ Intl.NumberFormat().format(product.stok[0].harga[0].harga_produk) }}</p>
											</div>
										</li>
									</ul>
								</div>
								<p v-else class="text-sm text-justify text-slate-500">Tidak Ada Data</p>
								<!-- <Skeleton :count="5" v-else /> -->
							</div>
							<div class="col-span-2 md:col-span-1">
								<p class="pb-2 text-lg font-bold text-gray-600 capitalize">Jenis Finishing</p>
								<ul>
									<li class="text-sm text-gray-700" v-for="data in product.finishing">{{ data.nama_finishing }}</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="py-4 border-b-2 border-slate-200">
						<p class="pb-2 text-xl font-bold text-gray-600 capitalize">Deskripsi</p>
						<p v-if="product.deskripsi_produk" class="text-xs text-justify text-slate-500" v-html="product.deskripsi_produk"></p>
						<Skeleton :count="5" v-else />
					</div>
					<div class="py-4 border-b-2 border-slate-200">
						<p class="pb-2 text-xl font-bold text-gray-600 capitalize">Informasi Pemesanan</p>
						<p v-if="product.informasi_pemesanan" class="text-xs text-justify text-slate-500" v-html="product.informasi_pemesanan"></p>
						<Skeleton :count="5" v-else />
					</div>
				</div>
			</div>
		</div>

		<!-- Modal Update Produk -->
		<Modal size="max-w-3xl" title="Edit Data Produk" @close-modal="closeModal" v-show="modal">
			<template #content>
				<div class="col-span-9">
					<label class="label">Nama Produk</label>
					<input type="text" class="form-input form-input-lg" placeholder="Nama Produk ... " v-model="product.nama_produk" />
					<p class="mt-2 text-xs text-red-500" v-if="validation.nama_produk">{{ validation.nama_produk[0] }}</p>
				</div>
				<div class="col-span-3">
					<label class="label">Satuan Produk</label>
					<input type="text" class="form-input form-input-lg" placeholder="Satuan Produk ... " v-model="product.satuan_produk" />
					<p class="mt-2 text-xs text-red-500" v-if="validation.satuan_produk">{{ validation.satuan_produk[0] }}</p>
				</div>

				<div class="col-span-12 space-y-2 md:col-span-6">
					<div class="gap-4" v-for="(data, index) in product.finishing">
						<label class="label">Finishing</label>
						<div class="flex-col">
							<input type="text" class="form-input form-input-lg" placeholder="Finishing" v-model="product.finishing[index].nama_finishing" />
							<!-- <span class="text-xs text-red-500" v-if="validation['finishing.' + index]">Form ini harus diisi.</span> -->
						</div>
					</div>
					<div class="flex justify-end">
						<button class="mt-1 btn btn-sm btn-indigo btn-with-icon" @click="addForm">
							<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
							</svg>
							Tambah Data Finishing
						</button>
					</div>
					<template v-if="addFinishing && addFinishing.length > 0">
						<label class="label">Data Finishing Baru</label>
						<div class="flex gap-4 mb-2" v-for="(data, index) in addFinishing">
							<div class="flex-col">
								<input type="text" class="form-input form-input-lg" placeholder="Finishing" v-model="addFinishing[index]" />
								<!-- <span class="text-xs text-red-500" v-if="validation['finishing.' + index]">Form ini harus diisi.</span> -->
							</div>
							<button class="mt-1 btn btn-sm btn-red" @click="deleteFinishingForm(index)">
								<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 rotate-45" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
								</svg>
							</button>
						</div>
					</template>
				</div>
				<div class="col-span-12 space-y-2 md:col-span-6">
					<div class="grid grid-cols-12 gap-4" v-for="(produk, index) in product.stok">
						<template v-if="produk.harga.length < 2">
							<div class="col-span-6">
								<label class="label">Jenis Bahan</label>
								<input type="text" class="bg-gray-100 form-input form-input-lg" v-model="produk.bahan_baku.nama_bahan_baku" disabled />
								<!-- <select class="form-input form-input-lg" v-model="produk.bahan_baku.id_bahan_baku" disabled>
								<option value="">Pilih Jenis Bahan</option>
								<option :value="bahan.id_bahan_baku" v-for="bahan in bahanBaku">
									{{ bahan.nama_bahan_baku }}
								</option>
							</select> -->
								<!-- <span class="text-xs text-red-500" v-if="validation['id_bahan.' + index]">Form ini harus diisi.</span> -->
							</div>
							<div class="col-span-6">
								<div>
									<label class="label">Harga Awal</label>
									<div class="flex justify-between gap-2">
										<div class="flex-col">
											<div><number placeholder="Harga.." class="form-input form-input-lg" v-model="produk.harga[0].harga_produk" v-bind="number"></number></div>
											<!-- <span class="text-xs text-red-500" v-if="validation['harga.' + index]">Form ini harus diisi.</span> -->
										</div>
									</div>
								</div>
							</div>
						</template>
					</div>
					<div class="flex justify-end">
						<button class="mt-1 btn btn-sm btn-indigo btn-with-icon" @click="addJenisBahanForm">
							<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
							</svg>
							Tambah Data Jenis Bahan
						</button>
					</div>
					<template v-if="addJenisBahan && addJenisBahan.length > 0">
						<label class="label">Data Jenis Bahan Baru</label>
						<div class="grid grid-cols-12 gap-4" v-for="(data, index) in addJenisBahan">
							<div class="col-span-6">
								<label class="label">Jenis Bahan</label>
								<select class="form-input form-input-lg" v-model="addJenisBahan[index].id">
									<option value="">Pilih Jenis Bahan</option>
									<option :hidden="addJenisBahan.find((item) => item.id == product.stok[index].bahan_baku.id_bahan_baku)" :value="bahan.id_bahan_baku" v-for="bahan in bahanBaku">
										{{ bahan.nama_bahan_baku }}
									</option>
								</select>
								<!-- <span class="text-xs text-red-500" v-if="validation['id_bahan.' + index]">Form ini harus diisi.</span> -->
							</div>
							<div class="col-span-6">
								<div>
									<label class="label">Harga Awal</label>
									<div class="flex justify-between gap-2">
										<div class="flex-col">
											<div><number placeholder="Harga.." class="form-input form-input-lg" v-model="addJenisBahan[index].harga" v-bind="number"></number></div>
											<!-- <span class="text-xs text-red-500" v-if="validation['harga.' + index]">Form ini harus diisi.</span> -->
										</div>

										<button class="mt-1 btn btn-sm btn-red" @click="deleteJenisBahanForm(index)">
											<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 rotate-45" fill="none" viewBox="0 0 24 24" stroke="currentColor">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
											</svg>
										</button>

										<!-- <p class="mt-2 text-xs text-red-500" v-if="validation.nama_produk">{{ validation.harga[0] }}</p> -->
									</div>
									<!-- <input type="text" class="form-input form-input-lg" placeholder="Harga Awal ... " v-model="jenisBahan.harga" /> -->
								</div>
							</div>
						</div>
					</template>
				</div>
				<div class="col-span-12">
					<label class="label">Deskripsi</label>
					<div class="mt-1">
						<section>
							<client-only>
								<quill-editor ref="editor" v-model="product.deskripsi_produk" :options="editorOption" />
							</client-only>
						</section>
						<p class="mt-2 text-xs text-red-500" v-if="validation.deskripsi_produk">{{ validation.deskripsi_produk[0] }}</p>
					</div>
				</div>
				<div class="col-span-12">
					<label class="label">Informasi Pemesanan</label>
					<div class="mt-1">
						<section>
							<client-only>
								<quill-editor ref="editor" v-model="product.informasi_pemesanan" :options="editorOption" />
							</client-only>
						</section>
						<p class="mt-2 text-xs text-red-500" v-if="validation.informasi_pemesanan">{{ validation.informasi_pemesanan[0] }}</p>
					</div>
				</div>
			</template>
			<template #submit>
				<button class="btn btn-lg btn-indigo" @click.prevent="updateProduk">Update</button>
			</template>
		</Modal>

		<!-- Modal Update Image -->
		<Modal size="max-w-2xl" title="Edit Image Produk" @close-modal="closeModalImage" v-show="modalImage">
			<template #content>
				<div class="col-span-12">
					<ul class="px-2 py-1 mt-2 bg-green-200 border-2 border-green-500 divide-y divide-green-500 rounded-md">
						<li v-for="(img, key) in uploadedImage.allImages" class="p-2 text-xs">
							<div class="flex items-center justify-between">
								<button @click.prevent="selectImage" class="font-semibold tracking-wider text-blue-500 hover:text-blue-700">{{ img.filename || img.name }}</button>
								<button class="btn btn-sm btn-red" @click.prevent="removeImage(key)">
									<svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor">
										<path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
									</svg>
								</button>
							</div>
						</li>
					</ul>
				</div>

				<div class="flex flex-col justify-center col-span-12 px-6 pt-5 pb-6 mt-1 border-2 border-gray-300 border-dashed rounded-md">
					<div class="space-y-1 text-center">
						<svg class="w-12 h-12 mx-auto text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
							<path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
						<div class="flex justify-center text-sm text-gray-600">
							<label for="file-upload" class="relative font-medium text-indigo-500 bg-white rounded-md cursor-pointer hover:text-indigo-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
								<span>Tambah Gambar</span>
								<input id="file-upload" name="file-upload" ref="imageInput" type="file" class="sr-only" @change="handleSelectedImage" multiple accept="image/*" />
							</label>
						</div>
						<p class="text-xs text-gray-500">PNG, JPG up to 10MB</p>
					</div>
				</div>
			</template>
			<template #submit>
				<button class="btn btn-lg btn-indigo" @click.prevent="updateImage">Update</button>
			</template>
		</Modal>

		<Spinner v-show="isloading"></Spinner>
	</div>
</template>

<script>
import Spinner from "../../components/SpinnerLoading.vue";
import {Skeleton} from "vue-loading-skeleton";
import {number} from "@coders-tm/vue-number-format";

export default {
	layout: "auth",
	components: {Spinner, Skeleton, number},
	data() {
		return {
			firstLoad: false,
			editorOption: {
				// Some Quill options...
				theme: "snow",
				modules: {
					toolbar: [
						["bold", "italic", "underline", "strike"],
						["blockquote", "code-block"],
					],
				},
			},
			// number: {
			// 	decimal: ".",
			// 	separator: ",",
			// 	prefix: "Rp.  ",
			// 	precision: 2,
			// 	masked: false,
			// },
			product: [],
			uploadedImage: {
				addImage: [],
				deletedImage: [],
				allImages: [],
			},

			// image: [],
			categories: [],
			bahanBaku: [],

			modal: false,
			modalImage: false,
			isloading: false,
			validation: [],

			previewImage: null,
			activeImage: 0,
			imageUrl: "http://localhost:8000/storage/image_produk/",

			addFinishing: [],
			addJenisBahan: [],
		};
	},
	computed: {
		currentImage() {
			return this.product.image_produk[this.activeImage].filename;
		},
	},

	mounted() {
		this.getData();
	},

	methods: {
		async getData() {
			const product = await this.$axios.$get("/api/produk/" + this.$route.params.id);
			const categories = await this.$axios.$get("/api/kategori");
			const bahanBaku = await this.$axios.$get("/api/bahan-baku");

			this.product = product;
			this.product.image_produk = JSON.parse(this.product.image_produk);
			this.uploadedImage.allImages = [...this.product.image_produk];

			this.categories = categories;
			this.bahanBaku = bahanBaku;
		},
		async updateProduk() {
			const finishing = this.product.finishing;
			const finishingResult = [];

			finishing.forEach((element) => {
				const data = {
					"id_finishing": element.id_finishing,
					"nama_finishing": element.nama_finishing,
				};
				finishingResult.push(data);
			});

			const jenis_bahan = this.product.stok;
			const jenis_bahan_result = [];

			jenis_bahan.forEach((element) => {
				const data = {
					"id_bahan_baku": element.bahan_baku.id_bahan_baku,
					"nama_bahan_baku": element.bahan_baku.nama_bahan_baku,
					"id_harga_jual": element.harga[0].id_harga_jual,
					"harga_produk": element.harga[0].harga_produk,
				};

				jenis_bahan_result.push(data);
			});

			await this.$axios
				.patch("/api/produk/" + this.product.id_produk, {
					nama_produk: this.product.nama_produk,
					satuan_produk: this.product.satuan_produk,
					// id_kategori_produk: this.product.id_kategori_produk,
					deskripsi_produk: this.product.deskripsi_produk,
					informasi_pemesanan: this.product.informasi_pemesanan,
					finishing: JSON.stringify(finishingResult),
					jenis_bahan: JSON.stringify(jenis_bahan_result),
					new_finishing: this.addFinishing.length > 0 ? JSON.stringify(this.addFinishing) : null,
					new_jenis_bahan: this.addJenisBahan.length > 0 ? JSON.stringify(this.addJenisBahan) : null,
				})
				.then(() => {
					this.modal = !this.modal;
					this.getData();
				})
				.then(() => {
					this.$swal.fire({
						icon: "success",
						title: "Success...",
						text: "Data Berhasil diubah!",
						showConfirmButton: false,
						timer: 3000,
						timerProgressBar: true,
					});
				})
				.catch((error) => (this.validation = error.response.data));
		},
		activateImage(imageIndex) {
			this.activeImage = imageIndex;
		},

		handleSelectedImage() {
			let selectedImage = this.$refs.imageInput.files;

			for (let i = 0; i < selectedImage.length; i++) {
				let isFileExists = this.product.image_produk.find((file) => file.type === selectedImage[i].type && file.name === selectedImage[i].name && file.size === selectedImage[i].size && file.lastModified === selectedImage[i].lastModified);

				if (!isFileExists) {
					this.uploadedImage.addImage.push(selectedImage[i]);
					this.uploadedImage.allImages.push(selectedImage[i]);
				}
			}
		},

		openModal() {
			this.modal = !this.modal;
		},
		closeModal() {
			this.modal = false;
			this.addFinishing = [];
			this.addJenisBahan = [];
		},
		openModalImage() {
			this.modalImage = !this.modalImage;
		},

		addForm() {
			this.addFinishing.push("");
		},
		addJenisBahanForm() {
			this.addJenisBahan.push({id: "", harga: ""});
		},
		deleteFinishingForm(index) {
			this.addFinishing.splice(index, 1);
		},
		deleteJenisBahanForm(index) {
			this.addJenisBahan.splice(index, 1);
		},

		closeModalImage() {
			this.modalImage = false;
			this.uploadedImage.addImage = [];
			this.uploadedImage.deletedImage = [];
			this.getData();
		},

		removeImage(key) {
			if (this.product.image_produk[key]) this.uploadedImage.deletedImage.push(this.product.image[key]);
			else {
				let index = this.uploadedImage.addImage.findIndex((image) => image == this.uploadedImage.allImages[key]);
				this.uploadedImage.addImage.splice(index, 1);
			}

			this.uploadedImage.allImages.splice(key, 1);
		},

		async updateImage() {
			let image = this.uploadedImage.addImage;
			let deletedimage = this.uploadedImage.deletedImage;
			let imageData = new FormData();

			image.forEach((img, index) => imageData.append("image_produk[" + index + "]", image[index]));
			deletedimage.forEach((img, index) => imageData.append("deletedImage[" + index + "]", JSON.stringify(deletedimage[index])));

			this.isloading = !this.isloading;
			this.$axios
				.post("/api/produk/image/" + this.product.id_produk, imageData)
				.then(() => {
					this.modalImage = !this.modalImage;
					this.getData();
				})
				.then(() => {
					this.$swal.fire({
						icon: "success",
						title: "Success...",
						text: "Gambar Berhasil diubah!",
						showConfirmButton: false,
						timer: 3000,
						timerProgressBar: true,
					});
				})
				.catch((error) => (this.validation = error.response.data))
				.finally(() => (this.isloading = !this.isloading));
		},

		async deleteProduk() {
			this.$swal
				.fire({
					title: "Are you sure?",
					text: "Data yang dihapus tidak dapat dikembalikan!",
					icon: "warning",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Yes",
					cancelButtonText: "No",
				})
				.then((result) => {
					if (result.isConfirmed) {
						this.isloading = !this.isloading;
						setTimeout(() => {
							this.$axios
								.delete("api/produk/" + this.product.id_produk)
								.then(() => this.$router.push({name: "produk"}))
								.then(() => this.$swal.fire("Deleted!", "Your file has been deleted.", "success"))
								.catch((error) => console.log(error))
								.finally(() => (this.isloading = !this.isloading));
						}, 500);
					}
				});
		},
	},
};
</script>
