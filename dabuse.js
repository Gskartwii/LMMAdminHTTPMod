function checkform() {
	var val = document.getElementById("newval").value;
	if (val === "true" || val === "false") return true;
	return false;
}
document.write("Determines if abusive commands should be disabled. Can be either \"true\" or \"false\".<br />");