<?php /*

<table class="table">
	<tr>
		<td>STT</td>
		<td>Size</td>
		<td>Hình ảnh </td>
		<td>Số lượng</td>
		<td> Hãng </td>
		<?php for($i=$first; $i<=$last; $i++) : ?>
		<td><?php echo $i; ?></td>
		<?php endfor; ?>
	</tr>

	<?php if($count > 0) : ?>

	<?php $i=1; foreach($da_nhap as $v) : ?>
		<tr>
			<td><?php echo $i; ?></td>
			<td><?php echo $v['Nhap']['size']; ?></td>
			<td></td>
			<td><?php echo $v['Nhap']['quality']; ?></td>
			<td><?php echo $categories[$v['Nhap']['category_id']]; ?></td>
			<?php for($j=$first; $j<=$last; $j++) : ?>
			<td><?php  ?></td>
			<?php endfor; ?>	
		</tr>
	<?php $i++; endforeach; ?>

	<?php endif; ?>
</table>
*/ ?>