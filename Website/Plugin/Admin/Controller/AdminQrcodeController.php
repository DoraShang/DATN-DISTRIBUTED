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

class AdminQrcodeController extends AdminAppController
{

    public $uses = array('Qrcode', 'Node');
    public $limit = 10;

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('limit', $this->limit);
    }
    public function beforeRender()
    {
        parent::beforeRender();

        $data_poroduct = $this->Node->find(
            'list',
            array(
                'conditions' => array(
                    'type' => 'product'
                ), 'fields' => array('id', 'title')
            )
        );
        $product = array();
        if (is_array($data_poroduct) && count($data_poroduct) > 0) {
            $product = $data_poroduct;
        }

        $this->set('product', $product);
    }

    public function qrcode_index($type = null)
    {
        $this->autoRender = false;
    }

    public function qrcode_list()
    {
        $n_id = isset($_GET['n_id']) && is_numeric($_GET['n_id']) ? $_GET['n_id'] : '';
        if (isset($n_id) && is_numeric($n_id)) {
            $this->paginate = array(
                'conditions' => array(
                    'node_id' => $n_id
                ),
                'limit' => 15,
                'order' => array('Qrcode.id' => 'desc')
            );
            $this->set('n_id', $n_id);
            $this->data = $this->paginate('Qrcode');
        } else {
            $this->redirect($this->referer());
            die;
        }
    }

    public function qrcode_add()
    {
        $n_id = isset($_GET['n_id']) && is_numeric($_GET['n_id']) ? $_GET['n_id'] : '';
        if ($this->data) {
            $data = $this->data['Qrcode'];

            $check = $this->Qrcode->find('first', array(
                'conditions' => array(
                    'title' => $data['title'],
                    'node_id' => $n_id
                )
            ));
            if (is_array($check) && count($check) > 0) {
                $this->Session->setFlash('Số seri đã tồn tại', 'success');
                $this->redirect($this->referer());
                die;
            }
            $this->Qrcode->save($data);
            $id = $this->Qrcode->getLastInsertID();

            $this->Session->setFlash('Đã thêm mới', 'success');
            // $this->redirect($this->get_redirect('qrcode', 'add', $id));
        }
        $this->set('n_id', $n_id);
    }

    public function qrcode_edit($id = null)
    {
        $n_id = isset($_GET['n_id']) && is_numeric($_GET['n_id']) ? $_GET['n_id'] : '';
        if (is_numeric($n_id)) {
            if ($this->data) {
                $data = $this->data['Qrcode'];

                $check = $this->Qrcode->find('first', array(
                    'conditions' => array(
                        'title' => $data['title'],
                        'node_id' => $n_id
                    )
                ));
                if (is_array($check) && count($check) > 0) {
                    $this->Session->setFlash('Số seri đã tồn tại', 'success');
                    $this->redirect($this->referer());
                    die;
                }

                $this->Qrcode->id = $id;
                $this->Qrcode->save($data);

                $this->Session->setFlash('Đã sửa', 'success');
                // die;
            }
            $this->set('n_id', $n_id);
            $this->data = $this->Qrcode->findById($id);
        } else {

            $this->redirect($this->referer());
            die;
        }
    }

    public function qrcode_delete($id = null)
    {
        $this->autoRender = false;

        $this->Qrcode->id = $id;
        $this->Qrcode->delete($id);

        $this->Session->setFlash('Đã xóa', 'success');
    }
}
