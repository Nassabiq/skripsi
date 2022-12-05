export const state = () => ({
	data: [],
	allData: [],
	detailData: [],
});

export const getters = {};

export const mutations = {
	setTransaksi: (state, data) => {
		state.data = data;
	},
	setAllTransaksi: (state, data) => {
		state.allData = data;
	},
	setDetailTransaksi: (state, data) => {
		state.detailData = data;
	},
};

export const actions = {
	async fetchTransaksi({commit}, {id, status_pembayaran, status_pesanan}) {
		let data = await this.$axios.$get("/api/transaksi?user=" + id + "&pembayaran=" + status_pembayaran + "&status_pesanan=" + status_pesanan);
		commit("setTransaksi", data);
	},
	async fetchAllTransaksi({commit}, {page, show, search, status_pesanan}) {
		let data = await this.$axios.$get("/api/transaksi/all?page=" + page + "&show=" + show + "&search=" + search + "&status_pesanan=" + status_pesanan);
		commit("setAllTransaksi", data);
	},
	async fetchDetailTransaksi({commit}, id) {
		let data = await this.$axios.$get("/api/transaksi/" + id);
		commit("setDetailTransaksi", data);
	},
};
