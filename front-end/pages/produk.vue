<template>
	<div class="margin-auth">
		<div class="grid grid-cols-6 gap-4">
			<div class="grid grid-cols-12 col-span-3 gap-4">
				<div class="col-span-8">
					<div class="p-2 my-2 border-2 border-green-400 rounded-md shadow-md bg-green-50 basis-11/12">
						<div class="flex items-center justify-between space-x-2">
							<div class="bg-gray-300 aspect-square basis-1/5"></div>
							<div class="detail-pesanan basis-3/5">
								<p class="font-semibold">Produk Terpilih</p>
								<p class="text-xs font-light tracking-wider">Kategori</p>
							</div>
							<div class="text-right price justify-items-end basis-1/5">
								<p class="text-xs font-semibold">Rp. 2.000.000</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
</template>

<script>
export default {
	name: "IndexPage",
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
