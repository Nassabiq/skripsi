<template>
	<div class="auth-template">
		<div class="grid grid-cols-12 gap-4 md:place-items-stretch">
			<div class="col-span-12 md:col-span-7 md:place-self-center">
				<div class="mx-auto sm:w-full sm:max-w-md">
					<img src="~/assets/img/logo.png" class="w-80 h-80" alt="" srcset="" />
					<!-- <h2 class="mt-6 font-serif text-4xl font-extrabold leading-9 text-center text-gray-900 uppercase">Registration</h2> -->
				</div>
			</div>
			<div class="col-span-12 md:col-span-5">
				<div class="w-full max-w-md mx-auto">
					<div class="px-4 py-8 bg-white rounded-lg shadow sm:px-8">
						<form @submit.prevent="register">
							<div>
								<label for="name" class="block text-sm font-medium leading-5 text-gray-700"> Nama </label>
								<div class="mt-1 rounded-md shadow-sm">
									<input id="name" type="text" v-model="name" class="block w-full px-3 py-2 placeholder-gray-400 transition duration-150 ease-in-out border border-gray-300 rounded-md appearance-none focus:outline-none focus:ring-green-500 focus:border-green-300 sm:text-sm sm:leading-5" />
								</div>
								<p class="mt-2 text-xs text-red-500" v-if="validation.nama_user">{{ validation.nama_user[0] }}</p>
							</div>

							<div class="mt-6">
								<label for="email" class="block text-sm font-medium leading-5 text-gray-700"> Email address </label>
								<div class="mt-1 rounded-md shadow-sm">
									<input id="email" type="email" v-model="email" class="block w-full px-3 py-2 placeholder-gray-400 transition duration-150 ease-in-out border border-gray-300 rounded-md appearance-none focus:outline-none focus:ring-green-500 focus:border-green-300 sm:text-sm sm:leading-5" />
								</div>
								<p class="mt-2 text-xs text-red-500" v-if="validation.email">{{ validation.email[0] }}</p>
							</div>

							<div class="mt-6">
								<label for="password" class="block text-sm font-medium leading-5 text-gray-700"> Password </label>
								<div class="mt-1 rounded-md shadow-sm">
									<input id="password" type="password" v-model="password" class="block w-full px-3 py-2 placeholder-gray-400 transition duration-150 ease-in-out border border-gray-300 rounded-md appearance-none focus:outline-none focus:ring-green-500 focus:border-green-300 sm:text-sm sm:leading-5" />
								</div>
								<p class="mt-2 text-xs text-red-500" v-if="validation.password">{{ validation.password[0] }}</p>
							</div>

							<div class="mt-6">
								<label for="password_confirmation" class="block text-sm font-medium leading-5 text-gray-700"> Confirm Password </label>
								<div class="mt-1 rounded-md shadow-sm">
									<input id="password_confirmation" type="password" v-model="password_confirmation" class="block w-full px-3 py-2 placeholder-gray-400 transition duration-150 ease-in-out border border-gray-300 rounded-md appearance-none focus:outline-none focus:ring-green-500 focus:border-green-300 sm:text-sm sm:leading-5" />
								</div>
								<p class="mt-1 text-xs text-red-500" v-if="validation.length > 0">{{ validation[0].password }}</p>
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
			// e.preventDefault();
			if (this.password !== this.password_confirmation) {
				this.validation.push({password: "Password konfirmasi tidak sesuai"});
			} else {
				await this.$axios
					.post("/api/register", {
						nama_user: this.name,
						email: this.email,
						password: this.password,
					})
					.then(() => {
						this.$router.push("/login");
					})
					.then(() => {
						// this.$router.push("/login");
						this.$swal.fire({
							icon: "success",
							showConfirmButton: false,
							text: "Registrasi Sukses, Silahkan Login",
							timer: 2000,
							timerProgressBar: true,
						});
					})
					.catch((error) => {
						this.validation = error.response.data;
					});
			}
		},
	},
};
</script>
