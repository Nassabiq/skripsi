export const state = () => ({
	data: [],
	role: [],
});

export const getters = {};

export const mutations = {
	SET_USER: (state, data) => {
		state.data = data;
	},
	SET_ROLE: (state, data) => {
		state.role = data;
	},
};

export const actions = {
	async fetchUser({commit}, {search, page, show, role}) {
		let data = await this.$axios.$get("/api/all-user?page=" + page + "&show=" + parseInt(show) + "&search=" + search + "&role=" + role);
		commit("SET_USER", data);
	},
	async fetchRole({commit}) {
		let data = await this.$axios.$get("/api/role");
		commit("SET_ROLE", data);
	},
};
