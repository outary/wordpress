<?php
/**
**	xiu主题“直达链接”修改为自定义名称
**	地址：http://zycao.com/xiu-theme-add-diy-link.html	
**  按照下面提示进行修改
**/

//functions.xiu.php(增加判断是否自定义直达链接并显示) 中找到下面代码
function hui_post_link(){
    global $post;
    $post_ID = $post->ID;
    $link = get_post_meta($post_ID, 'link', true);

    if( $link ){
		if($linktitle){
		echo '<a class="post-linkto'. (is_single()?' action':'') .'" href="'. $link .'"'. (_hui('post_link_blank_s')?' target="_blank"':'') . (_hui('post_link_nofollow_s')?' rel="external nofollow"':'') .'>'. (is_single()?'<i class="glyphicon glyphicon-share-alt"></i>':'') ._hui('post_link_h1') .'</a>';
		}
        
    }
}

//修改为
function hui_post_link(){
    global $post;
    $post_ID = $post->ID;
    $link = get_post_meta($post_ID, 'link', true);
	$linktitle = get_post_meta($post_ID, 'linktitle', true);

    if( $link ){
		if($linktitle){
			echo '<a class="post-linkto'. (is_single()?' action':'') .'" href="'. $link .'"'. (_hui('post_link_blank_s')?' target="_blank"':'') . (_hui('post_link_nofollow_s')?' rel="external nofollow"':'') .'>'. (is_single()?'<i class="glyphicon glyphicon-share-alt"></i>':'') .$linktitle.'</a>';
		}
		else{
			echo '<a class="post-linkto'. (is_single()?' action':'') .'" href="'. $link .'"'. (_hui('post_link_blank_s')?' target="_blank"':'') . (_hui('post_link_nofollow_s')?' rel="external nofollow"':'') .'>'. (is_single()?'<i class="glyphicon glyphicon-share-alt"></i>':'') ._hui('post_link_h1') .'</a>';
		}
        
    }
}

//在functions.admin.php(增加后台自定义直达链接填写框)文件中找到
$postmeta_link = array(
    array(
        "name" => "link",
        "std" => "",
        "title" => __('直达链接', 'haoui').'：'
    )
);
//修改为：
$postmeta_link = array(
    array(
        "name" => "link",
        "std" => "",
        "title" => __('直达链接', 'haoui').'：'
    ),
	array(
		"name" => "linktitle",
        "std" => "",
        "title" => __('直达链接标题', 'haoui').'：'
	
	)
);