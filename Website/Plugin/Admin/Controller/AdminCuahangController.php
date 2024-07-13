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

class AdminCuahangController extends AdminAppController
{

    public $uses = array('Cuahang', 'Admin.Category', 'Node', 'CategoryLinked');
    public $type = 'cuahang';
    public $tbl = 'Cuahang';
    public $form_title = 'Cửa hàng';

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('form_title', $this->form_title);
        $this->set('type', $this->type);
        $this->set('tbl', $this->tbl);
    }

    public function cuahang_list($node_id = null)
    {
        $this->paginate = array(
            'limit' => 10,
            'order' => 'Cuahang.pos DESC, Cuahang.id DESC',
        );

        $this->data = $this->paginate('Cuahang');
    }

    public function cuahang_add($node_id = null)
    {
        $this->District = ClassRegistry::init('District');
        $district = $this->District->find('all', array());
        // foreach ($district as $v) {
        //     $province[] = $v;
        // }
        $this->set('province', $district);

        if ($this->data) {
            $data = $this->data[$this->tbl];

            $this->{$this->tbl}->save($data);

            $this->Session->setFlash('Đã thêm', 'success');
            $this->redirect(DOMAINAD . 'admin_' . $this->type . '/' . $this->type . '_list/' . $node_id);
        }
    }

    public function cuahang_edit($id = null)
    {
        $this->District = ClassRegistry::init('District');
        $district = $this->District->find('all', array());
        // foreach ($district as $v) {
        //     $province[] = $v;
        // }
        $this->set('province', $district);

        if ($this->data) {
            $data = $this->data[$this->tbl];

            $this->{$this->tbl}->id = $id;
            $this->{$this->tbl}->save($data);

            $this->Session->setFlash('Đã sửa', 'success');
            $this->redirect(DOMAINAD . 'admin_' . $this->type . '/' . $this->type . '_list');
        }

        $this->data = $this->{$this->tbl}->findById($id);
    }

    public function cuahang_delete($rate_id = null)
    {
        $this->autoRender = false;
        $this->{$this->tbl}->delete($rate_id);
        $this->Session->setFlash('Đã xóa', 'success');
        $this->redirect($this->referer());
    }
}
