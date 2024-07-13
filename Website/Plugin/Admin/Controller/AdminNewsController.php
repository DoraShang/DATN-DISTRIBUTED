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

class AdminNewsController extends AdminAppController
{
    public $uses = array(
        'News', 'Admin.Category', 'Node', 'Tag', 'CategoryLinked', 'Admin.Tag',
        'Adv', 'Cloud', 'Colo', 'Domain', 'Server', 'Service', 'Hosting', 'Email'
    );
    public $limit = 10;

    public function beforeFilter()
    {
        parent::beforeFilter();
        // $this->get_categories(array('type'=>'news'));

        $this->Session->delete('news_title');
        $this->Session->delete('news_title_org');
        $this->set('limit', $this->limit);
    }

    public function news_search()
    {
        $key = (isset($_GET['key']) && $_GET['key'] != '') ? trim($_GET['key']) : '';
        $this->paginate = array(
            'joins' => array(
                array(
                    'table' => 'news',
                    'alias' => 'News',
                    'type' => 'INNER',
                    'conditions' => array('News.node_id = Node.id')
                )
            ),
            'conditions' => array(
                'Node.title LIKE' => '%' . $key . '%'
            ),
            'limit' => 10,
            'order' => 'Node.pos DESC, Node.id DESC',
            'fields' => array('Node.*', 'News.*')
        );

        $this->data = $this->paginate('Node');
    }

    public function news_waiting($filter_category = null, $filter_status = null)
    {
        $this->paginate = array(
            'limit' => $this->limit,
            'order' => 'UserPost.id DESC',
            'conditions' => array(
                'UserPost.status' => 0
            )
        );

        $this->data = $this->paginate('UserPost');
    }

    public function news_list($filter_category = null, $filter_status = null)
    {

        $this->_role('news_list');
        //         $tbl = array('Adv', 'Cloud', 'Colo', 'Domain', 'Server', 'Service', 'Hosting', 'Email');

        //         foreach($tbl as $v)
        //         {
        //             $data = $this->{$v}->find('all');

        //             foreach($data as $d)
        //             {
        //                 $title = $d[$v]['name'];
        //                 $slug = strtolower(Inflector::slug($title, '-'));
        //                 $type = strtolower($v);

        //                 $save = array(
        //                     'title'=>$title,
        //                     'slug' => $slug,
        //                     'type'=>$type
        //                 );

        //                 $check = $this->Node->findBySlug($slug);

        //                 if(is_array($check) && count($check) > 0)
        //                 {
        //                     $save['slug'] = $slug . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9);
        //                 }

        //                 $this->Node->create();
        //                 $this->Node->save($save);
        //                 $node_id = $this->Node->getLastInsertId();

        //                 $this->{$v}->id = $d[$v]['id'];
        //                 $this->{$v}->saveField('node_id', $node_id);
        //             }
        //         }

        //         echo 'done';

        // die;

        $conditions = array();
        $conditions['Node.type'] = 'news';

        $filter_category = isset($_GET['list_category']) ? $_GET['list_category'] : '';
        $filter_status = isset($_GET['filter_status']) ? $_GET['filter_status'] : '';
        $search = isset($_GET['s']) ? $_GET['s'] : '';

        if ($filter_category != '') {
            $check_cat = $this->Category->findById($filter_category);
            $data_check_cat = $this->Category->find('all', array(
                'conditions' => array(
                    'Category.lft >=' => $check_cat['Category']['lft'],
                    'Category.rght <=' => $check_cat['Category']['rght']
                )
            ));

            $buff[] = array();

            foreach ($data_check_cat as $v) {
                $buff[] = $v['Category']['id'];
            }

            $conditions['CategoryLinked.category_id'] = $buff;
        }

        if ($filter_status != '')
            $conditions['Node.status'] = $filter_status;

        if ($this->admin['id'] != 1) {
            $conditions['News.id'] = $this->admin['id'];
        }
        if ($search != '') {
            $conditions['Node.title LIKE'] = '%' . $search . '%';
        }
        $this->paginate = array(
            'joins' => array(
                array(
                    'table' => 'news',
                    'alias' => 'News',
                    'type' => 'INNER',
                    'conditions' => array('News.node_id = Node.id')
                ),
                array(
                    'table' => 'category_linkeds',
                    'alias' => 'CategoryLinked',
                    'type' => 'LEFT',
                    'conditions' => 'CategoryLinked.node_id = Node.id'
                )
            ),
            'conditions' => $conditions,
            'limit' => $this->limit,
            'order' => 'Node.pos DESC, Node.id DESC',
            'group' => 'CategoryLinked.node_id',
            'fields' => array('Node.*', 'News.*')
        );

        $this->data = $this->paginate('Node');
        $this->set('filter_category', $filter_category);
        $this->set('filter_status', $filter_status);
    }

    public function news_add()
    {
        $this->_role('news_add');
        if ($this->data) {
            $data = $this->data['News'];
            $data_node = $this->data['Node'];
            if (isset($data_node['slug']) && $data_node['slug'] != '') {
                $data_node['slug'] = $data_node['slug'];
            } else {
                $data_node['slug'] = strtolower(Inflector::slug($data_node['title'], '-'));
            }
            $data_node['created'] = strtotime($data_node['created']);
            $data_node['modified'] = time();
            $data_node['type'] = 'news';

            $data['image'] = $this->remove_hostname($data['image']);
            $data['admin_id'] = $this->admin['id'];

            $data_tags = array();
            if (isset($data['tags']))
                $data_tags = $data['tags'] != "" ? explode(',', $data['tags']) : '';

            $check = $this->Node->findBySlug($data_node['slug']);

            if (is_array($check) && count($check) > 0) {
                // $this->Session->setFlash("Đã tồn tại bài viết", 'success');
                // $this->redirect($this->referer());
                // die;
                $data_node['slug'] = $data_node['slug'] . '-' . time();
            }

            //save node
            $this->Node->save($data_node);
            $node_id = $this->Node->getLastInsertID();

            //save news
            $data['node_id'] = $node_id;


            $data['content_search'] = strip_tags($data['content']);
            $data['content_search'] = $this->Capp->convert_vi_to_en($data['content_search']);
            $data['content_search'] = strtolower($data['content_search']);
            $date = getdate($data_node['created']);
            $data['year_create'] = $date['year'];


            $this->News->save($data);

            if (isset($this->data['category_id']) && is_array($this->data['category_id']) && count($this->data['category_id']) > 0) {
                foreach ($this->data['category_id'] as $v) {
                    $this->CategoryLinked->create();
                    $this->CategoryLinked->save(array(
                        'category_id' => $v,
                        'node_id' => $node_id
                    ));
                }
            }

            //save tags
            $tag_relation = array();

            if (is_array($data_tags) && count($data_tags) > 0) {
                foreach ($data_tags as $v) {
                    $tag_title = trim($v);

                    if ($tag_title != "") {
                        $tag_slug = strtolower(Inflector::slug($tag_title, '-'));
                        $check_tag = $this->Node->findBySlug($tag_slug);

                        if (is_array($check_tag) && count($check_tag)) {
                            $tag_relation[] = $check_tag['Node']['id'];
                        } else {
                            $this->Node->create();
                            $this->Node->save(array(
                                'title' => $tag_title,
                                'slug' => $tag_slug,
                                'type' => 'tag'
                            ));

                            $tag_relation[] = $this->Node->getLastInsertID();
                        }
                    }
                }
            }

            if (count($tag_relation) > 0) {
                foreach ($tag_relation as $v) {
                    $this->Tag->create();
                    $this->Tag->save(array(
                        'node_id' => $node_id,
                        'node_tag_id' => $v
                    ));
                }
            }

            $this->Session->setFlash('Đã thêm', 'success');
            $this->redirect($this->get_redirect('news', 'add', $node_id));
            die;
        }
    }

    public function news_edit($id = null)
    {
        $this->_role('news_edit');
        if ($this->data) {
            $data = $this->data['News'];
            $data_node = $this->data['Node'];

            $data_node['slug'] = strtolower($data_node['slug']);
            $data_node['created'] = strtotime($data_node['created']);
            $data_node['modified'] = time();

            $data_node['type'] = 'news';
            $data['image'] = $this->remove_hostname($data['image']);

            if (isset($data['tags']))
                $data_tags = $data['tags'] != "" ? explode(',', $data['tags']) : '';

            $check = $this->Node->find('first', array(
                'conditions' => array(
                    'Node.slug' => $data_node['slug'],
                    'NOT' => array(
                        'Node.id' => $id
                    )
                )
            ));

            if (is_array($check) && count($check) > 0) {
                $this->Session->setFlash("Đã tồn tại bài viết", 'success');
                $this->redirect($this->referer());
                die;
                //$data_node['slug'] = $data_node['slug'].'-'.time();
            }

            //update node
            if (isset($data_node['id'])) unset($data_node['id']);

            $this->Node->id = $id;
            $this->Node->save($data_node);

            if (isset($this->data['category_id']) && is_array($this->data['category_id']) && count($this->data['category_id']) > 0) {
                $this->CategoryLinked->deleteAll(array('CategoryLinked.node_id' => $id), false);

                foreach ($this->data['category_id'] as $v) {
                    $this->CategoryLinked->create();
                    $this->CategoryLinked->save(array(
                        'category_id' => $v,
                        'node_id' => $id
                    ));
                }
            }

            //update news
            $check_news_id = $this->News->find('first', array(
                'conditions' => array(
                    'News.node_id' => $id
                )
            ));

            $news_id = 0;
            if (is_array($check_news_id) && count($check_news_id) > 0)
                $news_id = $check_news_id['News']['id'];

            if ($news_id != 0) {
                if (isset($data['id'])) unset($data['id']);

                $data['content_search'] = strip_tags($data['content']);
                $data['content_search'] = $this->Capp->convert_vi_to_en($data['content_search']);
                $data['content_search'] = strtolower($data['content_search']);
                $date = getdate($data_node['created']);
                $data['year_create'] = $date['year'];
                $this->News->id = $news_id;
                $this->News->save($data);
            }

            //update tag
            $this->Tag->deleteAll(array('Tag.node_id' => $id), false);
            $tag_relation = array();

            if (isset($data_tags) && is_array($data_tags) && count($data_tags) > 0) {
                foreach ($data_tags as $v) {
                    $tag_title = trim($v);

                    if ($tag_title != "") {
                        $tag_slug = strtolower(Inflector::slug($tag_title, '-'));
                        $check_tag = $this->Node->findBySlug($tag_slug);

                        if (is_array($check_tag) && count($check_tag)) {
                            $tag_relation[] = $check_tag['Node']['id'];
                        } else {
                            $this->Node->create();
                            $this->Node->save(array(
                                'title' => $tag_title,
                                'slug' => $tag_slug,
                                'type' => 'tag'
                            ));

                            $tag_relation[] = $this->Node->getLastInsertID();
                        }
                    }
                }
            }

            if (count($tag_relation) > 0) {
                foreach ($tag_relation as $v) {
                    $this->Tag->create();
                    $this->Tag->save(array(
                        'node_id' => $id,
                        'node_tag_id' => $v
                    ));
                }
            }

            $this->Session->setFlash('Đã sửa', 'success');
            $this->redirect($this->get_redirect('news', 'edit', $id));
            die;
        }

        $this->data = $this->News->find('first', array(
            'joins' => array(
                array(
                    'table' => 'nodes',
                    'alias' => 'Node',
                    'type' => 'INNER',
                    'conditions' => array('Node.id = News.node_id')
                )
            ),
            'conditions' => array(
                'Node.id' => $id
            ),
            'fields' => array('Node.*', 'News.*')
        ));

        $cats = $this->CategoryLinked->find('all', array(
            'conditions' => array(
                'CategoryLinked.node_id' => $id
            ),
            'fields' => 'category_id'
        ));

        $buff = array();

        if (is_array($cats) && count($cats) > 0) {
            foreach ($cats as $v) {
                $buff[] = $v['CategoryLinked']['category_id'];
            }
        }

        $this->set('cat_selected', $buff);

        $this->set('data_tags', $this->Tag->find('all', array(
            'joins' => array(
                array(
                    'table' => 'nodes',
                    'alias' => 'Node',
                    'type' => 'INNER',
                    'conditions' => array('Tag.node_tag_id=Node.id')
                )
            ),
            'conditions' => array(
                'Tag.node_id' => $id
            ),
            'limit' => 6,
            'fields' => array('Node.*', 'Tag.*')
        )));
    }


    public function news_res($user_post_id)
    {
        if ($this->data) {
            $data = $this->data['News'];
            $data_node = $this->data['Node'];
            $data_node['slug'] = strtolower(Inflector::slug($data_node['title'], '-'));
            $data_node['created'] = strtotime($data_node['created']);
            $data_node['modified'] = time();
            $data_node['type'] = 'news';

            $data['image'] = $this->remove_hostname($data['image']);
            $data['admin_id'] = $this->admin['id'];

            $data_tags = array();
            if (isset($data['tags']))
                $data_tags = $data['tags'] != "" ? explode(',', $data['tags']) : '';

            $check = $this->Node->findBySlug($data_node['slug']);

            if (is_array($check) && count($check) > 0) {
                // $this->Session->setFlash("Đã tồn tại bài viết", 'success');
                // $this->redirect($this->referer());
                // die;
                $data_node['slug'] = $data_node['slug'] . '-' . time();
            }

            //save node
            $this->Node->save($data_node);
            $node_id = $this->Node->getLastInsertID();

            //save news
            $data['node_id'] = $node_id;
            $this->News->save($data);

            if (isset($this->data['category_id']) && is_array($this->data['category_id']) && count($this->data['category_id']) > 0) {
                foreach ($this->data['category_id'] as $v) {
                    $this->CategoryLinked->create();
                    $this->CategoryLinked->save(array(
                        'category_id' => $v,
                        'node_id' => $node_id
                    ));
                }
            }

            //save tags
            $tag_relation = array();

            if (is_array($data_tags) && count($data_tags) > 0) {
                foreach ($data_tags as $v) {
                    $tag_title = trim($v);

                    if ($tag_title != "") {
                        $tag_slug = strtolower(Inflector::slug($tag_title, '-'));
                        $check_tag = $this->Node->findBySlug($tag_slug);

                        if (is_array($check_tag) && count($check_tag)) {
                            $tag_relation[] = $check_tag['Node']['id'];
                        } else {
                            $this->Node->create();
                            $this->Node->save(array(
                                'title' => $tag_title,
                                'slug' => $tag_slug,
                                'type' => 'tag'
                            ));

                            $tag_relation[] = $this->Node->getLastInsertID();
                        }
                    }
                }
            }

            if (count($tag_relation) > 0) {
                foreach ($tag_relation as $v) {
                    $this->Tag->create();
                    $this->Tag->save(array(
                        'node_id' => $node_id,
                        'node_tag_id' => $v
                    ));
                }
            }

            $this->UserPost->id = $user_post_id;
            $this->UserPost->saveField('status', 1);

            $this->Session->setFlash('Đã duyệt bài viết', 'success');
            $this->redirect(DOMAINAD . 'admin_news/news_waiting');
            die;
        }


        $check = $this->UserPost->findById($user_post_id);
        $c = html_entity_decode($check['UserPost']['content']);
        $slug = strtolower(Inflector::slug($check['UserPost']['title'], '-'));
        $data = array(
            'Node' => array(
                'title' => $check['UserPost']['title'],
                'created' => $check['UserPost']['created'],
                'slug' => $slug
            ),
            'News' => array(
                'description' => $check['UserPost']['title'],
                'content' => $c,
                // 'content' => $check['UserPost']['content'],
            )
        );

        $this->data = $data;

        $buff = array();
        $buff[] = $check['UserPost']['category_id'];
        $this->set('cat_selected', $buff);

        // $this->set('data_tags', $this->Tag->find('all', array(
        //     'joins'=>array(
        //         array(
        //             'table'=>'nodes',
        //             'alias'=>'Node',
        //             'type'=>'INNER',
        //             'conditions'=>array('Tag.node_tag_id=Node.id')
        //         )
        //     ),
        //     'conditions'=>array(
        //         'Tag.node_id' => $id
        //     ),
        //     'limit'=>6,
        //     'fields'=>array('Node.*', 'Tag.*')
        // )));

    }

    public function get_category_name($cid)
    {
        $this->autoRender = false;

        $check = $this->Category->findById($cid);

        return $check['Category']['title'];
    }

    public function get_list_category_name($news_node_id)
    {
        $this->autoRender = false;

        $data = $this->CategoryLinked->find('all', array(
            'conditions' => array(
                'CategoryLinked.node_id' => $news_node_id
            ),
            'limit' => 20
        ));

        $str  = "";

        if (is_array($data) && count($data) > 0) {
            foreach ($data as $v) {
                if (isset($this->category_tree[$v['CategoryLinked']['category_id']]))
                    $str .= $this->category_tree[$v['CategoryLinked']['category_id']] . ' ,';
            }
        }

        return trim($str, ', ');
    }

    public function news_copy($id = null)
    {
        $this->data = $this->News->find('first', array(
            'joins' => array(
                array(
                    'table' => 'nodes',
                    'alias' => 'Node',
                    'type' => 'INNER',
                    'conditions' => array('Node.id = News.node_id')
                )
            ),
            'conditions' => array(
                'Node.id' => $id
            ),
            'fields' => array('Node.*', 'News.*')
        ));

        $this->set('data_tags', $this->Tag->find('all', array(
            'joins' => array(
                array(
                    'table' => 'nodes',
                    'alias' => 'Node',
                    'type' => 'INNER',
                    'conditions' => array('Tag.node_tag_id=Node.id')
                )
            ),
            'conditions' => array(
                'Tag.node_id' => $id
            ),
            'limit' => 6,
            'fields' => array('Node.*', 'Tag.*')
        )));

        $cats = $this->CategoryLinked->find('all', array(
            'conditions' => array(
                'CategoryLinked.node_id' => $id
            ),
            'fields' => 'category_id'
        ));

        $buff = array();

        if (is_array($cats) && count($cats) > 0) {
            foreach ($cats as $v) {
                $buff[] = $v['CategoryLinked']['category_id'];
            }
        }

        $this->set('cat_selected', $buff);
    }

    public function update_field($field, $news_id)
    {
        $change = 0;
        $data = $this->News->findById($news_id);
        if ($data['News'][$field] == 0)
            $change = 1;

        $this->News->id = $news_id;
        $this->News->saveField($field, $change);
        $this->Session->setFlash('Đã cập nhật', 'success');
        $this->redirect($this->referer());
    }

    //    public function news_delete($id = null)
    //    {
    //        $this->autoRender = FALSE;
    //        $this->News->delete($id);
    //        $this->TagsNews->deleteAll(array('news_id'=>$id));
    //        $this->Session->setFlash('Đã xóa', 'success');
    //        $this->redirect($this->referer());
    //    }
    //    
    //    public function news_sort()
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
    //    public function check_exits_news($title)
    //    {
    //        $this->autoRender = false;
    //        $slug = strtolower(Inflector::slug($title, '-'));
    //        echo ($this->News->check_exist_slug($slug) == FALSE) ? "0" : "1"; die;
    //    }
}
