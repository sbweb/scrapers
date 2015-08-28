<?php


$url = "http://www.nadeemtron.com/".$item['url'];
$result = get_web_page( $url );
$base_url = "http://www.nadeemtron.com";

if ( $result['errno'] != 0 ) {
 	echo "... error: bad url, timeout, redirect loop ...";
}
else if ( $result['http_code'] != 200 ) {
   echo "... error: no page, no permissions, no service ...";
   }
   else {

$page = $result['content'];
$result = get_all_string_between($page, '<div class="content-pages-show-blog-post">', '<div class="right-column">');
$html = str_get_html($result[0]);
//echo $html;
// Find all article blocks
$i = 0;

   
    $item['title']     = $html->find('div.headline', 0)->plaintext;
    $item['content']    = $html->find('div.content', 0)->innertext;

 



   }
   
 