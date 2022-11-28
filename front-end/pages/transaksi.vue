<template>
	<div class="margin-auth">
		<div class="space-y-4 lg:px-24">
			<p class="text-2xl font-semibold text-gray-900">Riwayat Transaksi</p>
			<div class="flex gap-4">
				<label class="relative flex items-center justify-center px-4 py-1 text-xs font-medium text-gray-900 bg-white border rounded-full shadow-sm cursor-pointer group hover:bg-gray-50 focus:outline-none" :class="status_pesanan == '' ? 'ring-2 ring-slate-500' : ''">
					<input type="radio" v-model="status_pesanan" @change="getData()" value="" class="sr-only" aria-labelledby="size-choice-0-label" />
					<span id="size-choice-0-label">Semua</span>
					<span class="absolute rounded-full pointer-events-none -inset-px" :class="status_pesanan == 0 ? 'border border-slate-500' : 'border-2 border-transparent'" aria-hidden="true"></span>
				</label>
				<label class="relative flex items-center justify-center px-4 py-1 text-xs font-medium text-gray-900 bg-white border rounded-full shadow-sm cursor-pointer group hover:bg-gray-50 focus:outline-none" :class="status_pesanan == 1 ? 'ring-2 ring-red-500' : ''">
					<input type="radio" v-model="status_pesanan" @change="getData()" :value="1" class="sr-only" aria-labelledby="size-choice-0-label" />
					<span id="size-choice-0-label">Belum Bayar</span>
					<span class="absolute rounded-full pointer-events-none -inset-px" :class="status_pesanan == 1 ? 'border border-red-500' : 'border-2 border-transparent'" aria-hidden="true"></span>
				</label>
				<label class="relative flex items-center justify-center px-4 py-1 text-xs font-medium text-gray-900 bg-white border rounded-full shadow-sm cursor-pointer group hover:bg-gray-50 focus:outline-none" :class="status_pesanan == 2 ? 'ring-2 ring-indigo-500' : ''">
					<input type="radio" v-model="status_pesanan" @change="getData()" :value="2" class="sr-only" aria-labelledby="size-choice-0-label" />
					<span id="size-choice-0-label">Dalam Pengerjaan</span>
					<span class="absolute rounded-full pointer-events-none -inset-px" :class="status_pesanan == 2 ? 'border border-indigo-500' : 'border-2 border-transparent'" aria-hidden="true"></span>
				</label>
				<label class="relative flex items-center justify-center px-4 py-1 text-xs font-medium text-gray-900 bg-white border rounded-full shadow-sm cursor-pointer group hover:bg-gray-50 focus:outline-none" :class="status_pesanan == 3 ? 'ring-2 ring-yellow-500' : ''">
					<input type="radio" v-model="status_pesanan" @change="getData()" :value="3" class="sr-only" aria-labelledby="size-choice-0-label" />
					<span id="size-choice-0-label">Siap diambil / dikirim</span>
					<span class="absolute rounded-full pointer-events-none -inset-px" :class="status_pesanan == 3 ? 'border border-yellow-500' : 'border-2 border-transparent'" aria-hidden="true"></span>
				</label>
				<label class="relative flex items-center justify-center px-4 py-1 text-xs font-medium text-gray-900 bg-white border rounded-full shadow-sm cursor-pointer group hover:bg-gray-50 focus:outline-none" @change="getData()" :class="status_pesanan == 4 ? 'ring-2 ring-green-500' : ''">
					<input type="radio" v-model="status_pesanan" :value="4" class="sr-only" aria-labelledby="size-choice-0-label" />
					<span id="size-choice-0-label">Selesai</span>
					<span class="absolute rounded-full pointer-events-none -inset-px" :class="status_pesanan == 4 ? 'border border-green-500' : 'border-2 border-transparent'" aria-hidden="true"></span>
				</label>
			</div>
			<template v-if="transaksi.length > 0">
				<div v-for="data in transaksi" class="bg-white border-2 rounded-md border-slate-200">
					<div class="flex flex-col justify-between px-6 py-4 border-b-2 md:flex-row border-slate-200">
						<div class="flex space-x-8">
							<div class="space-y-2">
								<p class="text-sm font-semibold text-gray-600">No Transaksi</p>
								<p class="text-xs text-gray-500">{{ data.id_transaksi }}</p>
							</div>
							<div class="space-y-2">
								<p class="text-sm font-semibold text-gray-600">Tgl Transaksi</p>
								<p class="text-xs text-gray-500">{{ data.tgl_transaksi }}</p>
							</div>
							<div class="space-y-2">
								<p class="text-sm font-semibold text-gray-600">Total Transaksi</p>
								<p class="text-xs text-gray-500">{{ "Rp. " + Intl.NumberFormat().format(data.total_harga) }}</p>
							</div>
						</div>
						<div class="flex space-x-4">
							<template v-if="data.status_pesanan == 1">
								<button @click.prevent="openModal(data)" class="flex items-center justify-center gap-2 px-2 py-0 border-2 border-red-500 rounded-full hover:bg-red-100">
									<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-red-500">
										<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
									</svg>
									<span class="text-xs text-red-500">Pembayaran</span>
								</button>
							</template>
							<template v-else>
								<a href="#" class="flex items-center justify-center gap-2 px-2 py-1 border-2 rounded-md border-slate-300 hover:bg-slate-100">
									<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
										<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
									</svg>
									<span class="text-xs">Invoice</span>
								</a>
							</template>
						</div>
					</div>
					<div class="flex px-6 py-4 space-x-8 border-b-2 bg-gray-50 border-slate 200" v-for="detail in data.detail_transaksi">
						<div class="flex-shrink-0 overflow-hidden border border-gray-200 rounded-md w-36 h-36">
							<img :src="'http://localhost:8000/storage/image_produk/' + detail.sku.produk.id_produk + '/' + JSON.parse(detail.sku.produk.image_produk)[0].filename" class="object-cover object-center w-full h-full" />
						</div>
						<div class="flex flex-col flex-1 ml-4">
							<div>
								<div class="flex justify-between text-base font-medium text-gray-900">
									<div>
										<p class="font-semibold tracking-wider text-gray-700 whitespace-nowrap">{{ detail.sku.produk.nama_produk }}</p>
										<p class="text-xs text-gray-600 whitespace-nowrap">{{ detail.sku.bahan_baku.nama_bahan_baku }}</p>
										<p class="my-2 text-xs tracking-widest text-gray-600 whitespace-nowrap"><span class="font-semibold tracking-normal">Finishing: </span>{{ detail.finishing.nama_finishing }}</p>
										<p class="my-2 text-xs text-gray-600" v-html="detail.sku.produk.deskripsi_produk.slice(0, 200) + ' ...'"></p>
									</div>
									<div>
										<!-- <p class="px-2 py-1 text-xs font-semibold text-gray-700 whitespace-nowrap" v-if>
										{{ "Rp. " + Intl.NumberFormat().format(detail.sku.harga[detail.sku.harga.length - 1].harga_produk) }}
									</p> -->
									</div>
								</div>
							</div>
							<div class="flex items-end justify-between flex-1">
								<ul class="text-xs text-gray-500">
									<template v-if="detail.sku.produk.satuan_produk == 'm2'">
										<li class="text-xs text-gray-600">Panjang: {{ JSON.parse(detail.ukuran).panjang }} meter, Lebar: {{ JSON.parse(detail.ukuran).lebar }} meter</li>
										<li class="text-xs text-gray-600">QTY: {{ detail.qty_produk + " pcs" }}</li>
									</template>
									<template v-else>
										<li class="text-xs text-gray-600">QTY: {{ detail.qty_produk + "" + detail.sku.produk.satuan_produk }}</li>
									</template>
								</ul>
								<div class="flex">
									<p class="font-semibold text-gray-700 whitespace-nowrap" v-text="harga(detail)"></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</template>
			<div v-else class="bg-white border-2 rounded-md h-72 border-slate-200">
				<div class="flex items-center justify-center w-full h-full space-x-4">
					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-red-500">
						<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
					</svg>
					<span class="text-2xl font-semibold text-gray-800">Tidak Ada Data</span>
				</div>
			</div>
		</div>
		<!-- Modal Tambah Produk -->
		<Modal size="max-w-5xl" title="Pembayaran" @close-modal="closeModal" :data="modalData" v-show="modal">
			<template #content>
				<div class="col-span-12 md:col-span-2">
					<div class="w-full">
						<img src="~/assets/img/qr.png" class="w-full" alt="" srcset="" />
						<p class="text-xs text-center text-gray-600">No.Rekening: xxxxxxxxxx</p>
						<p class="text-xs text-center text-gray-600">a.n / admin (bank)</p>
					</div>
				</div>
				<div class="col-span-12 md:col-span-5">
					<ul class="space-y-2">
						<li class="flex items-center justify-between px-4 py-2 bg-white border-2 rounded-md border-slate-200">
							<p class="text-xs font-semibold text-gray-600">Total Pesanan :</p>
							<p class="text-sm text-gray-600">{{ "Rp. " + Intl.NumberFormat().format(modalData.total_harga) }}</p>
						</li>
						<li v-if="modalData.pengiriman == 0" class="flex items-center justify-between px-4 py-2 bg-white border-2 rounded-md border-slate-200">
							<p class="text-xs font-semibold text-gray-600">Ongkos Kirim :</p>
							<p class="text-sm text-gray-600">{{ "Rp. " + Intl.NumberFormat().format(10000) }}</p>
						</li>
						<li v-if="modalData.pengiriman == 0" class="flex items-center justify-between px-4 py-2 border-2 rounded-md bg-gray-50 border-slate-200">
							<p class="text-sm font-semibold text-gray-600">Total :</p>
							<p class="text-gray-600">{{ "Rp. " + Intl.NumberFormat().format(modalData.total_harga + 10000) }}</p>
						</li>
					</ul>

					<!-- {{ modalData }} -->
				</div>
				<div class="col-span-12 md:col-span-5">
					<span class="text-sm font-semibold text-gray-700">Cara Pembayaran</span>
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
	name: "Transaksi",
	layout: "user",
	auth: false,
	components: {Modal},
	data() {
		return {
			user: this.$auth.user,
			search: "",
			status_pembayaran: false,
			status_pesanan: "",

			modal: false,
			modalData: [],
		};
	},
	created() {
		this.getData();
	},
	computed: {
		transaksi() {
			return this.$store.state.transaksi.data;
		},
	},
	methods: {
		async getData() {
			this.$store.dispatch("transaksi/fetchTransaksi", {
				id: this.user.id_user,
				status_pembayaran: this.status_pembayaran,
				status_pesanan: this.status_pesanan,
			});
		},
		qty(data) {
			if (data.sku.produk.satuan_produk == "m2") {
				let ukuran = JSON.parse(data.ukuran);
				return ukuran.panjang * ukuran.lebar + " " + data.sku.produk.satuan_produk + " - " + data.qty_produk + " pcs";
			} else return data.qty_produk + data.sku.produk.satuan_produk;
		},
		harga(data) {
			if (data.sku.produk.satuan_produk == "m2") {
				let ukuran = JSON.parse(data.ukuran).panjang * JSON.parse(data.ukuran).lebar;
				let price = data.sku.harga[data.sku.harga.length - 1].harga_produk * ukuran * data.qty_produk;
				return "Rp. " + Intl.NumberFormat().format(price);
			} else {
				let price = data.sku.harga[data.sku.harga.length - 1].harga_produk * data.qty_produk;
				return "Rp. " + Intl.NumberFormat().format(price);
			}
		},

		openModal(data) {
			this.modalData = data;
			this.modal = !this.modal;
		},
		closeModal() {
			this.modalData = [];
			this.modal = !this.modal;
		},
	},
};
</script>

<style scoped>
.pills {
	@apply px-2 py-1 text-xs tracking-wider border rounded-full whitespace-nowrap;
}

.pills-red {
	@apply text-red-500 border-red-500;
}
.pills-red:hover {
	@apply text-white bg-red-500 font-semibold;
}
.pills-red:focus {
	@apply text-white bg-red-500 border-red-500 font-semibold;
}

.pills-blue {
	@apply text-blue-500 border-blue-500;
}
.pills-blue:hover {
	@apply text-white bg-blue-500 font-semibold;
}
.pills-blue:focus {
	@apply text-white bg-blue-500 border-blue-500 font-semibold;
}

.pills-green {
	@apply text-green-500 border-green-500;
}
.pills-green:hover {
	@apply text-white bg-green-500 font-semibold;
}
.pills-green:focus {
	@apply text-white bg-green-500 border-green-500 font-semibold;
}

.pills-yellow {
	@apply text-yellow-500 border-yellow-500;
}
.pills-yellow:hover {
	@apply text-white bg-yellow-500 font-semibold;
}
.pills-yellow:focus {
	@apply text-white bg-yellow-500 border-yellow-500 font-semibold;
}
</style>
