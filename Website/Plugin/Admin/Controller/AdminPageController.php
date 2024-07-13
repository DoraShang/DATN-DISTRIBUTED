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

class AdminPageController extends AdminAppController
{

    public $uses = array('Node', 'Page', 'Element', 'PageElement');

    public $my_template = array(
        'page_whyAdt' => 'Page Why ADT',
    );

    public function beforeFilter()
    {
        parent::beforeFilter();

        $this->set('my_template', $this->my_template);
    }

    public function page_list()
    {
        $this->paginate = array(
            'order' => array('Page.pos DESC', 'Page.id DESC'),
            'fields' => array('Page.*'),
            'limit' => 12
        );

        $this->data = $this->paginate('Page');
    }

    public function page_add()
    {
        if ($this->data) {
            $data_page = $this->data['Page'];

            $data_page['slug'] = Inflector::slug(strtolower($data_page['title']), '-');

            $check = $this->Page->findBySlug($data_page['slug']);
            if (is_array($check) && count($check) > 0) {
                $this->Session->setFlash("Tên đã tồn tại", "error");
                $this->redirect($this->referer());
                die;
            }
            $this->Page->save($data_page);
            $this->Session->setFlash("Đã thêm", "success");
            $this->redirect($this->referer());
            die;
        }
    }

    public function page_edit($node_id)
    {
        if ($this->data) {
            $data_node = $this->data['Node'];
            $data_page = $this->data['Page'];

            $data_node['type'] = 'page';
            $data_node['slug'] = strtolower(Inflector::slug($data_node['title'], '-'));

            $check = $this->Node->find('first', array(
                'conditions' => array(
                    'Node.slug' => $data_node['slug'],
                    'NOT' => array(
                        'Node.id' => $node_id
                    )
                )
            ));

            if (is_array($check) && count($check) > 0) {
                $this->Session->setFlash("Tên đã tồn tại", "error");
                $this->redirect($this->referer());
                die;
            }

            $this->Node->id = $node_id;
            $this->Node->save($data_node);

            $data_page['node_id'] = $node_id;
            $data_page['image'] = $this->remove_hostname($data_page['image']);

            $check_page = $this->Page->find('first', array(
                'conditions' => array(
                    'Page.node_id' => $node_id
                )
            ));

            $this->Page->id = $check_page['Page']['id'];
            $this->Page->save($data_page);

            //            $update = array();
            //            $conn = array('Page.node_id'=>$node_id);
            //            
            //            foreach($data_page as $k=>$v)
            //            {
            //                $update[$k] = '"' . $v . '"';
            //            }
            //            
            //            $this->Page->updateAll($update, $conn);            

            $this->Session->setFlash("Đã sửa", "success");
            $this->redirect($this->referer());
            die;
        }

        $this->data = $this->Node->find('first', array(
            'joins' => array(
                array(
                    'table' => 'pages',
                    'alias' => 'Page',
                    'type' => 'INNER',
                    'conditions' => array('Page.node_id=Node.id')
                )
            ),
            'conditions' => array(
                'Node.id' => $node_id
            ),
            'fields' => array('Page.*, Node.*')
        ));
    }

    public function template_edit($page_id)
    {
        $this->layout = 'blank';

        $key_field = [
            'image',
            'title',
            'description',
            'content',
            'link',
            'txt',
        ];

        if ($this->data) {
            // pr($this->data);
            // die;
            $check = $this->Element->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'page_elements',
                        'alias' => 'PageElement',
                        'type' => 'INNER',
                        'conditions' => array('PageElement.id_element=Element.id')
                    )
                ),
                'conditions' => array(
                    'PageElement.id_page' => $page_id
                ),
                'order' => 'PageElement.pos ASC, Element.id ASC',
                // 'group' => 'PageElement.id_page',
                'fields' => array('Element.*')
            ));
            if (is_array($check) && count($check) > 0) {
                foreach ($check as $v) {
                    $this->Element->delete($v['Element']['id']);
                }
            }
            $page_element = $this->data['page_element'];

            $element_id = array();
            $i = 1;
            foreach ($page_element as $v) {
                $tmp = array();
                $element = array();
                $field = $v['field'];
                $tmp['type_tem'] = $v['type_tem'];
                $element['pos'] = $i;
                foreach ($key_field as $k) {
                    if (!key_exists($k, $field)) {
                        $field[$k] = '';
                    }
                }
                $tmp['fields'] = json_encode($field);
                $this->Element->create();
                $this->Element->save($tmp);
                $eid = $this->Element->getLastInsertID();
                $element['id'] = $eid;
                $element_id[] = $element;
                $i++;
            }
            if (is_array($element_id) && count($element_id) > 0) {
                $this->PageElement->deleteAll(array('PageElement.id_page' => $page_id), false);
                foreach ($element_id as $e) {
                    $this->PageElement->create();
                    $this->PageElement->save(array(
                        'id_element' => $e['id'],
                        'pos' => $e['pos'],
                        'id_page' => $page_id
                    ));
                }
            }
        }
        $page = $this->Page->find('first', array(
            'conditions' => array(
                'Page.id' => $page_id,
            )
        ));
        $this->set('page', $page);
        $this->data = $this->Element->find('all', array(
            'joins' => array(
                array(
                    'table' => 'page_elements',
                    'alias' => 'PageElement',
                    'type' => 'INNER',
                    'conditions' => array('PageElement.id_element=Element.id')
                )
            ),
            'conditions' => array(
                'PageElement.id_page' => $page_id
            ),
            'order' => 'PageElement.pos ASC, Element.id ASC',
            // 'group' => 'PageElement.id_page',
            'fields' => array('Element.*', 'PageElement.*')
        ));
    }
}
