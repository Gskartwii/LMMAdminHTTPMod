while true do
	wait()
	if workspace.CanContinue.Value==1 then break end
end
local pid=game.PlaceId
local owners,admins,members,banned,crashed,muted={}

--[[function printConsole(...)
	coroutine.wrap(function()
		local toPrint=""
		for _,v in pairs(arg) do
			toPrint=toPrint..tostring(v)
			print("GotArg")
		end
		print("Might print")
		print(toPrint)
		workspace.ToPrint.Value=workspace.ToPrint.Value..toPrint.."\n"
	end)
end]]--

function printConsole(str)
	print(str)
	workspace.ToPrint.Value=workspace.ToPrint.Value..str.."\n"
end

function printErrorConsole(msg,stack,scr)
	printConsole("Error in "..scr:GetFullName())
	printConsole("Error: "..msg)
	printConsole("Stack trace: "..stack)
end

game:service("ScriptContext").Error:connect(printErrorConsole)

function HttpReq(address)
	printConsole("Doing request: "..address)
	local rep=game.HttpService:GetAsync(address)
	printConsole("Got reply: "..rep)
	return rep
end
--printConsole("derp")
--printConsole("derp2")
function GenerateRankTables()
	coroutine.wrap(function() 
		local SettingsModule = nil
		--local Settings=workspace:FindFirstChild("LuaModelMaker's Admin Settings")
		--if Settings then SettingsModule = require(Settings) else SettingsModule = {} print("Couldn't require!") end
		SettingsModule=_G.SettingsModule
		local Ranks=SettingsModule.Ranks
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

GenerateRankTables()
--[[for _,v in pairs(owners) do
	print(v)
end]]
sid=HttpReq("http://gskartwii.arkku.net/roblox/getsid?pid="..pid)

for _,v in pairs(owners) do
	HttpReq("http://gskartwii.arkku.net/roblox/adminadd.php?name="..v.."&rank=owner&pid="..pid)
end
for _,v in pairs(admins) do
	HttpReq("http://gskartwii.arkku.net/roblox/adminadd.php?name="..v.."&rank=admin&pid="..pid)
end
for _,v in pairs(members) do
	HttpReq("http://gskartwii.arkku.net/roblox/adminadd.php?name="..v.."&rank=member&pid="..pid)
end
for _,v in pairs(banned) do
	HttpReq("http://gskartwii.arkku.net/roblox/adminadd.php?name="..v.."&rank=bans&pid="..pid)
end
for _,v in pairs(crashed) do
	HttpReq("http://gskartwii.arkku.net/roblox/adminadd.php?name="..v.."&rank=crashes&pid="..pid)
end
for _,v in pairs(muted) do
	HttpReq("http://gskartwii.arkku.net/roblox/adminadd.php?name="..v.."&rank=mutes&pid="..pid)
end
game.Players.PlayerAdded:connect(function(Player)
	HttpReq("http://gskartwii.arkku.net/roblox/loggin?action=join&username="..Player.Name.."&sid="..sid.."&plrlist="..GeneratePlayerList().."&pid="..pid)
end)
game.Players.PlayerRemoving:connect(function(Player)
	HttpReq("http://gskartwii.arkku.net/roblox/loggin?username="..Player.Name.."&action=leave&sid="..sid.."&plrlist="..GeneratePlayerList(Player.Name).."&pid="..pid)
end)
game.OnClose=function()
	HttpReq("http://gskartwii.arkku.net/roblox/loggin?action=kill&username=null&sid="..sid.."&pid="..pid)
end