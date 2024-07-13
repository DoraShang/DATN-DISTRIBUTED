<div id="main">
    <div class="container-fluid">
        <?php echo $this->Admin->admin_head('Danh sách tài khoản'); ?>
        <?php echo $this->Admin->admin_breadcrumb('Quản lý tài khoản'); ?>

        <div style="margin:10px 0 10px;" class="row-fluid">
            <?php echo $this->Session->flash(); ?>
        </div>

        <div class="box box-color box-bordered">
            <div class="box-title">
                <h3><i class="icon-table"></i>Danh sách</h3>
            </div>
            <div class="box-content nopadding">
                <table class="table table-hover table-nomargin dataTable table-bordered advertiseTable">
                    <thead>
                        <tr class="text-bold warning">
                            <th width="30">STT</th>
                            <td>Username</td>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Điện thoại</th>
                            <td width="70">Sửa</td>
                            <td width="70">Xóa</td>
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
                            <td><?php echo $v['User']['username']; ?></td>
                            <td><?php echo $v['User']['fullname']; ?></td>
                            <td><?php echo $v['User']['email']; ?></td>
                            <td><?php echo $v['User']['phone']; ?></td>
                            <td>
                                <a
                                    href="<?php echo Router::url(array('plugin'=>'admin','controller'=>'admin_customer','action'=>'customer_edit', $v['User']['id'])); ?>">
                                    <i class="icon icon-edit"></i></a>
                            </td>
                            <td>
                                <a href="#" class="confirm-delete"
                                    goto="<?php echo Router::url(array('plugin'=>'admin','controller'=>'admin_customer','action'=>'customer_delete', $v['User']['id'])); ?>">
                                    <i class="icon icon-trash"></i>
                                </a>
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