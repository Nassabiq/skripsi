// Manager Penjualan
export default function ({redirect, $auth}) {
	if ($auth.user.role.nama_role != "Manager Penjualan") redirect("/");
}
