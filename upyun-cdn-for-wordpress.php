<?php
/**
**	非插件使用又拍云upyun静态CDN加速wordpress网站
**	地址：http://zycao.com/upyun-cdn.html
**  
**  Build by kn007
**  原代码链接：https://kn007.net/topics/cdn-share-my-hook-function/
**		
**	请复制以下代码到主题funcitons.php中，并修改自己的网址及cdn域名
**/


//cnd 加速 到upyun
define('FocusCDNHost','http://veryer.cn');//wordpress网站网址
define('FocusCDNRemote','http://cdn1.veryer.cn');//cdn域名
define('FocusCDNIncludes','wp-content/uploads,/wp-content/plugins,wp-content/themes/xiu/images,wp-includes');//设置加速目录
define('FocusCDNExcludes','.php|.xml|.html|.po|.mo|.pot|.xsl|.txt|.htm|.gitignore|.md|.eot|.svg|.ttf|.woff');//设置文件白名单
define('FocusCDNRelative','');//Check this if you want to have links like <wp-content/abc.png> rewritten - i.e. without your blog's domain as prefix.
  
function do_cdnrewrite_ob_start() {
$rewriter = new FocusCDNRewriteWordpress();
$rewriter->register_as_output_buffer();
}
add_action('template_redirect', 'do_cdnrewrite_ob_start');
  
class FocusCDNRewriteWordpress extends FocusCDNRewrite
{
function __construct() {
$excl_tmp = FocusCDNExcludes;
$excludes = array_map('trim', explode('|', $excl_tmp));
  
parent::__construct(
FocusCDNHost,
FocusCDNRemote,
FocusCDNIncludes,
$excludes,
!!FocusCDNRelative
);
}
public function register_as_output_buffer() {
if ($this->blog_url != FocusCDNRemote) {
ob_start(array(&$this, 'rewrite'));
}
}
  
}
  
class FocusCDNRewrite {
var $blog_url    = null;
var $cdn_url     = null;
var $include_dirs   = null;
var $excludes    = array();
var $rootrelative   = false;
  
function __construct($blog_url, $cdn_url, $include_dirs, array $excludes, $root_relative) {
$this->blog_url   = $blog_url;
$this->cdn_url    = $cdn_url;
$this->include_dirs  = $include_dirs;
$this->excludes   = $excludes;
$this->rootrelative  = $root_relative;
}
  
protected function exclude_single(&$match) {
foreach ($this->excludes as $badword) {
if (stristr($match, $badword) != false) {
return true;
}
}
return false;
}
  
protected function rewrite_single(&$match) {
if ($this->exclude_single($match[0])) {
return $match[0];
} else {
if (!$this->rootrelative || strstr($match[0], $this->blog_url)) {
return str_replace($this->blog_url, $this->cdn_url, $match[0]);
} else {
return $this->cdn_url . $match[0];
}
}
}
  
protected function include_dirs_to_pattern() {
$input = explode(',', $this->include_dirs);
if ($this->include_dirs == '' || count($input) < 1) {
return 'wp\-content|wp\-includes';
} else {
return implode('|', array_map('quotemeta', array_map('trim', $input)));
}
}
  
public function rewrite(&$content) {
$dirs = $this->include_dirs_to_pattern();
$regex = '#(?<=[(\"\'])';
$regex .= $this->rootrelative
? ('(?:'.quotemeta($this->blog_url).')?')
: quotemeta($this->blog_url);
$regex .= '/(?:((?:'.$dirs.')[^\"\')]+)|([^/\"\']+\.[^/\"\')]+))(?=[\"\')])#';
return preg_replace_callback($regex, array(&$this, 'rewrite_single'), $content);
}
  
}
