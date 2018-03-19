<?php include TEMPLATE_PATH . "/admin/common/header.php"; ?>
<iframe name="_iframe_inner_post" src="about:blank" style="display:none;"></iframe>
<?php include TEMPLATE_PATH . "/admin/common/navigation.php"; ?>
<div class="container">
<form action="?action=save" method="post">
<fieldset>
	<legend><?php if ($TEMPLATE['data'] && $TEMPLATE['data']['id']){ ?>编辑<?php } else { ?>添加<?php } ?>活动信息</legend>
	<label for="info_title">活动名称</label>
	<input type="text" id="info_title" name="info[title]" value="<?php echo $TEMPLATE['data']['title']; ?>"/>
	<label for="info_time_begin">开始时间</label>
	<input type="text" id="info_time_begin" name="info[time_begin]" value="<?php echo $TEMPLATE['data']['time_begin'];?>" placeholder="yyyy-mm-dd hh:MM:ss"/>
	<label for="info_time_end">结束时间</label>
	<input type="text" id="info_time_end" name="info[time_end]" value="<?php echo $TEMPLATE['data']['time_end'];?>" placeholder="yyyy-mm-dd hh:MM:ss"/>
	<br/>
	<button class="btn btn-primary" type="submit">保存</button>
	<button class="btn" type="reset" onclick="history.go(-1);return false;">返回</button>
	<input type="hidden" name="info[id]" value="<?php echo $TEMPLATE['data']['id']; ?>" />
</form>
</div>
<?php include TEMPLATE_PATH . "/admin/common/footer.php"; ?>