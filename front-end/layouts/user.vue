<template>
	<div>
		<Navbar></Navbar>
		<main>
			<div class="py-6 mx-auto max-w-screen-2xl sm:px-6 lg:px-8">
				<!-- Replace with your content -->
				<Nuxt />

				<!-- /End replace -->
			</div>
		</main>
	</div>
</template>

<script>
import Navbar from "../components/layouts/NavbarClient.vue";
export default {
	components: {Navbar},
};
</script>

<style></style>
