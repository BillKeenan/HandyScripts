--pull a random line from a file
--- create a new text expander item, set to applescript and paste this in.

property fileToRead = "[FULL FILE PATH]" 
set the_text to read fileToRead
set par_count to (count paragraphs in the_text)

set y to read fileToRead
set x to paragraph (random number from 0 to par_count) of y 
return x 
