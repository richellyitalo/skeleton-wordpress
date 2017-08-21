<?php 
/*
 * Funções de uso Facebook
 */
function fetchUrl($url){
	//Can we use cURL?

	if(is_callable('curl_init')){

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$feedData = curl_exec($ch);
		curl_close($ch);

	//If not then use file_get_contents

	} elseif ( ini_get('allow_url_fopen') == 1 || ini_get('allow_url_fopen') === TRUE ) {
		$feedData = @file_get_contents($url);
	//Or else use the WP HTTP API

	} else {
		if( !class_exists( 'WP_Http' ) ) include_once( ABSPATH . WPINC. '/class-http.php' );
		$request = new WP_Http;
		$result = $request->request($url);
		$feedData = $result['body'];
	}
	return $feedData;

}
/*
 * Retorna os atributos do facebook
 * 
 */
//$json_object = fetchUrl('https://graph.facebook.com/' . $page_id . '/posts?access_token=' . $access_token);
function feed_facebook($fanpage = 'nikefutebol', $limit = 2, $offset = 0) {
	//$feed = fetchUrl("https://graph.facebook.com/{$fanpage}/posts?fields=status_type,picture,message,link&limit={$limit}&offset={$offset}&access_token=396646610471255|rIw3iyPeaaB8cAsDmoOxdsXOPQM");
	$tokenFacebook = '185963128571419|cfb9b355f53906512c7a156ced9cce60';
	// Token localhost
	//$tokenFacebook = 'EAACpIeo4RhsBAB91f8t3NhTIxdxQvwxvbVKvVDGTRLcTz77sDZCWCeQMw3dsgJVhcDA6xO6ZCnzQe2HXIQ1XOjJwwekKka9CijEh78vXKz1Sza2cZAe5GAHrbzKVAweyzZAZC562bjLmHxPhTBLHKBfBfT0VeOH6SZCc0ldCQRwO8EZCJRl5DRKnnc2MZAH3yZBMZD';
	//$feed = $this->_fetchUrl("https://graph.facebook.com/{$fanpage}/posts?limit={$limit}&offset={$offset}&access_token=" . $tokenFacebook);

	$feed = fetchUrl("https://graph.facebook.com/{$fanpage}/?fields=posts.limit({$limit}).offset({$offset}){type,full_picture,link,permalink_url,caption,name,description,message,created_time}&access_token=" . $tokenFacebook);

	//$feed = fetchUrl("https://graph.facebook.com/{$fanpage}/posts?limit=15&access_token=396646610471255|rIw3iyPeaaB8cAsDmoOxdsXOPQM");
	$fbfeed = json_decode($feed);
	$posts = array();
//        $i = 0;
	foreach($fbfeed->posts->data as $post){
		if($post->type == 'photo'){
			//$post->thumbnail = $post->full_picture;//"https://graph.facebook.com/{$post->object_id}/picture?type=normal&amp;width=750&amp;height=537";
			array_push($posts, $post);
//            $i++;
			/*
			//exibe apenas update de fotos
			?>
			<div class="col-md-6 th-item">
				<div class="row">
					<div class="col-xs-4 col-sm-3 col-md-3">
						<a href="<?php echo $post->link ?>" target="_blank">
							<img src="<?php echo $post->picture ?>" class="img-circle fxall img-responsive" />
						</a>
					</div>
					<div class="col-xs-8 col-sm-9 col-md-9 th-comment">
						<p>
							<?php echo $post->message ?>
						</p>
						<a href="<?php echo $post->link ?>" class="btn btn-success" target="_blank">ver no facebook</a>
					</div>
				</div>
			</div>
			<?php
			*/
		}
//        if($i == $limit)
//            break;
	}
	return $posts;
}


function get_feed_facebook_aspost( $group = '', $limit = 10, $offset = 0 ) {
    $news_fb = feed_facebook( $group, $limit, $offset );
    $posts_fb = array();

    foreach ( $news_fb as $new ) {
        $posts_fb[] = (object) array(
            'post_type' => 'fb',
            'ID'        => $new->id,
            'post_title'     => $new->message,
            'post_excerpt'   => $new->message,
            'image_url' => $new->full_picture,
            'post_url'  => $new->link,
            'post_date_for_humans' => date_i18n( get_option( 'date_format' ), strtotime( $new->created_time ) ),
            'post_date' => DateTime::createFromFormat( DateTime::ISO8601, $new->created_time )->format( 'Y-m-d H:i:s' )
        );
    }

    return $posts_fb;

}
?>