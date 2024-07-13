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
class AdminVideoController extends AdminAppController {

    public $uses = array('Video');
    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    public function video_index($type = null) {
        $this->autoRender = false;
    }

    public function video_list() {
        $this->paginate = array(
            'limit' => 12,
            'order' => 'Video.pos DESC, Video.id DESC'
        );

        $this->data = $this->paginate('Video');
    }

    public function video_add() {
        if ($this->data) {
            $data = $this->data['Video'];
            $this->Video->save($data);
            $this->Session->setFlash('Đã thêm mới', 'success');
            $this->redirect($this->referer());
        }
    }

    public function video_edit($id = null) {
        if ($this->data) {
            $data = $this->data['Video'];
            $data['created'] = time();

            $this->Video->id = $id;
            $this->Video->save($data);
            $this->Session->setFlash('Đã sửa', 'success');
            $this->redirect($this->referer());
        }

        $this->data = $this->Video->findById($id);
    }

    public function save_pos() {
        $vitri = $_POST['pos'];
        foreach ($vitri as $k => $v) {
            if ($v == "") {
                $v = 0;
            }
            $this->Video->updateAll(
                    array(
                'Video.pos' => $v,
                    ), array(
                'Video.id' => $k
                    )
            );
        }

        $this->redirect($this->referer());
    }

    public function video_delete($id = null) {
        $this->autoRender = false;
        $this->Video->id = $id;
        $this->Video->delete($id);
        $this->Session->setFlash('Đã xóa hình ảnh', 'success');
        $this->redirect($this->referer());
    }

}
