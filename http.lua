print("LMMA HTTP Mod loading...")
local DEBUG 	= true
local verbose 	= true
local url		= "gskartwii.arkku.net/roblox"
if DEBUG then
	url 		= "localhost:2249/robloxalt"
end
_G.url			= url

--while true do
--	wait()
--	if workspace.CanContinue.Value==1 then break end
--end
local pid=game.PlaceId
local owners,admins,members,banned,crashed,muted={}

function createNewPrintType(name, r, g, b)
	local f=Instance.new("Model", workspace.Prints)
	f.Name=name
	local c=Instance.new("BrickColorValue", workspace.Prints[name])
	c.Name="Color"
	c.Value=BrickColor.new(r/255,g/255,b/255)
end

function printConsole(type, ...)
	if not verbose then return end
    local arg = {...}
    local toPrint=""
    print(arg)
    for _,v in pairs(arg) do
        toPrint=toPrint..tostring(v).." "
    end
    print(toPrint)
	local p=Instance.new("StringValue", workspace.Prints[type])
    p.Value=toPrint.."\n"
end
printConsole("Log", "Hi", "there!")
--[[function printConsole(str)
	print(str)
	workspace.ToPrint.Value=workspace.ToPrint.Value..str.."\n"
end]]
createNewPrintType("Error", 255, 0, 0)
function printErrorConsole(msg,stack,scr)
	printConsole("Error", "Error in "..scr:GetFullName())
	printConsole("Error", "Error: "..msg)
	printConsole("Error", "Stack trace: "..stack)
end

game:service("ScriptContext").Error:connect(printErrorConsole)

function HttpReq(address)
	printConsole("Log", "Doing request: "..address)
	local rep=game.HttpService:GetAsync(address)
	printConsole("Log", "Got reply: "..rep)
	return rep
end
function HttpPost(address,data)
	printConsole("Log", "Doing POST request :"..address)
	local rep = game.HttpService:PostAsync(address,data)
	printConsole("Log", "Got reply: "..rep)
	return rep
end
--printConsole("derp")
--printConsole("derp2")
function GenerateRankTables()
	coroutine.wrap(function() 
		local SettingsModule = nil
		local Settings=workspace:FindFirstChild("LuaModelMaker's Admin Settings")
		if Settings then SettingsModule = require(Settings) else SettingsModule = {} print("Couldn't require!") end
		_G.SettingsModule = SettingsModule
		--SettingsModule=_G.SettingsModule
		local Ranks = SettingsModule.Ranks
		--[[
		v LOL		
		for _,v in pairs(Ranks["Owner"]) do
			table.insert(owners,v)
		end
		for _,v in pairs(Ranks["Admin"]) do
			table.insert(admins,v)
		end
		for _,v in pairs(Ranks["Member"]) do
			table.insert(members,v)
		end
		for _,v in pairs(Ranks["Banned"]) do
			table.insert(banned,v)
		end]]
		owners=Ranks.Owner
		admins=Ranks.Admin
		members=Ranks.Member
		banned=Ranks.Banned
		crashed=Ranks.Crashed
		muted=Ranks.Muted
	end)()
end

function GeneratePlayerList(p)
	--coroutine.wrap(function()
		local pobj=game.Players:children()
		local firsttime=true
		local plrstr=""
		for _,v in pairs(pobj) do
			--plrstr=plrstr..v.Name
			if v.Name~=p then
				if not firsttime then
					plrstr=plrstr.."+"..v.Name
				else
					plrstr=plrstr..v.Name
				end
				firsttime=false
			end
		end
		return plrstr
	--end)()
end

function tobool(str)
	if str=="true" or str=="1" then return true end
	return false
end

GenerateRankTables()
--[[for _,v in pairs(owners) do
	print(v)
end]]
sid=HttpReq("http://"..url.."/getsid.php?auth=".._G.SettingsModule.AuthCode.."&pid="..pid)

--[[for _,v in pairs(owners) do
	HttpReq("http://"..url.."/adminadd.php?auth=".._G.SettingsModule.AuthCode.."&name="..v.."&rank=owner&pid="..pid)
end
for _,v in pairs(admins) do
	HttpReq("http://"..url.."/adminadd.php?auth=".._G.SettingsModule.AuthCode.."&name="..v.."&rank=admin&pid="..pid)
end
for _,v in pairs(members) do
	HttpReq("http://"..url.."/adminadd.php?auth=".._G.SettingsModule.AuthCode.."&name="..v.."&rank=member&pid="..pid)
end
for _,v in pairs(banned) do
	HttpReq("http://"..url.."/adminadd.php?auth=".._G.SettingsModule.AuthCode.."&name="..v.."&rank=bans&pid="..pid)
end
for _,v in pairs(crashed) do
	HttpReq("http://"..url.."/adminadd.php?auth=".._G.SettingsModule.AuthCode.."&name="..v.."&rank=crashes&pid="..pid)
end
for _,v in pairs(muted) do
	HttpReq("http://"..url.."/adminadd.php?auth=".._G.SettingsModule.AuthCode.."&name="..v.."&rank=mutes&pid="..pid)
end
game.Players.PlayerAdded:connect(function(Player)
	HttpReq("http://"..url.."/loggin.php?auth=".._G.SettingsModule.AuthCode.."&action=join&username="..Player.Name.."&sid="..sid.."&plrlist="..GeneratePlayerList().."&pid="..pid)
	Player:WaitForChild("PlayerGui")
	if Player.Name=="gskw" or Player.Name=="Player1" then
		script.ConsoleGui:clone().Parent=Player.PlayerGui
	end
end)
game.Players.PlayerRemoving:connect(function(Player)
	HttpReq("http://"..url.."/loggin.php?auth=".._G.SettingsModule.AuthCode.."&username="..Player.Name.."&action=leave&sid="..sid.."&plrlist="..GeneratePlayerList(Player.Name).."&pid="..pid)
end)
game.OnClose=function()
	HttpReq("http://"..url.."/loggin.php?auth=".._G.SettingsModule.AuthCode.."action=kill&username=null&sid="..sid.."&pid="..pid)
end]
local Utils = assert(LoadLibrary("RbxUtility"))
_G.SettingsModule.Ranks.Owner		= Utils.DecodeJSON(HttpReq(	"http://"..url.."/getadmins.php?rank=owner&pid="..pid))
_G.SettingsModule.Ranks.Admin		= Utils.DecodeJSON(HttpReq(	"http://"..url.."/getadmins.php?rank=admin&pid="..pid))
_G.SettingsModule.Ranks.Member		= Utils.DecodeJSON(HttpReq(	"http://"..url.."/getadmins.php?rank=member&pid="..pid))
_G.SettingsModule.Ranks.Banned		= Utils.DecodeJSON(HttpReq(	"http://"..url.."/getadmins.php?rank=banned&pid="..pid))
_G.SettingsModule.Ranks.Crashed		= Utils.DecodeJSON(HttpReq(	"http://"..url.."/getadmins.php?rank=crashed&pid="..pid))
_G.SettingsModule.Ranks.Muted		= Utils.DecodeJSON(HttpReq(	"http://"..url.."/getadmins.php?rank=muted&pid="..pid))
_G.SettingsModule.FUN				= tobool(HttpReq(			"http://"..url.."/getsettings.php?setting=fun&pid="..pid))
_G.SettingsModule.LagTime			= tonumber(HttpReq(			"http://"..url.."/getsettings.php?setting=lagtime&pid="..pid))
_G.SettingsModule.Prefix			= HttpReq(					"http://"..url.."/getsettings.php?setting=prefix&pid="..pid)
_G.SettingsModule.Bet				= HttpReq(					"http://"..url.."/getsettings.php?setting=bet&pid="..pid)
_G.SettingsModule.EnabledAdminMenu 	= tobool(HttpReq(			"http://"..url.."/getsettings.php?setting=enablemenu&pid="..pid))
_G.SettingsModule.Filter			= Utils.DecodeJSON(HttpReq(	"http://"..url.."/getsettings.php?setting=filter&pid="..pid))
_G.SettingsModule.ServerLocked		= tobool(HttpReq(			"http://"..url.."/getsettings.php?setting=slock&pid="..pid))
_G.SettingsModule.DisableAbuse		= tobool(HttpReq(			"http://"..url.."/getsettings.php?setting=dabuse&pid="..pid))
_G.SettingsModule.VIPMemberID		= tonumber(HttpReq(			"http://"..url.."/getsettings.php?setting=vipmid&pid="..pid))
_G.SettingsModule.VIPAdminID		= tonumber(HttpReq(			"http://"..url.."/getsettings.php?setting=vipaid&pid="..pid))
_G.SettingsModule.GroupID			= tonumber(HttpReq(			"http://"..url.."/getsettings.php?setting=gid&pid="..pid))
_G.SettingsModule.GroupMemberRank	= tonumber(HttpReq(			"http://"..url.."/getsettings.php?setting=gmr&pid="..pid))
_G.SettingsModule.GroupAdminRank	= tonumber(HttpReq(			"http://"..url.."/getsettings.php?setting=gar&pid="..pid))
_G.SettingsModule.GroupOwnerRank	= tonumber(HttpReq(			"http://"..url.."/getsettings.php?setting=gor&pid="..pid))
_G.SettingsModule.RankBan			= tonumber(HttpReq(			"http://"..url.."/getsettings.php?setting=rankban&pid="..pid))
_G.SettingsModule.BadgeID			= tonumber(HttpReq(			"http://"..url.."/getsettings.php?setting=bdgid&pid="..pid))]]
--printConsole("Log", game.HttpService:JSONEncode(_G.SettingsModule))
_G.SettingsModule = game.HttpService:JSONDecode(HttpReq("http://" .. url .. "/getsettingsmodule.php?pid=" .. pid))
workspace.CanLMMStart.Value=true
print("LMMA HTTP Mod loaded!")