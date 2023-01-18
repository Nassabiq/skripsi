<template>
	<footer class="bg-gray-200">
		<div class="min-h-full py-8 mx-auto max-w-7xl space-y-4">
			<div class="grid grid-cols-12 gap-8 p-4 border-b border-gray-400">
				<div class="col-span-8 pr-12 space-y-2">
					<p class="font-semibold text-lg">UQI Media Digital Printing</p>
					<p class="text-sm text-gray-700">UQI Media Digital Printing adalah sebuah perusahaan yang bergerak dalam bidang jasa percetakan digital, offset dan merchandise.</p>
					<div class="flex items-center gap-2 pt-2">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-green-500">
							<path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
						</svg>
						<p class="text-xs font-semibold text-gray-700">Jl. Raya Cemplang KM.19, RT 2 RW 6, Dukuh, Kec. Cibungbulang, Bogor, Jawa Barat 16630.</p>
					</div>
				</div>
				<div class="col-span-4 space-y-2">
					<p class="tracking-wider font-semibold">Quick Access</p>
					<ul class="text-sm text-gray-700 space-y-1">
						<li>
							<NuxtLink to="/">
								<a href="#" class="hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium" aria-current="page">Home</a>
							</NuxtLink>
						</li>
						<li>
							<NuxtLink to="/katalog">
								<a href="#" class="hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium" aria-current="page">Katalog</a>
							</NuxtLink>
						</li>
						<li>
							<NuxtLink to="/transaksi" v-if="$auth.loggedIn">
								<a href="#" class="hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium" aria-current="page">Transaksi</a>
							</NuxtLink>
						</li>
					</ul>
				</div>
			</div>
			<p class="text-center text-xs text-gray-700 cursor-pointer">
				&#169;
				<span class="text-green-600 hover:text-green-500 font-semibold">2023 UQI Media Digital Printing</span>
				| All Right Reserved
			</p>
		</div>
	</footer>
</template>

<script>
export default {};
</script>

<style scoped>
.nuxt-link-exact-active > a {
	@apply font-semibold text-green-600;
}
/* font-semibold text-green-600 */
</style>
