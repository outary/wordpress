<?php
/**
 *
 * Smartideo 2 （WordPress 视频播放插件）增加美拍视频 
 * http://zycao.com/smartideo-2-add-meipai.html
 *
 * 插件作者：Fengzi 
 * 原插件地址：http://www.fengziliu.com/smartideo-2.html
 *
 * 增加下面两段代码到相似位置
 */
 
//代码一
wp_embed_register_handler( 'smartideo_meipai',
'#https?://(?:www\.)?meipai\.com/media/(?<video_id>\d+)#i',
array($this, 'smartideo_embed_handler_meipai') );


//代码二
public function smartideo_embed_handler_meipai( $matches, $attr, $url, $rawattr ) {
	$meipai_url = "http://www.meipai.com/media/{$matches['video_id']}";
	$meipai_data = file_get_contents($meipai_url);
	if (!empty($meipai_data)) {
		preg_match('/<meta content="(.*)" property="og:video:url">/', $meipai_data, $meipai_result); 
	}
	$embed = $this->get_iframe("{$meipai_result[1]}", $url);
	return apply_filters( 'embed_meipai', $embed, $matches, $attr, $url, $rawattr );
}