<template>
	<div class="auth-template">
		<div class="grid grid-cols-12 gap-4 md:place-items-stretch">
			<div class="col-span-12 md:col-span-7 md:place-self-center">
				<div class="mx-auto sm:w-full sm:max-w-md">
					<h2 class="mt-6 font-serif text-4xl font-extrabold leading-9 text-center text-gray-900 uppercase">Registration</h2>
					<!-- {{ validation }} -->
				</div>
			</div>
			<div class="col-span-12 md:col-span-5">
				<div class="w-full max-w-md mx-auto">
					<div class="px-4 py-8 bg-white rounded-lg shadow sm:px-8">
						<form @submit.prevent="register">
							<div>
								<label for="name" class="block text-sm font-medium leading-5 text-gray-700"> Nama </label>
								<div class="mt-1 rounded-md shadow-sm">
									<input id="name" type="text" autofocus v-model="name" class="block w-full px-3 py-2 placeholder-gray-400 transition duration-150 ease-in-out border border-gray-300 rounded-md appearance-none focus:outline-none focus:ring-green-500 focus:border-green-300 sm:text-sm sm:leading-5" />
								</div>
							</div>

							<div class="mt-6">
								<label for="email" class="block text-sm font-medium leading-5 text-gray-700"> Email address </label>
								<div class="mt-1 rounded-md shadow-sm">
									<input id="email" type="email" v-model="email" class="block w-full px-3 py-2 placeholder-gray-400 transition duration-150 ease-in-out border border-gray-300 rounded-md appearance-none focus:outline-none focus:ring-green-500 focus:border-green-300 sm:text-sm sm:leading-5" />
								</div>
							</div>

							<div class="mt-6">
								<label for="password" class="block text-sm font-medium leading-5 text-gray-700"> Password </label>
								<div class="mt-1 rounded-md shadow-sm">
									<input id="password" type="password" required v-model="password" class="block w-full px-3 py-2 placeholder-gray-400 transition duration-150 ease-in-out border border-gray-300 rounded-md appearance-none focus:outline-none focus:ring-green-500 focus:border-green-300 sm:text-sm sm:leading-5" />
								</div>
							</div>

							<div class="mt-6">
								<label for="password_confirmation" class="block text-sm font-medium leading-5 text-gray-700"> Confirm Password </label>

								<div class="mt-1 rounded-md shadow-sm">
									<input id="password_confirmation" type="password" v-model="password_confirmation" required class="block w-full px-3 py-2 placeholder-gray-400 transition duration-150 ease-in-out border border-gray-300 rounded-md appearance-none focus:outline-none focus:ring-green-500 focus:border-green-300 sm:text-sm sm:leading-5" />
								</div>
							</div>

							<div class="mt-6">
								<span class="block w-full rounded-md shadow-sm">
									<button type="submit" class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white transition duration-150 ease-in-out bg-green-600 border border-transparent rounded-md hover:bg-green-500 focus:outline-none focus:border-green-700 focus:ring-indigo active:bg-green-700">Register</button>
								</span>
							</div>
							<p class="mt-2 text-sm leading-5 text-center text-gray-600 max-w">
								atau
								<NuxtLink to="/login" class="font-medium text-green-600 transition duration-150 ease-in-out hover:text-green-500 focus:outline-none focus:underline"> Login Disini </NuxtLink>
							</p>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import axios from "axios";
export default {
	name: "register",
	auth: false,
	data() {
		return {
			name: "",
			email: "",
			password: "",
			password_confirmation: "",

			validation: [],
		};
	},
	methods: {
		async register() {
			e.preventDefault();
			if (this.password !== this.password_confirmation) {
				this.validation.push({password: "Password konfirmasi tidak sesuai"});
			}
			await this.$axios
				.post("/api/register", {
					name: this.name,
					email: this.email,
					password: this.password,
				})
				.then(() => {
					// console.log("success");
					this.$router.push("/");
				})
				.catch((error) => {
					this.validation = error.response.data;
				});
		},
	},
};
</script>
