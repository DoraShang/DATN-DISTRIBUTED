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

class AdminHangController extends AdminAppController {

    public $uses = array('Hang');
    public $limit = 10;

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('limit', $this->limit);
    }

    public function hang_index($type = null) {
        $this->autoRender = false;
    }

    public function hang_list() {
     
        $this->paginate = array(
            'limit' => 12,
            'order' => array('Hang.pos'=>'desc', 'Hang.id'=>'desc')
        );

        $this->data = $this->paginate('Hang');
    }

    public function hang_add() {
        if ($this->data) {
            $data = $this->data['Hang'];
            $data['image'] = $this->remove_hostname($data['image']);
            $data['slug'] = strtolower(Inflector::slug($data['title'], '-'));
            
            if(isset($data['category_id']) && $data['category_id'] == "")
                $data['category_id'] = 0;

            $this->Hang->save($data);
            $id = $this->Hang->getLastInsertID();
            
            $this->Session->setFlash('Đã thêm mới', 'success');
            $this->redirect($this->get_redirect('hang', 'add', $id)); die;
        }
    }

    public function hang_edit($id = null) {
        if ($this->data) {
            $data = $this->data['Hang'];
            $data['slug'] = strtolower(Inflector::slug($data['title'], '-'));
            $data['image'] = $this->remove_hostname($data['image']);
            $data['created'] = time();
            if(isset($data['category_id']) && $data['category_id'] == "")
                $data['category_id'] = 0;
            $this->Hang->id = $id;
            $this->Hang->save($data);
            
            
            $this->Session->setFlash('Đã sửa', 'success');
            $this->redirect($this->get_redirect('hang', 'edit', $id)); die;
        }

        $this->data = $this->Hang->findById($id);
    }

    public function save_pos() {
        $vitri = $_POST['pos'];
        foreach ($vitri as $k => $v) {
            if ($v == "") {
                $v = 0;
            }
            $this->Hang->updateAll(
                    array(
                'Hang.pos' => $v,
                    ), array(
                'Hang.id' => $k
                    )
            );
        }

        $this->redirect($this->referer());
    }
    
    public function update_field($field, $hang_id)
    {
        $this->autoRender = FALSE;
        $changed = 1;
        $check = $this->Hang->findById($hang_id);
        if($check['Hang'][$field] == 1)
            $changed = 0;
        
        $this->Hang->id = $hang_id;
        $this->Hang->saveField($field, $changed);
        $this->Session->setFlash('Đã thay đổi', 'success');
        $this->redirect($this->referer());
    }

    public function hang_delete($id = null) {
        $this->autoRender = false;
        $redirect_page = isset($_GET['rp']) && $_GET['rp'] > 1 ? 'page:' . $_GET['rp'] : '';
        $redirect_list = DOMAINAD . 'admin_hang/hang_list/' . $redirect_page;

        $this->Hang->id = $id;
        $this->Hang->delete($id);
        
        $this->Session->setFlash('Đã xóa', 'success');
        $this->redirect($redirect_list);
    }

}
