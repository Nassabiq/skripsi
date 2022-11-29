// Manager Inventory
export default function ({redirect, $auth}) {
	if ($auth.user.role.nama_role != "Manager Inventory") redirect("/");
}
