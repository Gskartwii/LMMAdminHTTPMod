local p = PluginManager():CreatePlugin()
local t = p:CreateToolbar("LMM Admin HTTP Mod Installer")
local b = t:CreateButton("Install LMM Admin HTTP Mod", "WARNING: This will remove all your plugins, so back them up!", "")
b.Click:connect(function()
	print("NOW INSTALLING LMM ADMIN HTTP MOD") 
	local l = workspace:FindFirstChild("LuaModelMaker's Admin")
	local s = workspace:FindFirstChild("LuaModelMaker's Admin Settings")
	local pr = workspace:FindFirstChild("Prints")
	local h = workspace:FindFirstChild("HttpScript")
	local c = workspace:FindFirstChild("CanLMMStart")
	if l then print("Remove LMM Admin") l:Destroy() end
	if s then print("Remove LMM Admin Settings") s:Destroy() end
	if pr then print("Remove Prints") pr:Destroy end
	if h then print("Remove HttpScript") h:Destroy end
	if c then print("Remove CanLMMStart") c:Destroy end
	local a = game:GetService("InsertService"):LoadAsset(162478792)
	a.Name = "LMMMODEL"
	a.Parent = workspace
	for _,v in pairs(workspace.LMMMODEL:children()) do
		v.Parent = workspace
	end
	workspace.LMMMODEL:Destroy()
	print("INSTALLED")
end)