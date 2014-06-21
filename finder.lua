function iterate(obj, findstr)
	--print("iterate")
	for _,v in pairs(obj) do
		if v.Name==findstr then
			print("findstr is located at "..v:GetFullName())
		end
		iterate(v:children(), findstr)
	end
	--print("Not found!!")
end
iterate(game:GetChildren(), "Header")