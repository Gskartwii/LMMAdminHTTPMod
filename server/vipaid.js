function checkform() {
	if (!document.getElementById("newval").value.match(/^\d+$/))
		return false;
	return true;
}
document.write("The ID of your Admin Pass. Can be any number value.<br />");