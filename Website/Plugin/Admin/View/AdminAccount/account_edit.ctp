<div id="main">
    <div class="container-fluid">

        <div class="page-header">
            <div class="pull-left">
                <h3>Sửa đổi tài khoản</h3>
            </div>
        </div>

        <div class="breadcrumbs">
            <ul>
                <li>
                    <a href="/admin">Trang quản trị</a>
                    <i class="icon-angle-right"></i>
                </li>
                <li>
                    <a href="/admin/admin_account/account_list">Danh sách tài khoản</a>
                    <i class="icon-angle-right"></i>
                </li>
                <li>
                    <a href="/admin/admin_account/account_add">Tạo mới</a>
                    <i class="icon-angle-right"></i>
                </li>
                <li>
                    <a href="#" onclick="return false;">Cập nhật tài khoản</a>
                </li>
            </ul>
            <div class="close-bread">
                <a href="#">
                    <i class="icon-remove"></i>
                </a>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span12">
                <div class="box">
                    <div class="box-content">

                        <form action="" class="form-horizontal form-validate" enctype="multipart/form-data" id="bb" method="post" novalidate="novalidate">
                            <div class="tab-list">
                                <ul>
                                    <li>
                                        <a href="#tabinfo" class="active">Thông tin tài khoản</a>
                                    </li>
                                    <li>
                                        <a href="#tabpermission">Quyền truy cập</a>
                                    </li>
                                </ul>
                            </div>


                            <div id="tabinfo" class="tab-body">
                                <div class="control-group">
                                    <label for="email" class="control-label">Tên đăng nhập</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('Admin.username', array('type' => 'text', 'label' => false, 'div' => false, 'class' => 'input-xlarge')); ?>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label for="fullname" class="control-label">Tên đầy đủ</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('Admin.fullname', array('type' => 'text', 'label' => false, 'div' => false, 'class' => 'input-xlarge')); ?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label for="Phone" class="control-label">Số điện thoại</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('Admin.phone', array('type' => 'text', 'label' => false, 'div' => false, 'class' => 'input-xlarge')); ?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label for="email" class="control-label">Email</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('Admin.email', array('type' => 'text', 'label' => false, 'div' => false, 'class' => 'input-xlarge')); ?>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label for="password" class="control-label">Mật khẩu</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('Admin.password', array('type' => 'password', 'label' => false, 'div' => false, 'class' => 'input-xlarge', 'value' => '')); ?>
                                    </div>
                                </div>

                                <!--              <div class="control-group">
            <label for="fullname" class="control-label">Là Admin?</label>
            <div class="controls">
                <input checked="checked" class="input-xlarge" name="IsAdmin" type="checkbox" value="true"><input name="IsAdmin" type="hidden" value="false">
            </div>
        </div> -->

                                <!--      <div class="control-group">
            <label>Hình ảnh cá nhân</label> 
            <div class="controls">
                <div class="thumbnail-preview image"></div>
                <?php echo $this->Form->input('Admin.avatar', array('type' => 'text', 'label' => false, 'div' => false, 'id' => 'image', 'class' => 'span6 image_preview')); ?>
                <input type="button" class="btn btn-success" onclick="file_manager('image');" value="Chọn ảnh"> &nbsp;(.jpg, .gif, .png, .swf) 
            </div>
        </div> -->

                                <div class="form-actions">
                                    <input type="submit" class="btn btn-primary" value="Submit">
                                    <button type="button" class="btn">Cancel</button>
                                </div>
                            </div>

                            <div id="tabpermission" class="tab-body hide">
                                <div class="row-fluid">
                                    <table class="table table-hover table-nomargin dataTable table-bordered advertiseTable">
                                        <thead>
                                            <tr>
                                                <th width="80">STT</th>
                                                <th>Tên module</th>
                                                <th>Được xem</th>
                                                <th>Thêm mới</th>
                                                <th>Sửa</th>
                                                <th>Xóa</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $role = array();
                                            if ($this->data['Admin']['role'] != '')
                                                $role = explode(',', $this->data['Admin']['role']);

                                            foreach ($sidebar as $k => $v) {
                                                $prefix_lb = '';
                                                $row = array();

                                                if (is_array($v)) {
                                                    $i = 0;
                                                    foreach ($v as $key => $val) {
                                                        // pr($val);
                                                        if (is_array($val)) {
                                                            foreach ($val as $bk => $bv) {
                                                                $prefix_lb = '<span></span><span></span>';
                                                                $row[$prefix_lb . $k . ' -> ' . $key . ' -> ' . $bk] = $bv;
                                                            }
                                                        } else {
                                                            $prefix_lb = '<span></span>';
                                                            $row[$prefix_lb . $k . ' -> ' . $key] = $val;
                                                        }
                                                    }
                                                } else {
                                                    $row[$k] = $v;
                                                }
                                            ?>

                                                <tr class="tdhead">
                                                    <td colspan="6"><?php echo $k; ?></td>
                                                </tr>

                                                <?php $i = 0;
                                                foreach ($row as $label => $ctrl) {
                                                    $i++; ?>
                                                    <?php

                                                    if ($ctrl != '' && preg_match('/\//', $ctrl)) {
                                                        $d = explode('/', $ctrl);
                                                        $de = explode('_', $d[1]);
                                                        $ctr_name = $de[0] . '_';
                                                    ?>
                                                        <tr data-module="1" data-role="152" class="chkRole">
                                                            <td>
                                                                <div class="sort-text">
                                                                    <?php echo $i; ?>
                                                                </div>
                                                            </td>
                                                            <td><?php echo $label; ?></td>
                                                            <td>
                                                                <input type="checkbox" name="role[]" <?php if (in_array($ctr_name . 'list', $role)) echo 'checked="checked"'; ?> value="<?php echo $ctr_name; ?>list" class="isread">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" name="role[]" <?php if (in_array($ctr_name . 'add', $role)) echo 'checked="checked"'; ?> value="<?php echo $ctr_name; ?>add" class="isread">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" name="role[]" <?php if (in_array($ctr_name . 'edit', $role)) echo 'checked="checked"'; ?> value="<?php echo $ctr_name; ?>edit" class="isread">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" name="role[]" <?php if (in_array($ctr_name . 'delete', $role)) echo 'checked="checked"'; ?> value="<?php echo $ctr_name; ?>delete" class="isread">
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </form>
                    </div>


                </div>
            </div>
        </div>

    </div>
</div>