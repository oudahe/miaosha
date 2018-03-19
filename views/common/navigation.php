<!--导航 begin-->
	<div class="navbar">
		<div class="navbar-inner">
			<a class="brand" href="/list.php" target="_self">首页</a>
			<ul class="nav">
				<li <?php if ($TEMPLATE['type'] == 'trade'){?>class="active"<?php }?>><a href="/trade.php" target="_self">我的订单</a></li>
				<li <?php if ($TEMPLATE['type'] == 'cart'){?>class="active"<?php }?>><a href="/cart.php" target="_self">购物车</a></li>
		    </ul>
		    <ul class="nav pull-right">
				<li>
			<?php if (isset($TEMPLATE['login_userinfo']['uid']) && $TEMPLATE['login_userinfo']['uid']) { ?>
				<a href="/login.php?action=logout"><?php echo $TEMPLATE['login_userinfo']['username']; ?> 退出</a>
			<?php } else { ?>
				<a href="/login.php?action=login">登录</a>
			<?php } ?>
				</li>
			</ul>
		</div>
	</div>
	
<!--导航 end-->