<?php


$fb = $_POST["fb"];
$insta = $_POST["insta"];
$lang = $_POST["lang"];
$tube = $_POST["tube"];
$tube2 = $_POST["tube2"];

if($tube2 != ''){

	$params2 = array( 'sslverify' => false, 'timeout' => 60 );
	$url2 = 'https://www.googleapis.com/youtube/v3/channels?part=statistics&id='. $tube2 .'&key=AIzaSyDz2iGCmsfPSblRztiN3mOxY9oUK5nolz8';
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
	$youTubeData2 = wp_remote_get( $url2, $params2 );
	if ( is_wp_error( $youTubeData2 ) || $youTubeData2[ 'response' ][ 'code' ] >= 400 ) {
		return;
	}
	
	$response2 = json_decode( $youTubeData2[ 'body' ], true );
	$viewsCount_2 = intval( $response2[ 'items' ][ 0 ][ 'statistics' ][ 'subscriberCount' ] );
	$viewsCount2 = number_format ($viewsCount_2  , 0 , ' , ' ,  '.');
} else {
	$viewsCount2 = "Not available";
}

if($tube != ''){

	$params = array( 'sslverify' => false, 'timeout' => 60 );
	$url = 'https://www.googleapis.com/youtube/v3/channels?part=statistics&forUsername='. $tube .'&key=AIzaSyDz2iGCmsfPSblRztiN3mOxY9oUK5nolz8';
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
	$youTubeData = wp_remote_get( $url, $params );
	if ( is_wp_error( $youTubeData ) || $youTubeData[ 'response' ][ 'code' ] >= 400 ) {
		return;
	}
	
	$response = json_decode( $youTubeData[ 'body' ], true );
	$viewsCount_ = intval( $response[ 'items' ][ 0 ][ 'statistics' ][ 'subscriberCount' ] );
	$viewsCount = number_format ($viewsCount_  , 0 , ' , ' ,  '.');

} else {
	$viewsCount = "Not available";
}
	
if($fb != ''){
	$fb_data = getData($fb);
	$likes = $fb_data['likes'];
	$likes = number_format ( $likes , 0 , "," , "." );
	$hasFB = false;
	if($likes == "" || $likes == "0") $likes = "Not available";
	else {
		if($lang == 'en')
			//$likes .= " followers";
			$likes .= "";
		else
			//$likes .= " seguidores";
			$likes .= "";
		$hasFB = true;
	}
}
else{
	$likes = "Not available";
}
	
if($insta != ''){	
	$raw = file_get_contents('https://www.instagram.com/'.$insta);
	preg_match('/\"followed_by\"\:\s?\{\"count\"\:\s?([0-9]+)/',$raw,$m);
	$followers = intval($m[1]);
	$followers = number_format ( $followers , 0 , "," , "." );
	$hasInst = false;
	if($followers == "0") $followers = "Not available";
	else{
		if($lang == 'en')
			//$followers .= " followers";
			$followers .= "";
		else
			//$followers .= " seguidores";
			$followers .= "";
		$hasInst = true;
	}
}
else{
	$followers = "Not available";
}

$out = array();
$out['fb'] = $likes;
$out['insta'] = $followers;
$out['tube'] = $viewsCount;
$out['tube2'] = $viewsCount2;
header('Content-Type: application/json');
echo json_encode($out);


/*------------------------------------*\
    Function to retrieve FB like count
\*------------------------------------*/

function getData($username){
	$filename = dirname( __FILE__ ) . '/functions/access_token.data';
	$fp = fopen($filename,'r');	
	$access_token = fread ($fp, filesize ($filename));
	fclose($fp);
	$json = json_decode(file_get_contents("https://graph.facebook.com/".$username."?fields=likes&access_token=".$access_token),true);
	if($json == NULL){
		$app_id = '822894977819768';
		$secret = '7975977f9281c39637da6ca7f7f8dba4';
		$data = json_decode(file_get_contents("https://graph.facebook.com/v2.8/oauth/access_token?client_id=".$app_id."&client_secret=".$secret."&grant_type=client_credentials"));				
		$new_at = $data->access_token;
		$fp = fopen($filename,'w+');	
		fwrite($fp,$new_at);
		fclose($fp);						
		$access_token = $new_at;
		$json = json_decode(file_get_contents("https://graph.facebook.com/".$username."?fields=likes&access_token=".$access_token),true);
	} 
	return $json;
}
?>