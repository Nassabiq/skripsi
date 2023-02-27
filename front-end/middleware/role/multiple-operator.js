// Marketing
export default function ({redirect, $auth}) {
	const role = ["Marketing", "Operator Produksi", "Operator Finishing", "Admin"];
	const role_name = $auth.user.role.nama_role;

	if (!role.includes(role_name)) redirect("/");
}
