<?php
/* Smarty version {Smarty::SMARTY_VERSION}, created on 2018-12-21 09:19:24
  from "D:\wamp\www\wb\app\index\view\index\profile.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32-dev-22',
  'unifunc' => 'content_5c1cb01ccbde25_57832146',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6baa8d90753e1b0908ff15fb65fddbb104c6491e' => 
    array (
      0 => 'D:\\wamp\\www\\wb\\app\\index\\view\\index\\profile.html',
      1 => 1545383962,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/head.html' => 1,
    'file:../common/footer.html' => 1,
  ),
),false)) {
function content_5c1cb01ccbde25_57832146 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/head.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<div>
	<h2 class="username"><?php echo $_smarty_tpl->tpl_vars['u_info']->value['name'];?>
</h2>
	<a data-uid="<?php echo $_smarty_tpl->tpl_vars['u_info']->value['id'];?>
" class="btn btn-primary btn-sm dofan" <?php if (in_array($_smarty_tpl->tpl_vars['u_info']->value['id'],$_smarty_tpl->tpl_vars['star_ids']->value)) {?>style="display:none"<?php }?>> <big><b>+</b></big> 关注</a>
	<a data-uid="<?php echo $_smarty_tpl->tpl_vars['u_info']->value['id'];?>
" class="btn btn-danger btn-sm unfan" <?php if (!in_array($_smarty_tpl->tpl_vars['u_info']->value['id'],$_smarty_tpl->tpl_vars['star_ids']->value)) {?> style="display:none" <?php }?>> 取消关注</a> 

	<div style="clear:both;height:30px"></div>
	
	<div class="common_fans_stars">
		<p class="common_fans" style="padding:15px;border:solid 1px #ccc;border-radius:5px;font-size:20px">
			你和 Ta 共有 <font color="red"><?php echo $_smarty_tpl->tpl_vars['c_fans_num']->value;?>
</font> 个粉丝,<font color="red"><?php echo $_smarty_tpl->tpl_vars['c_stars_num']->value;?>
</font> 个关注哦~
		</p>
		
	</div>
	
	<div style="clear:both;height:30px"></div>
	
	<div style="clear:both">
		<h3>Ta 的微博</h3>
		<div id="homeinfobox">
			<a href="/index/index/fans?user_id=<?php echo $_smarty_tpl->tpl_vars['u_info']->value['id'];?>
" class="btn btn-primary btn-sm"><?php echo $_smarty_tpl->tpl_vars['fans_num']->value;?>
 粉丝</a>
			<a href="/index/index/fans?user_id=<?php echo $_smarty_tpl->tpl_vars['u_info']->value['id'];?>
" class="btn btn-warning btn-sm"><?php echo $_smarty_tpl->tpl_vars['stars_num']->value;?>
 关注</a>
		</div>
		
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['posts']->value, 'post');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['post']->value) {
?>
		<div class="post col-md-12" style="padding:15px;font-size:18px;border:1px solid #ccc;border-radius:10px;margin:15px 0">
			<div>
				<h3><?php echo $_smarty_tpl->tpl_vars['post']->value["title"];?>
</h3>
				<p>发表人:<a class="username"><?php echo $_smarty_tpl->tpl_vars['post']->value['user_name'];?>
</a></p>
			</div>

			<p><?php echo $_smarty_tpl->tpl_vars['post']->value['content'];?>
</p>
			<i><?php echo timeformat($_smarty_tpl->tpl_vars['post']->value['create_time']);?>
 前 发布</i>
		</div>
		
		<?php
}
} else {
?>

			<div class="post col-md-12" style="padding:15px;font-size:40px;">
				<h2 style="color:grey" class="text-center">暂无文章</h2>
			</div>
		<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

		
		<div style="clear:both"></div>
		 <div><?php echo $_smarty_tpl->tpl_vars['links']->value;?>
</div>
	</div>
</div>
<?php $_smarty_tpl->_subTemplateRender("file:../common/footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php }
}
