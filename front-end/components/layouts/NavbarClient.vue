<template>
	<div class="min-h-full">
		<nav class="bg-green-600">
			<div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
				<div class="flex items-center justify-between h-16">
					<div class="flex items-center basis-1/3">
						<div class="flex-shrink-0">
							<img src="~/assets/img/logo.png" class="w-12 h-12" alt="" srcset="" />
						</div>
						<div class="hidden md:block">
							<div class="flex items-baseline ml-10 space-x-4">
								<NuxtLink to="/">
									<a href="#" class="px-3 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-green-700 hover:text-white" aria-current="page">Home</a>
								</NuxtLink>
								<NuxtLink to="/katalog">
									<a href="#" class="px-3 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-green-700 hover:text-white">Katalog</a>
								</NuxtLink>
								<NuxtLink to="/transaksi">
									<a href="#" class="px-3 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-green-700 hover:text-white">Transaksi</a>
								</NuxtLink>
							</div>
						</div>
					</div>
					<div class="hidden md:block basis-2/3">
						<div class="flex items-center justify-end gap-4 ml-4 md:ml-6">
							<input class="block w-1/2 mt-1 text-xs border border-gray-300 rounded shadow form-input-lg focus:border-green-200 focus:outline-none focus:ring-2 focus:ring-green-300" placeholder="Search..." />
							<button type="button" @click.prevent="cart = !cart" class="p-1 text-gray-100 bg-green-700 rounded-full hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-green-800">
								<!-- cart -->
								<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
									<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
								</svg>
							</button>

							<!-- Profile dropdown -->
							<div class="relative ml-3">
								<div>
									<button @click.prevent="dropdown = !dropdown" type="button" class="flex items-center max-w-xs p-1 text-sm text-gray-100 bg-green-700 rounded-full focus:ring-white focus:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-700 hover:text-white" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
										<span class="sr-only">Open user menu</span>
										<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
											<path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
										</svg>
									</button>
								</div>
								<div class="absolute right-0 z-10 w-48 py-1 mt-2 transition duration-1000 ease-in-out origin-top-right transform bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1" :class="{'opacity-0 scale-95': !dropdown, 'opacity-100 scale-100': dropdown}">
									<!-- Active: "bg-gray-100", Not Active: "" -->
									<template v-if="$auth.loggedIn">
										<a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-100" role="menuitem" tabindex="-1" id="user-menu-item-0">Profile</a>
										<a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-100" role="menuitem" tabindex="-1" id="user-menu-item-1">Transaksi</a>
										<NuxtLink to="/dashboard">
											<a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-100" role="menuitem" tabindex="-1" id="user-menu-item-1">Admin Page</a>
										</NuxtLink>
										<a href="#" @click.prevent="logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-100" role="menuitem" tabindex="-1" id="user-menu-item-2">Log out</a>
									</template>
									<template v-else>
										<NuxtLink to="/login">
											<a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-100" role="menuitem" tabindex="-1" id="user-menu-item-0">Login</a>
										</NuxtLink>
									</template>
								</div>

								<!-- Cart Panel -->
								<div class="relative z-10" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
									<Cart @close-cart="cart = !cart" class="fixed inset-0 overflow-hidden transition duration-1000 ease-in-out transform" :class="{'translate-x-0 ': cart, 'translate-x-full': !cart}"></Cart>
								</div>
							</div>
						</div>
					</div>
					<div class="flex -mr-2 md:hidden">
						<!-- Mobile menu button -->
						<button type="button" class="inline-flex items-center justify-center p-2 text-gray-200 bg-green-700 rounded-md hover:bg-green-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800" aria-controls="mobile-menu" aria-expanded="false">
							<span class="sr-only">Open main menu</span>
							<svg class="block w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
								<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
							</svg>
							<svg class="hidden w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
								<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
							</svg>
						</button>
					</div>
				</div>
			</div>

			<!-- Mobile menu, show/hide based on menu state. -->
			<div class="md:hidden" id="mobile-menu">
				<div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
					<!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
					<a href="#" class="block px-3 py-2 text-base font-medium text-white bg-green-700 rounded-md" aria-current="page">Home</a>
					<a href="#" class="block px-3 py-2 text-base font-medium text-gray-100 rounded-md hover:bg-green-700 hover:text-white">Katalog</a>
				</div>
				<div class="pt-4 pb-3 border-t border-gray-700">
					<div class="flex items-center px-5">
						<div class="flex-shrink-0">
							<img class="w-10 h-10 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />
						</div>
						<div class="ml-3">
							<div class="text-base font-medium leading-none text-white">User Account</div>
							<div class="text-sm font-medium leading-none text-gray-200">email@example.com</div>
						</div>
					</div>
					<div class="px-2 mt-3 space-y-1">
						<a href="#" class="block px-3 py-2 text-base font-medium text-gray-200 rounded-md hover:bg-green-700 hover:text-white">Your Profile</a>

						<a href="#" class="block px-3 py-2 text-base font-medium text-gray-200 rounded-md hover:bg-green-700 hover:text-white">Settings</a>

						<a href="#" class="block px-3 py-2 text-base font-medium text-gray-200 rounded-md hover:bg-green-700 hover:text-white">Sign out</a>
					</div>
				</div>
			</div>
		</nav>
	</div>
</template>

<script>
import Cart from "../Cart.vue";
export default {
	components: {Cart},
	data() {
		return {
			dropdown: false,
			cart: false,
		};
	},
	methods: {
		showCart() {
			this.cart = !this.cart;
		},

		async logout() {
			await this.$auth.logout();
			// this.$router.push("/login");
			this.$swal.fire({
				icon: "success",
				showCloseButton: true,
				showConfirmButton: false,
				text: "Anda Berhasil Logout!",
			});
		},
	},
};
</script>

<style>
.nuxt-link-exact-active > a {
	@apply text-white bg-green-700;
}
</style>
