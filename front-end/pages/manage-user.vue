<template>
	<div class="margin-auth">
		<div class="card">
			<div class="grid grid-cols-12 gap-4">
				<div class="relative col-span-12 my-4 md:col-span-6">
					<input type="search" class="w-full py-2 pl-8 pr-4 text-sm font-medium text-gray-800 border border-gray-200 rounded-lg shadow md:w-3/5 focus:outline-2 focus:outline-blue-100 focus:ring-2 focus:ring-blue-300" placeholder="Search..." v-model="search" @keyup="searchData" />
					<div class="absolute top-0 left-0 inline-flex items-center px-2 py-3">
						<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
							<rect x="0" y="0" width="24" height="24" stroke="none"></rect>
							<circle cx="10" cy="10" r="7" />
							<line x1="21" y1="21" x2="15" y2="15" />
						</svg>
					</div>
				</div>
				<div class="flex items-end justify-between col-span-12 space-x-4 md:justify-end md:col-span-6">
					<div class="flex flex-col col-span-6">
						<span class="mb-2 text-xs font-semibold md:text-right"> Items Per Page : </span>
						<select @change="onChangeRecordsPerPage" v-model="show" class="px-2 py-2 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg shadow focus:outline-2 focus:outline-blue-100 focus:ring-2 focus:ring-blue-300">
							<option :value="5">5</option>
							<option :value="10">10</option>
							<option :value="25">25</option>
							<option :value="50">50</option>
						</select>
					</div>
					<div class="flex flex-col col-span-6">
						<span class="mb-2 text-xs font-semibold md:text-right"> Role : </span>
						<select class="px-2 py-2 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg shadow focus:outline-2 focus:outline-blue-100 focus:ring-2 focus:ring-blue-300" v-model="selectedRole" @change="onChangeRole">
							<option value="">Role</option>
							<option :value="item.id_role" v-for="item in role" v-text="item.nama_role"></option>
						</select>
					</div>
					<div class="flex self-end col-span-6">
						<button class="btn-with-icon btn btn-sm btn-blue" @click="modal = !modal">
							<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
							</svg>
							Tambah User
						</button>
					</div>
				</div>
			</div>
			<div class="my-4 overflow-x-auto overflow-y-auto bg-white rounded-lg shadow">
				<table class="table table-auto table-produk">
					<thead class="bg-gray-100">
						<tr class="text-left text-gray-800 font-title">
							<th class="p-3">No</th>
							<th class="p-3">Nama</th>
							<th class="p-3">Email</th>
							<th class="p-3">Role</th>
							<th class="p-3">Tgl Mendaftar</th>
							<th class="p-3"></th>
						</tr>
					</thead>
					<template v-if="user.data && user.data.length > 0">
						<tbody class="divide-y-2 divide-gray-100 divide-dotted">
							<tr class="text-sm" v-for="(item, index) in user.data">
								<td class="p-3">{{ index + 1 }}</td>
								<td class="p-3">{{ item.nama_user }}</td>
								<td class="p-3">{{ item.email }}</td>
								<td class="p-3">{{ item.role.nama_role }}</td>
								<td class="p-3">
									{{ $moment(item.tgl_register).format("DD MMMM YYYY") }}
								</td>
								<td class="p-2">
									<button class="btn btn-sm btn-indigo" @click.prevent="editUser(item)">
										<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
											<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
										</svg>
									</button>
									<button class="btn btn-sm btn-red" @click.prevent="deleteUser(item.id_user)">
										<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
											<path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
										</svg>
									</button>
								</td>
							</tr>
						</tbody>
					</template>

					<template v-else>
						<tbody class="divide-y-2 divide-gray-100 divide-dotted">
							<tr class="text-sm">
								<td colspan="6">
									<div class="flex items-center justify-center gap-4 py-20 font-semibold text-center">
										<svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
											<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
										</svg>
										<span class="text-2xl"> Tidak Ada Data </span>
									</div>
								</td>
							</tr>
						</tbody>
					</template>
				</table>
				<div class="py-2 mt-2 mb-6">
					<Pagination v-if="user.data && user.per_page < user.total" :total-pages="user.links.length - 2" :per-page="size" :current-page="user.current_page" @pagechanged="onPageChange"></Pagination>
				</div>
			</div>

			<!-- Modal Tambah Produk -->
			<Modal size="max-w-2xl" :title="!updateMode ? 'Tambah User' : 'Edit User'" @close-modal="closeModal" v-show="modal">
				<template #content>
					<div class="col-span-12 sm:col-span-4">
						<div class="my-2">
							<label class="label">Nama User</label>
							<input type="text" class="form-input form-input-lg" placeholder="Nama User ... " v-model="user_form.nama_user" />
							<p class="mt-2 text-xs text-red-500" v-if="validation.nama_user">{{ validation.nama_user[0] }}</p>
						</div>
					</div>
					<div class="col-span-12 sm:col-span-4">
						<div class="my-2">
							<label class="label">Email</label>
							<input type="email" class="form-input form-input-lg" placeholder="Email ... " v-model="user_form.email" />
							<p class="mt-2 text-xs text-red-500" v-if="validation.email">{{ validation.email[0] }}</p>
						</div>
					</div>
					<div class="col-span-12 sm:col-span-4">
						<div class="my-2">
							<label class="label">Role</label>
							<select class="form-input form-input-lg" v-model="user_form.role">
								<option value="">Role</option>
								<option :value="item.id_role" v-for="item in role" v-text="item.nama_role"></option>
							</select>
							<p class="mt-2 text-xs text-red-500" v-if="validation.role">{{ validation.role[0] }}</p>
						</div>
					</div>
				</template>
				<template #submit>
					<button v-if="!updateMode" class="btn btn-lg btn-green" @click="addUser">Submit</button>
					<button v-else class="btn btn-lg btn-indigo" @click="updateUser">Update</button>
				</template>
			</Modal>
		</div>
	</div>
</template>

<script>
export default {
	name: "manage-user",
	layout: "auth",
	data() {
		return {
			search: "",
			show: 10,
			selectedRole: "",
			page: 1,

			modal: false,
			updateMode: false,

			id_user: "null",
			user_form: {
				nama_user: "",
				email: "",
				role: "",
			},
			validation: [],
		};
	},
	created() {
		this.getData();
	},
	computed: {
		user() {
			return this.$store.state.user.data;
		},
		role() {
			return this.$store.state.user.role;
		},
	},
	methods: {
		async getData() {
			this.$store.dispatch("user/fetchUser", {
				search: this.search,
				show: this.show,
				page: this.page,
				role: this.selectedRole,
			});

			this.$store.dispatch("user/fetchRole");
		},
		closeModal() {
			this.user_form = {
				nama_user: "",
				email: "",
				role: "",
			};
			this.id_user = "";
			this.modal = false;
			this.updateMode = false;
			this.validation = [];
		},
		async addUser() {
			await this.$axios
				.post("/api/user", this.user_form)
				.then(() => this.closeModal())
				.then(() => this.getData())
				.then(() => this.$swal.fire("Success!", "Data Berhasil Ditambahkan.", "success"))
				.catch((error) => (this.validation = error.response.data));
		},
		editUser(data) {
			this.updateMode = true;
			this.modal = true;

			this.id_user = data.id_user;
			this.user_form = {
				nama_user: data.name,
				email: data.email,
				role: data.id_role,
			};
		},

		async updateUser() {
			this.$axios
				.patch("/api/user/" + this.id_user, this.user_form)
				.then(() => {
					this.closeModal();
					this.getData();
				})
				.then(() => {
					this.$swal.fire({
						icon: "success",
						title: "Success...",
						text: "Data Berhasil diubah!",
						showConfirmButton: false,
						timer: 1500,
						timerProgressBar: true,
					});
				})
				.catch((error) => (this.validation = error.response.data));
		},
		deleteUser(id) {
			this.$swal
				.fire({
					title: "Are you sure?",
					text: "Data yang dihapus tidak dapat dikembalikan!",
					icon: "warning",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Yes, delete it!",
				})
				.then((result) => {
					if (result.isConfirmed) {
						this.$axios
							.delete("api/user/" + id)
							.then(() => this.getData())
							.then(() => this.$swal.fire("Deleted!", "Data Berhasil dihapus.", "success"))
							.catch((error) => console.log(error));
					}
				});
		},
		searchData() {
			this.getData();
		},
		onChangeRecordsPerPage() {
			this.getData();
		},
		onPageChange(page) {
			this.page = page;
			this.getData();
		},
		onChangeRole() {
			this.getData();
		},
	},
};
</script>
