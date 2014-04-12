local owners,admins,members,banned,crashed,muted={}

function printConsole(...)
	coroutine.wrap(function()
		local toPrint=""
		for _,v in pairs(arg) do
			toPrint=toPrint..tostring(v)
			--print("GotArg")
		end
		--print("Might print")
		print(toPrint)
		workspace.ToPrint.Value=workspace.ToPrint.Value..toPrint.."\n"
	end)
end

printConsole("derp")
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

function GeneratePlayerList()
	--coroutine.wrap(function()
		local pobj=game.Players:children()
		local firsttime=true
		local plrstr=""
		for _,v in pairs(pobj) do
			plrstr=plrstr..v.Name
			if not firsttime then
				plrstr=plrstr.."+"
			end
			firsttime=false
		end
		return plrstr
	--end)()
end

GenerateRankTables()
--[[for _,v in pairs(owners) do
	print(v)
end]]
sid=game:service("HttpService"):GetAsync("http://gskartwii.arkku.net/roblox/getsid")
function SendMessage(Player, TitleText, BodyText, Time) coroutine.wrap(function()
	local SG = Instance.new("ScreenGui") SG.Name = "LuaMod".."".."elMaker's Admin Message"
	local Frame = Instance.new("Frame", SG) Frame.Name = "Message Frame" Frame.BackgroundColor3 = Color3.new(0.5,0.5,0.5) Frame.BackgroundTransparency = 0.5 Frame.Position = UDim2.new(0,0,1,0) Frame.Size = UDim2.new(1,0,0.5,0)
	local Title = Instance.new("TextLabel", Frame) Title.Name = "Title" Title.Text = TitleText Title.BackgroundTransparency = 1 Title.Size = UDim2.new(1,0,1,0) Title.Font = "ArialBold" Title.FontSize = "Size36" Title.TextColor3 = Color3.new(0,0,0) Title.TextStrokeColor3 = Color3.new(1,1,1) Title.TextStrokeTransparency = 0 Title.TextYAlignment = "Top" Title.TextWrapped = true
	local Body = Instance.new("TextLabel", Frame) Body.TextTransparency = 1 Body.Name = "Body" Body.Text = BodyText Body.BackgroundTransparency = 1 Body.Size = UDim2.new(1,0,1,0) Body.Font = "Arial" Body.FontSize = "Size24" Body.TextColor3 = Color3.new(0,0,0) Body.TextStrokeColor3 = Color3.new(1,1,1) Body.TextWrapped = true
	for _,Object in pairs(Player.PlayerGui:GetChildren()) do if Object.Name == "LuaMod".."elMaker".."'".."s Admin Message" then Object:Destroy() end end
	wait() SG.Parent = Player.PlayerGui wait()
	Frame:TweenPosition(UDim2.new(0,0,0.5,0), "In", "Sine", 0.5)
	wait(0.5)
	local Num,FadeIn,FadeOut = 1,nil,nil
	FadeIn = Run.Stepped:connect(function(Time, Step)
		Num = Num - 0.05
		Body.TextTransparency = Num
		Body.TextStrokeTransparency = Num
		if Num == 0 then
			FadeIn:disconnect()
		end
		wait(Step)
	end)
	wait(Time)
	Num = 0
	FadeOut = Run.Stepped:connect(function(Time, Step)
		Num = Num + 0.05
		Body.TextTransparency = Num
		Body.TextStrokeTransparency = Num
		if Num == 1 then
			FadeOut:disconnect()
		end
		wait(Step)
	end)
	wait(0.5)
	Frame:TweenPosition(UDim2.new(0,0,1,0), "Out", "Sine", 0.5)
	wait(0.5)
	SG:Destroy()
end)() end
function MessageEveryone(msg)
	for _,Player in pairs(game.Players:GetPlayers()) do
		SendMessage(Player, "SYSTEM MESSAGE", msg, 3)
	end
end

for _,v in pairs(owners) do
	retstr=game:service("HttpService"):GetAsync("http://gskartwii.arkku.net/roblox/adminadd.php?name="..v.."&rank=owner") print(v)
	if retstr then MessageEveryone(retstr) end
end
for _,v in pairs(admins) do
	retstr=game:service("HttpService"):GetAsync("http://gskartwii.arkku.net/roblox/adminadd.php?name="..v.."&rank=admin")
	if retstr then MessageEveryone(retstr) end
end
for _,v in pairs(members) do
	retstr=game:service("HttpService"):GetAsync("http://gskartwii.arkku.net/roblox/adminadd.php?name="..v.."&rank=member")
	if retstr then MessageEveryone(restr) end
end
for _,v in pairs(banned) do
	retstr=game:service("HttpService"):GetAsync("http://gskartwii.arkku.net/roblox/adminadd.php?name="..v.."&rank=bans")
	if retstr then MessageEveryone(retstr) end
end
for _,v in pairs(crashed) do
	retstr=game:service("HttpService"):GetAsync("http://gskartwii.arkku.net/roblox/adminadd.php?name="..v.."&rank=crashes")
	if retstr then MessageEveryone(retstr) end
end
for _,v in pairs(muted) do
	retstr=game:service("HttpService"):GetAsync("http://gskartwii.arkku.net/roblox/adminadd.php?name="..v.."&rank=mutes")
	if retstr then MessageEveryone(retstr) end
end
game.Players.PlayerAdded:connect(function(Player)
	game:service("HttpService"):GetAsync("http://gskartwii.arkku.net/roblox/loggin?action=join&username="..Player.Name.."&sid="..sid.."&plrlist="..GeneratePlayerList()) print("doing req ".."http://gskartwii.arkku.net/roblox/loggin?action=join&username="..Player.Name.."&sid="..sid.."&plrlist="..GeneratePlayerList())
end)
game.Players.PlayerRemoving:connect(function(Player)
	game:service("HttpService"):GetAsync("http://gskartwii.arkku.net/roblox/loggin?username="..Player.Name.."&action=leave&sid="..sid.."&plrlist="..GeneratePlayerList()) print("doing request ".."http://gskartwii.arkku.net/roblox/loggin?username="..Player.Name.."&action=leave&sid="..sid.."&plrlist="..GeneratePlayerList())
end)