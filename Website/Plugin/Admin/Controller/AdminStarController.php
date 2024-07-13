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

class AdminStarController extends AdminAppController
{

    public $uses = array('Star', 'Team', 'Customer', 'Node');
    public $star_categories = array();

    public function beforeFilter()
    {
        parent::beforeFilter();

        // $cat = $this->settings['cat_bld'];
        // $cat_arr = explode("\n", $cat);

        // $star_categories = array();

        // $key = 'Ban lãnh đạo';
        // $i = 0;

        // foreach ($cat_arr as $v) {
        //     $t = explode(':', $v);
        //     if (count($t) > 1) {
        //         if ($t[0] <= 3)
        //             $key = 'Ban Lãnh Đạo';
        //         else if ($t[0] > 3 && $t[0] <= 11)
        //             $key = 'Ban chuyên môn';
        //         else
        //             $key = 'Hội viên';

        //         $star_categories[$key][$t[0]] = $t[1];
        //     }
        // }

        // $this->star_categories = $star_categories;
        // $this->set('star_categories', $this->star_categories);
    }

    public function star_index($type = null)
    {
        $this->autoRender = false;
    }

    public function get_hocvien($id)
    {
        $this->autoRender = false;

        $u = $this->Customer->findById($id);
        return $u;
    }
    public function get_giaovien($id)
    {
        $this->autoRender = false;

        $u = $this->Team->findById($id);
        return $u;
    }

    public function star_list()
    {
        $this->_role('star_list');
        $this->paginate = array(
            'joins' => array(
                array(
                    'table' => 'nodes',
                    'alias' => 'Node',
                    'type' => 'INNER',
                    'conditions' => array('Star.node_id = Node.id')
                ),
                array(
                    'table' => 'customers',
                    'alias' => 'Customer',
                    'type' => 'INNER',
                    'conditions' => array('Star.customer_id = Customer.id')
                ),
            ),
            'conditions' => array(
                'Star.status' => [0, 1]
            ),
            'limit' => 10,
            'order' => array('Star.status' => 'asc', 'Star.id' => 'desc'),
            'fields' => array('Node.*', 'Star.*', 'Customer.*'),
        );

        $this->data = $this->paginate('Star');
        // pr($this->data);
        // die;
    }

    public function star_add()
    {
        $this->_role('star_add');
        if ($this->data) {
            $data = $this->data['Star'];
            $data['image'] = $this->remove_hostname($data['image']);
            if (isset($data['category_id']) && $data['category_id'] == "")
                $data['category_id'] = 0;
            $this->Star->save($data);
            $this->Session->setFlash('Đã thêm mới hình ảnh', 'success');
            $this->redirect($this->referer());
        }
    }

    public function star_edit($id = null)
    {
        $this->_role('star_edit');
        if ($this->data) {
            $data = $this->data['Star'];
            $data['image'] = $this->remove_hostname($data['image']);
            $data['created'] = time();
            if (isset($data['category_id']) && $data['category_id'] == "")
                $data['category_id'] = 0;
            $this->Star->id = $id;
            $this->Star->save($data);
            $this->Session->setFlash('Đã sửa hình ảnh', 'success');
            $this->redirect($this->referer());
        }

        $this->data = $this->Star->findById($id);
    }

    public function update_field($field, $hang_id)
    {
        $this->autoRender = FALSE;
        $changed = 1;
        $check = $this->Star->findById($hang_id);
        if ($check['Star'][$field] == 1)
            $changed = 0;

        $this->Star->id = $hang_id;
        $this->Star->saveField($field, $changed);
        $this->Session->setFlash('Đã thay đổi', 'success');
        $this->redirect($this->referer());
    }

    public function save_pos()
    {
        $vitri = $_POST['pos'];
        foreach ($vitri as $k => $v) {
            if ($v == "") {
                $v = 0;
            }
            $this->Star->updateAll(
                array(
                    'Star.pos' => $v,
                ),
                array(
                    'Star.id' => $k
                )
            );
        }

        $this->redirect($this->referer());
    }

    public function star_delete($id = null)
    {
        $this->_role('star_delete');
        $this->autoRender = false;
        $this->Star->id = $id;
        $this->Star->delete($id);
        $this->Session->setFlash('Đã xóa');
        $this->redirect($this->referer());
    }
}
