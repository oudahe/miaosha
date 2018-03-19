<?php include TEMPLATE_PATH . "/admin/common/header.php"; ?>
<iframe name="_iframe_inner_post" src="about:blank" style="display:none;"></iframe>
<?php include TEMPLATE_PATH . "/admin/common/navigation.php"; ?>
<div class="container">
<form action="?action=save" method="post">
<fieldset>
	<legend><?php if ($TEMPLATE['data'] && $TEMPLATE['data']['id']){ ?>编辑<?php } else { ?>添加<?php } ?>商品信息</legend>
	<label for="info_active_id">所属活动ID</label>
	<input type="text" id="info_active_id" name="info[active_id]" value="<?php echo $TEMPLATE['data']['active_id']; ?>"/>
	<label for="info_title">商品名称</label>
	<input type="text" id="info_title" name="info[title]" value="<?php echo $TEMPLATE['data']['title']; ?>"/>
	<label for="info_description">商品描述</label>
	<textarea id="info_description" name="info[description]" ><?php echo htmlspecialchars($TEMPLATE['data']['description']); ?></textarea>
	<label for="info_img">商品图片</label>
	<input type="text" id="info_img" name="info[img]" value="<?php echo $TEMPLATE['data']['img']; ?>"/>
	<label for="info_price_normal">原价</label>
	<input type="text" id="info_price_normal" name="info[price_normal]" value="<?php echo $TEMPLATE['data']['price_normal']; ?>"/>
	<label for="info_price_discount">优惠价</label>
	<input type="text" id="info_price_discount" name="info[price_discount]" value="<?php echo $TEMPLATE['data']['price_discount']; ?>"/>
	<label for="info_num_total">总数量</label>
	<input type="text" id="info_num_total" name="info[num_total]" value="<?php echo $TEMPLATE['data']['num_total']; ?>"/>
	<label for="info_num_user">每人限购数量</label>
	<input type="text" id="info_num_user" name="info[num_user]" value="<?php echo $TEMPLATE['data']['num_user']; ?>"/>
	<br/>
	<button class="btn btn-primary" type="submit">保存</button>
	<button class="btn" type="reset" onclick="history.go(-1);return false;">返回</button>
	<input type="hidden" name="info[id]" value="<?php echo $TEMPLATE['data']['id']; ?>" />
</form>
</div>
<?php include TEMPLATE_PATH . "/admin/common/footer.php"; ?>