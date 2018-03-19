<?php include TEMPLATE_PATH . "/common/header.php"; ?>
<?php include TEMPLATE_PATH . "/common/navigation.php"; ?>
<div class="container-fluid">
<?php if ($TEMPLATE['list_active']){ ?>
	<?php foreach($TEMPLATE['list_active'] as $data) { ?>
		<?php 
		if ($TEMPLATE['list_active_goods'][$data['id']]) { 
			foreach ($TEMPLATE['list_active_goods'][$data['id']] as $goods) {
				echo '<div class="span4">';
				echo '<img src="' . $goods['img'] . '" style="width:500px;" />';
				echo '<br/>';
				echo '活动： ' . $data['title'] . '<br/>';
				echo '商品： ' . $goods['title'] . '<br/>';
				echo '价格： ' . $goods['price_discount'] . '<br/>';
				echo ' 原价： <span style="text-decoration:line-through">' . $goods['price_normal'] . '</span><br/>';
                echo '库存： ' . $goods['num_left'] . '<br/>';
                if ($goods['sys_status'] == 0) {
                    echo '商品待上线，敬请期待';
                } elseif ($goods['sys_status'] == 1) {
                    if ($goods['num_left'] < 1) {
                        echo '商品已抢光，下次再来吧';
                    } else {
                        echo '<a href="/buy.php?id=' . $goods['id'] . '" onclick="check_status(' . $data['id'] . ', ' . $goods['id'] . ');return false;">立即抢购</a>';
                        echo '&nbsp; | &nbsp;';
                        echo '<a href="javascript:void();" onclick="addCart(' . $data['id'] . ', ' . $goods['id'] . ');return false;">加入购物车</a>';
                    }
                } elseif ($goods['sys_status'] == 2) {
                    echo '商品已下线，下次再来买吧';
                }
                echo '</div>';
			}
		}
		?>
	<?php } ?>
<?php } else { ?>
		<center>
			暂时还没有秒杀活动信息
		</center>
<?php } ?>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
			<form action="buy.php" method="post">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	                <h4 class="modal-title" id="myModalLabel">秒杀问答</h4>
	            </div>
	            <div id="question_info">
	            </div>
			</form>
        </div>
    </div>
</div>
<script type="text/javascript">
	<?php if (!isset($TEMPLATE['login_userinfo']['uid'])) { ?>
// 验证活动、商品状态
function check_status(aid, goods_id) {
    alert("请先登录");
}
// 获取问答信息
function getQuestion(aid, goods_id, user_sign) {
	alert("请先登录");
}		
// 加入购物车
function addCart(aid, goods_id) {
	alert("请先登录");
}
	<?php } else { ?>
// 验证活动、商品状态
function check_status(aid, goods_id) {
    var status_url = '/astatus/' + aid + '_' + goods_id + '.js';
    htmlobj = $.ajax({
        "url":status_url, async:true, 'dataType':'json', 'success': function(d) {
//            console.log(d);
            if (d['error_no']) {
                alert(d['error_msg']);
            } else {
                getQuestion(aid, goods_id, d['user_sign']);
            }
        }
    }
    );
}
// 获取问答信息
function getQuestion(aid, goods_id, user_sign) {
	var question_url = '/question.php?aid=' + aid;
	htmlobj = $.ajax({
			"url":question_url, async:true, 'dataType':'json', 'success': function(d) {
//				console.log(d);
				var sign = d['sign'];
				var ask = d['ask'];
				var title = d['title'];
				var html = '';
				for (var i in d['datalist']) {
					html += '<label><input type="radio" value="' + d['datalist'][i] + '" name="answer" /> ' + d['datalist'][i] + '</label><br/>';
				}
				html += '<label>商品数量： <input type="text" name="goods_num" value="1" /></label>';
				html += '<input type="hidden" name="question_sign" value="' + sign + '" />';
				html += '<input type="hidden" name="ask" value="' + ask + '" />';
				html += '<input type="hidden" name="active_id" value="' + aid + '" />';
				html += '<input type="hidden" name="goods_id" value="' + goods_id + '" />';
                html += '<input type="hidden" name="user_sign" value="' + user_sign + '" />';
				$("#question_info").html('<div class="modal-body">' + title + '[' + ask + ']</div>'
					+ '<div class="modal-body">' + html + '</div>'
					+ '<div class="modal-footer"><input type="submit" value="提交订单" /></div>');
				$('#myModal').modal();
		}
	});
}
// 加入购物车
function addCart(aid, goods_id) {
	$.cookie('mycarts_' + goods_id, aid);
	alert('成功加入购物车');
}
	<?php } ?>
</script>
<iframe name="iframe_inner_post" src="about:blank" style="display:none;"></iframe>
<script type="text/javascript" src="/static/js/jquery.cookie.js"></script>
<?php include TEMPLATE_PATH . "/common/footer.php"; ?>