<?php
//get the q parameter from URL
$q=$_GET["q"];

//find out which feed was selected
if($q=="Google") {
  $xml=("http://news.google.com/news?ned=us&topic=h&output=rss");
} elseif($q=="NBC") {
  $xml=("http://feeds.nbcnews.com/feeds/worldnews");
} elseif($q=="CNN") {
  $xml=("http://rss.cnn.com/rss/cnn_topstories.rss");
} elseif($q=="NYT") {
  $xml=("http://www.nytimes.com/services/xml/rss/nyt/HomePage.xml");
} elseif($q=="REU") {
  $xml=("http://feeds.reuters.com/reuters/topNews");
} elseif($q=="REU-TOP") {
  $xml=("http://feeds.reuters.com/reuters/topNews");
} elseif($q=="REU-BUS") {
  $xml=("http://feeds.reuters.com/reuters/businessNews");
} elseif($q=="REU-MON") {
  $xml=("http://feeds.reuters.com/news/wealth");
} elseif($q=="REU-POL") {
  $xml=("http://feeds.reuters.com/Reuters/PoliticsNews");
} elseif($q=="REU-SCI") {
  $xml=("http://feeds.reuters.com/reuters/scienceNews");
} elseif($q=="TRM") {
  $xml=("http://www.thetrumpet.com/rss");
}

$xmlDoc = new DOMDocument();
$xmlDoc->load($xml);

//get elements from "<channel>"
// $channel=$xmlDoc->getElementsByTagName('channel')->item(0);
// $channel_title = $channel->getElementsByTagName('title') ->item(0)->childNodes->item(0)->nodeValue;
// $channel_link = $channel->getElementsByTagName('link') ->item(0)->childNodes->item(0)->nodeValue;
// $channel_desc = $channel->getElementsByTagName('description') ->item(0)->childNodes->item(0)->nodeValue;

//output elements from "<channel>"
//echo("<li><a href='" . $channel_link
//  . "'>" . $channel_title . "</a>");
//echo($channel_desc . "</li>");

//get and output "<item>" elements
$x=$xmlDoc->getElementsByTagName('item');
for ($i=0; $i<=20; $i++) {
  $item_title=$x->item($i)->getElementsByTagName('title')
  ->item(0)->childNodes->item(0)->nodeValue;
  $item_link=$x->item($i)->getElementsByTagName('link')
  ->item(0)->childNodes->item(0)->nodeValue;
  $item_desc=$x->item($i)->getElementsByTagName('description')
  ->item(0)->childNodes->item(0)->nodeValue;
  echo ("<li class=\"ui-first-child\"><a href=\"javascript:SpritzHelper.GoToNewsReader('" . $item_link . "')\" class=\"ui-btn ui-btn-icon-right ui-icon-carat-r fontLarger-1\">" . $item_title . "</a></li>");
  //echo ("<li><a href='pages.php/#pgNewsRead?nl=" . $item_link . "'>" . $item_title . "</a></li>");
  //echo ("<li><a href='$.mobile.changePage(\"pgNewsRead\", {data:{param1:'" . $item_link . "'}});'>" . $item_title . "</a></li>");
}
?>