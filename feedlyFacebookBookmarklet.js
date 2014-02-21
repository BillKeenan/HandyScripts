<html>

Drag this link onto your toolbar
<a href="javascript:(function(){ 
var rssString = 'https://www.facebook.com/feeds/page.php?id=PAGEID&format=rss20';
var subscribeUrl = 'http://cloud.feedly.com/#subscription%2Ffeed%2F';
var docUrl = window.location.href;
var isId = docUrl.match(/(\d+)($|\?.*)/);
var id = 0;
if(isId){id = isId[1]}else{var graphUrl = docUrl.replace(/www/g,'graph');
var xmlhttp=new XMLHttpRequest();
xmlhttp.open('GET',graphUrl,false);
xmlhttp.send();
var json = JSON.parse(xmlhttp.responseText);
id = json.id;
}var completeUrl = rssString.replace(/PAGEID/,id);
var encodedUri = encodeURI(completeUrl);
var finalUrl = subscribeUrl.concat(encodedUri);
window.location=finalUrl;
})();
">Facebook To Feedly</a>
</html>

