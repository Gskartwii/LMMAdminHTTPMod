function checkform() {
	var val = document.getElementById("newval").value;
	if (val === "true" || val === "false") return true;
	return false;
}
document.write("Determines if the admin menu is enabled. Can be either \"true\" or \"false\".<br />");