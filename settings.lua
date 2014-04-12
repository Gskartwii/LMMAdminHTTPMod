-- Isn't this code easy to read? ;3
-- Put this inside a regular script
Elements					= {}
Elements["Ranks"]			= {}

Elements["Ranks"]["Owner"]	= {"ROBLOX", "Player1"} -- All commands, full power, stops admin, and other ownery things
Elements["Ranks"]["Admin"]	= {"BestFriend"}		-- Most commands, can kick, ban, crash, and has power in servers
Elements["Ranks"]["Member"]	= {"Friend"}			-- Some commands, no kick, ban, crash, shutdown, no real power
Elements["Ranks"]["Banned"]	= {"Noob"}				-- Can't join
Elements["Ranks"]["Crashed"]= {"BiggerNoob"}		-- Gets lagged >:D
Elements["Ranks"]["Muted"]	= {"Hacker"}			-- Can join server, but they can't chat(Great for nooby hackers)

-----------[[ SETTINGS ]]---------------
Elements["FUN"] 			= true 					-- Eveyone likes fun! so why not me true! If your place is a military place and you have no scene of humor you can change it to false 
Elements["LagTime"] 		= 5 					-- The crash command disconnects the player. then the time set here will count down until lag
Elements["Prefix"] 			= ";" 					-- What you say before a command. The ";" in [ ;kill me ]
Elements["Bet"]				= " " 					-- Separates arguments(Cannot be slash or dash or it will become space)
Elements["EnableAdminMenu"]	= true 					-- Set true or false if you want to enable the admin menu
Elements["Filter"]			= {"GetObjects"}		-- Used for Anti-Exploits. If someone says anything on this list they will be kicked.
Elements["ServerLocked"]	= false 				-- Used to kick non-admins when they join
Elements["DisableAbuse"]	= false					-- Disables abusive command like kill,fling,loopfling,ect... (FOR MEMBER RANK ONLY)

-----------[[ VIPS ]]-----------------
Elements["VIPMemberID"]		= 0						-- Put the gamepass ID for people to have member access, leave 0 if you do not want it
Elements["VIPAdminID"]		= 0 					-- Put the gamepass ID for people to have admin access, leave 0 if you do not want it

-----------[[ GROUPS ]]---------------
Elements["GroupID"]			= 0 					-- Links group to admin, leave 0 if you do not want to do so
Elements["GroupMemberRank"]	= 0 					-- the lowest rank that will get admin commands[Member Rank], If you have linked a group to this DO NOT make 0
Elements["GroupAdminRank"]	= 0 					-- the lowest rank that will get admin commands[Admin Rank], If you have linked a group to this DO NOT make 0
Elements["GroupOwnerRank"]	= 0 					-- the lowest rank that will get admin commands[Owner Rank], If you have linked a group to this DO NOT make 0
Elements["RankBan"]			= 0						-- 1st arguments is what action a rank will be from a group, 2nd is the rank

-----------[[ BADGES ]]---------------
Elements["BadgeID"]			= 0						-- Leave 0 if you don't want people to have admin when they get a badge[Member Rank], otherwise, put in badge ID

-- DO NOT TOUCH
_G.SettingsModule=Elements