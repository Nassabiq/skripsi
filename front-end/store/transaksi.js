export const state = () => ({
	data: [],
});

export const getters = {};

export const mutations = {
	setTransaksi: (state, data) => {
		state.data = data;
	},
};

export const actions = {
	async fetchTransaksi({commit}, {id, status_pembayaran, status_pesanan}) {
		let data = await this.$axios.$get("/api/transaksi?user=" + id + "&pembayaran=" + status_pembayaran + "&pesanan=" + status_pesanan);
		commit("setTransaksi", data);
	},
};
