<?php
/* Smarty version {Smarty::SMARTY_VERSION}, created on 2018-12-21 08:53:06
  from "D:\wamp\www\wb\app\index\view\index\index.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32-dev-22',
  'unifunc' => 'content_5c1ca9f2995040_42879532',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e13ffa93800f244cb42c6886a57fef3454c5e5c0' => 
    array (
      0 => 'D:\\wamp\\www\\wb\\app\\index\\view\\index\\index.html',
      1 => 1545309633,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/head.html' => 1,
    'file:../common/footer.html' => 1,
  ),
),false)) {
function content_5c1ca9f2995040_42879532 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/head.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<div>
<h2><b>热点</b></h2>
<p style="font-size:18px">欢迎新用户 &nbsp;&nbsp;&nbsp;<a id="hide-user" class="btn btn-default btn-xs">隐藏</a><a id="show-user" class="btn btn-primary btn-xs" style='display:none'>显示</a></p>
<div class="new-user">
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['users']->value, 'u');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['u']->value) {
?>
	<div class="username col-md-3" style="line-height:50px;">
		<div class="col-md-4" style="font-size:20px">
			<a <?php if (!empty($_smarty_tpl->tpl_vars['user']->value) && $_smarty_tpl->tpl_vars['user']->value["id"] == $_smarty_tpl->tpl_vars['u']->value["id"]) {?> href='/index/index/home' <?php } else { ?> href='/index/index/profile?user_id=<?php echo $_smarty_tpl->tpl_vars['u']->value["id"];?>
' <?php }?>><?php echo $_smarty_tpl->tpl_vars['u']->value['name'];?>
</a>
		</div>
		<div class="col-md-4">
			<?php if (!empty($_smarty_tpl->tpl_vars['user']->value) && $_smarty_tpl->tpl_vars['user']->value["id"] != $_smarty_tpl->tpl_vars['u']->value["id"]) {?>
				<a data-uid='<?php echo $_smarty_tpl->tpl_vars['u']->value["id"];?>
' class="btn btn-primary btn-sm dofan" <?php if (in_array($_smarty_tpl->tpl_vars['u']->value["id"],$_smarty_tpl->tpl_vars['star_ids']->value)) {?>style="display:none"<?php }?>> <big><b>+</b></big> 关注</a>
				<a data-uid='<?php echo $_smarty_tpl->tpl_vars['u']->value["id"];?>
' class="btn btn-danger btn-sm unfan" <?php if (!in_array($_smarty_tpl->tpl_vars['u']->value["id"],$_smarty_tpl->tpl_vars['star_ids']->value)) {?> style="display:none" <?php }?>> 取消关注</a> 
				
			<?php }?>
		</div>
	</div> 
<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

	
</div>
<div style="clear:both;height:30px"></div>
<div style="clear:both">
<h3>最新的1000条微博</h3>
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['posts']->value, 'post');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['post']->value) {
?>
<div class="post col-md-12" style="font-size:18px;border:1px solid #ccc;border-radius:10px;margin:15px 0">
<div>
	<h3><?php echo $_smarty_tpl->tpl_vars['post']->value['title'];?>
</h3>
	<p>发表人:<a class="username" <?php if (!empty($_smarty_tpl->tpl_vars['user']->value) && $_smarty_tpl->tpl_vars['user']->value["id"] != $_smarty_tpl->tpl_vars['post']->value["user_id"]) {?>href="/index/index/profile?user_id=<?php echo $_smarty_tpl->tpl_vars['post']->value['user_id'];?>
" <?php }?>><?php echo $_smarty_tpl->tpl_vars['post']->value['user_name'];?>
</a></p>
</div>

<p><?php echo $_smarty_tpl->tpl_vars['post']->value['content'];?>
</p>
<i><?php echo timeformat($_smarty_tpl->tpl_vars['post']->value['create_time']);?>
 前 发布</i>
</div>
<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

<div style="clear:both"></div>
<div><?php echo $_smarty_tpl->tpl_vars['links']->value;?>
</div>

</div>

<?php echo '<script'; ?>
>
	$("#hide-user").click(function(){
		$(".new-user").slideUp();
		$("#show-user").show();
		$(this).hide();
	});
	
	$("#show-user").click(function(){
		$(".new-user").slideDown();
		$("#hide-user").show();
		$(this).hide();
	});
<?php echo '</script'; ?>
>
<?php $_smarty_tpl->_subTemplateRender("file:../common/footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
