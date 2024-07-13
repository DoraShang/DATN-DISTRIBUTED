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


class AdminAppController extends AppController
{

    public $theme = 'Admin';
    public $node_required = array();


    public $components = array('Cookie', 'Session', 'Capp', 'RequestHandler', 'Upload');

    public $multiple_lang = false;
    public $has_order = false;

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'default';
    }

    public $sidebar = array(
        'Nội dung' => array(
            'Quản lý người dùng' => 'admin_customer/customer_list',
        ),
       
    );
    public function beforeRender()
    {
        parent::beforeRender();
        if ($this->Session->check('admin')) {
            $this->admin = $this->Session->read('admin');
            $this->set('admin', $this->admin);
        }
        $this->set('sidebar',$this->sidebar);
        
    }

    public function _role($act)
    {
        if (!isset($this->admin['id'])) {
            $this->redirect(DOMAINAD);
            die;
        }

        if ($this->admin['id'] == 1) return true;
        if ($this->admin['type'] == 1) return true;
        if (in_array($act, $this->role)) return true;

        $this->redirect(DOMAINAD);
        die;

        return false;
    }

    public function get_redirect($node_type, $node_action, $node_id)
    {
        if (!in_array($node_action, array('edit', 'add')))
            return DOMAINAD . 'admin_' . $node_type . '/' . $node_type . '_list';

        $type_request = $node_action . '_redirect';

        if ($this->{$type_request} == 'edit')
            $redirect = DOMAINAD . 'admin_' . $node_type . '/' . $node_type . '_edit/' . $node_id;

        if ($this->{$type_request} == 'add')
            $redirect = DOMAINAD . 'admin_' . $node_type . '/' . $node_type . '_add/';

        if ($this->{$type_request} == 'list') {
            $redirect = DOMAINAD . 'admin_' . $node_type . '/' . $node_type . '_list';

            if (isset($_GET['r']) && trim($_GET['r']) != '')
                $redirect = $_GET['r'];
        }
        return $redirect;
    }

    public function is_valid_json($str)
    {
        if ($str == '')
            return false;

        json_decode($str);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    public function alert($str, $url = null)
    {
        if ($url == null) {
            $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }
        header('Content-Type: text/html; charset=utf-8');
        echo '<script type="text/javascript">alert("' . $str . '"); document.location.href="' . $url . '";</script>';
    }
    
    public function check_logged()
    {
        if ($this->params['action'] != 'dashboard_login' && $this->params['action'] != 'dashboard_recover' && $this->params['action'] != 'dashboard_logout') {
            if (!$this->Session->check('admin')) {
                $this->redirect(Router::url(array(
                    'plugin' => 'admin',
                    'controller' => 'admin_dashboard',
                    'action' => 'dashboard_logout',
                ), true));
            } else {
                $this->admin = $this->Session->read('admin');
                $this->set('admin', $this->admin);
            }
        }
    }

}