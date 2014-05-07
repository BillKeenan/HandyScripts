toc = ''
def pbcopy(text)
  IO.popen("pbcopy", "w+") {|pipe| pipe << text}
end

ARGF.each do |f|
	if f =~ /(#+)(.*?)(\[.*\]|$)/
		indents = (($1.length) -1)*4
   		toc+= (' '*indents)+"* ["+$2+"]"+($3?$3:'')+"\n"
	end
end

pbcopy(toc)
