<template>
	<div class="margin-auth">
		<div class="card">
			<div class="grid grid-cols-12 gap-4">
				<div class="relative col-span-12 my-4 md:col-span-6">
					<input type="search" class="w-full py-2 pl-8 pr-4 text-sm font-medium text-gray-800 border border-gray-200 rounded-lg shadow md:w-3/5 focus:outline-2 focus:outline-blue-100 focus:ring-2 focus:ring-blue-300" placeholder="Search..." v-model="search" @keyup="searchData" />
					<div class="absolute top-0 left-0 inline-flex items-center px-2 py-3">
						<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
							<rect x="0" y="0" width="24" height="24" stroke="none"></rect>
							<circle cx="10" cy="10" r="7" />
							<line x1="21" y1="21" x2="15" y2="15" />
						</svg>
					</div>
				</div>
				<div class="flex items-center justify-between col-span-12 mb-2 space-x-2 md:justify-end md:mb-0 md:col-span-6">
					<div class="flex flex-col col-span-6">
						<span class="mb-2 text-xs font-semibold md:text-right"> Items Per Page : </span>
						<select @change="onChangeRecordsPerPage" v-model="show" class="px-2 py-2 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg shadow focus:outline-2 focus:outline-blue-100 focus:ring-2 focus:ring-blue-300">
							<option :value="5">5</option>
							<option :value="10">10</option>
							<option :value="25">25</option>
							<option :value="50">50</option>
						</select>
					</div>
					<div class="flex flex-col col-span-6">
						<span class="mb-2 text-xs font-semibold md:text-right"> Status : </span>
						<select class="px-2 py-2 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg shadow focus:outline-2 focus:outline-blue-100 focus:ring-2 focus:ring-blue-300" v-model="selectedStatus" @change="onChangeStatus">
							<option value="">Status Pesanan</option>
							<option :value="1">Belum Bayar</option>
							<option :value="2">Dalam Pengerjaan</option>
							<option :value="3">Siap Diambil / Dikirim</option>
							<option :value="4">Selesai</option>
						</select>
					</div>
				</div>
			</div>
			<template v-if="transaksi.data">
				<div v-for="(data, index) in transaksi.data" class="my-4 bg-white border-2 rounded-md border-slate-200">
					<div class="flex flex-col justify-between px-6 py-4 border-b-2 md:flex-row border-slate-200">
						<div class="flex flex-col space-x-8 md:flex-row">
							<div class="space-y-2">
								<p class="text-sm font-semibold text-gray-600">No Transaksi</p>
								<p class="text-xs text-gray-500">{{ data.id_transaksi }}</p>
							</div>
							<div class="space-y-2">
								<p class="text-sm font-semibold text-gray-600">Nama Pelanggan</p>
								<p class="text-xs text-gray-500 capitalize">{{ data.pelanggan.nama_pelanggan }}</p>
							</div>
							<div class="space-y-2">
								<p class="text-sm font-semibold text-gray-600">Tgl Transaksi</p>
								<p class="text-xs text-gray-500">{{ $moment(data.tgl_transaksi).format("DD MMMM YYYY") }}</p>
							</div>
							<div class="space-y-2">
								<p class="text-sm font-semibold text-gray-600">Total Transaksi</p>
								<p class="text-xs text-gray-500">{{ "Rp. " + Intl.NumberFormat().format(data.total_harga) }}</p>
							</div>
						</div>
						<div class="flex items-center space-x-4">
							<template v-if="data.status_pesanan != 1">
								<div class="flex items-center gap-2">
									<button class="btn btn-with-icon btn-sm btn-indigo" @click="changeStatus(data.id_transaksi, index)" v-if="data.status_pesanan !== 4">
										<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
											<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
										</svg>
										<span>{{ data.status_pesanan == 2 ? "Proses" : data.status_pesanan == 3 ? "Ubah Status" : "Selesaikan" }}</span>
									</button>
									<NuxtLink target="_blank" :to="{name: 'pencatatan-transaksi-id', params: {id: data.id_transaksi}}">
										<button class="btn btn-sm btn-with-icon btn-teal">
											<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
												<path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
											</svg>
											<span>Print</span>
										</button>
									</NuxtLink>
									<NuxtLink target="_blank" :to="{name: 'pemesanan-id', params: {id: data.id_transaksi}}">
										<button class="flex items-center justify-center gap-2 px-2 py-1 border-2 rounded-md border-slate-300 hover:bg-slate-100">
											<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
												<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
											</svg>
											<span class="text-xs">Invoice</span>
										</button>
									</NuxtLink>
								</div>
							</template>
							<template v-else>
								<div class="flex items-center px-4 py-1 text-xs font-semibold text-red-500 capitalize border border-red-500 rounded-full">belum bayar</div>
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
			<div>
				<Pagination v-if="transaksi.data && transaksi.per_page < transaksi.total" :total-pages="transaksi.links.length - 2" :per-page="show" :current-page="transaksi.current_page" @pagechanged="onPageChange"></Pagination>
			</div>
		</div>
	</div>
</template>

<script>
import Pagination from "../../components/Pagination.vue";
export default {
	name: "KelolaPemesanan",
	layout: "auth",
	components: {Pagination},
	data() {
		return {
			content: null,

			search: "",
			selectedStatus: "",
			show: 5,
			page: 1,
		};
	},
	created() {
		this.getDataTransaksi();
	},
	computed: {
		transaksi() {
			return this.$store.state.transaksi.allData;
		},
	},
	methods: {
		getDataTransaksi() {
			this.$store.dispatch("transaksi/fetchAllTransaksi", {
				page: this.page,
				show: this.show,
				search: this.search,
				status: this.selectedStatus,
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
		async changeStatus(id, index) {
			let data = this.transaksi.data[index].status_pesanan;
			let formData = new FormData();

			formData.append("status_pesanan", data);

			this.$swal
				.fire({
					title: "Ubah Status Transaksi?",
					text: "Data yang diubah tidak dapat dikembalikan!",
					icon: "question",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Yes!",
				})
				.then((result) => {
					if (result.isConfirmed) {
						this.$axios
							.post("/api/transaksi/status/" + id, formData)
							.then((response) => this.getDataTransaksi())
							.then(() => this.$swal.fire("Success!", "Status Pesanan Berhasil diubah.", "success"))
							.catch((error) => console.log(error));
					}
				});
		},

		onChangeStatus() {
			this.getDataTransaksi();
		},
		onChangeRecordsPerPage() {
			this.getDataTransaksi();
		},
		searchData() {
			this.getDataTransaksi();
		},
		onPageChange(page) {
			this.page = page;
			this.getDataTransaksi();
		},
	},
};
</script>
