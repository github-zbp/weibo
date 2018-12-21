<?php
/* Smarty version {Smarty::SMARTY_VERSION}, created on 2018-12-21 08:27:35
  from "D:\wamp\www\wb\app\index\view\index\home.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32-dev-22',
  'unifunc' => 'content_5c1ca3f7c5c627_49462696',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a679aa412878049ddc42cde2e68218b621b14e6f' => 
    array (
      0 => 'D:\\wamp\\www\\wb\\app\\index\\view\\index\\home.html',
      1 => 1545380803,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/head.html' => 1,
    'file:../common/footer.html' => 1,
  ),
),false)) {
function content_5c1ca3f7c5c627_49462696 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/head.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<div>
	<div id="postform">
		<form method="POST" action="/index/index/post">
		<h3><?php echo $_smarty_tpl->tpl_vars['name']->value;?>
, 有啥感想?</h3>
		<div class="form-group">
			<input type="text" class="form-control" name="title" placeholder="主题" required>
		</div>
		<div class="form-group">
			<textarea class="form-control" name="content" rows="10" placeholder="内容"    required ></textarea>
		</div> 
		<button type="submit" class="btn btn-primary pull-right">发表</button>
		</form>
		<div style="clear:both"></div>
		<div id="homeinfobox">
			<a href="/index/index/fans" class="btn btn-primary btn-sm"><?php echo $_smarty_tpl->tpl_vars['fans_num']->value;?>
 粉丝</a>
			<a href="/index/index/fans" class="btn btn-warning btn-sm"><?php echo $_smarty_tpl->tpl_vars['stars_num']->value;?>
 关注</a>
		</div>
	</div>
	<div style="height:30px"></div>
	<div>
	  <!-- Nav tabs -->
	  <ul class="nav nav-tabs" id="myTabs" role="tablist">
		<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">我的微博</a></li>
		<li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">关注者微博</a></li>
		<li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">私&nbsp;&nbsp;&nbsp;&nbsp;信</a></li>
		<!-- <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a></li> -->
	  </ul>

	  <!-- Tab panes -->
	  <div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="home">
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['my_wb']->value, 'w');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['w']->value) {
?>
			<div class="post col-md-12" style="padding:15px;font-size:18px;border:1px solid #ccc;border-radius:10px;margin:15px 0">
				<div>
					<h3><?php echo $_smarty_tpl->tpl_vars['w']->value['title'];?>
</h3>
					<p>发表人:<a class="username" <?php if ($_smarty_tpl->tpl_vars['w']->value['user_id'] != $_smarty_tpl->tpl_vars['uid']->value) {?>href="/index/index/profile?user_id=<?php echo $_smarty_tpl->tpl_vars['w']->value['user_id'];?>
"<?php }?>><?php echo $_smarty_tpl->tpl_vars['w']->value['user_name'];?>
</a></p>
				</div>

				<p><?php echo $_smarty_tpl->tpl_vars['w']->value['content'];?>
</p>
				<i><?php echo timeformat($_smarty_tpl->tpl_vars['w']->value['create_time']);?>
 前 发布</i>
			</div>
		<?php
}
} else {
?>

			<div class="post col-md-12" style="padding:15px;font-size:40px;">
				<h2 style="color:grey" class="text-center">暂无文章，快去发表吧</h2>
			</div>
		<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

		<div style="clear:both"></div>
		 <div><?php echo $_smarty_tpl->tpl_vars['links']->value;?>
</div>
		</div>
		
		<div role="tabpanel" class="tab-pane" id="profile">
			<div class="tab-post">
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['stars_wb']->value, 'w');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['w']->value) {
?>
				<div class="post col-md-12" style="padding:15px;font-size:18px;border:1px solid #ccc;border-radius:10px;margin:15px 0">
					<div>
						<h3><?php echo $_smarty_tpl->tpl_vars['w']->value['title'];?>
</h3>
						<p>发表人:<a class="username" href="/index/index/profile?user_id=<?php echo $_smarty_tpl->tpl_vars['w']->value['user_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['w']->value['user_name'];?>
</a></p>
					</div>

					<p><?php echo $_smarty_tpl->tpl_vars['w']->value['content'];?>
</p>
					<i><?php echo timeformat($_smarty_tpl->tpl_vars['w']->value['create_time']);?>
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

			</div>
		<div style="clear:both"></div>
		 <div class="page-wraper"><?php echo $_smarty_tpl->tpl_vars['s_links']->value;?>
</div>
		</div>
		
		
	  </div>

	</div>
	
</div>

<?php echo '<script'; ?>
>

$('#myTabs a').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
})
<?php echo '</script'; ?>
>
<?php $_smarty_tpl->_subTemplateRender("file:../common/footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php }
}
