<?php
include('simple_html_dom.php');
//http://stackoverflow.com/questions/14953867/how-to-get-page-content-using-curl
//Read a web page and check for errors:
$url = "http://www.nadeemtron.com/policy";
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
$result = get_all_string_between($page, '<div class="heading">', '<div class="right-column">');
$html = str_get_html($result[0]);
$ul = $html->find('div.pagination ul',0);
$a = $ul -> lastChild() -> find('a'. 0);

foreach($ul->find('li') as $li) {
	
	$items[] = $li->plaintext;

}
$last_page = $items[count($items)-2];
$start_page = $items[1];
//echo $html;
// Find all article blocks
$i = 0;
foreach($html->find('div.blog-widget') as $article) {
   
    $item['title']     = $article->find('h4.excerpt-title', 0)->plaintext;
    $item['url']    = $article->find('h4.excerpt-title a', 0)->href;
	if(preg_match('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $article->find('div.image', 0)->style, $matches)) {
    $image_url = $matches[0];
	}
	else {
		 $image_url = '';
	}
    $item['img'] = $image_url;
	include('content.php');
    $articles[] = $item;
	unset($item);
	

$i++;}

echo '<pre>'; print_r($articles); die;
   }
   
   function get_all_string_between($string, $start, $end)
{
    $result = array();
    $string = " ".$string;
    $offset = 0;
    while(true)
    {
        $ini = strpos($string,$start,$offset);
        if ($ini == 0)
            break;
        $ini += strlen($start);
        $len = strpos($string,$end,$ini) - $ini;
        $result[] = substr($string,$ini,$len);
        $offset = $ini+$len;
    }
    return $result;
}




  /**
     * Get a web file (HTML, XHTML, XML, image, etc.) from a URL.  Return an
     * array containing the HTTP server response header fields and content.
     */
    function get_web_page( $url )
    {
        $user_agent='Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

        $options = array(

            CURLOPT_CUSTOMREQUEST  =>"GET",        //set request type post or get
            CURLOPT_POST           =>false,        //set to GET
            CURLOPT_USERAGENT      => $user_agent, //set user agent
            CURLOPT_COOKIEFILE     =>"cookie.txt", //set cookie file
            CURLOPT_COOKIEJAR      =>"cookie.txt", //set cookie jar
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        );

        $ch      = curl_init( $url );
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close( $ch );

        $header['errno']   = $err;
        $header['errmsg']  = $errmsg;
        $header['content'] = $content;
        return $header;
    }
?>