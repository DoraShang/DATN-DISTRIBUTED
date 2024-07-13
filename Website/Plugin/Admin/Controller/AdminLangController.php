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
class AdminLangController extends AdminAppController {

    public $uses = array('Lang');

    public function lang_index($type = null) {
        $this->autoRender = false;
    }

    public function lang_list() {
        $this->data = $this->Lang->find('all', array(
            'order' => 'Lang.id DESC'
        ));
    }

    public function data_link($key)
    {
        $this->autoRender = false;
        
        $check = $this->Lang->find('first', array(
            'conditions'=>array(
                'Lang.key' => $key
            )
        ));

        return is_array($check) && count($check) > 0 ? $check['Lang']['vi'] : '';
    }

    public function lang_add() {
        if ($this->data) {
            $data = $this->data['Lang'];
            $data['key'] = trim($data['key']);

            $this->Lang->save($data);
            $this->Session->setFlash('Đã thêm mới', 'success');
            $this->redirect($this->referer());
        }
    }

    public function lang_edit($id = null) 
    {
        if ($this->data) 
        {
            $data = $this->data['Lang'];
            $data['key'] = trim($data['key']);
            if(isset($this->data['flag_image']) && $this->data['flag_image'] == 1)
                $data['vi'] = $this->remove_hostname($data['vi']);
            
            $this->Lang->id = $id;
            $this->Lang->save($data);

            $mobilekey = $data['key'] . '-mobile';
            
            if(isset($data[$mobilekey]))
            {
                $linkfield = $mobilekey;
                $linkdata = $data[$mobilekey];

                if(isset($this->data['flag_image']))
                    $linkdata = $this->remove_hostname($linkdata);

                // $update = array('vi'=>$linkdata, 'key'=>$linkfield);
                // pr($update);

                $this->Lang->updateAll(
                    array('Lang.vi'=>"'" . $linkdata . "'"),
                    array('Lang.key' => $linkfield)
                );
            }

            // pr($data); die;

            if(isset($data['image_link']) && $data['image_link'] == 1)
            {
                $linkfield = $data['key'] . '_link';
                $linkdata = $data['link'];

                $this->Lang->updateAll(
                    array('Lang.vi'=>"'" . $linkdata . "'"),
                    array('Lang.key' => $linkfield)
                );
            }

            if(isset($data['text_link']) && $data['text_link'] == 1)
            {
                $linkfield = $data['key'] . '_link';
                $linkdata = $data['link'];

                $this->Lang->updateAll(
                    array('Lang.vi'=>"'" . $linkdata . "'"),
                    array('Lang.key' => $linkfield)
                );
            }

            if(isset($data['image_link_text']) && $data['image_link_text'] == 1)
            {
                $linkfield = $data['key'] . '_link';
                $linkdata = $data['link'];

                $this->Lang->updateAll(
                    array('Lang.vi'=>"'" . $linkdata . "'"),
                    array('Lang.key' => $linkfield)
                );

                $titlefield = $data['key'] . '_title';
                $titledata = $data['title'];

                $this->Lang->updateAll(
                    array('Lang.vi'=>"'" . $titledata . "'"),
                    array('Lang.key' => $titlefield)
                );

                $desfield = $data['key'] . '_description';
                $desdata = $data['des'];


                $this->Lang->updateAll(
                    array('Lang.vi'=>"'" . $desdata . "'"),
                    array('Lang.key' => $desfield)
                );
            }


            $this->Session->setFlash('Đã sửa', 'success');
            $this->redirect($this->referer());
        }

        $editor = isset($_GET['editor']) ? 1 : 0;
        $set_category_id_for_section = isset($_GET['category']) ? 1 : 0;

        $image = isset($_GET['image']) ? 1 : 0;
        $set_image_for_section = isset($_GET['image']) ? 1 : 0;

        $textarea = isset($_GET['textarea']) ? 1 : 0;
        $set_textarea_for_section = isset($_GET['textarea']) ? 1 : 0;

        $set_image_link_for_section = isset($_GET['image_link']) ? 1 : 0;
        $set_text_link_for_section = isset($_GET['text_link']) ? 1 : 0;
        $set_image_link_text_for_section = isset($_GET['image_link_text']) ? 1 : 0;

        if(!is_numeric($id) || (is_numeric($id) && isset($_GET['image']) && !isset($_GET['quickfix'])))
        {
            $check = $this->Lang->find('first', array(
                'conditions'=>array(
                    'Lang.key' => $id
                )
            ));

            $link = DOMAINAD . 'admin_lang/lang_edit/' . $check['Lang']['id'];

            if(isset($_GET['editor']))
                $link = $link . '/?editor=1';

            if(isset($_GET['category']))
                $link = $link . '/?category=1';

            if(isset($_GET['image']))
                $link = $link . '/?image=1&quickfix=1';

            if(isset($_GET['image_link']))
                $link = $link . '/?image_link=1';

            if(isset($_GET['textarea']))
                $link = $link . '/?textarea=1';

            if(isset($_GET['text_link']))
                $link = $link . '/?text_link=1';

            if(isset($_GET['image_link_text']))
                $link = $link . '/?image_link_text=1';

            $this->redirect($link); die;
        }

        $this->set('editor', $editor);
        $this->set('category', $set_category_id_for_section);
        $this->set('image', $set_image_for_section);
        $this->set('textarea', $set_textarea_for_section);
        $this->set('image_link', $set_image_link_for_section);
        $this->set('text_link', $set_text_link_for_section);
        $this->set('image_link_text', $set_image_link_text_for_section);
        
        $this->data = $this->Lang->findById($id);
    }

    public function exists_lang_key($langkey)
    {
        $this->autoRender = false;

        $check = $this->Lang->find('first', array(
            'conditions'=>array(
                'Lang.key'=>$langkey
            )
        ));

        if(is_array($check) && count($check) > 0)
        {
            return 1;
        }

        return 0;
    }

    public function lang_delete($id = null) {
        $this->autoRender = false;
        $this->Lang->id = $id;
        $this->Lang->delete($id);
        $this->Session->setFlash('Đã xóa', 'success');
        $this->redirect($this->referer());
    }

}
