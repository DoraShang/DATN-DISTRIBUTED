<?php

/**
 * Application level View Helper
 *
 * This file is application-wide helper file. You can put all
 * application-wide helper-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Helper
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Helper', 'View');

/**
 * Application helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app.View.Helper
 */
class AppHelper extends Helper
{

    public $components = array('Session');
    public $helpers = array('Session');
    public $categories_id = array();

    public function is_allowed($act, $role_arr, $adm_arr)
    {
        if ($adm_arr['id'] == 1) return true;
        if ($adm_arr['type'] == 1) return true;
        if (in_array($act, $role_arr)) return true;

        return false;
    }

    function encrypt_string($string) {
        $output = false;

        $encrypt_method = "AES-256-CBC";
        $secret_key = SECURITY_KEY;
        $secret_iv = SECURITY_KEY;

        // hash
        $key = hash('sha256', $secret_key);
        
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);

        return $output;
    }
        
    function decrypt_string($string) {
        $output = false;

        $encrypt_method = "AES-256-CBC";
        $secret_key = SECURITY_KEY;
        $secret_iv = SECURITY_KEY;

        // hash
        $key = hash('sha256', $secret_key);
        
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);

        return $output;
    }

    public function send_ajax($name = null, $act = null, $arr = array(), $arr_required = array(), $error_message = '')
    {
        $params = '';

        $url = DOMAIN . $name;

        $str = '<script type="text/javascript">';
        $str .= ' 
        $("' . $act . '").click(function() {
                var str="";
                var hasMsg = 0;
                if($(".modal-msg").length > 0) hasMsg = 1;

                console.log(hasMsg);
        ';

        foreach ($arr as $v) {
            $str .= '
                var ' . $v . ' = $(".form-control[name=' . $v . ']").val();
                console.log(' . $v . ');
            ';

            if (in_array($v, $arr_required)) {
                $str .= '
                    if(' . $v . ' == "")
                    {
                        if(hasMsg == 1)
                        {
                            $(".modal-msg .msg").html("Vui lòng nhập đủ thông tin!");
                            $(".modal-msg").modal();
                        }
                        else
                        {
                            alert("Vui lòng nhập đủ thông tin!");
                        }
                        
                        return false;
                    }
                ';
            }
        }

        $str .= 'var d = {';
        foreach ($arr as $v) {
            $str .= $v . ':' . $v;
            $str .= ',';
        }
        $str = trim($str, ',');
        $str .= '};';

        // $str .= 'alert(str); return false;';
        $str .= '
            $.ajax({
                url: "' . $url . '",
                type: "post",
                data: d,
                dataType: "json",
                success: function(res) { 
                    console.log(res);

                    if(hasMsg == 1)
                    {
                        $(".modal-msg .msg").html(res.msg);
                        $(".modal-msg").modal();
                    }
                    else
                    {
                        alert(res.msg);
                    }
                },
                error: function(err) { console.log(err); }
            });

            return false;
        ';
        $str .= '});
            </script>';

        return $str;
    }

    public function _permission($act, $role_arr, $adm_arr)
    {
        if ($this->is_allowed) return true;
        else {
            $this->redirect(DOMAINAD);
            die;
        }

        return false;
    }

    public function t($name, $array = null, $fixed_vi = '')
    {
        $name = strtolower($name);
        $lang = Configure::read('lang');

        if (is_array($array) && count($array) > 0) {
            return $lang == 'vi' ? $array[$name] : $array[$name . '_' . $lang];
        }

        $lang_array = Configure::read('lang_array');

        if ($fixed_vi != '' && isset($lang_array[$name]['vi']))
            return $lang_array[$name]['vi'];

        if (isset($lang_array[$name][$lang]))
            return stripslashes($lang_array[$name][$lang]);

        return '';
    }

    public function t_a($name, $type = null)
    {
        $str = $this->t($name);
        if ($type != null)
            $str .= $this->adm_link('lang', $name, $type);
        else
            $str .= $this->adm_link('lang', $name);

        return $str;
    }

    public function _t_a($name, $type = null)
    {
        $str = $this->t($name);
        if ($type != null)
            $str .= $this->adm_link('lang', $name, $type);
        else
            $str .= $this->adm_link('lang', $name);

        return $str;
    }

    public function is_valid_json($str)
    {
        if ($str == '')
            return false;

        json_decode($str);
        return (json_last_error() == JSON_ERROR_NONE);
    }
    function object_to_array($data)
    {
        if (is_array($data) || is_object($data)) {
            $result = array();
            foreach ($data as $key => $value) {
                $result[$key] = $this->object_to_array($value);
            }
            return $result;
        }
        return $data;
    }
    public function adm_link($node_type, $value, $lang_type = 'lang', $class = '')
    {
        if (!$this->Session->check('admin')) return '';
        $active_editor = Configure::read('active_editor');
        if ($active_editor == 0) return '';

        $extra = '';

        if ($lang_type == 'editor') {
            $extra = '/?editor=1';
        }

        if ($lang_type == 'category') {
            $extra = '/?category=1';
        }

        if ($lang_type == 'image') {
            $extra = '/?image=1';
        }

        if ($lang_type == 'textarea') {
            $extra = '/?textarea=1';
        }

        if ($lang_type == 'image_link') {
            $extra = '/?image_link=1';
        }

        if ($lang_type == 'image_link_text') {
            $extra = '/?image_link_text=1';
        }

        if ($lang_type == 'text_link') {
            $extra = '/?text_link=1';
        }

        $link = '<a class="adm-glyphicon ' . $class . '" href="' . DOMAINAD . 'admin_' . $node_type . '/' . $node_type . '_edit/' . $value . $extra . '" target="_blank">
                    <span style="color: red;" class="fa fa-cog"></span>
                </a>';
        return $link;
    }

    public function _icon($str, $pre = 'fa fa-')
    {
        if ($str == '')
            return '';

        if (preg_match('/\//', $str))
            return '<img src="' . DOMAIN . $str . '" class="icon" />';
        else
            return '<i class="' . $pre . $str . '"></i>';
    }

    public function get_category_link($v)
    {
        $link = DOMAIN . $v['Node']['slug'] . '.html';
        $has_child = 0;

        if ($v['Category']['type'] == 'link_inline') {
            $node = $this->requestAction(DOMAIN . 'default/node/get_node/' . $v['Category']['link_inline']);

            if (is_array($node) && count($node) > 0)
                $link = DOMAIN . $node['Node']['slug'] . '.html';
            else
                $link = DOMAIN;
        }

        if ($v['Category']['type'] == 'link') {
            $link = $v['Category']['link'];
        }

        return $link;
    }

    public function add_break_line($str, $word_count)
    {
        if ($word_count == 2)
            return str_replace(' ', '<br />', $str);
        if ($word_count == 3)
            return preg_replace('/ /', '<br />', $str, 1);
        if ($word_count > 3) {
            $arr = explode(' ', $str);
            $r = '';
            $i = 0;
            foreach ($arr as $v) {
                $i++;
                if ($i == 2)
                    $r .= ' ' . $v . '<br />';
                else
                    $r .= ' ' . $v;
            }

            return trim($r);
        }
    }

    public function insert_slider_into_content($txt, $slider_html)
    {
        $arr = explode("</p>", $txt);
        $n = count($arr);
        $new_content = '';

        if ($n <= 1) {
            $arr2 = explode('</div>', $txt);
            $n2 = count($arr2);

            if ($n2 <= 1) {
                return $txt;
            } else {
                $paragraphAfter = floor($n2 / 2);

                for ($i = 0; $i < $n2; $i++) {
                    $new_content .= $arr2[$i] . '</div>';
                    if ($i == $paragraphAfter) {
                        $new_content .= $slider_html;
                    }
                }

                return $new_content;
            }
        } else {
            $paragraphAfter = floor($n / 2);

            for ($i = 0; $i < $n; $i++) {
                $new_content .= $arr[$i] . '</p>';
                if ($i == $paragraphAfter) {
                    $new_content .= $slider_html;
                }
            }

            return $new_content;
        }

        return $txt;
    }

    public function alert($str, $url = null)
    {
        if ($url == null) {
            $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }
        echo '<script type="text/javascript">alert("' . $str . '"); document.location.href="' . $url . '";</script>';
    }

    public function get_data_for_tree($category_data, $return = array())
    {
        $this->Category = ClassRegistry::init('Category');
        $data = array();

        if (isset($category_data['Category']['parent_id']) && is_numeric($category_data['Category']['parent_id']) && $category_data['Category']['parent_id'] > 0) {
            $data = $this->Category->find('first', array(
                'joins' => array(
                    array(
                        'table' => 'nodes',
                        'alias' => 'Node',
                        'type' => 'INNER',
                        'conditions' => array(
                            'Category.node_id = Node.id'
                        )
                    )
                ),
                'conditions' => array(
                    'Category.id' => $category_data['Category']['parent_id']
                ),
                'fields' => array('Category.*', 'Node.*')
            ));
        }

        if (is_array($data) && count($data) > 0) {
            $return[] = $data;
        }

        if (isset($data['Category']['parent_id']) && is_numeric($data['Category']['parent_id']) && $data['Category']['parent_id'] > 0) {
            $return[] = $data;
            return $this->get_data_for_tree($data, $return);
        }
        return $return;
    }

    public function breadarray($current_category)
    {
        $arr = array();
        $lang = Configure::read('lang');

        if ($lang == 'vi')
            $sufix = '';
        else
            $sufix = '_' . $lang;

        $return = array();

        $data = $this->get_data_for_tree($current_category);
        sort($data);

        $arr[0]['link'] = DOMAIN;
        $arr[0]['title'] = $this->t('home');
        $i = 0;

        $links = array();

        if (is_array($data) && count($data) > 0) {
            foreach ($data as $v) {
                $i++;
                $link = DOMAIN . $v['Node']['slug'] . '.html';

                if (!in_array($link, $links) && $v['Node']['title' . $sufix] != '') {
                    $arr[$i]['link'] = DOMAIN . $v['Node']['slug'] . '.html';
                    $arr[$i]['title'] = $v['Node']['title' . $sufix];
                    $links[] = $link;
                }
            }
        }

        $i++;

        if (isset($current_category['Node']['title' . $sufix]) && $current_category['Node']['title' . $sufix] != '') {
            $arr[$i]['link'] = DOMAIN . $current_category['Node']['slug'] . '.html';
            $arr[$i]['title'] = $current_category['Node']['title' . $sufix];
        }
        return $arr;
    }

    /**
     * 
     * @param type $data_node dữ liệu node hiện tại (có thể là bất kỳ)
     * @param type $data_category (dữ liệu mục lục - không có tác dụng với dữ liệu data_node là category - type category)
     * @param type $type kiểu dữ liệu (chỉ tồn tại ở 2 trạng thái category|null cho list và !category cho detail)
     * @param type $separator
     * @return type string
     */
    public function breadcrumb($data_node, $data_category = null, $type = 'category', $separator = '&raquo;')
    {
        $str = ' <a href="' . DOMAIN . '"> &nbsp; &nbsp; ' . $this->t('home') . '</a>';
        $str .= ' ' . $separator . ' ';

        $lang = Configure::read('lang');

        if ($lang == 'vi')
            $sufix = '';
        else
            $sufix = '_' . $lang;

        if ($type == 'category' || $type == null) {
            $str .= '<span class="bg2">' . $data_node['Node']['title' . $sufix] . '</span>';
            return $str;
        }

        $str .= '<a class="nobg" href="' . DOMAIN . $data_category['Node']['slug'] . '.html">';
        $str .= '<span>' . $data_category['Node']['title' . $sufix] . '</span>';
        $str .= '</a>';
        $str .= ' ' . $separator . ' ';

        $str .= '<span class="end">' . $data_node['Node']['title' . $sufix] . '</span>';

        return $str;
    }

    public function word_limiter($str, $limit, $end = '...')
    {
        $str = strip_tags($str);
        $str = preg_replace('/[\r\n]/is', ' ', $str);
        $str = preg_replace('/\ +/', ' ', $str);
        $str = trim($str);

        if ($str == '')
            return $str;

        $arr = explode(' ', $str);
        if (count($arr) <= $limit)
            return $str;

        $buff = array();

        for ($i = 0; $i < $limit; $i++) {
            $buff[] = $arr[$i];
        }

        return implode(' ', $buff) . $end;
    }

    public function get_sitemap_priority($type)
    {
        $return = '';

        switch ($type) {
            case 'category':
                $return = '0.6';
                break;
            case 'news':
                $return = '0.2';
                break;
            case 'tags':
                $return = '0.3';
            default:
                break;
        }

        return $return;
    }

    public function get_sitemap_changefreq($type)
    {
        $return = '';

        switch ($type) {
            case 'category':
                $return = 'weekly';
                break;
            case 'news':
                $return = 'monthly';
                break;
            case 'tags':
                $return = 'weekly';
            default:
                break;
        }

        return $return;
    }

    public function youtube_iframe($youtube_link = null, $w = 640, $h = 360)
    {
        $video_id = $this->youtube_id($youtube_link);
        $yl = 'https://www.youtube.com/embed';
        $iframe =   '<iframe id="ytplayer" type="text/html" width="'
            . $w . '" height="'
            . $h . '" src="'
            . $yl . '/'
            . $video_id . '?autoplay=0&origin=" frameborder="0"></iframe>';

        return $video_id != null ? $iframe : null;
    }

    public function youtube_thumb($youtube_link = null)
    {
        $video_id = $this->youtube_id($youtube_link);
        return $video_id != null ? 'http://img.youtube.com/vi/' . $video_id . '/hqdefault.jpg' : null;
    }

    public function youtube_id($youtube_link = null)
    {
        $arr = parse_url($youtube_link);

        if (!isset($arr['query'])) return null;

        $video_id = '';
        $youtube_v = $arr['query'];

        if (preg_match('/\&/', $youtube_v)) {
            $buff = explode('&', $youtube_v);
            foreach ($buff as $v) {
                if (preg_match('/v=/', $v)) {
                    $youtube_v = $v;
                    break;
                }
            }
        }

        $id_arr = explode('=', $youtube_v);

        return $id_arr[1];
    }



    public function img_src($image, $width = 0, $height = 0)
    {
        if (trim($image) == "") return $image;

        $image = str_replace('/app/webroot/', '/', $image);
        $str = '';

        if (strpos($image, 'wp-content') !== false) {
            $str = 'https://asiadiscoverytour.com/' . $image;
        } else {
            $d = ROOT_DIRECTORY != '' ? ROOT_DIRECTORY . '/' : '';
            $str .= DOMAIN . 'thumb/' . $width . 'x' . $height . '/' . $d . trim($image, '/');
        }

        return $str;
    }


    public function img($image, $alt = '', $width = 0, $height = 0, $other = '')
    {
        if (trim($image) == "") return $image;

        $str = '<img alt="' . $alt . '" src=';
        if (preg_match('/http:\/\//', $image) || preg_match('/https:\/\//', $image)) {
            $str .= '"' . $image . '"';
            if ($width != 0)
                $str .= ' width="' . $width . '"';
            if ($height != 0)
                $str .= ' height="' . $height . '"';
        } else {
            $d = ROOT_DIRECTORY != '' ? ROOT_DIRECTORY . '/' : '';
            $str .= '"' . DOMAIN . 'thumb/' . $width . 'x' . $height . '/' . $d . trim($image, '/');
            $str .= '"';
        }

        if ($other != '') {
            $str .= ' ' . $other;
        }

        $str .= ' />';
        return $str;
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

    public function get_node_link($data)
    {


        $link = DOMAIN . $data['Node']['slug'] . '.html';
        return  $link;
    }
}
