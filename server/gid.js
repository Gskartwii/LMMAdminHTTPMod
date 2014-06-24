function checkform() {
	if (!document.getElementById("newval").value.match(/^\d+$/))
		return false;
	return true;
}
document.write("The ID of the group you want to use. Can be any number value.<br />");