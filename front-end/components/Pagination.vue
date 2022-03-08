<template>
	<div id="prev-next" class="flex justify-center mt-4 space-x-2">
		<NuxtLink v-if="prevPage" :to="prevLink" class="px-4 py-2 font-medium text-gray-700 bg-white border border-gray-200 rounded hover:bg-gray-200">
			Prev page
		</NuxtLink>
		<NuxtLink v-if="nextPage" :to="`${urlPrefix}/page/${pageNo + 1}`" class="px-4 py-2 font-medium text-gray-700 bg-white border border-gray-200 rounded hover:bg-gray-200">
			Next page
		</NuxtLink>
	</div>
</template>

<script>
export default {
	props: ["prevPage", "nextPage", "pageNo", "urlPrefix"],
	computed: {
		prevLink() {
			return this.pageNo === 2 ? this.urlPrefix : `${this.urlPrefix}/page/${this.pageNo - 1}`;
		},
	},
};
</script>

<style></style>
