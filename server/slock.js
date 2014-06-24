function checkform() {
	var val = document.getElementById("newval").value;
	if (val === "true" || val === "false") return true;
	return false;
}
document.write("Determines if the server is locked or not. Server lock means only admins can enter it. Can be either \"true\" or \"false\".<br />");