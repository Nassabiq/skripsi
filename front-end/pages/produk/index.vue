<template>
	<div class="gap-4 space-y-4 margin-auth">
		<div class="card">
			<DataProduk :products="products" :categories="categories" @get-data="getData"></DataProduk>
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
		};
	},
	computed: {},

	mounted() {
		this.getAllData();
	},
	methods: {
		async getAllData() {
			this.$axios
				.get("/api/product")
				.then((response) => {
					this.products = response.data.products;
					this.categories = response.data.category;
					this.units = response.data.satuan;
				})
				.catch((error) => {
					console.log(error);
				});
		},

		getData() {
			this.getAllData();
		},
	},
};
</script>
