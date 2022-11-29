// Marketing
export default function ({redirect, $auth}) {
	if ($auth.user.role.nama_role != "Marketing") redirect("/");
}
