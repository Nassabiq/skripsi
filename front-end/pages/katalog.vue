<template>
	<div class="margin-auth">
		<div class="grid grid-cols-12 gap-4">
			<div class="col-span-12 space-y-4 md:col-span-4 card">
				<p class="text-2xl font-semibold text-gray-800">Katalog</p>
				<ul class="list-disc list-inside">
					<li class="px-4 py-2 text-sm">All Data</li>
					<li class="px-4 py-2 text-sm" v-for="data in categories">{{ data.nama_kategori }}</li>
				</ul>
			</div>
			<div class="col-span-12 md:col-span-8"></div>
		</div>
		<!-- <button v-if="$auth.loggedIn" @click.prevent="logout" class="text-xl font-semibold text-blue-600">Logout</button>
    {{ user.name }} -->
	</div>
</template>

<script>
export default {
	name: "Katalog",
	layout: "user",
	auth: false,
	data() {
		return {
			// user : this.$auth.user
			products: [],
			categories: [],
		};
	},
	mounted() {
		this.getProducts();
		this.getCategories();
	},
	methods: {
		async getProducts() {
			const products = await this.$axios.$get("/api/produk?page=" + this.productPage + "&show=" + parseInt(this.productSize) + "&search=" + this.search);
			this.products = products;
		},
		async getCategories() {
			const categories = await this.$axios.$get("/api/kategori");
			this.categories = categories;
		},
		// async logout(){
		//   await this.$auth.logout();
		//   this.$router.push('/login');
		// }
	},
};
</script>
