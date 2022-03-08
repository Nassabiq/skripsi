<template>
	<div>
		<nav class="">
			<ol class="flex items-center px-4 py-1 my-1 space-x-2 text-sm font-medium tracking-wider text-green-800 capitalize bg-transparent rounded-lg">
				<li>
					<NuxtLink to="/" class="hover:text-blue-500">
						Home
					</NuxtLink>
				</li>
				<!-- <li><span class="mx-2 text-gray-500">/</span></li> -->
				<li v-for="(crumb, index) in crumbs" :key="index" class="hover:text-blue-500">
					<NuxtLink :to="crumb.path">
						{{ $route.fullPath === crumb.path && title !== null ? title : crumb.title }}
					</NuxtLink>
				</li>
			</ol>
		</nav>
	</div>
</template>

<script>
const titleCase = require("ap-style-title-case");
export default {
	props: {
		title: {
			type: String,
			default: null,
		},
	},
	computed: {
		crumbs() {
			const fullPath = this.$route.fullPath;
			const params = fullPath.startsWith("/") ? fullPath.substring(1).split("/") : fullPath.split("/");
			const crumbs = [];
			let path = "";
			params.forEach((param, index) => {
				path = `${path}/${param}`;
				const match = this.$router.match(path);
				if (match.name !== null) {
					crumbs.push({
						title: titleCase(param.replace(/-/g, " ")),
						...match,
					});
				}
			});
			return crumbs;
		},
	},
};
</script>
<style scoped>
li:after {
	content: " » ";
	/* display: ; */
}
li:last-child:after {
	content: "";
}
</style>
