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

class AdminDocumentController extends AdminAppController {

    public $uses = array('Document', 'Admin.Category', 'Node', 'CategoryLinked');

    public function document_index($type = null) {
        $this->autoRender = false;
    }

    public function beforeFilter() 
    {
        parent::beforeFilter();
        $this->get_categories(array('type'=>'document'));
    }

    public function get_list_category_name($news_node_id)
    {
        $this->autoRender = false;

        $data = $this->CategoryLinked->find('all', array(
            'conditions'=>array(
                'CategoryLinked.node_id'=>$news_node_id
            ),
            'limit'=>20
        ));

        $str  = "";

        if(is_array($data) && count($data)>0)
        {
            foreach($data as $v)
            {
                if(isset($this->category_tree[$v['CategoryLinked']['category_id']]))
                    $str .= $this->category_tree[$v['CategoryLinked']['category_id']] . ' ,';
            }
        }

        return trim($str, ', ');
    }

    public function document_list() {
        $this->_role('document_list');
        $conditions = array();
        $conditions['Node.type'] = 'document';
        
        $filter_category = isset($_GET['list_category']) ? $_GET['list_category'] : '';
        $filter_status = isset($_GET['filter_status']) ? $_GET['filter_status'] : '';
                
        if($filter_category != '')
        {
            $check_cat = $this->Category->findById($filter_category);
            $data_check_cat = $this->Category->find('all', array(
                'conditions'=>array(
                    'Category.lft >=' => $check_cat['Category']['lft'],
                    'Category.rght <=' => $check_cat['Category']['rght']
                )
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
                    'table'=>'documents',
                    'alias'=>'Document',
                    'type'=>'INNER',
                    'conditions'=>array('Document.node_id = Node.id')
                ),
                array(
                    'table'=>'category_linkeds',
                    'alias'=>'CategoryLinked',
                    'type'=>'LEFT',
                    'conditions'=>'CategoryLinked.node_id = Node.id'
                )
            ),
            'conditions'=> $conditions,
            'limit' => 10,
            'order' => 'Node.pos DESC, Node.id DESC',
            'group'=>'CategoryLinked.node_id',
            'fields'=>array('Node.*', 'Document.*')
        );
        
        $this->data = $this->paginate('Node');
        $this->set('filter_category', $filter_category);
        $this->set('filter_status', $filter_status);
    }

    public function document_add() {
        $this->_role('document_add');
        if ($this->data) {
            $data = $this->data['Document'];
            $data_node = $this->data['Node'];
            $data_node['slug'] = strtolower(Inflector::slug($data_node['title'], '-'));
            $data_node['created'] = strtotime($data_node['created']);
            $data_node['modified'] = time();
            $data_node['type'] = 'document';
            
            $data['image'] = $this->remove_hostname($data['image']);
            $data['link'] = $this->remove_hostname($data['link']);
            
            $data['admin_id'] = $this->admin['id'];

            $check = $this->Node->findBySlug($data_node['slug']);

            if(is_array($check) && count($check) > 0)
            {
                $this->Session->setFlash("Đã tồn tại", 'error');
                $this->redirect($this->referer());
                die;
                //$data_node['slug'] = $data_node['slug'].'-'.time();
            }

            //save node
            $this->Node->save($data_node);
            $node_id = $this->Node->getLastInsertID();

            //save news
            $data['node_id'] = $node_id;
            $this->Document->save($data);

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
            $this->redirect($this->referer());
        }
    }

    public function document_edit($id = null) {
        $this->_role('document_edit');
        if ($this->data) {
            $data = $this->data['Document'];
            $data_node = $this->data['Node'];

            $data_node['slug'] = strtolower($data_node['title']);
            $data_node['created'] = strtotime($data_node['created']);
            $data_node['modified'] = time();
            
            $data_node['type'] = 'document';
            $data['image'] = $this->remove_hostname($data['image']);
            $data['link'] = $this->remove_hostname($data['link']);

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
                $this->Session->setFlash("Đã tồn tại", 'error');
                $this->redirect($this->referer());
                die;
                //$data_node['slug'] = $data_node['slug'].'-'.time();
            }

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
            $check_news_id = $this->Document->find('first', array(
                'conditions'=>array(
                    'Document.node_id' => $id
                )
            ));

            $news_id = 0;
            if(is_array($check_news_id) && count($check_news_id) > 0)
                $news_id = $check_news_id['Document']['id'];

            if($news_id != 0)
            {
                if(isset($data['id'])) unset($data['id']);
                $this->Document->id = $news_id;
                $this->Document->save($data);
            }

            $this->Session->setFlash('Đã sửa', 'success');
            $this->redirect($this->referer());
        }
        
        $this->data = $this->Document->find('first', array(
            'joins'=>array(
                array(
                    'table'=>'nodes',
                    'alias'=>'Node',
                    'type'=>'INNER',
                    'conditions'=>array('Node.id = Document.node_id')
                )
            ),
            'conditions'=>array(
                'Node.id'=>$id
            ),
            'fields'=>array('Node.*', 'Document.*')
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

    public function save_pos() {
        $vitri = $_POST['pos'];
        foreach ($vitri as $k => $v) {
            if ($v == "") {
                $v = 0;
            }
            $this->Document->updateAll(
                    array(
                'Document.pos' => $v,
                    ), array(
                'Document.id' => $k
                    )
            );
        }

        $this->redirect($this->referer());
    }

    public function document_delete($id = null) {
        $this->_role('document_delete');
        $this->autoRender = false;
        $this->Document->id = $id;
        $this->Document->delete($id);
        $this->Session->setFlash('Đã xóa');
        $this->redirect($this->referer());
    }

}
