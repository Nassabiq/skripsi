// Operator Produksi
export default function ({redirect, $auth}) {
	if ($auth.user.role.nama_role != "Operator Produk") redirect("/");
}
