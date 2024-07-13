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

class AdminPartnerController extends AdminAppController {

    public $uses = array('Partner');

    public function partner_index($type = null) {
        $this->autoRender = false;
    }

    public function partner_list() {
        $this->paginate = array(
            'limit' => 12,
            'order' => 'Partner.pos DESC, Partner.id DESC'
        );

        $this->data = $this->paginate('Partner');
    }

    public function partner_add() {
        if ($this->data) {
            $data = $this->data['Partner'];
            $data['image'] = $this->remove_hostname($data['image']);
            if(isset($data['category_id']) && $data['category_id'] == "")
                $data['category_id'] = 0;
            $this->Partner->save($data);
            $this->Session->setFlash('Đã thêm mới hình ảnh', 'success');
            $this->redirect($this->referer());
        }
    }

    public function partner_edit($id = null) {
        if ($this->data) {
            $data = $this->data['Partner'];
            $data['image'] = $this->remove_hostname($data['image']);
            $data['created'] = time();
            if(isset($data['category_id']) && $data['category_id'] == "")
                $data['category_id'] = 0;
            $this->Partner->id = $id;
            $this->Partner->save($data);
            $this->Session->setFlash('Đã sửa hình ảnh', 'success');
            $this->redirect($this->referer());
        }

        $this->data = $this->Partner->findById($id);
    }

    public function save_pos() {
        $vitri = $_POST['pos'];
        foreach ($vitri as $k => $v) {
            if ($v == "") {
                $v = 0;
            }
            $this->Partner->updateAll(
                    array(
                'Partner.pos' => $v,
                    ), array(
                'Partner.id' => $k
                    )
            );
        }

        $this->redirect($this->referer());
    }

    public function partner_delete($id = null) {
        $this->autoRender = false;
        $this->Partner->id = $id;
        $this->Partner->delete($id);
        $this->Session->setFlash('Đã xóa hình ảnh');
        $this->redirect($this->referer());
    }

}
