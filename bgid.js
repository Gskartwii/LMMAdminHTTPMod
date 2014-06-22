function checkform() {
	if (!document.getElementById("newval").value.match(/^\d+$/))
		return false;
	return true;
}
document.write("The ID of the badge that should give the owner member admin. Can be any number value.<br />");