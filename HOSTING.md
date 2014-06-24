Hosting a LMMA HTTP Mod server
==============================
Setting up the server
---------------------
First of all, you need a HTTPD server with PHP and MySQL support.
When you have one, follow these steps:

1. Clone the "server" directory from the repository into your preferred directory. This can be any directory that is available through HTTP.
2. Get the phpQuery library and place it in a directory called "include".
3. Create a MySQL database called 3591_other.
4. Run template.sql in your MySQL database.
5. Create a file called mysqlconn.php and paste the following there:

		mysql_connect("localhost", "(your db username)", "(your db password)");
		mysql_select_db("3591_other");
6. Your server should now be working fine.

Setting up the script
---------------------
You will now have to configure the install.lua script to work fine.

1. Open http.lua located in the "client" directory
2. Change line 5 to the following:

		local url = "(your HTTP server address here):(your HTTP server port here)/(your server files directory here)"
3. Create a new place
4. Install the original mod in the place using the [installer plugin](http://www.roblox.com/LMM-Admin-HTTP-Mod-installer-plugin-item?id=162639709).
5. Open the HttpScript (located in the workspace) and paste the contents of http.lua there.
6. Select the following items in the workspace:
	* LuaModelMaker's Admin
	* LuaModelMaker's Admin Settings
	* Prints
	* HttpScript
	* CanLMMStart
7. Publish them to ROBLOX as a model.
8. Open up installerplugin.lua located in the directory "install".
9. Change line 16 to the following:

		local a = game:GetService("InsertService"):LoadAsset([Your model ID here])
10. Create a new script in the workspace.
11. Paste the contents of installerplugin.lua there.
12. Right-click the script and publish it to ROBLOX as a model.