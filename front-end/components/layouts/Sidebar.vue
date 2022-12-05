<template>
	<div>
		<a href="#" class="flex items-center px-4 space-x-2 text-white">
			<!-- <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
			</svg> -->
			<img src="~/assets/img/logo.png" class="w-24 h-24" alt="" srcset="" />
			<span class="text-2xl font-extrabold">UQI Media</span>
		</a>
		<nav class="pt-4">
			<ul>
				<li>
					<NuxtLink to="/dashboard">
						<a href="#" class="relative flex flex-row items-center px-4 py-1 my-1 text-sm transition duration-200 rounded-md hover:bg-green-300 hover:text-black">
							<svg xmlns="http://www.w3.org/2000/svg" class="icon-size" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
							</svg>
							<span class="font-font-light">Dashboard</span>
						</a>
					</NuxtLink>
				</li>
				<li>
					<span class="px-4 py-1 mt-2 text-xs font-medium text-white">Menu</span>
				</li>
				<li>
					<NuxtLink to="/bahan-baku">
						<a href="#" class="relative flex flex-row items-center px-4 py-1 my-1 text-sm transition duration-200 rounded-md hover:bg-green-300 hover:text-black">
							<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-size">
								<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
							</svg>
							<span class="font-font-light">Data Bahan Baku</span>
						</a>
					</NuxtLink>
				</li>
				<li>
					<NuxtLink to="/produk">
						<a href="#" class="relative flex flex-row items-center px-4 py-1 my-1 text-sm transition duration-200 rounded-md hover:bg-green-300 hover:text-black">
							<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-size">
								<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
							</svg>
							<span class="font-font-light">Data Produk</span>
						</a>
					</NuxtLink>
				</li>
				<li>
					<div class="relative">
						<DropdownMenu title="Persediaan" class="cursor-pointer">
							<template #icon>
								<svg xmlns="http://www.w3.org/2000/svg" class="icon-size" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
								</svg>
							</template>
							<template #list>
								<NuxtLink to="/persediaan/pengadaan">
									<li class="dropdown-item">Pengadaan Persediaan</li>
								</NuxtLink>
								<NuxtLink to="/persediaan/kelola-stok-masuk">
									<li class="dropdown-item">Kelola Stok Masuk</li>
								</NuxtLink>
								<NuxtLink to="/persediaan/laporan-persediaan">
									<li class="dropdown-item">Laporan Persediaan</li>
								</NuxtLink>
							</template>
						</DropdownMenu>
					</div>
				</li>
				<li>
					<DropdownMenu title="Penjualan" class="cursor-pointer">
						<template #icon>
							<svg xmlns="http://www.w3.org/2000/svg" class="icon-size" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
							</svg>
						</template>
						<template #list>
							<NuxtLink to="/penjualan/laporan-penjualan">
								<li class="dropdown-item">Laporan Penjualan</li>
							</NuxtLink>
							<NuxtLink to="/penjualan/analisis-hpp">
								<li class="dropdown-item">Analisis HPP</li>
							</NuxtLink>
							<NuxtLink to="/penjualan/kelola-harga-jual">
								<li class="dropdown-item">Kelola Harga Jual</li>
							</NuxtLink>
						</template>
					</DropdownMenu>
				</li>
				<li>
					<NuxtLink to="/pemesanan">
						<a href="#" class="relative flex flex-row items-center px-4 py-1 my-1 text-sm transition duration-200 rounded-md hover:bg-green-300 hover:text-black">
							<svg xmlns="http://www.w3.org/2000/svg" class="icon-size" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
							</svg>
							<span class="font-font-light">Kelola Pemesanan</span>
						</a>
					</NuxtLink>
				</li>
				<li>
					<NuxtLink to="/manage-user">
						<a href="#" class="relative flex flex-row items-center px-4 py-1 my-1 text-sm transition duration-200 rounded-md hover:bg-green-300 hover:text-black">
							<svg xmlns="http://www.w3.org/2000/svg" class="icon-size" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
							</svg>
							<span class="font-font-light">Kelola User</span>
						</a>
					</NuxtLink>
				</li>
				<li>
					<span class="w-full px-4 py-1 mt-2 text-xs font-medium text-white">-</span>
				</li>

				<li>
					<NuxtLink to="/">
						<a href="#" class="relative flex flex-row items-center px-4 py-1 my-1 text-sm transition duration-200 rounded-md hover:bg-green-300 hover:text-black">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="icon-size">
								<path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-4.28 9.22a.75.75 0 000 1.06l3 3a.75.75 0 101.06-1.06l-1.72-1.72h5.69a.75.75 0 000-1.5h-5.69l1.72-1.72a.75.75 0 00-1.06-1.06l-3 3z" clip-rule="evenodd" />
							</svg>

							<span class="font-font-light">Back To Home</span>
						</a>
					</NuxtLink>
				</li>
			</ul>
		</nav>
	</div>
</template>

<script>
import DropdownMenu from "./SidebarDropdown.vue";
export default {
	components: {DropdownMenu},
	data() {
		return {
			// path: this.$nuxt.$route.path,
		};
	},
	computed: {},
};
</script>

<style scoped>
.icon-size {
	@apply pr-3 w-9 h-9;
}
.dropdown-item {
	@apply block px-4 py-2 mt-2 text-sm text-green-800 bg-transparent rounded-lg;
}
.dropdown-item:hover {
	@apply text-gray-900 bg-green-200;
}
.dropdown-item:focus {
	@apply text-gray-900 bg-green-200 outline-none ring;
}
.nuxt-link-exact-active {
	@apply block my-1 text-sm font-medium tracking-wider text-green-800 bg-transparent bg-green-200 rounded-lg pointer-events-none;
}
.active {
	@apply text-sm text-left rounded-md bg-green-300 text-black;
}
@screen md {
	.dropdown-item {
		@apply mt-0;
	}
}
</style>
