export const state = () => ({
	data: [],
});

export const getters = {};

export const mutations = {
	setCarts: (state, data) => {
		state.data = data;
	},
};

export const actions = {
	async fetchCarts({commit}) {
		let data = await this.$axios.$get("/api/cart");
		commit("setCarts", data);
	},
};
