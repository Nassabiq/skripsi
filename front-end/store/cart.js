export const state = () => ({
	data: [],
});

export const getters = {};

export const mutations = {
	setCarts: (state, data) => {
		state.data = data;
	},
	deleteCart: (state, id) => {
		let index = state.data.findIndex((c) => c.id_cart == id);
		console.log(index);
		if (index > -1) {
			state.data.splice(index, 1);
		}
	},
};

export const actions = {
	async fetchCarts({commit}) {
		let data = await this.$axios.$get("/api/cart");
		commit("setCarts", data);
	},

	async removeCart({commit}, id) {
		this.$swal
			.fire({
				icon: "question",
				title: "Hapus data ini?",
				text: "Data yang sudah dihapus tidak dapat dikembalikan",
				width: 600,
				showCloseButton: true,
				showCancelButton: true,
				cancelButtonColor: "#d33",
				confirmButtonText: "Remove",
			})
			.then((result) => {
				/* Read more about isConfirmed, isDenied below */
				if (result.isConfirmed) {
					this.$axios
						.delete("api/cart/" + id)
						.then(() => commit("deleteCart", id))
						.then(() => this.$swal.fire("Deleted!", "Your file has been deleted.", "success"));
				}
			});
	},
};
