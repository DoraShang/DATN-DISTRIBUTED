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


class AjaxController extends DefaultAppController
{

    public $uses = array('Customer');

    public $components = array('Cookie', 'Session', 'RequestHandler', 'Upload', 'Capp');

    public function beforeFilter()
    {
        parent::beforeFilter();

        // if ($this->Session->check('user') == false) {
        //     die;
        // }

        $this->user = $this->Session->read('user');
        $this->autoRender = false;
    }


    public function upload_avatar()
    {
        $this->autoRender = false;

        $uid = 1;

        $files = $_FILES;

        if (isset($files['file']['name']) && is_array($files['file']['name']) && count($files['file']['name']) > 0) {
            $n = count($files['file']['name']);

            $buff_images = array();

            for ($i = 0; $i < $n; $i++) {
                $img = '';
                if (isset($files['file']['type'][$i]) && strpos($files['file']['type'][$i], 'image') !== false) {
                    $new_upload = array(
                        'name' => $files['file']['name'][$i],
                        'type' => $files['file']['type'][$i],
                        'tmp_name' => $files['file']['tmp_name'][$i],
                        'error' => $files['file']['error'][$i],
                        'size' => $files['file']['size'][$i],
                    );

                    $img_name = time() . rand(0, 10) . rand(0, 10) . $uid;

                    $this->Upload->name = $img_name;
                    $this->Upload->new = $new_upload;

                    @$img = $this->Upload->Process();

                    if ($img != '') {
                        $buff_images[$i]['img_cat'] = '';
                        $buff_images[$i]['img_name'] = '';
                        $buff_images[$i]['img_stt'] = 1;

                        $img_old_name = $files['file']['name'][$i];
                        $img_old_name_arr = explode('-', $img_old_name);

                        if (count($img_old_name_arr) == 4) {
                            $buff_images[$i]['img_cat'] = trim($img_old_name_arr[0]);
                            $buff_images[$i]['img_name'] = trim($img_old_name_arr[1] . ' - ' . $img_old_name_arr[2]);

                            $bd = explode('.', $img_old_name_arr[3]);
                            $buff_images[$i]['img_stt'] = $bd[0];
                        }

                        $image = IMG_DIR . $img;
                        $image = str_replace(ROOT, '', $image);
                        $buff_images[$i]['img_link'] = trim($image, '/\\');

                        $user = $this->Session->read('user');
                        $user_id = $user['id'];
                        $this->Customer->id = $user_id;
                        $this->Customer->saveField('image', $buff_images[$i]['img_link']);
                        $user['image'] = $buff_images[$i]['img_link'];
                        $this->Session->write('user', $user);
                    }
                }
            }

            echo json_encode($buff_images);
            die;
        }

        echo json_encode(array());
        die;
    }

    public function upload_imgs()
    {
        $this->autoRender = false;
        $uid = 1;

        $files = $_FILES;

        $buff_images = array();
        foreach($files as $file)
        {
        if (isset($file['name']) && $file['name'] != '') {

                $img = '';

                if (isset($file['type']) && strpos($file['type'], 'image') !== false) {

                    $new_upload = array(
                        'name' => $file['name'],
                        'type' => $file['type'],
                        'tmp_name' => $file['tmp_name'],
                        'error' => $file['error'],
                        'size' => $file['size'],
                    );

                    $img_name = time() . rand(0, 10) . rand(0, 10) . $uid;

                    $this->Upload->name = $img_name;
                    $this->Upload->new = $new_upload;

                    @$img = $this->Upload->Process();

                    if ($img != '') {
                        $buff_images['img_cat'] = '';
                        $buff_images['img_name'] = '';
                        $buff_images['img_stt'] = 1;

                        $img_old_name = $file['name'];
                        $img_old_name_arr = explode('-', $img_old_name);

                        if (count($img_old_name_arr) == 4) {
                            $buff_images['img_cat'] = trim($img_old_name_arr[0]);
                            $buff_images['img_name'] = trim($img_old_name_arr[1] . ' - ' . $img_old_name_arr[2]);

                            $bd = explode('.', $img_old_name_arr[3]);
                            $buff_images['img_stt'] = $bd[0];
                        }

                        $image = IMG_DIR . $img;
                        $image = str_replace(ROOT, '', $image);
                        $buff_images['img_link'] = trim($image, '/\\');
                    }
                }

            }

        }
     
        echo json_encode($buff_images);
        die;
    }



    public function upload_dropzone_imgs()
    {
        $this->autoRender = false;
        $uid = 1;

        $files = $_FILES;
        // pr($files);

        $buff_images = array();
        foreach($files as $file)
        {
             if (isset($file['name']) && $file['name'] != '') {

                $img = '';

                if (isset($file['type']) && strpos($file['type'], 'image') !== false) {

                    $new_upload = array(
                        'name' => $file['name'],
                        'type' => $file['type'],
                        'tmp_name' => $file['tmp_name'],
                        'error' => $file['error'],
                        'size' => $file['size'],
                    );

                    $img_name = time() . rand(0, 10) . rand(0, 10) . $uid;

                    $this->Upload->name = $img_name;
                    $this->Upload->new = $new_upload;

                    @$img = $this->Upload->Process();

                    if ($img != '') {
                        $buff_images['img_cat'] = '';
                        $buff_images['img_name'] = '';
                        $buff_images['img_stt'] = 1;

                        $img_old_name = $file['name'];
                        $img_old_name_arr = explode('-', $img_old_name);

                        if (count($img_old_name_arr) == 4) {
                            $buff_images['img_cat'] = trim($img_old_name_arr[0]);
                            $buff_images['img_name'] = trim($img_old_name_arr[1] . ' - ' . $img_old_name_arr[2]);

                            $bd = explode('.', $img_old_name_arr[3]);
                            $buff_images['img_stt'] = $bd[0];
                        }

                        $image = IMG_DIR . $img;
                        $image = str_replace(ROOT, '', $image);

                        echo trim($image, '/\\'); die;
                    }
                }

            }

     
        die;
    }
}

}
