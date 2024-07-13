<form action="" method="post" class="form-horizontal form-main">
<div id="main">
<div class="container-fluid">
<?php echo $this->Admin->admin_head('Danh sách Đăng ký học thử'); ?>
<?php echo $this->Admin->admin_breadcrumb('Đăng ký học'); ?>


<?php if($multiple_lang == true) : ?>
    <div class="lang">
        <a href="<?php echo $url_here; ?>?lang=vi" <?php if($lang == 'vi') echo 'class="active"'; ?>><i class="icon icon-vi"></i> Tiếng Việt</a>
        <a href="<?php echo $url_here; ?>?lang=en" <?php if($lang == 'en') echo 'class="active"'; ?>><i class="icon icon-en"></i> Tiếng Anh</a>
        <!--<a href="<?php echo $url_here; ?>?lang=jp" <?php if($lang == 'jp') echo 'class="active"'; ?>><i class="icon icon-en"></i> Tiếng Nhật</a>-->
        <!-- <a href="<?php echo $url_here; ?>?lang=cn" <?php if($lang == 'cn') echo 'class="active"'; ?>><i class="icon icon-en"></i> Tiếng Trung</a> -->
        <!--<a href="<?php echo $url_here; ?>?lang=kr" <?php if($lang == 'kr') echo 'class="active"'; ?>><i class="icon icon-en"></i> Tiếng Hàn</a>-->
    </div>
<?php endif; ?>

    <?php echo $this->Session->flash(); ?>

<div class="box">
<div class="box-content nopadding">

<?php /*
<div class="row-fluid" style="padding-top: 14px;">
    
<div class="category-select offset2 filter-item">
    <strong class="span2 filter-label">Lọc loại Đăng ký</strong>

    <select id="list_category">
        <option value="<?php echo DOMAINAD; ?>admin_contact/contact_list">Tất cả</option>
        <?php foreach($form_dk as $k=>$v) { ?>
        <option value="<?php echo DOMAINAD; ?>admin_contact/contact_list?form_type=<?php echo $k; ?>"
        <?php if(isset($_GET['form_type']) && $_GET['form_type'] == $k) echo 'selected="selected"'; ?>
        ><?php echo $v; ?></option>
        <?php } ?>
    </select>

    <span class="btn btn-warning" id="btn-filter">Lọc</span>
</div>
</div>
*/ ?>

<?php echo $this->Session->flash(); ?>
	<table class="table table-bordered table-hover">
		<tr class="warning" style="font-weight: bold;">
			<td width="30">STT</td>
			<td>Họ tên</td>
			<td>Email</td>
			<td>Điện thoại</td>
			<td>Khóa học</td>
			<td width="70">Trạng thái</td>
			<td width="70">Thay đổi</td>
		</tr>
		<?php if($this->data) : ?>
		<?php 
			$current_page = $this->Paginator->current(); 
			$i = 1;
			if($current_page != 1)	$i = $current_page * 10; 
			foreach($this->data as $v) : 
		?>
		<tr>
			<td><?php echo $i; $i++; ?></td>
			<td><?php echo $v['Contact']['fullname']; ?></td>
			<td><?php echo $v['Contact']['email']; ?></td>
			<td><?php echo $v['Contact']['phone']; ?></td>
			<td><?php echo $v['Contact']['content']; ?></td>
            <td>
                <a href="<?php echo DOMAINAD; ?>admin_contact/update_status/<?php echo $v['Contact']['id']; ?>">
                    <?php if($v['Contact']['status'] == 1) : ?>
                        <i class="icon icon-ok"></i>
                    <?php else : ?>
                        <i class="icon icon-pause"></i>
                    <?php endif; ?>
                </a>
            </td>
			<td>
                <a href="#" class="confirm-delete" goto="<?php echo DOMAINAD . 'admin_contact/contact_delete/' . $v['Contact']['id']; ?>"><i class="icon icon-trash"></i></a>
			</td>
		</tr>
		<?php endforeach; ?>
		<?php endif; ?>
	</table>

	<div class="pagination">
		<?php echo $this->Paginator->first('<< Đầu'); ?>	
		<?php echo $this->Paginator->numbers(array('separator'=>'')); ?>	
		<?php echo $this->Paginator->last('Cuối >>'); ?>	
	</div>
	 

    </div>
    </div>
</div>

</div>
</div>
</form>

<script type="text/javascript">
	$('select#list_category').change(function() {
		var v = $(this).val();

		document.location.href = v;
	});
</script>