<?php include TEMPLATE_PATH . "/common/header.php"; ?>
<?php include TEMPLATE_PATH . "/common/navigation.php"; ?>
<div class="container-fluid">
<?php if ($TEMPLATE['carts_list']){ ?>
	<form action="buy.php" method="post">
	<?php foreach($TEMPLATE['carts_list'] as $data) { ?>
		<?php 
		if ($data['goods']) { 
			$goods_list = $data['goods'];
			$active = $data['active'];
			foreach ($goods_list as $goods) {
				echo '<div class="span3">';
				echo '<img src="' . $goods['img'] . '" style="width:500px;" />';
				echo '<br/>';
				echo '活动： ' . $active['title'] . '<br/>';
				echo '商品： ' . $goods['title'] . '<br/>';
				echo '价格： ' . $goods['price_discount'] . '<br/>';
				echo ' 原价： <span style="text-decoration:line-through">' . $goods['price_normal'] . '</span><br/>';
				echo '数量： <input type="text" name="num[]" value="1" style="width:30px;" />';
				echo '<input type="hidden" name="goods[]" value="' . $goods['id'] . '" />';
				echo '</div>';
			}
		}
		?>
	<?php } ?>
	<br style="clear:both;"/>
	<center>
	<input type="hidden" name="action" value="buy_cart" />
	<input type="button" onclick="getQuestion(<?php echo $active['id'] . ', ' . $goods['id']; ?>);return false;" value="确定提交" />
	<input type="button" value="清空购物车" onclick="clearCarts();return false;" />
	</center>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	                <h4 class="modal-title" id="myModalLabel">秒杀问答</h4>
	            </div>
	            <div id="question_info">
	            </div>
        </div>
    </div>
</div>

	</form>
<?php } else { ?>
		<center>
			购物车没有商品，<a href="/">去首页看看吧</a>。
		</center>
<?php } ?>
</div>
<script type="text/javascript">
// 获取问答信息
function getQuestion(aid, goods_id) {
	var url = '/question.php?aid=' + aid;
	htmlobj = $.ajax({
			"url":url, async:false, 'dataType':'json', 'success': function(d) {
				console.log(d);
				var sign = d['sign'];
				var ask = d['ask'];
				var title = d['title'];
				var html = '';
				for (var i in d['datalist']) {
					html += '<label><input type="radio" value="' + d['datalist'][i] + '" name="answer" /> ' + d['datalist'][i] + '</label><br/>';
				}
				html += '<input type="hidden" name="question_sign" value="' + sign + '" />';
				html += '<input type="hidden" name="ask" value="' + ask + '" />';
				html += '<input type="hidden" name="active_id" value="' + aid + '" />';
				html += '<input type="hidden" name="goods_id" value="' + goods_id + '" />';
				$("#question_info").html('<div class="modal-body">' + title + '[' + ask + ']</div>'
					+ '<div class="modal-body">' + html + '</div>'
					+ '<div class="modal-footer"><input type="submit" value="提交订单" /></div>');
				$('#myModal').modal();
		}
	});
}
// 加入购物车
function clearCarts() {
	for (var p in $.cookie()) {
		if (p.indexOf('mycarts_') == 0) {
			$.removeCookie(p);
		}
	}
	location.href = '/cart.php';
}
</script>
<iframe name="iframe_inner_post" src="about:blank" style="display:none;"></iframe>
<script type="text/javascript" src="/static/js/jquery.cookie.js"></script>
<?php include TEMPLATE_PATH . "/common/footer.php"; ?>