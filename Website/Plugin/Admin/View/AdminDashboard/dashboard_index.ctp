<div class="navbar margin-none">
    <div class="navbar-inner radius-none">
        <a class="brand" href="#">Thống kê cơ bản</a>
    </div>
</div>

<div class="well bg-white radius-none">
<table>
	<tr>
		<td width="240"><strong>Tổng sản phẩm : </strong></td>
		<td><?php echo $tong_sp; ?> sản phẩm</td>
	</tr>
	<tr>
		<td><strong>Tổng sản phẩm còn hàng : </strong></td>
		<td><?php echo $mathangton; ?> sản phẩm</td>
	</tr>
	<tr>
		<td width="240"><strong>Số lượng bán trong tháng : </strong></td>
		<td><?php echo $sell_in_month; ?> cái</td>
	</tr>
	<tr>
		<td class="text-info"><strong>Tổng tiền đã nhập : </strong></td>
		<td><?php if(isset($tong_tien_nhap['0']['0']['tong'])) echo number_format($tong_tien_nhap['0']['0']['tong']) . ' VND'; ?></td>
	</tr>
	<tr>
		<td class="text-error"><strong>Tổng tiền đã bán : </strong></td>
		<td><?php if(isset($tong_tien_ban['0']['0']['tong'])) echo number_format($tong_tien_ban['0']['0']['tong']) . ' VND'; ?></td>
	</tr>
</table>
</div>