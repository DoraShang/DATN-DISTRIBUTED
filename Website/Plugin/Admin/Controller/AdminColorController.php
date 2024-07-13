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

class AdminColorController extends AdminAppController {

    public $uses = array('Color');
    public $limit = 10;

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('limit', $this->limit);
    }

    public function color_index($type = null) {
        $this->autoRender = false;
    }

    public function color_list() {
     
        $this->paginate = array(
            'limit' => 12,
            'order' => array('Color.pos'=>'desc', 'Color.id'=>'desc')
        );

        $this->data = $this->paginate('Color');
    }

    public function color_add() {
        if ($this->data) {
            $data = $this->data['Color'];
            // $data['image'] = $this->remove_hostname($data['image']);
            $data['slug'] = strtolower(Inflector::slug($data['title'], '-'));
            
            if(isset($data['category_id']) && $data['category_id'] == "")
                $data['category_id'] = 0;

            $this->Color->save($data);
            $id = $this->Color->getLastInsertID();
            
            $this->Session->setFlash('Đã thêm mới', 'success');
            $this->redirect($this->get_redirect('color', 'add', $id)); die;
        }
    }

    public function color_edit($id = null) {
        if ($this->data) {
            $data = $this->data['Color'];
            $data['slug'] = strtolower(Inflector::slug($data['title'], '-'));
            // $data['image'] = $this->remove_hostname($data['image']);
            $data['created'] = time();
            if(isset($data['category_id']) && $data['category_id'] == "")
                $data['category_id'] = 0;
            $this->Color->id = $id;
            $this->Color->save($data);
            
            
            $this->Session->setFlash('Đã sửa', 'success');
            $this->redirect($this->get_redirect('color', 'edit', $id)); die;
        }

        $this->data = $this->Color->findById($id);
    }

    public function save_pos() {
        $vitri = $_POST['pos'];
        foreach ($vitri as $k => $v) {
            if ($v == "") {
                $v = 0;
            }
            $this->Color->updateAll(
                    array(
                'Color.pos' => $v,
                    ), array(
                'Color.id' => $k
                    )
            );
        }

        $this->redirect($this->referer());
    }
    
    public function update_field($field, $color_id)
    {
        $this->autoRender = FALSE;
        $changed = 1;
        $check = $this->Color->findById($color_id);
        if($check['Color'][$field] == 1)
            $changed = 0;
        
        $this->Color->id = $color_id;
        $this->Color->saveField($field, $changed);
        $this->Session->setFlash('Đã thay đổi', 'success');
        $this->redirect($this->referer());
    }

    public function color_delete($id = null) {
        $this->autoRender = false;
        $redirect_page = isset($_GET['rp']) && $_GET['rp'] > 1 ? 'page:' . $_GET['rp'] : '';
        $redirect_list = DOMAINAD . 'admin_color/color_list/' . $redirect_page;

        $this->Color->id = $id;
        $this->Color->delete($id);
        
        $this->Session->setFlash('Đã xóa', 'success');
        $this->redirect($redirect_list);
    }

}
