<?php
/* Smarty version {Smarty::SMARTY_VERSION}, created on 2018-12-21 08:09:38
  from "D:\wamp\www\wb\app\index\view\common\head.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32-dev-22',
  'unifunc' => 'content_5c1c9fc2b1b875_80743019',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '87944b8985cd55465c06bdfc80a6ff798d03dc4e' => 
    array (
      0 => 'D:\\wamp\\www\\wb\\app\\index\\view\\common\\head.html',
      1 => 1545307206,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5c1c9fc2b1b875_80743019 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="it">
<head>
<meta content="text/html; charset=UTF-8" http-equiv="content-type">
<title>Retwis - Example Twitter clone based on the Redis Key-Value DB</title>
<!-- <link href="/static/css/style.css" rel="stylesheet" type="text/css"> -->
<link href="/static/bootstrap/bootstrap.min.css" rel="stylesheet" type="text/css">
<?php echo '<script'; ?>
 src="/static/bootstrap/jquery.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="/static/bootstrap/bootstrap.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="/static/js/main.js"><?php echo '</script'; ?>
>
</head>
<body>
<div id="container">
<div id="page" class="col-md-8 col-md-offset-2" style="margin-top:10px;padding:30px">
<div id="header" style="margin-bottom:50px">
<a href="/"><img style="border:none;margin-top:10px" src="/static/images/logo.png" width="192" height="85" alt="Retwis"></a>
<div style="height:20px;"></div>
<div id="navbar" class="pull-right">
<a href="/" class="btn btn-primary">首&nbsp;&nbsp;&nbsp;&nbsp;页</a>	
<?php if ($_smarty_tpl->tpl_vars['user']->value) {?>
<a href="/index/index/home" class="btn btn-warning">我的主页</a>
<a href="/index/index/logout" class="btn btn-danger">退出</a>
<?php } else { ?>
<a href="/index/index/login" class="btn btn-warning">登陆或注册</a>
<?php }?>
</div>
</div><?php }
}
