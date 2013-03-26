--- This will take a url on your clipboard, and use google to shorten it
--- create a new text expander item, set to applescript and paste this in.

set the ClipURL to (the clipboard as string)

ignoring case
	if ((characters 1 through 4 of ClipURL as string) is not "http") then
		return "Malformed URL."
	else
		set curlCMD to ¬
			"curl  -H 'Content-Type:application/json' -d '{\"longUrl\": \"" & ClipURL & "\" }' https://www.googleapis.com/urlshortener/v1/url"
		
		-- Run the script and get the result:
		
		set postWeather to "grep -o '[^\"]*goo.gl[^\"]*'"
		set forecast to do shell script curlCMD & " | " & postWeather
		
		return forecast
	end if
end ignoring