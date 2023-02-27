// Marketing
export default function ({redirect, $auth}) {
	const role = ["Admin", "Manager Inventory", "Manager Penjualan"];
	const role_name = $auth.user.role.nama_role;

	if (!role.includes(role_name)) redirect("/");
}
