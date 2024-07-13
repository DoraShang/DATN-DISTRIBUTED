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

class AdminPriceController extends AdminAppController {

    public $uses = array('Price');
    public $limit = 10;

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('limit', $this->limit);
    }

    public function price_index($type = null) {
        $this->autoRender = false;
    }

    public function price_list() {
     
        $this->paginate = array(
            'limit' => 12,
            'order' => array('Price.pos'=>'desc', 'Price.id'=>'desc')
        );

        $this->data = $this->paginate('Price');
    }

    public function price_add() {
        if ($this->data) {
            $data = $this->data['Price'];
            $data['image'] = $this->remove_hostname($data['image']);
            $data['slug'] = strtolower(Inflector::slug($data['title'], '-'));
            
            if(isset($data['category_id']) && $data['category_id'] == "")
                $data['category_id'] = 0;

            $this->Price->save($data);
            $id = $this->Price->getLastInsertID();
            
            $this->Session->setFlash('Đã thêm mới', 'success');
            $this->redirect($this->get_redirect('size', 'add', $id)); die;
        }
    }

    public function price_edit($id = null) {
        if ($this->data) {
            $data = $this->data['Price'];
            $data['slug'] = strtolower(Inflector::slug($data['title'], '-'));
            $data['image'] = $this->remove_hostname($data['image']);
            $data['created'] = time();
            if(isset($data['category_id']) && $data['category_id'] == "")
                $data['category_id'] = 0;
            $this->Price->id = $id;
            $this->Price->save($data);
            
            
            $this->Session->setFlash('Đã sửa', 'success');
            $this->redirect($this->get_redirect('size', 'edit', $id)); die;
        }

        $this->data = $this->Price->findById($id);
    }

    public function save_pos() {
        $vitri = $_POST['pos'];
        foreach ($vitri as $k => $v) {
            if ($v == "") {
                $v = 0;
            }
            $this->Price->updateAll(
                    array(
                'Price.pos' => $v,
                    ), array(
                'Price.id' => $k
                    )
            );
        }

        $this->redirect($this->referer());
    }
    
    public function update_field($field, $price_id)
    {
        $this->autoRender = FALSE;
        $changed = 1;
        $check = $this->Price->findById($price_id);
        if($check['Price'][$field] == 1)
            $changed = 0;
        
        $this->Price->id = $price_id;
        $this->Price->saveField($field, $changed);
        $this->Session->setFlash('Đã thay đổi', 'success');
        $this->redirect($this->referer());
    }

    public function price_delete($id = null) {
        $this->autoRender = false;
        $redirect_page = isset($_GET['rp']) && $_GET['rp'] > 1 ? 'page:' . $_GET['rp'] : '';
        $redirect_list = DOMAINAD . 'admin_size/price_list/' . $redirect_page;

        $this->Price->id = $id;
        $this->Price->delete($id);
        
        $this->Session->setFlash('Đã xóa', 'success');
        $this->redirect($redirect_list);
    }

}
