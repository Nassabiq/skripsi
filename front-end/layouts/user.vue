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
		<Footer></Footer>
	</div>
</template>

<script>
import Navbar from "../components/layouts/NavbarClient.vue";
import Footer from "../components/layouts/Footer.vue";

export default {
	components: {Navbar, Footer},
};
</script>

<style></style>
