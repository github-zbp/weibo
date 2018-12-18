<?php
/* Smarty version {Smarty::SMARTY_VERSION}, created on 2018-12-18 12:32:40
  from "D:\wamp\www\wb\app\index\view\index\login.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32-dev-22',
  'unifunc' => 'content_5c18e8e87adeb3_19346997',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '75febf7e0de2397b256afcdabd6942a28b1bdc78' => 
    array (
      0 => 'D:\\wamp\\www\\wb\\app\\index\\view\\index\\login.html',
      1 => 1545136353,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/head.html' => 1,
    'file:../common/footer.html' => 1,
  ),
),false)) {
function content_5c18e8e87adeb3_19346997 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/head.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<body>
<div id="page">
<div id="header">
<a href="/"><img style="border:none" src="/static/images/logo.png" width="192" height="85" alt="Retwis"></a>
<div id="navbar">
</div>
</div>
<div id="welcomebox">
<div id="registerbox">
<h2>注册!</h2>
<b>想试试Retwis? 请注册账号!</b>
<form method="POST" action="/index/index/doRegister">
<table>
<tr>
  <td>用户名</td><td><input type="text" name="name" required></td>
</tr>
<tr>
  <td>密码</td><td><input type="password" name="password" required></td>
</tr>
<tr>
  <td>密码(again)</td><td><input type="password" name="password2" required></td>
</tr>
<tr>
<td colspan="2" align="right"><input type="submit" name="doit" value="注册"></td></tr>
</table>
</form>
<h2>已经注册了? 请直接登陆</h2>
<form method="POST" action="/index/index/doLogin">
<table><tr>
  <td>用户名</td><td><input type="text" name="name" required></td>
  </tr><tr>
  <td>密码:</td><td><input type="password" name="password" required></td>
  </tr><tr>
  <td colspan="2" align="right"><input type="submit" name="doit" value="Login"></td>
</tr></table>
</form>
</div>
介绍! Retwis  是一个简单的<a href="http://twitter.com">Twitter</a>克隆, 也是<a href="http://code.google.com/p/redis/">Redis</a> key-value 数据库的一个使用安全. 关键点:
<ul>
<li>Redis 是一种key-value 数据库, 而且是本项目中 <b>唯一</b>使用的数据库, 没有用mysql等.</li>
<li>应用程序可以通过一致性哈希轻易的部署多台服务器</li>
<li>PHP与redis服务器的连接用pecl的官方扩展<a href="pecl.php.net/package/redis">php-redis</a>
</ul>
</div>
<?php $_smarty_tpl->_subTemplateRender("file:../common/footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
