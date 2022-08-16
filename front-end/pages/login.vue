<template>
	<div class="auth-template">
		<div class="sm:mx-auto sm:w-full sm:max-w-md">
			<!-- <div class="w-full"> -->
			<img src="~/assets/img/logo.png" class="block mx-auto w-36 h-36" alt="" srcset="" />
			<!-- </div> -->

			<p class="mt-6 font-serif text-4xl font-extrabold leading-9 tracking-wide text-center text-gray-900 uppercase">
				Login Page
			</p>
			<p class="mt-2 font-serif text-lg leading-9 tracking-widest text-center text-gray-900">
				UQI Media Digital Printing
			</p>
		</div>

		<div class="mt-4 sm:mx-auto sm:w-full sm:max-w-md">
			<div class="px-4 py-8 bg-white shadow sm:rounded-lg sm:px-10">
				<form @submit.prevent="login">
					<div>
						<label for="email" class="block text-sm font-medium leading-5 text-gray-700">
							Email address
						</label>

						<div class="mt-1 rounded-md shadow-sm">
							<input v-model="form.email" id="email" name="email" type="email" required autofocus class="block w-full px-3 py-2 placeholder-gray-400 transition duration-150 ease-in-out border border-gray-300 rounded-md appearance-none focus:outline-none focus:ring-green-500 focus:border-green-300 sm:text-sm sm:leading-5" />
						</div>
					</div>

					<div class="mt-6">
						<label for="password" class="block text-sm font-medium leading-5 text-gray-700">
							Password
						</label>

						<div class="mt-1 rounded-md shadow-sm">
							<input v-model="form.password" id="password" type="password" required class="block w-full px-3 py-2 placeholder-gray-400 transition duration-150 ease-in-out border border-gray-300 rounded-md appearance-none focus:outline-none focus:ring-green-500 focus:border-green-300 sm:text-sm sm:leading-5" />
						</div>
					</div>

					<div class="mt-6">
						<span class="block w-full rounded-md shadow-sm">
							<button type="submit" class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white transition duration-150 ease-in-out bg-green-600 border border-transparent rounded-md hover:bg-green-500 focus:outline-none focus:border-green-700 focus:ring-green-500 active:bg-green-700">
								<div>
									Sign in
								</div>
							</button>
						</span>
						<!-- <NuxtLink to="/register" class="flex justify-end text-sm font-semibold text-green-500 hover:text-green-700">
							Register
						</NuxtLink> -->
					</div>
				</form>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	name: "login",
	data() {
		return {
			form: {
				email: "",
				password: "",
			},
		};
	},
	methods: {
		async login() {
			try {
				await this.$auth.loginWith("laravelSanctum", {
					data: this.form,
				});
				this.$router.push("/");
			} catch (error) {
				console.log("gagal " + error);
			}
		},
	},
};
</script>
