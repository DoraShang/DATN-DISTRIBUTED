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

class AdminRateController extends AdminAppController
{

    public $uses = array('Rate', 'Admin.Category', 'Node', 'CategoryLinked');

    public $type = 'rate';
    public $tbl = 'Rate';
    public $form_title = 'Đánh giá của khách hàng';

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('form_title', $this->form_title);
        $this->set('type', $this->type);
        $this->set('tbl', $this->tbl);
    }

    public function rate_list()
    {

        $search = isset($_GET['s']) ? $_GET['s'] : '';
        $conditions = array();
        if ($search != '') {
            $conditions['nodes.title LIKE'] = '%' . $search . '%';
        }
        $this->paginate = array(
            'fields' => array('customers.*', $this->tbl . '.*', 'nodes.*'),
            'joins' => array(
                array(
                    'table' => 'customers',
                    'type' => 'LEFT',
                    'conditions' => array($this->tbl . '.customer_id = customers.id')
                ),
                array(
                    'table' => 'nodes',
                    'type' => 'LEFT',
                    'conditions' => array($this->tbl . '.node_id = nodes.id')
                )
            ),
            'conditions' => $conditions,
            'order' => array('Rate.pos' => 'desc', 'Rate.id' => 'desc')


        );
        // var_dump($this->paginate($this->tbl)[0]);
        // die;
        $this->data = $this->paginate($this->tbl);
    }

    public function index($node_id = null)
    {
        // $conditions = array();
        // $conditions['Node.type'] = $this->type;

        // $filter_category = isset($_GET['list_category']) ? $_GET['list_category'] : '';
        // $filter_status = isset($_GET['filter_status']) ? $_GET['filter_status'] : '';

        // if($filter_category != '')
        // {
        //     $check_cat = $this->Category->findById($filter_category);
        //     $data_check_cat = $this->Category->find('all', array(
        //         'conditions'=>array(
        //             'Category.lft >=' => $check_cat['Category']['lft'],
        //             'Category.rght <=' => $check_cat['Category']['rght']
        //         ),
        //     ));

        //     $buff[] = array();

        //     foreach($data_check_cat as $v)
        //     {
        //         $buff[] = $v['Category']['id'];
        //     }

        //     $conditions['CategoryLinked.category_id'] = $buff;
        // }

        // if($filter_status != '')
        //     $conditions['Node.status'] = $filter_status;

        $tbl = $this->tbl;

        $this->paginate = array(
            // 'joins'=>array(
            //     array(
            //         'table'=>$this->type . 's',
            //         'alias'=>$this->tbl,
            //         'type'=>'INNER',
            //         'conditions'=>array($this->tbl . '.node_id = Node.id')
            //     ),
            //     array(
            //         'table'=>'category_linkeds',
            //         'alias'=>'CategoryLinked',
            //         'type'=>'INNER',
            //         'conditions'=>'CategoryLinked.node_id = Node.id'
            //     )
            // ),
            // 'conditions'=> $conditions,
            'conditions' => array(
                $tbl . '.node_id' => $node_id
            ),
            // 'group'=>'CategoryLinked.node_id',
            'limit' => 10,
            // 'order' => 'Node.pos DESC, Node.id DESC',
            // 'fields'=>array('Node.*', $this->tbl . '.*')
        );

        die;
        $this->data = $this->paginate($this->tbl);
        $this->set('node_id', $node_id);
        // $this->set('filter_category', $filter_category);
        // $this->set('filter_status', $filter_status);
    }

    public function rate_add($node_id = null)
    {
        if ($this->data) {
            $data = $this->data[$this->tbl];
            // $data_node = $this->data['Node'];
            // $data_node['slug'] = strtolower(Inflector::slug($data_node['title'], '-'));
            // // $data_node['created'] = strtotime($data_node['created']);
            // $data_node['modified'] = time();
            // $data_node['type'] = 'rate';

            $data['image'] = $this->remove_hostname($data['image']);
            if (is_numeric($node_id))
                $data['node_id'] = $node_id;
            // $check = $this->Node->findBySlug($data_node['slug']);

            // if(is_array($check) && count($check) > 0)
            // {
            //     $this->Session->setFlash("Đã tồn tại bài viết", 'error');
            //     $this->redirect($this->referer());
            //     die;
            //     //$data_node['slug'] = $data_node['slug'].'-'.time();
            // }

            //save node
            // $this->Node->save($data_node);
            // $node_id = $this->Node->getLastInsertID();

            // //save news
            // $data['node_id'] = $node_id;
            $this->{$this->tbl}->save($data);

            // if(isset($this->data['category_id']) && is_array($this->data['category_id']) && count($this->data['category_id']) > 0)
            // {
            //     foreach($this->data['category_id'] as $v)
            //     {
            //         $this->CategoryLinked->create();
            //         $this->CategoryLinked->save(array(
            //             'category_id'=>$v,
            //             'node_id'=>$node_id
            //         ));
            //     }
            // }

            $this->Session->setFlash('Đã thêm', 'success');
            $this->redirect(DOMAINAD . 'admin_' . $this->type . '/' . $this->type . '_list/' . $node_id);
        }
    }

    public function rate_edit($id = null)
    {
        if ($this->data) {
            $data = $this->data[$this->tbl];
            // $data_node = $this->data['Node'];
            // $data_node['slug'] = strtolower($data_node['slug']);
            // $data_node['modified'] = time();

            // $data_node['type'] = $this->type;
            if (isset($data['image']))
                $data['image'] = $this->remove_hostname($data['image']);

            // $check = $this->Node->find('first', array(
            //     'conditions'=>array(
            //         'Node.slug'=>$data_node['slug'],
            //         'NOT'=>array(
            //             'Node.id'=>$id
            //         )
            //     )
            // ));

            // if(is_array($check) && count($check) > 0)
            // {
            //     $this->Session->setFlash("Đã tồn tại bài viết", 'success');
            //     $this->redirect($this->referer());
            //     die;
            // }

            //update node
            // if(isset($data_node['id'])) unset($data_node['id']);

            // $this->Node->id = $id; 
            // $this->Node->save($data_node);

            // if(isset($this->data['category_id']) && is_array($this->data['category_id']) && count($this->data['category_id']) > 0)
            // {
            //     $this->CategoryLinked->deleteAll(array('CategoryLinked.node_id' => $id), false);

            //     foreach($this->data['category_id'] as $v)
            //     {
            //         $this->CategoryLinked->create();
            //         $this->CategoryLinked->save(array(
            //             'category_id'=>$v,
            //             'node_id'=>$id
            //         ));
            //     }
            // }

            // //update news
            // $check_news_id = $this->{$this->tbl}->find('first', array(
            //     'conditions'=>array(
            //         $this->tbl . '.node_id' => $id
            //     )
            // ));

            // $news_id = 0;
            // if(is_array($check_news_id) && count($check_news_id) > 0)
            //     $news_id = $check_news_id[$this->tbl]['id'];

            // if($news_id != 0)
            // {
            //     if(isset($data['id'])) unset($data['id']);
            //     $this->{$this->tbl}->id = $news_id;
            //     $this->{$this->tbl}->save($data);
            // }
            $this->{$this->tbl}->id = $id;
            $this->{$this->tbl}->save($data);

            $this->Session->setFlash('Đã sửa', 'success');
            $this->redirect(DOMAINAD . 'admin_' . $this->type . '/' . $this->type . '_list');
        }

        $this->data = $this->{$this->tbl}->findById($id);

        // $cats = $this->CategoryLinked->find('all', array(
        //     'conditions'=>array(
        //         'CategoryLinked.node_id' => $id
        //     ),
        //     'fields'=>'category_id'
        // ));

        // $buff = array();

        // if(is_array($cats) && count($cats) > 0)
        // {
        //     foreach($cats as $v)
        //     {
        //         $buff[] = $v['CategoryLinked']['category_id'];
        //     }
        // }

        // $this->set('cat_selected', $buff);
    }

    public function update_field($field, $rid)
    {
        $this->autoRender = false;
        $change = 1;
        $tbl = $this->tbl;

        $check = $this->{$tbl}->findById($rid);
        if ($check[$tbl][$field] == 1)
            $change = 0;

        $this->{$tbl}->id = $rid;
        $this->{$tbl}->saveField($field, $change);

        $this->redirect($this->referer());
    }


    public function rate_copy($node_id)
    {
        $this->edit($node_id);
    }

    public function count_node($node_id)
    {
        $this->autoRender = false;

        $tbl = $this->tbl;

        return $this->{$this->tbl}->find('count', array(
            'conditions' => array(
                $tbl . '.node_id' => $node_id
            )
        ));
    }

    public function rate_delete($id = null)
    {
        $this->autoRender = false;
        $this->{$this->tbl}->delete($id);
        $this->Session->setFlash('Đã xóa', 'success');
        $this->redirect($this->referer());
    }
}
