// Manager IT
export default function ({redirect, $auth}) {
	if ($auth.user.role.nama_role != "Admin") redirect("/");
}
