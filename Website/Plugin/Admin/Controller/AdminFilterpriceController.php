<?php
/**
 * PHP version 5
 * 
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 * @author   Bui Thanh Cong <buithanhcong.nd@gmail.com>
 * @license  MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class AdminFilterpriceController extends AdminAppController {

    public $uses = array('Banner');
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('banner_type', array(
            'logo'=>'Logo',
            'slideshow'=>'Slideshow',
            // 'slideshow'=>'Slideshow (trang trong)',
            'slideshow-mobile' => 'Slideshow Mobile',
            'partner' => 'Đối tác',
            // 'human'=>'Bộ máy nhân sự',
            // 'album'=>'Thư viện ảnh',
            'home-banner'=>'Banner trang chủ (ở giữa các danh sách sản phẩm)',
            // 'featured' => 'Banner (dưới slideshow trang chủ)',
            // 'tech' => 'Banner dưới dịch vụ nổi bật (trang chủ)',
            // 'picture'=>'Ảnh trước và sau (Trang chủ)',
            'sidebar' => 'Banner cột trái (trên)',
            'sidebar2' => 'Banner cột trái (dưới)',
            'sidebar-right' => 'Banner cột phải (trên)',
            'sidebar-right2' => 'Banner cột phải (dưới)',
            // 'banner' => 'Banner trang trong mặc định (dưới mục lục)',
            // 'intro' => 'Banner Intro',
            // 'contact' => 'Banner Liên hệ',
            // 'default-header'=>'Banner dưới menu trang chi tiết dịch vụ',
            // 'default-qc-dv'=>'Banner quảng cáo trên cùng bài chi tiết dịch vụ',
        ));
    }

    public function banner_index($type = null) {
        $this->autoRender = false;
    }

    public function banner_list() {
        $banner_current_type = isset($_GET['t']) ? $_GET['t'] : '';
        $con = array();

        if($banner_current_type == '')
        {
            $con['NOT']['Banner.type'] = 'hidden';
        }
        else
        {
            $con['Banner.type'] = $banner_current_type;
        }
        
        $this->paginate = array(
            'conditions'=>$con,
            'limit' => 12,
            'order' => 'Banner.pos DESC, Banner.id DESC'
        );

        $this->data = $this->paginate('Banner');
        $this->set('banner_current_type', $banner_current_type);
    }

    public function banner_add() {
        if ($this->data) {
            $data = $this->data['Banner'];
            $data['image'] = $this->remove_hostname($data['image']);
            if(isset($data['category_id']) && $data['category_id'] == "")
                $data['category_id'] = 0;
            $this->Banner->save($data);
            $this->Session->setFlash('Đã thêm mới hình ảnh', 'success');
            $this->redirect($this->referer());
        }
    }

    public function banner_edit($id = null) {
        if ($this->data) {
            $data = $this->data['Banner'];
            $data['image'] = $this->remove_hostname($data['image']);
            $data['created'] = time();
            if(isset($data['category_id']) && $data['category_id'] == "")
                $data['category_id'] = 0;
            $this->Banner->id = $id;
            $this->Banner->save($data);
            $this->Session->setFlash('Đã sửa hình ảnh', 'success');
            $this->redirect($this->referer());
        }

        $this->data = $this->Banner->findById($id);
    }

    public function save_pos() {
        $vitri = $_POST['pos'];
        foreach ($vitri as $k => $v) {
            if ($v == "") {
                $v = 0;
            }
            $this->Banner->updateAll(
                    array(
                'Banner.pos' => $v,
                    ), array(
                'Banner.id' => $k
                    )
            );
        }

        $this->redirect($this->referer());
    }

    public function banner_delete($id = null) {
        $this->autoRender = false;
        $this->Banner->id = $id;
        $this->Banner->delete($id);
        $this->Session->setFlash('Đã xóa hình ảnh', 'success');
        $this->redirect($this->referer());
    }

}
