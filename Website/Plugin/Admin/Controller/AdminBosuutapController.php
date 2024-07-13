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

class AdminBosuutapController extends AdminAppController {

    public $uses = array('Bosuutap');
    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    public function bosuutap_index($type = null) {
        $this->autoRender = false;
    }

    public function bosuutap_list() {
     
        $this->paginate = array(
            'limit' => 10,
            'order' => 'Bosuutap.id DESC'
        );

        $this->data = $this->paginate('Bosuutap');
    }

    public function bosuutap_add() {
        if ($this->data) {

            $data = $this->data['Bosuutap'];
            $data['image'] = $this->remove_hostname($data['image']);

            $this->Bosuutap->save($data);
            
            $this->Session->setFlash('Đã thêm mới', 'success');
            $this->redirect($this->referer());
        }
    }

    public function bosuutap_edit($id = null) {
        if ($this->data) {

            $data = $this->data['Bosuutap'];          
            $data['image'] = $this->remove_hostname($data['image']);

            $this->Bosuutap->id = $id;
            $this->Bosuutap->save($data);
            
            $this->Session->setFlash('Đã sửa', 'success');
            $this->redirect($this->referer());
        }

        $this->data = $this->Bosuutap->findById($id);
    }

    // public function tag_count_post($node_id)
    // {
    //     $count = $this->Tag->find('count', array(
    //         'conditions'=>array(
    //             'Tag.node_tag_id'=>$node_id
    //         ),
    //         'group'=>array('Tag.node_id')
    //     ));
    //     return $count;
    // }

    public function bosuutap_delete($id = null) {
        $this->autoRender = false;
        $this->Bosuutap->id = $id;
        $this->Bosuutap->delete($id);
        
        
        $this->Session->setFlash('Đã xóa', 'success');
        $this->redirect($this->referer());
    }

    public function update_field($field, $bosuutap_id)
    {
        $this->autoRender = FALSE;
        $changed = 1;

        $check = $this->Bosuutap->findById($bosuutap_id);
        if($check['Bosuutap'][$field] == 1)
        {
            $changed = 0;
        }
        
        $this->Bosuutap->id = $bosuutap_id;
        $this->Bosuutap->saveField($field, $changed);
        
        $this->Session->setFlash('Đã thay đổi', 'success');
        $this->redirect($this->referer());
    }
}
