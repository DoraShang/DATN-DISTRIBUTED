
    <div class="main">
        <div class="container-fluid">
            <div class="tb-wrap hide">
                <h3>Danh sách thiết bị</h3>
                <ul class="tb-list" id="tb_list">
                </ul>
                <div class="text-center">
                    <button class="them-tb" onclick="modal_add_vehicle()">Thêm thiết bị</button>
                    <button class="them-tb" onclick="scan_vehicles()">Quét thiết bị</button>
                </div>
            </div>
            <div class="tb-control-wrap">
                <div class="title d-flex justify-between alig align-center">
                    <h3>Xe 1</h3>
                    <h3 class="vehecle-ip">
                        0.0.0.0
                    </h3>
                    <button class="doi-tb" onclick="swith_vehicles()">
                        Đổi thiết bị
                    </button>
                </div>
                <div class="control-body">
                    <div class="row">
                        <div class="col-sm-6 col-xs-12">
                            <div>
                                <h3 class="txt-title">Video camera</h3>
                                <div class="video-stream"
                                    style="background-image: url('<?php echo $theme_directory .'img/video_404.png'?>');">
                                    <img id="video" src="" alt="">
                                </div>
                            </div>
                            <div class="parent center-control">
                                <h3 class="txt-title">Trung Tâm điều khiển</h3>
                                <div>
                                    <div class="set blue">
                                        <nav class="d-pad hide">
                                            <a class="up control" data-direction="up"></a>
                                            <a class="right control" data-direction="right"></a>
                                            <a class="down control" data-direction="down"></a>
                                            <a class="left control" data-direction="left"></a>
                                        </nav>
                                        <nav class="o-pad">
                                            <a class="up control" href="javascript:;" data-direction="up"
                                                onclick="to_up()"></a>
                                            <a class="right control" href="javascript:;" data-direction="right"
                                                onclick="to_right()"></a>
                                            <a class="down control" href="javascript:;" data-direction="down"
                                                onclick="to_down()"></a>
                                            <a class="left control" href="javascript:;" data-direction="left"
                                                onclick="to_left()"></a>
                                        </nav>
                                    </div>
                                    <div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <h3 class="text-center txt-title">Thông số cảm biến</h3>
                            <div class="d-flex align-start">
                                <div>
                                    <div class="sensor-list">
                                        <div class="d-flex align-center">
                                            <div class="sensor-item">
                                                <div class="line">
                                                    <span class="line-1"></span>
                                                </div>
                                                <div class="sensor1-txt">
                                                    0
                                                </div>
                                            </div>
                                            <div class="sensor-item">
                                                <div class="line">
                                                    <span class="line-2"></span>
                                                </div>
                                                <div class="sensor2-txt">
                                                    0
                                                </div>
                                            </div>
                                            <div class="sensor-item">
                                                <div class="line">
                                                    <span class="line-3"></span>
                                                </div>
                                                <div class="sensor3-txt">
                                                    0
                                                </div>
                                            </div>
                                            <div class="sensor-item">
                                                <div class="line">
                                                    <span class="line-4"></span>
                                                </div>
                                                <div class="sensor4-txt">
                                                    0
                                                </div>
                                            </div>
                                            <div class="sensor-item">
                                                <div class="line">
                                                    <span class="line-5"></span>
                                                </div>
                                                <div class="sensor5-txt">
                                                    0
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="infor-vehical">
                                        <h3 class="txt-title">Thông tin</h3>
                                        <div class="vehicle-mode">
                                            <b>Trạng thái xe: </b>
                                            <select onchange="swith_mode(this)">
                                                <option value="auto" selected>Tự động</option>
                                                <option value="manual">Thủ công</option>
                                            </select>
                                        </div>
                                        <div class="vehicle-members">
                                            <p><b>Số thiết bị đang kết nối: </b> <span>0</span></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="console">
                                    <div class="consolebody">
                                        <p>>Commands log</p>
                                        <p>>...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_add_tb" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Thêm thiết bị</h4>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="form-group">
                            <input type="text" class="form-control tb-ip" placeholder="IP" name="" style="color: #000;">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="add_vehicle()">Thêm</button>
                    <button type="button" class="btn btn-default btn-modal-close" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="loading hide">
    <img src="https://www.ttfabrics.com/images/loading.gif"
        alt="BERRY SWEET | Timeless Treasures Fabrics | Wholesale Fabric Supplier ..." class=" nofocus" tabindex="0"
        aria-label="BERRY SWEET | Timeless Treasures Fabrics | Wholesale Fabric Supplier ..." role="button">
</div>

<?php	
$scripts = array(
    // 'js/wow.min.js',
    // 'js/jquery.datetimepicker.full.min.js',
    // 'js/jquery_number.js',
    // 'js/typeaheap.js',
    'js/constant.js',
    // 'js/user.js',
    'js/app.js',
    // 'js/test.js'
); 
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.7.5/socket.io.js"
    integrity="sha512-luMnTJZ7oEchNDZAtQhgjomP1eZefnl82ruTH/3Oj/Yu5qYtwL7+dVRccACS/Snp1lFXq188XFipHKYE75IaQQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<?php foreach ($scripts as $v) : ?>
<script type="text/javascript" src="<?php echo $theme_directory; ?><?php echo $v; ?>?v=<?php echo time(); ?>">
</script>
<?php endforeach; ?>