
<form action="" method="post" class="form-horizontal form-main">
<div id="main">
<div class="container-fluid">
<?php echo $this->Admin->admin_head('Thay đổi thông tin cá nhân'); ?>
<?php echo $this->Admin->admin_breadcrumb('Thay đổi Profile'); ?>

<?php echo $this->Session->flash(); ?>

<?php if($multiple_lang == true) : ?>
    <div class="lang">
        <a href="<?php echo $url_here; ?>?lang=vi" <?php if($lang == 'vi') echo 'class="active"'; ?>><i class="icon icon-vi"></i> Tiếng Việt</a>
        <a href="<?php echo $url_here; ?>?lang=en" <?php if($lang == 'en') echo 'class="active"'; ?>><i class="icon icon-en"></i> Tiếng Anh</a>
        <!--<a href="<?php echo $url_here; ?>?lang=jp" <?php if($lang == 'jp') echo 'class="active"'; ?>><i class="icon icon-en"></i> Tiếng Nhật</a>-->
        <!-- <a href="<?php echo $url_here; ?>?lang=cn" <?php if($lang == 'cn') echo 'class="active"'; ?>><i class="icon icon-en"></i> Tiếng Trung</a> -->
        <!--<a href="<?php echo $url_here; ?>?lang=kr" <?php if($lang == 'kr') echo 'class="active"'; ?>><i class="icon icon-en"></i> Tiếng Hàn</a>-->
    </div>
<?php endif; ?>

<div class="box">
<div class="box-content">

	<div class="control-group">
		<label class="control-label">Họ và Tên</label>
		<div class="controls">
			<?php 
			echo $this->Form->input('Admin.fullname', array('label'=>false,'div'=>false, 'class'=>'input-xlarge','required'=>'required')); 
			?>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label">Email</label>
		<div class="controls">
			<?php 
			echo $this->Form->input('Admin.email', array('label'=>false,'div'=>false, 'class'=>'input-xlarge','required'=>'required')); 
			?>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label">Mật khẩu mới</label>
		<div class="controls">
			<?php 
			echo $this->Form->input('Admin.new_password', array('label'=>false,'div'=>false, 'class'=>'input-xlarge','value'=>'')); 
			?>
                        <small><i>Bỏ trống nếu không thay đổi</i></small>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label">Mật khẩu hiện tại</label>
		<div class="controls">
			<?php 
			echo $this->Form->input('Admin.current_password', array('label'=>false,'div'=>false, 'class'=>'input-xlarge')); 
			?>
                        <small><i>Bỏ trống nếu không thay đổi mật khẩu</i></small>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label">&nbsp;</label>
		<div class="controls">
			<input type="submit" name="submit" value="Xác nhận" class="btn btn-primary" />
		</div>
	</div> 

</div>
</div>
</div>

</div>
</form>