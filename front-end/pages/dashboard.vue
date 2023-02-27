<template>
	<div class="gap-4 space-y-4 margin-auth">
		<div class="grid grid-cols-3 gap-4">
			<div class="col-span-3 md:col-span-1">
				<div class="flex items-center gap-4 px-5 py-4 bg-white border border-green-500 rounded-lg">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-12 h-12 text-green-600">
						<path fill-rule="evenodd" d="M5.625 1.5H9a3.75 3.75 0 013.75 3.75v1.875c0 1.036.84 1.875 1.875 1.875H16.5a3.75 3.75 0 013.75 3.75v7.875c0 1.035-.84 1.875-1.875 1.875H5.625a1.875 1.875 0 01-1.875-1.875V3.375c0-1.036.84-1.875 1.875-1.875zm5.845 17.03a.75.75 0 001.06 0l3-3a.75.75 0 10-1.06-1.06l-1.72 1.72V12a.75.75 0 00-1.5 0v4.19l-1.72-1.72a.75.75 0 00-1.06 1.06l3 3z" clip-rule="evenodd" />
						<path d="M14.25 5.25a5.23 5.23 0 00-1.279-3.434 9.768 9.768 0 016.963 6.963A5.23 5.23 0 0016.5 7.5h-1.875a.375.375 0 01-.375-.375V5.25z" />
					</svg>
					<div>
						<p class="text-sm">Pesanan Baru</p>
						<p class="text-xl font-semibold">Pesanan Baru</p>
					</div>
				</div>
			</div>
			<div class="col-span-3 md:col-span-1">
				<div class="flex items-center gap-4 px-5 py-4 bg-white border border-green-500 rounded-lg">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-12 h-12 text-green-600">
						<path fill-rule="evenodd" d="M5.625 1.5H9a3.75 3.75 0 013.75 3.75v1.875c0 1.036.84 1.875 1.875 1.875H16.5a3.75 3.75 0 013.75 3.75v7.875c0 1.035-.84 1.875-1.875 1.875H5.625a1.875 1.875 0 01-1.875-1.875V3.375c0-1.036.84-1.875 1.875-1.875zM9.75 17.25a.75.75 0 00-1.5 0V18a.75.75 0 001.5 0v-.75zm2.25-3a.75.75 0 01.75.75v3a.75.75 0 01-1.5 0v-3a.75.75 0 01.75-.75zm3.75-1.5a.75.75 0 00-1.5 0V18a.75.75 0 001.5 0v-5.25z" clip-rule="evenodd" />
						<path d="M14.25 5.25a5.23 5.23 0 00-1.279-3.434 9.768 9.768 0 016.963 6.963A5.23 5.23 0 0016.5 7.5h-1.875a.375.375 0 01-.375-.375V5.25z" />
					</svg>
					<div>
						<p class="text-sm">Produksi dalam Proses</p>
						<p class="text-xl font-semibold">Produksi dalam Proses</p>
					</div>
				</div>
			</div>
			<div class="col-span-3 md:col-span-1">
				<div class="flex items-center gap-4 px-5 py-4 bg-white border border-green-500 rounded-lg">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-12 h-12 text-green-600">
						<path fill-rule="evenodd" d="M9 1.5H5.625c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0016.5 9h-1.875a1.875 1.875 0 01-1.875-1.875V5.25A3.75 3.75 0 009 1.5zm6.61 10.936a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 14.47a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
						<path d="M12.971 1.816A5.23 5.23 0 0114.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 013.434 1.279 9.768 9.768 0 00-6.963-6.963z" />
					</svg>

					<div>
						<p class="text-sm">Penjualan Bulan ini</p>
						<p class="text-xl font-semibold">Penjualan Bulan ini</p>
					</div>
				</div>
				<!-- <div class="px-5 py-4 bg-white border border-green-500 rounded-lg">Penjualan Bulan Ini</div> -->
			</div>
			<div class="col-span-3">
				<div class="px-5 py-4 bg-white border border-gray-100 rounded-lg">Data Transaksi selain yang sudah selesai</div>
			</div>
		</div>
		<!-- <p class="text-3xl font-semibold text-blue-600">Hello {{ user.name }}</p>
		<button v-if="$auth.loggedIn" @click.prevent="logout" class="text-xl font-semibold text-blue-600">Logout</button> -->
	</div>
</template>

<script>
export default {
	name: "Dashboard",
	layout: "auth",
	middleware: "role/isNotPelanggan",
	data() {
		return {
			user: this.$auth.user,
		};
	},
	methods: {
		async logout() {
			await this.$auth.logout();
			this.$router.push("/login");
		},
	},
};
</script>
