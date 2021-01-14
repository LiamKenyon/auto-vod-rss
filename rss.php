<?php
$url = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
$paths = explode("/", $url);
$paths = str_replace("-"," ",$paths);
// print_r($paths);
$rss_array = array(
    'https://democracynow.org/podcast-video.xml',
    'https://podsync.net/1QPr',
    'https://siftrss.com/f/d08JgNM5V3',
    'https://siftrss.com/f/LbQX1zVA6z0',
    'https://podsync.net/WQ2O',
    'https://podsync.net/hH351ceab',
    'https://podsync.net/IbukRyeEb'
);
global $limit,$startTime,$endTime,$channel,$thumbnail,$title,$description,$pubDate,$date,$url;
$limit = 2;
$startTime = date("YmdGis O",strtotime("-1 day"));
$endTime = date("YmdGis O",strtotime("+1 day"));
if ($paths[5] == "epg") {
    header("Content-type: text/xml");
    print "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
    print "<!DOCTYPE tv SYSTEM \"xmltv.dtd\">";
    print "<tv date=\"".date("YmdGis 0")."\" source-info-name=\"RSS EPG\" source-info-url=\"".$url."\">";
} else {
    header("Content-type: text/m3u");
    print "#EXTM3U"."\n\n";
}
for ($i=0; $i<count($rss_array); $i++ ) {
    // if(++$i > $limit) break;
    $rssfeed = simplexml_load_file($rss_array[$i]);
    foreach ($rssfeed->channel as $channel) {
        // $podcast = $channel->title;
        $thumbnail = $channel->image->url;
        $channel = str_ireplace(',','',$channel->title);
    }
    $num = 0;
    foreach ($rssfeed->channel->item as $item) {
        $title = $item->title;
        $titleM3U = str_ireplace(',','',$title);
        $titleEPG = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', filter_var(str_ireplace('/,"+;)/','',$title), FILTER_SANITIZE_STRING, FILTER_SANITIZE_EMAIL));
        $description = $item->description;
        $description = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', filter_var(str_ireplace('','',$description), FILTER_SANITIZE_STRING, FILTER_SANITIZE_EMAIL));
        $pubDate = strtotime($item->pubDate);
        $date = date('Y-m-d', $pubDate);
        $url = $item->enclosure[url];
        $itemNum = $num++;
        if ($paths[5] == "epg") {
            print "<channel id=\"".$pubDate.$itemNum."\">";
            print "<display-name>".$channel."</display-name>";
            print "</channel>";
            print "<programme start=\"".$startTime."\" stop=\"".$endTime."\" channel=\"".$pubDate.$itemNum."\">";
            print "<title lang=\"en\">".$titleEPG."</title>";
            print "<desc lang=\"en\">".$description."</desc>";
            print "</programme>";
            // print "\n";
        } else {
            print "#EXTINF:-1 ";
            print "tvg-logo=\"".$thumbnail."\" ";
            print "epg-id=\"".$pubDate.$itemNum."\" ";
            print "tvg-id=\"".$pubDate.$itemNum."\" ";
            print "epg-url=\"".$url."/epg\" ";
            print "tvg-url=\"".$url."/epg\" ";
            print "group-title=\"📺 ".$channel."\",".$date." | ".$titleM3U;
            print "\n";
            print $url."\n\n";
        }
    }
}
if ($paths[5] == "epg") {
    print "</tv>";
}
?>
