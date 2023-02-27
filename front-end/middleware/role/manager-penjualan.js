// Manager Penjualan
export default function ({redirect, $auth}) {
	const role = ["Admin", "Manager Penjualan"];
	const role_name = $auth.user.role.nama_role;

	if (!role.includes(role_name)) redirect("/");
}
