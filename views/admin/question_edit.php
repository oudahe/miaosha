<?php include TEMPLATE_PATH . "/admin/common/header.php"; ?>
<iframe name="_iframe_inner_post" src="about:blank" style="display:none;"></iframe>
<?php include TEMPLATE_PATH . "/admin/common/navigation.php"; ?>
<div class="container">
<form action="?action=save" method="post">
<fieldset>
	<legend><?php if ($TEMPLATE['data']){ ?>编辑<?php } else { ?>添加<?php } ?>问答信息</legend>
	<label for="info_active_id">所属活动ID</label>
	<input type="text" id="info_active_id" name="info[active_id]" value="<?php echo $TEMPLATE['data']['active_id']; ?>"/>
	<label for="info_title">问题描述</label>
	<input type="text" id="info_title" name="info[title]" value="<?php echo $TEMPLATE['data']['title']; ?>"/>
	<label for="info_ask1">问题1</label>
	<input type="text" id="info_ask1" name="info[ask1]" value="<?php echo $TEMPLATE['data']['ask1']; ?>"/>
	<label for="info_answer1">答案1</label>
	<input type="text" id="info_answer1" name="info[answer1]" value="<?php echo $TEMPLATE['data']['answer1']; ?>"/>
	<label for="info_ask2">问题2</label>
	<input type="text" id="info_ask2" name="info[ask2]" value="<?php echo $TEMPLATE['data']['ask2']; ?>"/>
	<label for="info_answer2">答案2</label>
	<input type="text" id="info_answer2" name="info[answer2]" value="<?php echo $TEMPLATE['data']['answer2']; ?>"/>
	<label for="info_ask3">问题3</label>
	<input type="text" id="info_ask3" name="info[ask3]" value="<?php echo $TEMPLATE['data']['ask3']; ?>"/>
	<label for="info_answer3">答案3</label>
	<input type="text" id="info_answer3" name="info[answer3]" value="<?php echo $TEMPLATE['data']['answer3']; ?>"/>
	<label for="info_ask4">问题4</label>
	<input type="text" id="info_ask4" name="info[ask4]" value="<?php echo $TEMPLATE['data']['ask4']; ?>"/>
	<label for="info_answer4">答案4</label>
	<input type="text" id="info_answer4" name="info[answer4]" value="<?php echo $TEMPLATE['data']['answer4']; ?>"/>
	<label for="info_ask5">问题5</label>
	<input type="text" id="info_ask5" name="info[ask5]" value="<?php echo $TEMPLATE['data']['ask5']; ?>"/>
	<label for="info_answer5">答案5</label>
	<input type="text" id="info_answer5" name="info[answer5]" value="<?php echo $TEMPLATE['data']['answer5']; ?>"/>
	<label for="info_ask6">问题6</label>
	<input type="text" id="info_ask6" name="info[ask6]" value="<?php echo $TEMPLATE['data']['ask6']; ?>"/>
	<label for="info_answer6">答案6</label>
	<input type="text" id="info_answer6" name="info[answer6]" value="<?php echo $TEMPLATE['data']['answer6']; ?>"/>
	<label for="info_ask7">问题7</label>
	<input type="text" id="info_ask7" name="info[ask7]" value="<?php echo $TEMPLATE['data']['ask7']; ?>"/>
	<label for="info_answer7">答案7</label>
	<input type="text" id="info_answer7" name="info[answer7]" value="<?php echo $TEMPLATE['data']['answer7']; ?>"/>
	<label for="info_ask8">问题8</label>
	<input type="text" id="info_ask8" name="info[ask8]" value="<?php echo $TEMPLATE['data']['ask8']; ?>"/>
	<label for="info_answer8">答案8</label>
	<input type="text" id="info_answer8" name="info[answer8]" value="<?php echo $TEMPLATE['data']['answer8']; ?>"/>
	<label for="info_ask9">问题9</label>
	<input type="text" id="info_ask9" name="info[ask9]" value="<?php echo $TEMPLATE['data']['ask9']; ?>"/>
	<label for="info_answer9">答案9</label>
	<input type="text" id="info_answer9" name="info[answer9]" value="<?php echo $TEMPLATE['data']['answer9']; ?>"/>
	<label for="info_ask10">问题10</label>
	<input type="text" id="info_ask10" name="info[ask10]" value="<?php echo $TEMPLATE['data']['ask10']; ?>"/>
	<label for="info_answer10">答案10</label>
	<input type="text" id="info_answer10" name="info[answer10]" value="<?php echo $TEMPLATE['data']['answer10']; ?>"/>
	<br/>
	<button class="btn btn-primary" type="submit">保存</button>
	<button class="btn" type="reset" onclick="history.go(-1);return false;">返回</button>
	<input type="hidden" name="info[id]" value="<?php echo $TEMPLATE['data']['id']; ?>" />
</form>
</div>
<?php include TEMPLATE_PATH . "/admin/common/footer.php"; ?>