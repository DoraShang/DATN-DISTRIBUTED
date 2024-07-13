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

class AdminSizeController extends AdminAppController {

    public $uses = array('Size');
    public $limit = 10;

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('limit', $this->limit);
    }

    public function size_index($type = null) {
        $this->autoRender = false;
    }

    public function size_list() {
     
        $this->paginate = array(
            'limit' => 12,
            'order' => array('Size.pos'=>'desc', 'Size.id'=>'desc')
        );

        $this->data = $this->paginate('Size');
    }

    public function size_add() {
        if ($this->data) {
            $data = $this->data['Size'];
            $data['image'] = $this->remove_hostname($data['image']);
            $data['slug'] = strtolower(Inflector::slug($data['title'], '-'));
            
            if(isset($data['category_id']) && $data['category_id'] == "")
                $data['category_id'] = 0;

            $this->Size->save($data);
            $id = $this->Size->getLastInsertID();
            
            $this->Session->setFlash('Đã thêm mới', 'success');
            $this->redirect($this->get_redirect('size', 'add', $id)); die;
        }
    }

    public function size_edit($id = null) {
        if ($this->data) {
            $data = $this->data['Size'];
            $data['slug'] = strtolower(Inflector::slug($data['title'], '-'));
            $data['image'] = $this->remove_hostname($data['image']);
            $data['created'] = time();
            if(isset($data['category_id']) && $data['category_id'] == "")
                $data['category_id'] = 0;
            $this->Size->id = $id;
            $this->Size->save($data);
            
            
            $this->Session->setFlash('Đã sửa', 'success');
            $this->redirect($this->get_redirect('size', 'edit', $id)); die;
        }

        $this->data = $this->Size->findById($id);
    }

    public function save_pos() {
        $vitri = $_POST['pos'];
        foreach ($vitri as $k => $v) {
            if ($v == "") {
                $v = 0;
            }
            $this->Size->updateAll(
                    array(
                'Size.pos' => $v,
                    ), array(
                'Size.id' => $k
                    )
            );
        }

        $this->redirect($this->referer());
    }
    
    public function update_field($field, $size_id)
    {
        $this->autoRender = FALSE;
        $changed = 1;
        $check = $this->Size->findById($size_id);
        if($check['Size'][$field] == 1)
            $changed = 0;
        
        $this->Size->id = $size_id;
        $this->Size->saveField($field, $changed);
        $this->Session->setFlash('Đã thay đổi', 'success');
        $this->redirect($this->referer());
    }

    public function size_delete($id = null) {
        $this->autoRender = false;
        $redirect_page = isset($_GET['rp']) && $_GET['rp'] > 1 ? 'page:' . $_GET['rp'] : '';
        $redirect_list = DOMAINAD . 'admin_size/size_list/' . $redirect_page;

        $this->Size->id = $id;
        $this->Size->delete($id);
        
        $this->Session->setFlash('Đã xóa', 'success');
        $this->redirect($redirect_list);
    }

}
