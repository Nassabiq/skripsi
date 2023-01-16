<template>
	<div class="margin-auth">
		<!-- <p class="text-3xl font-semibold text-blue-600">Hello World</p> -->
	</div>
</template>

<script>
export default {
	name: "IndexPage",
	layout: "user",
	auth: "guest",
	data() {
		return {
			user: this.$auth.user,
		};
	},
	methods: {},
};
</script>
