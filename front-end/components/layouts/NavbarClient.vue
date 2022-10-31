<template>
	<div class="min-h-full">
		<nav class="bg-green-600">
			<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
				<div class="flex h-16 items-center justify-between">
					<div class="flex items-center basis-1/3">
						<div class="flex-shrink-0">
							<img class="h-8 w-8" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=500" alt="Your Company" />
						</div>
						<div class="hidden md:block">
							<div class="ml-10 flex items-baseline space-x-4">
								<!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
								<a href="#" class="bg-green-700 text-white px-3 py-2 rounded-md text-sm font-medium" aria-current="page">Home</a>
								<a href="#" class="text-gray-300 hover:bg-green-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Katalog</a>
								<a href="#" class="text-gray-300 hover:bg-green-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Transaksi</a>
							</div>
						</div>
					</div>
					<div class="hidden md:block basis-2/3">
						<div class="ml-4 flex gap-4 justify-end items-center md:ml-6">
							<input class="block w-1/2 mt-1 text-xs border border-gray-300 rounded shadow form-input-lg focus:border-green-200 focus:outline-none focus:ring-2 focus:ring-green-300" placeholder="Search..." />
							<button type="button" @click.prevent="cart = !cart" class="rounded-full bg-green-700 p-1 text-gray-100 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-green-800">
								<!-- cart -->
								<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
									<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
								</svg>
							</button>

							<!-- Profile dropdown -->
							<div class="relative ml-3">
								<div>
									<button @click.prevent="dropdown = !dropdown" type="button" :class="dropdown ? 'bg-green-600 focus:ring-white' : 'bg-green-700'" class="flex max-w-xs items-center rounded-full text-sm p-1 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-700 text-gray-100 hover:text-white" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
										<span class="sr-only">Open user menu</span>
										<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
											<path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
										</svg>
									</button>
								</div>

								<!--
                                    Dropdown menu, show/hide based on menu state.

                                    Entering: "transition ease-out duration-100"
                                        From: "transform opacity-0 scale-95"
                                        To: "transform opacity-100 scale-100"
                                        Leaving: "transition ease-in duration-75"
                                        From: "transform opacity-100 scale-100"
                                        To: "transform opacity-0 scale-95"
                                -->
								<div class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" v-show="dropdown" tabindex="-1">
									<!-- Active: "bg-gray-100", Not Active: "" -->
									<a href="#" class="block bg-green-100 px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-0">Profile</a>
									<a href="#" class="block hover:bg-green-100 px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-1">Transaksi</a>
									<a href="#" class="block hover:bg-green-100 px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-2">Sign out</a>
								</div>

								<!-- Cart Panel -->
								<div class="relative z-10" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
									<!-- <div class="fixed inset-0 bg-gray-500 transition-opacity duration-500 ease-in-out" :class="{'bg-opacity-75 ': cart, ' bg-opacity-0': !cart}"></div> -->
									<div class="fixed inset-0 overflow-hidden transition duration-1000 ease-in-out transform" :class="{'translate-x-0 ': cart, 'translate-x-full': !cart}">
										<div class="absolute inset-0 overflow-hidden">
											<div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
												<div class="pointer-events-auto relative w-screen max-w-md">
													<div class="flex h-full flex-col overflow-y-scroll bg-white py-6 shadow-xl">
														<div class="px-4 sm:px-6">
															<div class="flex justify-between">
																<h2 class="text-lg font-semibold text-gray-900" id="slide-over-title">Cart</h2>
																<button @click.prevent="showCart" type="button" class="rounded-md text-gray-700 hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-white">
																	<span class="sr-only">Close panel</span>
																	<!-- Heroicon name: outline/x-mark -->
																	<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
																		<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
																	</svg>
																</button>
															</div>
														</div>
														<div class="relative mt-6 flex-1 px-4 sm:px-6">
															<!-- Replace with your content -->
															<div class="absolute inset-0 px-4 sm:px-6">
																<div class="h-full border-2 border-dashed border-gray-200" aria-hidden="true"></div>
															</div>
															<!-- /End replace -->
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="-mr-2 flex md:hidden">
						<!-- Mobile menu button -->
						<button type="button" class="inline-flex items-center justify-center rounded-md bg-green-700 p-2 text-gray-200 hover:bg-green-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800" aria-controls="mobile-menu" aria-expanded="false">
							<span class="sr-only">Open main menu</span>
							<svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
								<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
							</svg>
							<svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
								<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
							</svg>
						</button>
					</div>
				</div>
			</div>

			<!-- Mobile menu, show/hide based on menu state. -->
			<div class="md:hidden" id="mobile-menu">
				<div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
					<!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
					<a href="#" class="bg-green-700 text-white block px-3 py-2 rounded-md text-base font-medium" aria-current="page">Home</a>
					<a href="#" class="text-gray-100 hover:bg-green-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Katalog</a>
				</div>
				<div class="border-t border-gray-700 pt-4 pb-3">
					<div class="flex items-center px-5">
						<div class="flex-shrink-0">
							<img class="h-10 w-10 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />
						</div>
						<div class="ml-3">
							<div class="text-base font-medium leading-none text-white">User Account</div>
							<div class="text-sm font-medium leading-none text-gray-200">email@example.com</div>
						</div>
					</div>
					<div class="mt-3 space-y-1 px-2">
						<a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-200 hover:bg-green-700 hover:text-white">Your Profile</a>

						<a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-200 hover:bg-green-700 hover:text-white">Settings</a>

						<a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-200 hover:bg-green-700 hover:text-white">Sign out</a>
					</div>
				</div>
			</div>
		</nav>

		<!-- <header class="bg-white shadow">
			<div class="mx-auto max-w-7xl py-6 px-4 sm:px-6 lg:px-8">
				<h1 class="text-3xl font-bold tracking-tight text-gray-900">Dashboard</h1>
			</div>
		</header> -->
		<main>
			<div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
				<!-- Replace with your content -->
				<!-- <Nuxt /> -->

				<!-- /End replace -->
			</div>
		</main>
	</div>
</template>

<script>
export default {
	data() {
		return {
			dropdown: false,
			cart: false,
			transition: "home",
		};
	},
	methods: {
		showCart() {
			this.cart = !this.cart;
		},
	},
};
</script>

<style>
.home-enter-active,
.home-leave-active {
	transition: opacity 5s;
}
.home-enter,
.home-leave-active {
	opacity: 0;
}
</style>
