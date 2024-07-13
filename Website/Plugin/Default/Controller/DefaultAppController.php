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
App::uses('AppController', 'Controller');

class DefaultAppController extends AppController
{

    //Theme đang sử dụng
    public $theme = 'Default';

    // public $hook = array();
    public $hook = array('product', 'guide', 'service');

    //Danh sách components
    public $components = array('Cookie', 'Session', 'Ccontent', 'Cmodal', 'Cmeta', 'Capp', 'Clayout', 'RequestHandler', 'Upload', 'Paginator');

    //Các bảng được sử dụng trong chương trình
    public $uses = array(
        'User',
    );

    public $is_mobile = false;

    public $Controller;

    public function beforeFilter()
    {
        parent::beforeFilter();
        $is_mobile = 0;

        if ($this->RequestHandler->isMobile() || isset($_GET['mobile'])) {
            $is_mobile = 1;
            // $this->theme = 'Mobile';
        }

        $this->set('is_mobile', $is_mobile);
    }

    public function init_data()
    {

    }

    public function alert($str, $url = null)
    {
        if ($url == null) {
            $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }
        header('Content-Type: text/html; charset=utf-8');
        echo '<script type="text/javascript">alert("' . $str . '"); document.location.href="' . $url . '";</script>';
    }

    public function removeXss($string)
    {
        //Fix & but allow unicode
        $string = preg_replace('#&(?!\#[0-9]+;)#si', '&amp;', $string);
        $string = str_replace("<", "&lt;", $string);
        $string = str_replace(">", "&gt;", $string);
        $string = str_replace("\"", "&quot;", $string);
        $string = str_replace("\'", "&quot;", $string);
        static $preg_find    = array('#javascript#i', '#vbscript#i');
        static $preg_replace = array('java script',   'vb script');
        return preg_replace($preg_find, $preg_replace, $string);
    }

    public function remove_hostname($image = null)
    {
        $url = parse_url($image);
        if (isset($url['path'])) {
            $img = trim($url['path'], '/');
            $arr = explode('/', $img);
            if ($arr[0] == ROOT_DIRECTORY)
                unset($arr[0]);

            return implode('/', $arr);
        }
        return null;

        if ($image) {
            $domain = trim(DOMAIN, '/');
            $domain = str_replace('http://', '', $domain);
            $domain = str_replace('www.', '', $domain);
            $domain = trim($domain, '/');
            $domain = explode('/', $domain);

            $image = trim($image, '/');
            $image_arr = explode('/', $image);

            $flag = false;
            $key = 0;

            foreach ($image_arr as $k => $v) {
                if ($v == ROOT_DIRECTORY) {
                    $flag = true;
                    $key = $k;
                    break;
                }

                if (in_array($v, $domain)) {
                    $flag = true;
                    $key = $k;
                    break;
                }
            }

            if ($flag == true) {
                for ($i = 0; $i <= $key; $i++) {
                    unset($image_arr[$i]);
                }
            }

            return implode('/', $image_arr);
        }
    }
    // 
    public function beforeRender()
    {
        parent::beforeRender();

        $this->set('theme', $this->theme);
        $this->set('theme_directory', DOMAIN . 'theme/' . $this->theme . '/');

    }

}