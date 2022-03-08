<template>
	<div class="py-10 bg-lime-500">
		<div class="block p-5 mx-auto bg-white print:block drop-shadow-lg paper">{{ pengadaan }}</div>
	</div>
</template>

<script>
export default {
	auth: false,
	data() {
		return {
			pengadaan: [],
		};
	},
	mounted() {
		this.getData();
	},
	methods: {
		async getData() {
			this.$axios
				.get("/api/pengadaan/" + this.$route.params.id)
				.then((response) => {
					console.log(response);
					this.pengadaan = response.data;
				})
				.catch((error) => {
					console.log(error);
				});
		},
	},
};
</script>

<style scoped>
.paper {
	width: 21cm;
	height: 29.7cm;
}
</style>
