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

class AdminSaleController extends AdminAppController {

    public $uses = array('Sale', 'Admin.Category', 'Node', 'CategoryLinked');
    public $type = 'sale';
    public $tbl = 'Sale';
    public $form_title = 'Khuyến mại';

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('form_title', $this->form_title);
        $this->set('type', $this->type);
        $this->set('tbl', $this->tbl);
    }

    public function sale_list() {
        $conditions = array();
        $conditions['Node.type'] = $this->type;
        
        $filter_category = isset($_GET['list_category']) ? $_GET['list_category'] : '';
        $filter_status = isset($_GET['filter_status']) ? $_GET['filter_status'] : '';
                
        if($filter_category != '')
        {
            $check_cat = $this->Category->findById($filter_category);
            $data_check_cat = $this->Category->find('all', array(
                'conditions'=>array(
                    'Category.lft >=' => $check_cat['Category']['lft'],
                    'Category.rght <=' => $check_cat['Category']['rght']
                ),
            ));

            $buff[] = array();

            foreach($data_check_cat as $v)
            {
                $buff[] = $v['Category']['id'];
            }

            $conditions['CategoryLinked.category_id'] = $buff;
        }
        
        if($filter_status != '')
            $conditions['Node.status'] = $filter_status;
            
        $this->paginate = array(
            'joins'=>array(
                array(
                    'table'=>$this->type . 's',
                    'alias'=>$this->tbl,
                    'type'=>'INNER',
                    'conditions'=>array($this->tbl . '.node_id = Node.id')
                ),
                array(
                    'table'=>'category_linkeds',
                    'alias'=>'CategoryLinked',
                    'type'=>'INNER',
                    'conditions'=>'CategoryLinked.node_id = Node.id'
                )
            ),
            'conditions'=> $conditions,
            'group'=>'CategoryLinked.node_id',
            'limit' => 10,
            'order' => 'Node.pos DESC, Node.id DESC',
            'fields'=>array('Node.*', $this->tbl . '.*')
        );

        
        $this->data = $this->paginate('Node');
        $this->set('filter_category', $filter_category);
        $this->set('filter_status', $filter_status);
    }

    public function sale_add() {
        if($this->data)
        {
            $data = $this->data[$this->tbl];
            $data_node = $this->data['Node'];
            $data_node['slug'] = strtolower(Inflector::slug($data_node['title'], '-'));
            // $data_node['created'] = strtotime($data_node['created']);
            // $data_node['modified'] = time();
            $data_node['type'] = 'sale';
            
            $data['image'] = $this->remove_hostname($data['image']);
            $check = $this->Node->findBySlug($data_node['slug']);

            if(is_array($check) && count($check) > 0)
            {
                $this->Session->setFlash("Đã tồn tại bài viết", 'error');
                $this->redirect($this->referer());
                die;
                //$data_node['slug'] = $data_node['slug'].'-'.time();
            }

            //save node
            $this->Node->save($data_node);
            $node_id = $this->Node->getLastInsertID();

            //save news
            $data['node_id'] = $node_id;
            $this->{$this->tbl}->save($data);

            if(isset($this->data['category_id']) && is_array($this->data['category_id']) && count($this->data['category_id']) > 0)
            {
                foreach($this->data['category_id'] as $v)
                {
                    $this->CategoryLinked->create();
                    $this->CategoryLinked->save(array(
                        'category_id'=>$v,
                        'node_id'=>$node_id
                    ));
                }
            }

            $this->Session->setFlash('Đã thêm', 'success');
            $this->redirect(DOMAINAD . 'admin_'.$this->type . '/' . $this->type . '_list');
        }
    }

    public function sale_edit($id = null) {
        if ($this->data) {
            $data = $this->data[$this->tbl];
            $data_node = $this->data['Node'];

            $data_node['slug'] = strtolower($data_node['slug']);
            // $data_node['modified'] = time();
            
            $data_node['type'] = $this->type;
            $data['image'] = $this->remove_hostname($data['image']);

            $check = $this->Node->find('first', array(
                'conditions'=>array(
                    'Node.slug'=>$data_node['slug'],
                    'NOT'=>array(
                        'Node.id'=>$id
                    )
                )
            ));
            
            if(is_array($check) && count($check) > 0)
            {
                $this->Session->setFlash("Đã tồn tại bài viết", 'success');
                $this->redirect($this->referer());
                die;
            }

            //update node
            if(isset($data_node['id'])) unset($data_node['id']);

            $this->Node->id = $id; 
            $this->Node->save($data_node);

            if(isset($this->data['category_id']) && is_array($this->data['category_id']) && count($this->data['category_id']) > 0)
            {
                $this->CategoryLinked->deleteAll(array('CategoryLinked.node_id' => $id), false);

                foreach($this->data['category_id'] as $v)
                {
                    $this->CategoryLinked->create();
                    $this->CategoryLinked->save(array(
                        'category_id'=>$v,
                        'node_id'=>$id
                    ));
                }
            }

            //update news
            $check_news_id = $this->{$this->tbl}->find('first', array(
                'conditions'=>array(
                    $this->tbl . '.node_id' => $id
                )
            ));

            $news_id = 0;
            if(is_array($check_news_id) && count($check_news_id) > 0)
                $news_id = $check_news_id[$this->tbl]['id'];

            if($news_id != 0)
            {
                if(isset($data['id'])) unset($data['id']);
                $this->{$this->tbl}->id = $news_id;
                $this->{$this->tbl}->save($data);
            }
            
            $this->Session->setFlash('Đã sửa', 'success');
            $this->redirect(DOMAINAD . 'admin_'.$this->type . '/' . $this->type . '_list');
        }

        $this->data = $this->{$this->tbl}->find('first', array(
            'joins'=>array(
                array(
                    'table'=>'nodes',
                    'alias'=>'Node',
                    'type'=>'INNER',
                    'conditions'=>array('Node.id = '.$this->tbl.'.node_id')
                )
            ),
            'conditions'=>array(
                'Node.id'=>$id
            ),
            'fields'=>array('Node.*', $this->tbl . '.*')
        ));

        $cats = $this->CategoryLinked->find('all', array(
            'conditions'=>array(
                'CategoryLinked.node_id' => $id
            ),
            'fields'=>'category_id'
        ));

        $buff = array();

        if(is_array($cats) && count($cats) > 0)
        {
            foreach($cats as $v)
            {
                $buff[] = $v['CategoryLinked']['category_id'];
            }
        }

        $this->set('cat_selected', $buff);
    }

    public function sale_copy($node_id)
    {
        $this->sale_edit($node_id);
    }
}
