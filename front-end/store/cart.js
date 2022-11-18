export const state = () => ({
	cart: [],
});

export const getters = {};

export const mutations = {
	setCarts: (state, data) => {
		state.cart.push(...data);
	},
};

export const actions = {
	async fetchCarts() {
		let data = await this.$axios.$get("/api/cart");
		commit("setCarts", response);
		return data;
	},
};
