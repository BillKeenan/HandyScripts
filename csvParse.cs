class Program
{
public enum parseState
{
word,
comma,
quote
}

public Program(){
}

public ArrayList parse(string text)
{

ArrayList vals = new ArrayList();
int i =0;
while (i < text.Length)
{
vals.Add(GetWord(text,ref i));
}
//special handling if the last char is empty
if (text[text.Length -1].ToString() == ",")
{
//append an empty val
vals.Add(String.Empty);
}

return vals;

}

private string GetWord(string text, ref int position)
{
parseState state = parseState.word;
string word = string.Empty;
while (position < text.Length)
{
string letter = text[position].ToString();
position++;
switch (letter)
{
case ",":
if (state == parseState.word)
{
//were done;

return word;
}
else if (state == parseState.quote)
{
//were in a quoted section add it
word += letter;
}
else if (state == parseState.comma)
{
//empty string, fair enough, return it
//were done;
return word;
}
break;
case "\"":
if (state == parseState.word || state == parseState.comma)
{
//beginning of quoted section
state = parseState.quote;
}
else if (state == parseState.quote)
{
//end of a quoted section
//were done
return word;
}
break;
default:
if (state == parseState.word)
{
//normal
word += letter;
}
else if (state == parseState.quote)
{
//were in a quoted word
word += letter;
}
else if (state == parseState.comma)
{
//start of a word, fine.
word += letter;
state = parseState.word;
}
break;

}

}
return word;
}
}
