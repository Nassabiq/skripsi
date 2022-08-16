<template>
	<div class="gap-4 space-y-4 margin-auth">
		<div class="card">
			<DataProduk :products="products" :categories="categories" @get-data="getData" @load-data="loadData" @load-page="loadPage" @search-data="searchData"></DataProduk>
		</div>
		<div class="grid grid-cols-2 gap-4">
			<div class="col-span-2 md:col-span-1">
				<div class="card">
					<DataKategori :categories="categories" :units="units" @get-data="getData"></DataKategori>
				</div>
			</div>
			<div class="col-span-2 md:col-span-1">
				<div class="card">
					<DataSatuan :units="units" @get-data="getData"></DataSatuan>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import DataProduk from "../../components/products/DataProduk.vue";
import DataKategori from "../../components/products/DataKategori.vue";
import DataSatuan from "../../components/products/DataSatuan.vue";

export default {
	name: "IndexPage",
	components: {
		DataProduk,
		DataKategori,
		DataSatuan,
	},
	layout: "auth",
	auth: false,
	data() {
		return {
			products: [],
			categories: [],
			units: [],

			productSize: 5,
			productSearch: "",
			productPage: 1,
		};
	},
	computed: {},

	mounted() {
		this.getProducts();
		this.getCategories();
		this.getSatuan();
	},
	methods: {
		async getProducts() {
			this.$axios
				.get("/api/product?page=" + this.productPage + "&show=" + parseInt(this.productSize) + "&search=" + this.productSearch)
				.then((response) => {
					console.log(response.data);
					this.products = response.data;
				})
				.catch((error) => {
					console.log(error);
				});
		},
		async getCategories() {
			this.$axios
				.get("/api/category")
				.then((response) => {
					this.categories = response.data;
				})
				.catch((error) => {
					console.log(error);
				});
		},
		async getSatuan() {
			this.$axios
				.get("/api/satuan")
				.then((response) => {
					this.units = response.data;
				})
				.catch((error) => {
					console.log(error);
				});
		},
		loadData(data) {
			if (data) {
				this.productSize = data;
			}
			this.getProducts();
		},

		loadPage(data) {
			this.productPage = data;
			this.getProducts();
		},

		searchData(data) {
			this.productSearch = data;
			this.getProducts();
		},

		getData() {
			this.getProducts();
			this.getCategories();
			this.getSatuan();
		},
	},
};
</script>
