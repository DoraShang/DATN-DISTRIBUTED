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

class AdminBannerController extends AdminAppController
{

    public $uses = array('Banner', 'Bosuutap');
    public $limit = 10;

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('bosuutap_list', $this->Bosuutap->find('list', array('fields' => array('id', 'title'))));

        $this->set('banner_type', array(
            // 'homebottom'=>'Trang chủ dưới danh sách khóa học',
            // 'bannerlinkhi'=>'Banner dưới Link hữu ích Trang chủ',
            // 'partner'=>'Đối tác',
            // 'duan'=>'Banner Dự án',
            // 'taitro'=>'Banner cột phải',
            'slideshow' => 'Slideshow',
            'video' => 'video slide',
            'slideshow_m' => 'Slideshow Mobile',
            // 'story'=>'Giới thiệu - Câu chuyện về chúng tôi',
            // 'rightSlideshow'=>'Banner bên phải Slideshow',
            // 'bottomSlideshow'=>'Banner bên dưới Slideshow',
        ));

        $this->set('limit', $this->limit);
    }

    public function banner_index($type = null)
    {
        $this->autoRender = false;
    }

    public function banner_list()
    {
        $this->_role('banner_list');
        $banner_current_type = isset($_GET['t']) ? $_GET['t'] : '';
        $con = array();

        if ($banner_current_type == '') {
            $con['NOT']['Banner.type'] = 'hidden';
        } else {
            $con['Banner.type'] = $banner_current_type;
        }

        $this->paginate = array(
            'conditions' => $con,
            'limit' => $this->limit,
            'order' => array('Banner.pos' => 'desc', 'Banner.id' => 'desc')
        );

        $this->data = $this->paginate('Banner');
        $this->set('banner_current_type', $banner_current_type);
    }

    public function banner_add()
    {
        $this->_role('banner_add');
        if ($this->data) {
            $data = $this->data['Banner'];
            $data['image'] = $this->remove_hostname($data['image']);
            if (isset($data['category_id']) && $data['category_id'] == "")
                $data['category_id'] = 0;
            $this->Banner->save($data);
            $id = $this->Banner->getLastInsertID();
            $this->Session->setFlash('Đã thêm mới hình ảnh', 'success');
            $this->redirect($this->get_redirect('banner', 'add', $id));
            die;
        }
    }
    public function update_field($field, $id)
    {
        $this->autoRender = FALSE;
        $changed = 1;
        $check = $this->Banner->findById($id);
        if ($check['Banner'][$field] == 1)
            $changed = 0;

        $this->Banner->id = $id;
        $this->Banner->saveField($field, $changed);
        $this->Session->setFlash('Đã thay đổi', 'success');
        $this->redirect($this->referer());
    }
    public function banner_edit($id = null)
    {
        $this->_role('banner_edit');
        if ($this->data) {
            $data = $this->data['Banner'];
            if (!preg_match('/youtube/', $data['image']))
                $data['image'] = $this->remove_hostname($data['image']);

            $data['created'] = time();
            if (isset($data['category_id']) && $data['category_id'] == "")
                $data['category_id'] = 0;
            $this->Banner->id = $id;
            $this->Banner->save($data);
            $this->Session->setFlash('Đã sửa hình ảnh', 'success');
            $this->redirect($this->get_redirect('banner', 'edit', $id));
            die;
        }

        $this->data = $this->Banner->findById($id);
    }

    public function save_pos()
    {
        $vitri = $_POST['pos'];
        foreach ($vitri as $k => $v) {
            if ($v == "") {
                $v = 0;
            }
            $this->Banner->updateAll(
                array(
                    'Banner.pos' => $v,
                ),
                array(
                    'Banner.id' => $k
                )
            );
        }

        $this->redirect($this->referer());
    }

    public function banner_delete($id = null)
    {
        $this->_role('banner_delete');
        $this->autoRender = false;
        $redirect_page = isset($_GET['rp']) && $_GET['rp'] > 1 ? 'page:' . $_GET['rp'] : '';
        $redirect_list = DOMAINAD . 'admin_banner/banner_list/' . $redirect_page;

        $this->Banner->id = $id;
        $this->Banner->delete($id);
        $this->Session->setFlash('Đã xóa hình ảnh', 'success');
        $this->redirect($redirect_list);
    }
}
