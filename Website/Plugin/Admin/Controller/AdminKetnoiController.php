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

class AdminKetnoiController extends AdminAppController
{
    public $uses = array(
        'Ketnoi', 'Admin.Category', 'Node', 'Tag', 'CategoryLinked', 'Admin.Tag', 'Hang',
        'Adv', 'Cloud', 'Colo', 'Domain', 'Server', 'Service', 'Hosting', 'Email', 'Customer', 'CustomerBanner'
    );
    public $limit = 10;
    
    public function beforeFilter() 
    {
        parent::beforeFilter();
        
        $this->Session->delete('ketnoi_title');
        $this->Session->delete('ketnoi_title_org');
        $this->set('limit', $this->limit);

        $this->set('hangs', $this->Hang->find('list', array('fields'=>array('id', 'title'))));
    }
    
    public function ketnoi_search()
    {
        $key = (isset($_GET['key']) && $_GET['key'] != '') ? trim($_GET['key']) : '';
        $this->paginate = array(
            'joins'=>array(
                array(
                    'table'=>'ketnois',
                    'alias'=>'Ketnoi',
                    'type'=>'INNER',
                    'conditions'=>array('Ketnoi.node_id = Node.id')
                )
            ),
            'conditions'=> array(
                'Node.title LIKE'=>'%'.$key.'%'
            ),
            'limit' => 10,
            'order' => 'Node.pos DESC, Node.id DESC',
            'fields'=>array('Node.*', 'Ketnoi.*')
        );
        
        $this->data = $this->paginate('Node');
    }
        
    public function ketnoi_list($filter_category = null, $filter_status = null)
    {
        $this->_role('ketnoi_list');
        $conditions = array();
        $conditions['Node.type'] = 'ketnoi';
        
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
            'limit' => $this->limit,
            'order' => array('Customer.duyet_thongtin' => 'asc', 'Customer.id' => 'desc'),
        );
        
        $this->data = $this->paginate('Customer');
        $this->set('filter_category', $filter_category);
        $this->set('filter_status', $filter_status);
    }


    public function ketnoi_banner($filter_category = null, $filter_status = null)
    {
        $this->_role('ketnoi_list');
        $id = $_GET['c'];
        
        $this->paginate = array(
            'conditions' => array(
                'CustomerBanner.customer_id' => $id,
            ),
            'limit' => $this->limit,
            'order' => array('CustomerBanner.status' => 'asc', 'CustomerBanner.id' => 'desc'),
        );
        
        $this->data = $this->paginate('CustomerBanner');
    }

    public function ketnoi_add()
    {
        $this->_role('ketnoi_add');
        if($this->data)
        {
            $data = $this->data['Ketnoi'];
            $data_node = $this->data['Node'];

            // pr($data_node); 
            // pr($data); 
            // die;

            $data_node['slug'] = strtolower(Inflector::slug($data_node['title'], '-'));
            $data_node['created'] = strtotime($data_node['created']);
            $data_node['modified'] = time();
            $data_node['type'] = 'ketnoi';
            
            $data['image'] = $this->remove_hostname($data['image']);
            $data['admin_id'] = $this->admin['id'];
            
            $data_tags = array();
            if(isset($data['tags']))
                $data_tags = $data['tags'] != "" ? explode(',', $data['tags']) : '';
            
            $check = $this->Node->findBySlug($data_node['slug']);

            if(is_array($check) && count($check) > 0)
            {
                $data_node['slug'] = $data_node['slug'].'-'.time();
            }

            //save node
            $this->Node->save($data_node);
            $node_id = $this->Node->getLastInsertID();

            //save ketnoi
            $data['node_id'] = $node_id;
            $this->Ketnoi->save($data);
            
            $this->Session->setFlash('Đã thêm', 'success');
            $this->redirect($this->get_redirect('ketnoi', 'add', $node_id)); die;
        }
    }

    public function ketnoi_edit($id = null)
    {
        $this->_role('ketnoi_edit');
        if($this->data)
        {
            $data = $this->data['Ketnoi'];
            $data_node = $this->data['Node'];

            $data_node['slug'] = strtolower($data_node['slug']);
            $data_node['created'] = strtotime($data_node['created']);
            $data_node['modified'] = time();
            
            $data_node['type'] = 'ketnoi';
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
                // $this->Session->setFlash("Đã tồn tại bài viết", 'success');
                // $this->redirect($this->referer());
                // die;
                $data_node['slug'] = $data_node['slug'].'-'.time();
            }

            //update node
            if(isset($data_node['id'])) unset($data_node['id']);

            $this->Node->id = $id; 
            $this->Node->save($data_node);

            //update ketnoi
            $check_ketnoi_id = $this->Ketnoi->find('first', array(
                'conditions'=>array(
                    'Ketnoi.node_id' => $id
                )
            ));

            $ketnoi_id = 0;
            if(is_array($check_ketnoi_id) && count($check_ketnoi_id) > 0)
                $ketnoi_id = $check_ketnoi_id['Ketnoi']['id'];

            if($ketnoi_id != 0)
            {
                if(isset($data['id'])) unset($data['id']);
                $this->Ketnoi->id = $ketnoi_id;
                $this->Ketnoi->save($data);
            }
            
            $this->Session->setFlash('Đã sửa', 'success');
            $this->redirect($this->get_redirect('ketnoi', 'edit', $id)); die;
        }
        
        $this->data = $this->Ketnoi->find('first', array(
            'joins'=>array(
                array(
                    'table'=>'nodes',
                    'alias'=>'Node',
                    'type'=>'INNER',
                    'conditions'=>array('Node.id = Ketnoi.node_id')
                )
            ),
            'conditions'=>array(
                'Node.id'=>$id
            ),
            'fields'=>array('Node.*', 'Ketnoi.*')
        ));
    }

    public function get_category_name($cid)
    {
        $this->autoRender = false;
        $d = $this->Category->findById($cid);
        return $d['Category']['title'];
    }

    public function ketnoi_copy($id = null)
    {
        $this->data = $this->Ketnoi->find('first', array(
            'joins'=>array(
                array(
                    'table'=>'nodes',
                    'alias'=>'Node',
                    'type'=>'INNER',
                    'conditions'=>array('Node.id = Ketnoi.node_id')
                )
            ),
            'conditions'=>array(
                'Node.id'=>$id
            ),
            'fields'=>array('Node.*', 'Ketnoi.*')
        ));

        $this->set('data_tags', $this->Tag->find('all', array(
            'joins'=>array(
                array(
                    'table'=>'nodes',
                    'alias'=>'Node',
                    'type'=>'INNER',
                    'conditions'=>array('Tag.node_tag_id=Node.id')
                )
            ),
            'conditions'=>array(
                'Tag.node_id' => $id
            ),
            'limit'=>6,
            'fields'=>array('Node.*', 'Tag.*')
        )));

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
    
    public function update_field($field, $ketnoi_id)
    {
        $change = 0;
        $data = $this->Customer->findById($ketnoi_id);
        if($data['Customer'][$field] == 0)
            $change = 1;
        
        $this->Customer->id = $ketnoi_id;
        $this->Customer->saveField($field, $change);
        $this->Session->setFlash('Đã cập nhật', 'success');
        $this->redirect($this->referer());
    }


    public function customer_delete($ketnoi_id)
    {
        $change = 0;
        $data = $this->Customer->delete($ketnoi_id);
        $this->Session->setFlash('Đã xoá', 'success');
        $this->redirect($this->referer());
    }

    

    public function update_field_b($field, $ketnoi_id)
    {
        $change = 0;
        $data = $this->CustomerBanner->findById($ketnoi_id);
        if($data['CustomerBanner'][$field] == 0)
            $change = 1;
        
        $this->CustomerBanner->id = $ketnoi_id;
        $this->CustomerBanner->saveField($field, $change);
        $this->Session->setFlash('Đã cập nhật', 'success');
        $this->redirect($this->referer());
    }
    
//    public function ketnoi_delete($id = null)
//    {
//        $this->autoRender = FALSE;
//        $this->Ketnoi->delete($id);
//        $this->TagsKetnoi->deleteAll(array('ketnoi_id'=>$id));
//        $this->Session->setFlash('Đã xóa', 'success');
//        $this->redirect($this->referer());
//    }
//    
//    public function ketnoi_sort()
//    {
//        $this->autoRender = FALSE;
//        $p = $_POST['sort'];
//       
//        foreach ($p as $k => $v) {
//            if($v == '' || $v == '0')
//                $v = 1;
//            
//            $this->Node->id = $k;
//            $this->Node->saveField('pos', $v);
//            $i++;
//        }
//        $this->redirect($this->referer());
//    }
//
//    public function check_exits_ketnoi($title)
//    {
//        $this->autoRender = false;
//        $slug = strtolower(Inflector::slug($title, '-'));
//        echo ($this->Ketnoi->check_exist_slug($slug) == FALSE) ? "0" : "1"; die;
//    }
}