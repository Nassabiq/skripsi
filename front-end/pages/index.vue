<template>
	<div class="margin-auth">
		<p class="text-3xl font-semibold text-blue-600">Hello World</p>
		<button v-if="$auth.loggedIn" @click.prevent="logout" class="text-xl font-semibold text-blue-600">Logout</button>
		{{ $auth.loggedIn }}
		{{ $store.state.auth.loggedIn }}
		<!-- {{ user.name }} -->
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
	methods: {
		async logout() {
			await this.$auth.logout();
			this.$router.push("/login");
		},
	},
};
</script>
