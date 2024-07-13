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

App::uses('CKEditor', 'Vendor');
App::uses('CKFinder', 'Vendor');

class AdminBenefitController extends AdminAppController {

    public $uses = array('Benefit');
    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    public function benefit_index($type = null) {
        $this->autoRender = false;
    }

    public function benefit_list() {
     
        $this->paginate = array(
            'limit' => 12,
            'order' => 'Benefit.id DESC'
        );

        $this->data = $this->paginate('Benefit');
    }

    public function benefit_add() {
        if ($this->data) {
            $data = $this->data['Benefit'];
            $data['image'] = $this->remove_hostname($data['image']);
            if(isset($data['category_id']) && $data['category_id'] == "")
                $data['category_id'] = 0;
            $this->Benefit->save($data);
            $this->Session->setFlash('Đã thêm mới Tiện ích', 'success');
            $this->redirect($this->referer());
        }
    }

    public function benefit_edit($id = null) {
        if ($this->data) {
            $data = $this->data['Benefit'];
            $data['image'] = $this->remove_hostname($data['image']);
            $data['created'] = time();
            if(isset($data['category_id']) && $data['category_id'] == "")
                $data['category_id'] = 0;
            $this->Benefit->id = $id;
            $this->Benefit->save($data);
            $this->Session->setFlash('Đã sửa Tiện ích', 'success');
            $this->redirect($this->referer());
        }

        $this->data = $this->Benefit->findById($id);
    }

    public function save_pos() {
        $vitri = $_POST['pos'];
        foreach ($vitri as $k => $v) {
            if ($v == "") {
                $v = 0;
            }
            $this->Benefit->updateAll(
                    array(
                'Benefit.pos' => $v,
                    ), array(
                'Benefit.id' => $k
                    )
            );
        }

        $this->redirect($this->referer());
    }

    public function benefit_delete($id = null) {
        $this->autoRender = false;
        $this->Benefit->id = $id;
        $this->Benefit->delete($id);
        $this->Session->setFlash('Đã xóa Tiện ích', 'success');
        $this->redirect($this->referer());
    }

}
