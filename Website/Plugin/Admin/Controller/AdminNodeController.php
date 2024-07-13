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

class AdminNodeController extends AdminAppController
{

    public $uses = array('Node', 'Category', 'News', 'Page', 'Faq', 'Picture', 'Video', 'Image', 'CategoryLinked', 'Tuvan', 'Product', 'Document', 'Tiendo', 'Ketnoi', 'Guide', 'Service');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->autoRender = false;
    }

    public function node_search()
    {
        $this->layout = 'default';

        $key = (isset($_GET['key']) && $_GET['key'] != '') ? trim($_GET['key']) : '';

        $this->paginate = array(
            'conditions' => array(
                'Node.title LIKE' => '%' . $key . '%',
                'Node.type' => array('news', 'product')
            ),
            'limit' => 10,
            'order' => 'Node.pos DESC, Node.id DESC',
            'fields' => array('Node.*')
        );

        $this->data = $this->paginate('Node');
        $this->render('node_search');
    }

    public function get_list_category_name($item_node_id)
    {
        $this->autoRender = false;

        $data = $this->CategoryLinked->find('all', array(
            'conditions' => array(
                'CategoryLinked.node_id' => $item_node_id
            ),
            'limit' => 20
        ));

        $str  = "";

        if (is_array($data) && count($data) > 0) {
            foreach ($data as $v) {
                $str .= $this->category_tree[$v['CategoryLinked']['category_id']] . ' ,';
            }
        }

        return trim($str, ', ');
    }

    // public function update_cat()
    // {
    //     $array = array();
    //     $data = $this->Node->find('all', array(
    //         'conditions'=>array(
    //             'Node.type'=>array('news', 'product')
    //         )
    //     ));

    //     foreach($data as $v)
    //     {
    //         $array[$v['Node']['id']] = $v['Node']['category_id'];
    //     }

    //     foreach($array as $k=>$v)
    //     {
    //         $this->CategoryLinked->create();
    //         $this->CategoryLinked->save(array(
    //             'category_id' => $v,
    //             'node_id'=>$k
    //         ));
    //     }
    //     pr($array);
    //     echo count($array);
    // }

    public function get_slug()
    {
        $this->autoRender = false;
        $param = isset($_GET['t']) ? trim($_GET['t']) : '';
        $slug = strtolower(Inflector::slug($param, '-'));
        return $slug;
    }

    public function set_title_to_ckfinder()
    {
        $this->autoRender = FALSE;
        $title = $_GET['title'];
        $slug = strtolower(Inflector::slug($title, '-'));
        $this->Session->write('news_title', $slug);
        $this->Session->write('news_title_org', $title); /*su dung trong finder.php*/
    }

    public function update_home_section()
    {
        $data = $this->data;

        $tbl = ucfirst($data['type']);
        $field = $data['field'];
        $value = $data['value'];
        $id = $data['id'];

        $check = $this->{$tbl}->find('first', array(
            'conditions' => array(
                $tbl . '.' . $field => $value
            )
        ));

        // pr($check);
        // pr($data); die();
        if (is_array($check) && count($check) > 0) {
            $this->{$tbl}->id = $check["{$tbl}"]['id'];
            $this->{$tbl}->saveField($field, 0);
        }

        $this->{$tbl}->id = $id;
        $this->{$tbl}->saveField($field, $value);

        $this->redirect($this->referer());
    }

    public function update_pos()
    {
        $p = $_POST['sort'];
        $delete = $_POST['delete_all'];
        foreach ($p as $k => $v) {
            if ($v == '') {
                $v = 1;
            }

            $this->Node->id = $k;
            $this->Node->saveField('pos', $v);
        }

        if (is_array($delete) && count($delete) > 0) {
            foreach ($delete as $v) {
                $this->node_delete($v, true);
            }
            $this->Session->setFlash("Đã Xóa thành công", "success");
        }

        $this->redirect($this->referer());
    }

    public function update_status($node_id)
    {
        $updated = 0;
        $check = $this->Node->findById($node_id);
        if ($check['Node']['status'] == 0)
            $updated = 1;

        $this->Node->id = $node_id;
        $this->Node->saveField('status', $updated);

        $this->Session->setFlash('Đã thay đổi', 'success');
        $this->redirect($this->referer());
    }

    public function node_delete($node_id, $check_delete_all = false)
    {
        if (in_array($node_id, $this->node_required)) {
            $this->Session->setFlash('Dữ liệu mặc định không thể xóa.', 'error');
            $this->redirect($this->referer());
            die;
        }

        $data = $this->Node->findById($node_id);
        $type = $data['Node']['type'];
        $redirect_page = isset($_GET['rp']) && $_GET['rp'] > 1 ? 'page:' . $_GET['rp'] : '';
        $rp = isset($_GET['rp']) && is_numeric($_GET['rp']) ? $_GET['rp'] : '';
        $redirect_list = DOMAINAD;

        switch ($type) {
            case 'category':
                $this->delete_category($node_id);
                $redirect_list = $redirect_list . 'admin_category/category_list/' . $rp;
                break;
            case 'news':
                $this->delete_news($node_id);
                $redirect_list = $redirect_list . 'admin_news/news_list/' . $redirect_page;
                break;
            case 'product':
                $this->delete_product($node_id);
                $redirect_list = $redirect_list . 'admin_product/product_list/' . $redirect_page;
                break;
            case 'guide':
                $this->delete_guide($node_id);
                $redirect_list = $redirect_list . 'admin_guide/guide_list/' . $redirect_page;
                break;
            case 'service':
                $this->delete_service($node_id);
                $redirect_list = $redirect_list . 'admin_service/service_list/' . $redirect_page;
                break;
            case 'faq':
                $this->delete_faq($node_id);
                $redirect_list = $redirect_list . 'admin_faq/faq_list/' . $redirect_page;
                break;
            case 'picture':
                $this->delete_picture($node_id);
                $redirect_list = $redirect_list . 'admin_picture/picture_list/' . $redirect_page;
                break;
            case 'video':
                $this->delete_video($node_id);
                $redirect_list = $redirect_list . 'admin_video/video_list/' . $redirect_page;
                break;
            case 'tuvan':
                $this->delete_tuvan($node_id);
                $redirect_list = $redirect_list . 'admin_tuvan/tuvan_list/' . $redirect_page;
                break;
            case 'tiendo':
                $this->delete_tiendo($node_id);
                $redirect_list = $redirect_list . 'admin_tiendo/tiendo_list/' . $redirect_page;
                break;
            case 'document':
                $this->delete_document($node_id);
                $redirect_list = $redirect_list . 'admin_document/document_list/' . $redirect_page;
                break;
            case 'ketnoi':
                $this->delete_ketnoi($node_id);
                $redirect_list = $redirect_list . 'admin_ketnoi/ketnoi_list/' . $redirect_page;
                break;
            default:
                $this->Node->delete($node_id);
                break;
        }
        if ($check_delete_all == true) {
        } else {
            $this->Session->setFlash('Đã xóa mục lục.', 'success');
            $this->redirect($redirect_list);
        }
    }

    public function delete_ketnoi($node_id)
    {
        $this->_role('ketnoi_delete');
        $this->Node->delete($node_id);
        $this->Product->deleteAll(array('Ketnoi.node_id' => $node_id));
        // $this->CategoryLinked->deleteAll(array('CategoryLinked.node_id'=>$node_id));
    }

    public function delete_product($node_id)
    {
        $this->_role('product_delete');
        $this->Node->delete($node_id);
        $this->Product->deleteAll(array('Product.node_id' => $node_id));
        $this->Image->deleteAll(array('Image.node_id' => $node_id), false);
        $this->Video->deleteAll(array('Video.node_id' => $node_id), false);
        $this->CategoryLinked->deleteAll(array('CategoryLinked.node_id' => $node_id));
    }
    public function delete_guide($node_id)
    {
        $this->_role('guide_delete');
        $this->Node->delete($node_id);
        $this->Guide->deleteAll(array('Guide.node_id' => $node_id));
        $this->Image->deleteAll(array('Image.node_id' => $node_id), false);
        $this->Video->deleteAll(array('Video.node_id' => $node_id), false);
        $this->CategoryLinked->deleteAll(array('CategoryLinked.node_id' => $node_id));
    }
    public function delete_service($node_id)
    {
        $this->_role('service_delete');
        // pr($node_id);
        $this->Node->delete($node_id);
        $this->Service->deleteAll(array('Service.node_id' => $node_id));
        $this->Image->deleteAll(array('Image.node_id' => $node_id), false);
        $this->Video->deleteAll(array('Video.node_id' => $node_id), false);
        $this->CategoryLinked->deleteAll(array('CategoryLinked.node_id' => $node_id));
    }
    public function delete_tiendo($node_id)
    {
        $this->Node->delete($node_id);
        $this->Tiendo->deleteAll(array('Tiendo.node_id' => $node_id));
        $this->CategoryLinked->deleteAll(array('CategoryLinked.node_id' => $node_id));
    }

    public function delete_tuvan($node_id)
    {
        $this->Node->delete($node_id);
        $this->Tuvan->deleteAll(array('Tuvan.node_id' => $node_id));
    }

    public function delete_video($node_id)
    {
        $this->Node->delete($node_id);
        $this->Video->deleteAll(array('Video.node_id' => $node_id));
    }

    public function delete_faq($node_id)
    {
        $this->Node->delete($node_id);
        $this->Faq->deleteAll(array('Faq.node_id' => $node_id));
    }

    public function delete_picture($node_id)
    {
        $this->Node->delete($node_id);
        $this->Picture->deleteAll(array('Picture.node_id' => $node_id));
    }

    public function delete_page($node_id)
    {
        $this->Node->delete($node_id);
        $this->Page->deleteAll(array('Page.node_id' => $node_id));
    }

    public function delete_news($node_id)
    {
        $this->_role('news_delete');
        $this->Node->delete($node_id);
        $this->News->deleteAll(array('News.node_id' => $node_id));
        $this->CategoryLinked->deleteAll(array('CategoryLinked.node_id' => $node_id));
    }


    public function delete_document($node_id)
    {
        $this->_role('document_delete');
        $this->Node->delete($node_id);
        $this->Document->deleteAll(array('Document.node_id' => $node_id));
        $this->CategoryLinked->deleteAll(array('CategoryLinked.node_id' => $node_id));
    }

    public function delete_category($node_id)
    {
        $this->_role('category_delete');
        $data = $this->Category->find('first', array(
            'conditions' => array(
                'Category.node_id' => $node_id
            )
        ));

        $category_lst = $this->Category->find('all', array(
            'conditions' => array(
                'Category.lft >=' => $data['Category']['lft'],
                'Category.rght <=' => $data['Category']['rght']
            )
        ));

        foreach ($category_lst as $v) {
            $this->Node->delete($v['Category']['node_id']);
            $this->Category->delete($v['Category']['id']);
        }
    }

    public function check_exits_news($title, $excluded = null)
    {
        if ($excluded == null) {
            $check = $this->Node->findByTitle($title);
        } else {
            $check = $this->Node->find('first', array(
                'conditions' => array(
                    'Node.title' => $title,
                    'NOT' => array(
                        'Node.id' => $excluded
                    )
                )
            ));
        }

        if (is_array($check) && count($check) > 0) {
            echo '1';
            die;
        }
        echo '0';
        die;
    }
}
