function checkform() {
	if (!document.getElementById("newval").value.match(/^\d+$/))
		return false;
	return true;
}
document.write("The rank ID of Admin in your group. Can be any number value.<br />");