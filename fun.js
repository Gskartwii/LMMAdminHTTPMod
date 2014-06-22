function checkform() {
	var val = document.getElementById("newval").value;
	if (val === "true" || val === "false") return true;
	return false;
}
document.write("Determines if fun commands are enabled. Can be either \"true\" or \"false\".<br />");