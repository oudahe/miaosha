<?php include TEMPLATE_PATH . "/admin/common/header.php"; ?>
<?php include TEMPLATE_PATH . "/admin/common/navigation.php"; ?>
<div class="container-fluid">
	<?php if ($TEMPLATE['datalist']){ ?>
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>ID</th>
				<th>活动ID</th>
				<th>用户ID</th>
                <th>操作</th>
                <th>结果</th>
                <th>详情</th>
                <th>时间</th>
                <th>IP</th>
				<th>状态</th>
				<th>操作</th>
			</tr>
		</thead>
	<?php foreach($TEMPLATE['datalist'] as $data) { ?>
		<tbody>
			<tr>				
				<td><?php echo $data['id'];?></td>
                <td><?php echo $data['active_id'];?></td>
                <td><?php echo $data['uid'];?></td>
                <td><?php echo $data['action'];?></td>
                <td><?php echo $data['result'];?></td>
                <td><?php echo $data['info'];?></td>
				<td><?php echo date('Y-m-d H:i:s', $data['sys_dateline']);?></td>
				<td><?php echo $data['sys_ip'];?></td>
				<td><?php echo $arr_log_status[$data['sys_status']];?></td>
				<td>
					<?php if ($data['sys_status']==='1') {?>
					 <a href="?action=reset&id=<?php echo $data['id'];?>">确认处理</a>
                    <?php } ?>
				</td>
			</tr>
		</tbody>
	<?php } ?>
	</table>
	<?php } else { ?>
		<center>
			暂时还没有日志信息
		</center>
	<?php } ?>
</div>
<iframe name="iframe_inner_post" src="about:blank" style="display:none;"></iframe>
<?php include TEMPLATE_PATH . "/admin/common/footer.php"; ?>