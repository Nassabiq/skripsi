<template>
	<div class="margin-auth">
		<p class="text-xl font-semibold text-blue-600">Pencatatan Transaksi</p>

		<div class="grid grid-cols-12 gap-4 md:grid-flow-col md:grid-rows-2">
			<div class="col-span-12 md:col-span-7">
				<div class="grid grid-cols-12 gap-4 card">
					<div class="col-span-12 sm:col-span-6">
						<label class="label">Id Transaksi</label>
						<input type="text" class="form-input form-input-sm" />
					</div>
					<div class="col-span-12 sm:col-span-6">
						<label class="label">Nama Pemesan</label>
						<input type="text" class="form-input form-input-sm" />
					</div>
					<div class="col-span-12 sm:col-span-6">
						<label class="label">No. Telp</label>
						<input type="text" class="form-input form-input-sm" />
					</div>
					<div class="col-span-12 sm:col-span-6">
						<label class="label">Tgl Transaksi</label>
						<input type="text" class="form-input form-input-sm" />
					</div>
					<div class="col-span-12 sm:col-span-12">
						<label for="about" class="label"> Alamat </label>
						<div class="mt-1">
							<textarea id="about" name="about" rows="3" class="form-input form-input-sm" placeholder="you@example.com"></textarea>
						</div>
					</div>
				</div>
			</div>

			<div class="col-span-12 md:col-span-7">
				<div class="grid grid-cols-12 gap-2 card">
					<div class="col-span-12 md:col-span-8">
						<label class="label">Produk</label>
						<select class="form-input form-input-sm">
							<option>United States</option>
							<option>Canada</option>
							<option>Mexico</option>
						</select>
					</div>
					<div class="col-span-12 sm:col-span-4">
						<label class="label">Qty</label>
						<input type="text" class="form-input form-input-sm" />
					</div>
					<div class="col-span-12 md:col-span-6">
						<label class="label">Ukuran</label>
						<input type="text" class="form-input form-input-sm" />
					</div>
					<div class="col-span-12 md:col-span-6">
						<label class="label">Jenis Bahan</label>
						<select class="form-input form-input-sm">
							<option>United States</option>
							<option>Canada</option>
							<option>Mexico</option>
						</select>
					</div>
					<div class="col-span-12 md:col-span-6">
						<label class="label">Finishing</label>
						<input type="text" class="form-input form-input-sm" />
					</div>
					<div class="col-span-12 md:col-span-6">
						<label class="label">Laminasi</label>
						<input type="text" class="form-input form-input-sm" />
					</div>
					<div class="col-span-12 place-self-end">
						<button class="btn btn-with-icon btn-blue btn-sm">
							<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
							</svg>
							<p class="text-xs">Tambah</p>
						</button>
					</div>
				</div>
			</div>

			<div class="relative col-span-12 md:col-span-5 md:row-span-2 card">
				<p class="font-semibold">Detail Pesanan</p>

				<div class="flex items-center justify-between gap-2">
					<div class="p-2 my-2 border-2 border-green-400 rounded-md shadow-md bg-green-50 basis-11/12">
						<div class="flex items-center justify-between space-x-2">
							<div class="w-1/5 bg-gray-300 aspect-square basis-1/5"></div>
							<div class="detail-pesanan basis-3/5">
								<p class="font-semibold">Produk Terpilih</p>
								<p class="text-xs font-light tracking-wider">
									Jenis Bahan
								</p>
							</div>
							<div class="text-right price justify-items-end basis-1/5">
								<p class="text-xs font-light tracking-wider">
									2pcs
								</p>
								<p class="text-xs font-semibold">
									Rp. 2.000.000
								</p>
							</div>
						</div>
					</div>
					<div class="space-y-2 basis-1/12">
						<div class="hover:animate-bounce">
							<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 hover:text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
							</svg>
						</div>
						<div class="hover:animate-bounce">
							<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
							</svg>
						</div>
					</div>
				</div>

				<div class="absolute inset-x-0 bottom-0 mx-4">
					<div class="">
						<label class="flex justify-end label">Jumlah Bayar</label>
						<input type="text" class="form-input form-input-lg" placeholder="Jumlah Bayar..." />
					</div>
					<div class="p-2 my-2 border-2 border-green-400 rounded-md shadow-md bg-green-50">
						<div class="flex items-center justify-between">
							<p class="text-xs tracking-wider">Total</p>
							<p class="text-sm font-semibold">Rp.2.000.000</p>
						</div>
						<hr class="my-1 bg-green-600" />
						<div class="flex items-center justify-between">
							<p class="text-xs tracking-wider">Kembalian</p>
							<p class="text-sm font-semibold">Rp.2.000.000</p>
						</div>
					</div>

					<div class="flex justify-end p-2">
						<button class="btn btn-green btn-sm">
							<p class="text-xs">Proses Pesanan</p>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	name: "PencatatanTransaksi",
	layout: "auth",
	auth: false,
	data() {
		return {};
	},
	methods: {},
	computed: {},
};
</script>
