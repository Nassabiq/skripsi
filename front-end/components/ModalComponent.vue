<template>
	<div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
		<div class="flex items-end justify-center min-h-screen pt-2 px-2 pb-10 text-center sm:block sm:p-0">
			<!--
            Background overlay, show/hide based on modal state.

            Entering: "ease-out duration-300"
                From: "opacity-0"
                To: "opacity-100"
            Leaving: "ease-in duration-200"
                From: "opacity-100"
                To: "opacity-0"
            -->
			<div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

			<!-- This element is to trick the browser into centering the modal contents. -->
			<span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

			<!--
            Modal panel, show/hide based on modal state.

            Entering: "ease-out duration-300"
                From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                To: "opacity-100 translate-y-0 sm:scale-100"
            Leaving: "ease-in duration-200"
                From: "opacity-100 translate-y-0 sm:scale-100"
                To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            -->
			<div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-full" :class="size">
				<div class="divide-y divide-gray-200">
					<div class="flex items-center justify-between px-5 py-4 bg-white">
						<div class="flex items-center space-x-4">
							<p class="text-xl font-semibold text-gray-800">{{ title }}</p>
						</div>
						<button class="text-gray-400 hover:text-gray-600" @click="$emit('close-modal')">
							<svg class="w-4 transition duration-150 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512.001 512.001">
								<path d="M284.286 256.002L506.143 34.144c7.811-7.811 7.811-20.475 0-28.285-7.811-7.81-20.475-7.811-28.285 0L256 227.717 34.143 5.859c-7.811-7.811-20.475-7.811-28.285 0-7.81 7.811-7.811 20.475 0 28.285l221.857 221.857L5.858 477.859c-7.811 7.811-7.811 20.475 0 28.285a19.938 19.938 0 0014.143 5.857 19.94 19.94 0 0014.143-5.857L256 284.287l221.857 221.857c3.905 3.905 9.024 5.857 14.143 5.857s10.237-1.952 14.143-5.857c7.811-7.811 7.811-20.475 0-28.285L284.286 256.002z" />
							</svg>
						</button>
					</div>
					<div class="grid grid-cols-12 gap-4 p-8">
						<!-- Content always in 12 grid, don't forget to use col-span -->
						<slot name="content"></slot>
					</div>
				</div>
				<div class="flex justify-end px-4 py-3 space-x-4 bg-gray-50">
					<slot name="submit"></slot>
					<button @click="$emit('close-modal')" type="button" class="btn btn-lg btn-red">
						Kembali
					</button>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	props: ["title", "size"],
	data() {
		return {};
	},
	methods: {},
};
</script>
