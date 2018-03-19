<?php include TEMPLATE_PATH . "/admin/common/header.php"; ?>
<?php include TEMPLATE_PATH . "/admin/common/navigation.php"; ?>
<div class="container-fluid">
		<a href="?action=edit" class="btn btn-primary">添加问答</a>

	<?php if ($TEMPLATE['datalist']){ ?>
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>ID</th>
				<th>活动ID</th>
				<th>问答描述</th>
				<th>问题&答案</th>
				<th>状态</th>
				<th>操作</th>
			</tr>
		</thead>
	<?php foreach($TEMPLATE['datalist'] as $data) { ?>
		<tbody>
			<tr>				
				<td><?php echo $data['id'];?></td>
				<td><?php echo $data['active_id'];?></td>
                <td><?php echo htmlspecialchars($data['title']);?></td>
                <td><?php for($i=1;$i<=10;++$i){
                	echo '<span>' . $i . '&nbsp;';echo $data['ask' . $i]; 
                	echo '&nbsp;&nbsp;'; 
                	echo  $data['answer' . $i];
                	echo '</span>&nbsp;&nbsp;&nbsp;&nbsp;';
                }?></td>
				<td><?php echo $arr_question_status[$data['sys_status']];?></td>
				<td>
					<a href="?action=edit&id=<?php echo $data['id'];?>">编辑</a>
					<?php if ($data['sys_status']==='1') {?>
					 | <a href="?action=reset&id=<?php echo $data['id'] ;?>">恢复</a>
					<?php } else { ?>
                     | <a href="?action=delete&id=<?php echo $data['id'];?>">删除</a>
                    <?php } ?>
				</td>
			</tr>
		</tbody>
	<?php } ?>
	</table>
	<?php } else { ?>
		<center>
			暂时还没有问答信息，现在就来<a href="?action=edit">添加问答</a>
		</center>
	<?php } ?>
</div>
<iframe name="iframe_inner_post" src="about:blank" style="display:none;"></iframe>
<?php include TEMPLATE_PATH . "/admin/common/footer.php"; ?>