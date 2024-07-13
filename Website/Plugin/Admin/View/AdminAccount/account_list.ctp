<div id="main">
    <div class="container-fluid">

		<div class="page-header">
		    <div class="pull-left">
		        <h3>Quản lý tài khoản đăng nhập</h3>
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
		        </li>
		    </ul>
		    <div class="close-bread">
		        <a href="#">
		            <i class="icon-remove"></i>
		        </a>
		    </div>
		</div>


		<div style="margin:10px 0;" class="row-fluid">
		   <!--  <div class="btn-group">
		        <div class="btn-group">
	              	<select name="sort" id="btnChangeAdminType" data-placeholder="Lọc theo nhóm" data-nosearch="true" style="float: left">
			            <option value="">Lọc nhóm người dùng</option>
		                <option value="1" <?php if($type == '1') echo 'selected="selected"'; ?>>Admin</option>
		                <option value="2" <?php if($type == '2') echo 'selected="selected"'; ?>>Nhân viên</option>
		                <option value="3" <?php if($type == '3') echo 'selected="selected"'; ?>>Cộng tác viên</option>
			        </select>

			        <select name="sort" id="btnModerateBy" data-placeholder="Lọc theo nhóm" data-nosearch="true" style="margin-left: 10px; float: left; margin-right: 4px;">
			            <option value="">Lọc theo quản lý</option>

			            <?php foreach($nhanvien as $k=>$v) { ?>
		                <option value="<?php echo $k; ?>" <?php if($parent_id == $k) echo 'selected="selected"'; ?>>
		                	<?php echo $v; ?>
		                </option>
		                <?php } ?>
			        </select>
		            <a style="float: left; " class="btn" rel="tooltip" title="" id="btnAdminFilter" data-original-title="Lọc"><i class="icon-ok"></i></a>
		        </div>
		    </div> -->
		    
		    <a href="<?php echo DOMAINAD; ?>admin_account/account_add" class="btn btn-large btn btn-orange pull-right">Thêm</a>
		</div>
		        

		<div class="box box-color box-bordered">
		    <div class="box-title">
		        <h3><i class="icon-table"></i>Danh sách tài khoản  ( <?php echo $total; ?> tài khoản )</h3>
		    </div>            
		    
		    <div class="box-content nopadding">
		        <table class="table table-hover table-nomargin dataTable table-bordered">
		            <thead>
		                <tr>
		                    <th>STT</th>
		                    <th>Tên tài khoản</th>
		                    <!-- <th>Vai trò</th> -->
		                    <th>Tên đăng nhập</th>
		                    <th>Email</th>
		                    <th>Điện thoại</th>
		                    <th>Lần đăng nhập cuối</th>
		                    <th>Kích hoạt</th>
		                    <!-- <th>Phụ trách</th> -->
		                    <th>Thao tác</th>
		                </tr>
		            </thead>
		            <tbody>
		                <?php if($this->data) : ?>
		                <?php 
		                    $current_page = $this->Paginator->current(); 
		                    $i = 1;
		                    if($current_page != 1)  $i = ($current_page - 1) * 20 + 1; 
		                    foreach($this->data as $v) : 
		                ?>
		                    <tr>
		                        <td><?php echo $i; ?></td>
		                        <td>
		                            <strong><?php echo $v['Admin']['fullname']; ?></strong>
		                        </td>
		                        <td>
		                            <?php echo $v['Admin']['username']; ?>
		                        </td>
		                      <!--   <td>
		                            <?php echo isset($types[$v['Admin']['type']]) ? $types[$v['Admin']['type']] : ''; ?>
		                        </td> -->
		                        <td><?php echo $v['Admin']['email']; ?></td>
		                        <td><?php echo $v['Admin']['phone']; ?></td>
		                        <td><?php echo is_numeric($v['Admin']['last_login']) ? date('d/m/Y h:i a', $v['Admin']['last_login']) : ''; ?></td>
	                          	<td class="hidden-1024">
	                          		<?php 
	                          			$lb = '<span class="label label-satgreen">Kích hoạt</span>';

	                          			if($v['Admin']['status'] != 1)
		                          			$lb = '<span class="label">Khoá</span>';

		                          		echo $lb;
	                          		?>
		                    	</td>
		                   <!--  <td>
		                        <?php echo isset($nhanvien[$v['Admin']['parent_id']]) ? $nhanvien[$v['Admin']['parent_id']] : ''; ?>
		                    </td> -->
		                        <td class="hidden-480">         
								    <a href="<?php echo DOMAINAD; ?>admin_account/account_edit/<?php echo $v['Admin']['id']; ?>" class="btn" rel="tooltip" title="" data-original-title="Edit"><i class="icon-edit"></i></a>
								    <a goto="<?php echo DOMAINAD; ?>admin_account/account_delete/<?php echo $v['Admin']['id']; ?>" class="btn confirm-delete" title="Delete"><i class="icon-remove"></i></a>
		                        </td>
		                    </tr>            
		                            
		                <?php $i++; endforeach; ?>
		                <?php endif; ?>  
		            </tbody>
		        </table>

		        <div class="pager1">
	    			<div class="pagination pagination-normal pagination-right ">
	    				<ul>
			            <?php echo $this->Paginator->first('<< Đầu', array('tag'=>'li')); ?>    
			            <?php echo $this->Paginator->numbers(array('separator'=>'', 'currentClass'=>'active', 'tag'=>'li', 'currentTag'=>'a')); ?>    
			            <?php echo $this->Paginator->last('Cuối >>', array('tag'=>'li')); ?>
	    				</ul>
			        </div>
		        </div>
		    </div>
		</div>                  
    </div>
</div>

<script type="text/javascript">
	$('#btnAdminFilter').click(function() {
		var u_type = $('#btnChangeAdminType').val();
		var u_parent = $('#btnModerateBy').val();

		var link = "<?php echo DOMAINAD; ?>admin_account/account_list?type=" + u_type + '&mtype=' + u_parent;
		document.location.href = link;

		return false;
	});
</script>