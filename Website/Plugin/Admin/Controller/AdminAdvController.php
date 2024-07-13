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

class AdminAdvController extends AdminAppController {

    public $uses = array('Adv', 'Admin.Category', 'Node', 'CategoryLinked');

    public $type = 'adv';
    public $tbl = 'Adv';
    public $table = 'advs';
    public $form_title = 'Quảng cáo';

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('form_title', $this->form_title);
        $this->set('type', $this->type);
        $this->set('tbl', $this->tbl);
        $this->get_categories(array('type'=>'adv'));
    }

    public function index($node_id = null) {
            
        $tbl = $this->tbl;

        $this->paginate = array(
            'joins'=>array(
                array(
                    'table'=>$this->table,
                    'alias'=>$this->tbl,
                    'type'=>'INNER',
                    'conditions'=>array($this->tbl . '.node_id = Node.id')
                ),
            ),
            'limit' => 40,
            'order' => array('Node.pos'=>'desc', 'Node.id'=>'desc'),
            'fields'=>array('Node.*', $this->tbl . '.*')
        );
        
        $this->data = $this->paginate('Node');
        $this->set('node_id', $node_id);
    }

    public function add($node_id = null) {
        if($this->data)
        {
            $data = $this->data[$this->tbl];
            $data_node = $this->data['Node'];
            $data_node['type'] = 'adv';
            
            $data['image'] = $this->remove_hostname($data['image']);

            $data['price'] = preg_replace('/[^0-9]/', '', $data['price']);

            $this->Node->save($data_node);
            $data['node_id'] = $this->Node->getLastInsertId();


            $this->{$this->tbl}->save($data);
            $lastID = $this->{$this->tbl}->getLastInsertId();

            $this->Session->setFlash('Đã thêm', 'success');
            $this->redirect(DOMAINAD . 'admin_'.$this->type.'/edit/' . $lastID);
        }
    }

    public function setting()
    {
        $f = 'adv-compare';
        $s = $this->settings[$f];
        $setting = unserialize($s);

        $fields = array(
            'num' => 'Số từ khóa', 
            'template' => 'Số mẫu quảng cáo', 
            'price' => 'Giá thành',
            'adv_category'=>'Danh mục Quảng cáo',
        );


        if($this->data)
        {
            $data = $this->data;

            $buff = array();

            foreach($fields as $k=>$v)
            {
                if(isset($data[$k]['show']))
                {
                    $buff[$k]['show'] = $data[$k]['show'];
                    $buff[$k]['title'] = $v;
                }
                else
                {
                    $buff[$k]['title'] = $fields[$k];
                    $buff[$k]['show'] = 0;
                }
            }

            $save = serialize($buff);

            $check = $this->Setting->findByName($f);
            $this->Setting->id = $check['Setting']['id'];
            $this->Setting->saveField('value', $save);

            $this->redirect(DOMAINAD . 'admin_'.$this->type.'/setting');
            die;
        }

        $data = array();

        foreach($fields as $k=>$v)
        {
            if(isset($setting[$k]))
            {
                $data[$k]['title'] = $fields[$k];
                $data[$k]['show'] = $setting[$k]['show'];
            }
            else
            {
                $data[$k]['title'] = $fields[$k];
                $data[$k]['show'] = 0;
            }
        }

        $this->data = $data;
    }

    public function edit($id = null) {
        if ($this->data) {
            $data = $this->data[$this->tbl];
            $data_node = $this->data['Node'];
            $data_node['type'] = 'adv';

            if(isset($data['image']))
            $data['image'] = $this->remove_hostname($data['image']);

            $data['price'] = preg_replace('/[^0-9]/', '', $data['price']);

            $check = $this->{$this->tbl}->findById($id);
            $node_id = $check[$this->tbl]['node_id'];

            $this->Node->id = $node_id;
            $this->Node->save(array(
                'title'=>$data_node['title'],
                'status'=>$data_node['status'],
            ));

            $this->{$this->tbl}->id = $id;
            $this->{$this->tbl}->save($data);

            $this->Session->setFlash('Đã sửa', 'success');
            $this->redirect(DOMAINAD . 'admin_'.$this->type.'/index/');
        }

        $this->data = $this->{$this->tbl}->find('first', array(
            'joins'=>array(
                array(
                    'table'=>'nodes',
                    'alias'=>'Node',
                    'type'=>'INNER',
                    'conditions'=>array($this->tbl . '.node_id = Node.id')
                ),
            ),
            'conditions'=>array(
                $this->tbl.'.id'=>$id
            ),
            'fields'=>array('Node.*', $this->tbl . '.*')
        ));
    }

    public function copy($node_id)
    {
        $this->edit($node_id);
    }

    public function count_node($node_id)
    {
        $this->autoRender = false;

        $tbl = $this->tbl;

        return $this->{$this->tbl}->find('count', array(
            'conditions'=>array(
                $tbl . '.node_id'=>$node_id
            )
        ));
    }

    public function update_field($field, $tbl_id)
    {
        $change = 0;
        $data = $this->{$this->tbl}->findById($tbl_id);
        if($data[$this->tbl][$field] == 0)
            $change = 1;
        
        $this->{$this->tbl}->id = $tbl_id;
        $this->{$this->tbl}->saveField($field, $change);

        $this->Session->setFlash('Đã cập nhật', 'success');
        $this->redirect($this->referer());
    }

    public function delete($id = null)
    {
        $this->autoRender = false;
        $this->{$this->tbl}->delete($id);
        $this->Session->setFlash('Đã xóa', 'success');
        $this->redirect($this->referer());
    }
}
