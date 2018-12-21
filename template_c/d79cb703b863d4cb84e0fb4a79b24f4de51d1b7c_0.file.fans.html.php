<?php
/* Smarty version {Smarty::SMARTY_VERSION}, created on 2018-12-21 08:52:59
  from "D:\wamp\www\wb\app\index\view\index\fans.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32-dev-22',
  'unifunc' => 'content_5c1ca9ebe27d41_56312212',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd79cb703b863d4cb84e0fb4a79b24f4de51d1b7c' => 
    array (
      0 => 'D:\\wamp\\www\\wb\\app\\index\\view\\index\\fans.html',
      1 => 1545374625,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/head.html' => 1,
    'file:../common/footer.html' => 1,
  ),
),false)) {
function content_5c1ca9ebe27d41_56312212 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/head.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<div>
	<h2 class="username"><?php echo $_smarty_tpl->tpl_vars['name']->value;?>
</h2>
	<?php if ($_smarty_tpl->tpl_vars['id']->value != $_smarty_tpl->tpl_vars['user']->value['id']) {?>
		<a data-uid="<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
" class="btn btn-primary btn-sm dofan" <?php if (in_array($_smarty_tpl->tpl_vars['id']->value,$_smarty_tpl->tpl_vars['star_ids']->value)) {?>style="display:none"<?php }?>> <big><b>+</b></big> 关注</a>
		<a data-uid="<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
" class="btn btn-danger btn-sm unfan" <?php if (!in_array($_smarty_tpl->tpl_vars['id']->value,$_smarty_tpl->tpl_vars['star_ids']->value)) {?> style="display:none" <?php }?>> 取消关注</a> 
	<?php }?>
	<div style="clear:both;height:30px"></div>
	<div style="clear:both">
		<h3>Ta 的粉丝( 显示前 50 个 )</h3>
		<div id="homeinfobox">
			<a href="#" class="btn btn-primary btn-sm"><?php echo $_smarty_tpl->tpl_vars['fans_num']->value;?>
 粉丝</a>
		</div>
		<div style="height:40px"></div>
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['f']->value, 'n', false, 'uid');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['uid']->value => $_smarty_tpl->tpl_vars['n']->value) {
?>
		<div class="fans-list">
			<div class="col-md-3" style="margin:10px 0">
				<div class="fans-name col-md-4" style="line-height:33px;font-size:20px"><a href="/index/index/profile?user_id=<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['n']->value;?>
</a></div>
				<div class="col-md-8">
				<?php if ($_smarty_tpl->tpl_vars['user']->value['id'] == $_smarty_tpl->tpl_vars['id']->value) {?>
					<a href="/index/index/msg?user_id=<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" class="btn btn-default btn-sm"><big><b>@</b></big> 私 信</a>
					<a data-uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" class="btn btn-primary btn-sm dofan"><big><b>+</b></big> 关 注</a>
				<?php }?>
				</div>
				
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
		<h3>Ta 的关注</h3>
		<div id="homeinfobox">
			<a href="#" class="btn btn-warning btn-sm"><?php echo $_smarty_tpl->tpl_vars['stars_num']->value;?>
 关注</a>
		</div>
		
		<div style="height:40px"></div>
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['s']->value, 'n', false, 'uid');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['uid']->value => $_smarty_tpl->tpl_vars['n']->value) {
?>
		<div class="fans-list">
			<div class="col-md-3" style="margin:10px 0">
				<div class="fans-name col-md-4" style="line-height:33px;font-size:20px"><a href="/index/index/profile?user_id=<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['n']->value;?>
</a></div>
				<div class="col-md-8">
					<?php if ($_smarty_tpl->tpl_vars['user']->value['id'] == $_smarty_tpl->tpl_vars['id']->value) {?>
					<a href="/index/index/msg?user_id=<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" class="btn btn-default btn-sm"><big><b>@</b></big> 私 信</a>
					
						<a data-uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" class="btn btn-danger btn-sm unfan">取消关注</a>
					<?php }?>
				</div>
				
			</div>
			
		</div>
		<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

		
	</div>
</div>
<div style="clear:both"></div>
<?php $_smarty_tpl->_subTemplateRender("file:../common/footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php }
}
