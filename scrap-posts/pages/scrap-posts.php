<?php
include('functions.php');
include('simple_html_dom.php');
$url = "http://www.nadeemtron.com/policy";

   if(isset($_POST['scrap']) && $_POST['scrap'] != '') {
	   $error = '';
		$url = $url.'?page='.$_POST['page'];
		$result = get_web_page( $url );
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
		$count = 0;
		
		foreach($html->find('div.blog-widget') as $article) {
		   
			$item['title']     = $article->find('h4.excerpt-title', 0)->plaintext;
			$post_name = $item['title'];
			$post = get_page_by_title( $post_name, OBJECT, 'post' );
			if($post->ID != '') {
				
				$error .= $post_name." already exist<br>";
			}
			else {			
			$item['url']    = $article->find('h4.excerpt-title a', 0)->href;
			if(count($article->find('div.image', 0)) > 0) {
			$style = $article->find('div.image', 0)->style;
			$change = 'N';
			if(preg_match("/\((\d+)\)/",$style, $output_array)) {
				
				//print_r($output_array[0]);
				$change = $output_array[0];
				$style = preg_replace("/\((\d+)\)/", "###", $style);
			}
	
			if(preg_match('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#',$style, $matches)) {
				if($change != 'N') {

				$image_url = str_replace('###',$change,$matches[0]);
				}
				else {
				$image_url = $matches[0];
				}
			}
			else {
			$image_url = '';
			}
			}
			else {
			$image_url = '';	
			}
			$item['img'] = $image_url;
			include('content.php');
			$my_post = array(
				  'post_title'    => $item['title'],
				  'post_content'  => $item['content'],
				  'post_status'   => 'publish',
				  'post_author'   => 1
				);
			$post_id = wp_insert_post( $my_post );
			if($image_url != '') {
			$upload_dir = wp_upload_dir();
			$image_data = file_get_contents($image_url);
			
			$filename = basename($image_url);
			
			$ext_info = pathinfo($filename, PATHINFO_EXTENSION);
			$ext_array = explode('?',$ext_info);
			$ext = $ext_array[0];
			
			$filename = time().rand().'.'.$ext;
			if(wp_mkdir_p($upload_dir['path']))
				$file = $upload_dir['path'] . '/' . $filename;
			else
				$file = $upload_dir['basedir'] . '/' . $filename;
			file_put_contents($file, $image_data);
			
			$wp_filetype = wp_check_filetype($filename, null );
			$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_title' => sanitize_file_name($filename),
				'post_content' => '',
				'post_status' => 'inherit'
			);
			$attach_id = wp_insert_attachment( $attachment, $file, $post_id );
			require_once(ABSPATH . 'wp-admin/includes/image.php');
			$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
			wp_update_attachment_metadata( $attach_id, $attach_data );
			
			set_post_thumbnail( $post_id, $attach_id );
			}
			unset($item);
			
			
		
		$count++;
			}}
		
		
   }
   else {
	   
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
   }
   }
?>
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url( 'assets/css/admin.css', __FILE__ ) ?>">

<div id="content_show">
<div class="authenticate">
	<div class="wrap1" style="min-height: 600px;">
		<div id="icon-plugins" class="icon32"></div>
		<h2>We have enhanced </h2>
        <?php if($count > 0) { echo "<h3>".$count." Post has been imported successfully</h3>"; } ?>
        <?php if($error) { echo "<h3>".$error."</h3>"; } ?>
		<div class="register-left">
	<div class="alert" style="margin: 0px auto; padding: 20px 15px; text-align: center;">
			<h3>We are fetching posts from <a href="http://www.nadeemtron.com/policy" target="_blank">http://www.nadeemtron.com/policy</a></h3>
		<form name="scrapFrm" id="scrapFrm" method="post">
        <select name="page" id="page">
        <?php for($i=$start_page;$i<=$last_page;$i++) { ?>
        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
        <?php } ?>
        </select>
		<input type="submit" name="scrap" id="scrap" value="Start to Scrap" class="btn btn-primary" />
		</form>
	</div>
          
      </div>
		
	</div>
</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function(e) {
		
		
	})
</script>