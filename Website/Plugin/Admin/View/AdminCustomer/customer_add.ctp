<div class="navbar margin-none">
    <div class="navbar-inner radius-none">
        <a class="brand" href="#">Thêm mới tài khoản</a>
    </div>
</div>

<div class="clearfix"></div>

<div class="well bg-white radius-none">    

<span class="text-error"><h4><?php echo $this->Session->flash(); ?></h4></span>
<form method="post" class="form-horizontal" action="">
	<div class="control-group">
		<label class="control-label">Họ và tên</label>
		<div class="controls">
			<?php echo $this->Form->input('Customer.fullname', array('type'=>'text','label'=>false,'div'=>false)); ?>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label">Email</label>
		<div class="controls">
			<?php echo $this->Form->input('Customer.email', array('type'=>'text','label'=>false,'div'=>false)); ?>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label">Mật khẩu</label>
		<div class="controls">
			<?php echo $this->Form->input('Customer.password', array('type'=>'text','label'=>false,'div'=>false, 'value'=>'')); ?>
			<small><i>Bỏ trống nếu không thay đổi</i></small>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label">Address</label>
		<div class="controls">
			<?php echo $this->Form->input('Customer.address', array('type'=>'text','label'=>false,'div'=>false)); ?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Khóa học</label>
		<div class="controls">
			<?php echo $this->Form->input('Customer.khoahoc', array('type'=>'text','label'=>false,'div'=>false)); ?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Level</label>
		<div class="controls">
			<?php echo $this->Form->input('Customer.level', array('type'=>'text','label'=>false,'div'=>false)); ?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Score</label>
		<div class="controls">
			<?php echo $this->Form->input('Customer.score', array('type'=>'text','label'=>false,'div'=>false)); ?>
		</div>
	</div>


	<!--<div class="control-group">
		<label class="control-label">Thư mục upload</label>
		<div class="controls">
			<?php echo $this->Form->input('Admin.folder', array('type'=>'text','label'=>false,'div'=>false)); ?>
			<em><i>(Nên để tên thư mục là chữ cái hoặc số không dấu, không có khoảng cách)</i></em>
		</div>
	</div>-->

	<!--<div class="control-group">
		<label class="control-label">Quyền</label>
		<div class="controls">
			<input type="checkbox" name="role[]" value="category" /> Mục lục &nbsp;
			<input type="checkbox" name="role[]" value="page" /> Page &nbsp;
			<input type="checkbox" name="role[]" value="product" /> Sản phẩm &nbsp;
			<input type="checkbox" name="role[]" value="news" /> Tin tức &nbsp;

			<br />
			<br />
			
			<input type="checkbox" name="role[]" value="banner" /> Media &nbsp;
			<input type="checkbox" name="role[]" value="account" /> Tài khoản &nbsp;
			<input type="checkbox" name="role[]" value="setting" /> Thiết lập &nbsp;
		</div>
	</div>-->

	<div class="control-group">
		<label class="control-label">&nbsp;</label>
		<div class="controls">
			<input type="submit" name="submit" value="Xác nhận" class="btn btn-primary" />
		</div>
	</div>
</form>
</div>