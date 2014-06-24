function checkform() {
	var val = document.getElementById("newval").value;
	if (val === "true" || val === "false") return true;
	return false;
}
document.write("Determines if the chat log should be public or not. Can be either \"true\" or \"false\".<br />");