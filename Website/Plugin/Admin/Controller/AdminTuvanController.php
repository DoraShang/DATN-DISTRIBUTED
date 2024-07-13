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

class AdminTuvanController extends AdminAppController
{
    public $uses = array('Tuvan', 'Admin.Category', 'Node', 'Tag', 'CategoryLinked');
    
    public function beforeFilter() 
    {
        parent::beforeFilter();
        $this->get_categories(array('type'=>'tuvan'));
        
        $this->Session->delete('tuvan_title');
        $this->Session->delete('tuvan_title_org');
    }
    
    public function tuvan_search()
    {
        $key = (isset($_GET['key']) && $_GET['key'] != '') ? trim($_GET['key']) : '';
        $this->paginate = array(
            'joins'=>array(
                array(
                    'table'=>'tuvans',
                    'alias'=>'Tuvan',
                    'type'=>'INNER',
                    'conditions'=>array('Tuvan.node_id = Node.id')
                )
            ),
            'conditions'=> array(
                'Node.title LIKE'=>'%'.$key.'%'
            ),
            'limit' => 10,
            'order' => 'Node.pos DESC, Node.id DESC',
            'fields'=>array('Node.*', 'Tuvan.*')
        );
        
        $this->data = $this->paginate('Node');
    }
        
    public function tuvan_list($filter_category = null, $filter_status = null)
    {
        $conditions = array();
        $conditions['Node.type'] = 'tuvan';
        
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
                    'table'=>'tuvans',
                    'alias'=>'Tuvan',
                    'type'=>'INNER',
                    'conditions'=>array('Tuvan.node_id = Node.id')
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
            'fields'=>array('Node.*', 'Tuvan.*')
        );
        
        $this->data = $this->paginate('Node');
        $this->set('filter_category', $filter_category);
        $this->set('filter_status', $filter_status);
    }

    public function tuvan_add()
    {
        if($this->data)
        {
            $data = $this->data['Tuvan'];
            $data_node = $this->data['Node'];
            $data_node['slug'] = strtolower(Inflector::slug($data_node['title'], '-'));
            $data_node['created'] = strtotime($data_node['created']);
            $data_node['modified'] = time();
            $data_node['type'] = 'tuvan';
            
            $data['image'] = $this->remove_hostname($data['image']);
            $data['admin_id'] = $this->admin['id'];

            $data_tags = $data['tags'] != "" ? explode(',', $data['tags']) : '';
            
            $check = $this->Node->findBySlug($data_node['slug']);

            if(is_array($check) && count($check) > 0)
            {
                $this->Session->setFlash("Đã tồn tại bài viết", 'success');
                $this->redirect($this->referer());
                die;
                //$data_node['slug'] = $data_node['slug'].'-'.time();
            }

            //save node
            $this->Node->save($data_node);
            $node_id = $this->Node->getLastInsertID();

            //save tuvan
            $data['node_id'] = $node_id;
            $this->Tuvan->save($data);

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

            //save tags
            $tag_relation = array();

            if(is_array($data_tags) && count($data_tags) > 0)
            {
                foreach($data_tags as $v)
                {
                    $tag_title = trim($v);
                    
                    if($tag_title != "")
                    {
                        $tag_slug = strtolower(Inflector::slug($tag_title, '-'));
                        $check_tag = $this->Node->findBySlug($tag_slug);

                        if(is_array($check_tag) && count($check_tag))
                        {
                            $tag_relation[] = $check_tag['Node']['id'];
                        }
                        else
                        {
                            $this->Node->create();
                            $this->Node->save(array(
                                'title'=>$tag_title,
                                'slug'=>$tag_slug,
                                'type'=>'tag'
                            ));

                            $tag_relation[] = $this->Node->getLastInsertID();
                        }
                    }
                }
            }

            if(count($tag_relation) > 0)
            {
                foreach($tag_relation as $v)
                {
                    $this->Tag->create();
                    $this->Tag->save(array(
                        'node_id'=>$node_id,
                        'node_tag_id'=>$v
                    ));
                }
            }
            
            $this->Session->setFlash('Đã thêm', 'success');
            $this->redirect(DOMAINAD . 'admin_tuvan/tuvan_list');
        }
    }

    public function tuvan_edit($id = null)
    {
        if($this->data)
        {
            $data = $this->data['Tuvan'];
            $data_node = $this->data['Node'];

            $data_node['slug'] = strtolower($data_node['slug']);
            $data_node['created'] = strtotime($data_node['created']);
            $data_node['modified'] = time();
            
            $data_node['type'] = 'tuvan';
            $data['image'] = $this->remove_hostname($data['image']);
            $data_tags = $data['tags'] != "" ? explode(',', $data['tags']) : '';

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
                //$data_node['slug'] = $data_node['slug'].'-'.time();
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

            //update tuvan
            $check_tuvan_id = $this->Tuvan->find('first', array(
                'conditions'=>array(
                    'Tuvan.node_id' => $id
                )
            ));

            $tuvan_id = 0;
            if(is_array($check_tuvan_id) && count($check_tuvan_id) > 0)
                $tuvan_id = $check_tuvan_id['Tuvan']['id'];

            if($tuvan_id != 0)
            {
                if(isset($data['id'])) unset($data['id']);
                $this->Tuvan->id = $tuvan_id;
                $this->Tuvan->save($data);
            }
            
            //update tag
            $this->Tag->deleteAll(array('Tag.node_id' => $id), false);
            $tag_relation = array();

            if(is_array($data_tags) && count($data_tags) > 0)
            {
                foreach($data_tags as $v)
                {
                    $tag_title = trim($v);

                    if($tag_title != "")
                    {
                        $tag_slug = strtolower(Inflector::slug($tag_title, '-'));
                        $check_tag = $this->Node->findBySlug($tag_slug);

                        if(is_array($check_tag) && count($check_tag))
                        {
                            $tag_relation[] = $check_tag['Node']['id'];
                        }
                        else
                        {
                            $this->Node->create();
                            $this->Node->save(array(
                                'title'=>$tag_title,
                                'slug'=>$tag_slug,
                                'type'=>'tag'
                            ));

                            $tag_relation[] = $this->Node->getLastInsertID();
                        }
                    }
                }
            }

            if(count($tag_relation) > 0)
            {
                foreach($tag_relation as $v)
                {
                    $this->Tag->create();
                    $this->Tag->save(array(
                        'node_id'=>$id,
                        'node_tag_id'=>$v
                    ));
                }
            }
            
            $this->Session->setFlash('Đã sửa', 'success');
            $this->redirect($this->referer());
        }
        
        $this->data = $this->Tuvan->find('first', array(
            'joins'=>array(
                array(
                    'table'=>'nodes',
                    'alias'=>'Node',
                    'type'=>'INNER',
                    'conditions'=>array('Node.id = Tuvan.node_id')
                )
            ),
            'conditions'=>array(
                'Node.id'=>$id
            ),
            'fields'=>array('Node.*', 'Tuvan.*')
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
    }

    public function get_list_category_name($tuvan_node_id)
    {
        $this->autoRender = false;

        $data = $this->CategoryLinked->find('all', array(
            'conditions'=>array(
                'CategoryLinked.node_id'=>$tuvan_node_id
            ),
            'limit'=>20
        ));

        $str  = "";

        if(is_array($data) && count($data)>0)
        {
            foreach($data as $v)
            {
                $str .= $this->category_tree[$v['CategoryLinked']['category_id']] . ' ,';
            }
        }

        return trim($str, ', ');
    }

    public function tuvan_copy($id = null)
    {
        $this->data = $this->Tuvan->find('first', array(
            'joins'=>array(
                array(
                    'table'=>'nodes',
                    'alias'=>'Node',
                    'type'=>'INNER',
                    'conditions'=>array('Node.id = Tuvan.node_id')
                )
            ),
            'conditions'=>array(
                'Node.id'=>$id
            ),
            'fields'=>array('Node.*', 'Tuvan.*')
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
    
    public function update_field($field, $tuvan_id)
    {
        $change = 0;
        $data = $this->Tuvan->findById($tuvan_id);
        if($data['Tuvan'][$field] == 0)
            $change = 1;
        
        $this->Tuvan->id = $tuvan_id;
        $this->Tuvan->saveField($field, $change);
        $this->Session->setFlash('Đã cập nhật', 'success');
        $this->redirect($this->referer());
    }
    
//    public function tuvan_delete($id = null)
//    {
//        $this->autoRender = FALSE;
//        $this->Tuvan->delete($id);
//        $this->TagsTuvan->deleteAll(array('tuvan_id'=>$id));
//        $this->Session->setFlash('Đã xóa', 'success');
//        $this->redirect($this->referer());
//    }
//    
//    public function tuvan_sort()
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
//    public function check_exits_tuvan($title)
//    {
//        $this->autoRender = false;
//        $slug = strtolower(Inflector::slug($title, '-'));
//        echo ($this->Tuvan->check_exist_slug($slug) == FALSE) ? "0" : "1"; die;
//    }
}