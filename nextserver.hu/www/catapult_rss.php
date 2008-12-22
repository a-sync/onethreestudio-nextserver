<?php
$opts = array('http' => array('timeout' => 3));
$context = stream_context_create($opts);
libxml_set_streams_context($context);
libxml_use_internal_errors(true);
$xmlDoc = new DOMDocument();
if ($xmlDoc->load("http://catapult.blog.hu/rss")) {
	$channel = $xmlDoc->getElementsByTagName('channel')->item(0);
	$channel_title = $channel->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
	$channel_link = $channel->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
	$channel_desc = $channel->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;

	$items = $xmlDoc->getElementsByTagName('item');
	$i = 0;
	foreach ($items as $item) {
		if ($i >= 2) continue;
		$item_title = $items->item($i)->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
		$item_link = $items->item($i)->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
		$item_desc = $items->item($i)->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;
		preg_match_all('@<p><a href=[\'"](.*?)[\'"][^>]*?><img[^>]*?class=[\'"]item_ctp[^>]*?></a></p>@si',$item_desc,$temp);
		if ($temp[0][0] != '') {
			$item_desc = str_replace($temp[0][0],'',$item_desc);
		}
		echo "<div class=\"cross_rss\"><h3><a href=\"$item_link\">$item_title</a></h3>$item_desc</div>";
		$i++;
	}
}
?>
