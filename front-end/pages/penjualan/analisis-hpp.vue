<template>
	<div class="margin-auth">
		<p class="text-3xl font-semibold text-blue-600">Analisis HPP</p>
		<!-- <button v-if="$auth.loggedIn" @click.prevent="logout" class="text-xl font-semibold text-blue-600">Logout</button>
    {{ user.name }} -->
	</div>
</template>

<script>
export default {
	name: "AnalisisHPP",
	layout: "auth",
	auth: false,
	data() {
		return {
			// user : this.$auth.user
		};
	},
	methods: {
		// async logout(){
		//   await this.$auth.logout();
		//   this.$router.push('/login');
		// }
	},
};
</script>
