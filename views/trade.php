<?php include TEMPLATE_PATH . "/common/header.php"; ?>
<?php include TEMPLATE_PATH . "/common/navigation.php"; ?>
<div class="container-fluid">
<?php if ($TEMPLATE['list_trade']){ ?>
	<?php foreach($TEMPLATE['list_trade'] as $data) { ?>
        <div class="span3">
		<?php 
		$goods_infos = json_decode($data['goods_info'], 1);
		foreach ($goods_infos as $goods_info) {
			if ($goods_info) { 
				$goods = $goods_info['goods_info'];
				$num = $goods_info['goods_num'];
				echo '<div>';
				echo '<img src="' . $goods['img'] . '" style="width:500px;" />';
				echo '<br/>';
				echo '商品： ' . $goods['title'] . '<br/>';
				echo '价格： ' . $goods['price_discount'] . '<br/>';
				echo ' 原价： <span style="text-decoration:line-through">' . $goods['price_normal'] . '</span><br/>';
				echo '数量： ' . $num . '<br/>';
				echo '</div>';
			}			
		}
		echo '<div style="clear:both;">';
		echo '状态： ' . $arr_trade_status[$data['sys_status']] . '&nbsp;&nbsp;<br/>';
		if ($data['sys_status'] < 2) {
			echo '<a href="/pay.php?id=' . $data['id'] . '">立即支付</a>';
			echo '&nbsp; | &nbsp;';
		}
		if ($data['sys_status'] < 5) {
			echo '<a href="/pay.php?action=cancel&id=' . $data['id'] . '">取消订单</a>';
		}
		echo '</div>';
		?>
        </div>
	<?php } ?>
<?php } else { ?>
		<center>
			暂时还没有秒杀订单信息
		</center>
<?php } ?>
</div>

<iframe name="iframe_inner_post" src="about:blank" style="display:none;"></iframe>
<?php include TEMPLATE_PATH . "/common/footer.php"; ?>