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

class ClayoutComponent extends Component
{
    public function beforeRender(Controller $Controller)
    {
        $this->Controller = $Controller;
        $flag = FALSE;
        $layout = 'default';

        $check = ROOT . DS . 'app' . DS . 'View' . DS . 'Themed' . DS . 'Default' . DS . 'Layouts' . DS;

        if ($this->Controller->nodeData['Node']['id'] == 0) {
            $file = $check . 'home.ctp';

            if (file_exists($file))
                $layout = 'home';

            $template_render = 'home_index';

            if (isset($this->Controller->is_mobile) && $this->Controller->is_mobile == 1) {
                $file_check = ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Default' . DS . 'View' . DS . 'Home' . DS . 'home_index_m.ctp';

                if (file_exists($file_check))
                    $template_render = 'home_index_m';
            }

            $this->Controller->view = $template_render;
            $flag = TRUE;
        }

        if ($this->Controller->cModalName == 'Category' && $flag == FALSE) {
            $type = $this->Controller->currentCategory['Category']['type'];
            $file = $check . $type . '.ctp';

            if (file_exists($file))
                $layout = $type;

            if ($type == 'page') {
                if (isset($this->Controller->data['Category']['template']) && $this->Controller->data['Category']['template'] != '') {
                    $temp = $this->Controller->data['Category']['template'];
                    $file = ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Default' . DS . 'View' . DS . 'Node' . DS . $temp . '.ctp';

                    if (isset($this->Controller->is_mobile) && $this->Controller->is_mobile == 1) {
                        $check_file = ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Default' . DS . 'View' . DS . 'Node' . DS . $temp . '_m.ctp';

                        if (file_exists($check_file)) {
                            $temp = $temp . '_m';
                            $file = $check_file;
                        }
                    }

                    if (file_exists($file))
                        $this->Controller->view = $temp;
                    else {
                        $file = ROOT . DS . 'app' . DS . 'View/Themed' . DS . 'Default' . DS . 'Plugin/Default' . DS . 'Node' . DS . $temp . '.ctp';

                        if (isset($this->Controller->is_mobile) && $this->Controller->is_mobile == 1) {
                            $check_file = ROOT . DS . 'app' . DS . 'View/Themed' . DS . 'Default' . DS . 'Plugin/Default' . DS . 'Node' . DS . $temp . '_m.ctp';

                            if (file_exists($check_file)) {
                                $temp = $temp . '_m';
                                $file = $check_file;
                            }
                        }

                        if (file_exists($file))
                            $this->Controller->view = $temp;
                        else {
                            $this->Controller->view = 'page_detail';

                            if (isset($this->Controller->is_mobile) && $this->Controller->is_mobile == 1) {
                                $check_file = ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Default' . DS . 'View' . DS . 'Node' . DS . 'page_detail_m.ctp';
                                $this->Controller->view = 'page_detail_m';
                            }
                        }
                    }
                } else {
                    $this->Controller->view = 'page_detail';

                    if (isset($this->Controller->is_mobile) && $this->Controller->is_mobile == 1) {
                        $check_file = ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Default' . DS . 'View' . DS . 'Node' . DS . 'page_detail_m.ctp';
                        $this->Controller->view = 'page_detail_m';
                    }
                }

                $flag = TRUE;
            }

            if ($type == 'news' && $flag == FALSE) {
                $view = 'news_list';

                if (isset($this->Controller->is_mobile) && $this->Controller->is_mobile == 1) {
                    $check_file = ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Default' . DS . 'View' . DS . 'Node' . DS . 'news_list_m.ctp';

                    if (file_exists($check_file))
                        $view = 'news_list_m';
                }

                if (isset($this->Controller->currentCategory['Category']['template']) && $this->Controller->currentCategory['Category']['template'] != '') {
                    $temp = $this->Controller->currentCategory['Category']['template'];
                    $file = ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Default' . DS . 'View' . DS . 'Node' . DS . $temp . '.ctp';

                    if (file_exists($file))
                        $view = $temp;

                    if (isset($this->Controller->is_mobile) && $this->Controller->is_mobile == 1) {
                        $check_file = ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Default' . DS . 'View' . DS . 'Node' . DS . $temp . '_m.ctp';

                        if (file_exists($check_file))
                            $view = $tem . '_m';
                    }

                    if (file_exists($file))
                        $this->Controller->view = $temp;
                }

                $this->Controller->view = $view;

                $flag = TRUE;
            }


            if ($flag == FALSE) {
                $this->Controller->view = $type . '_list';
                if (isset($this->Controller->is_mobile) && $this->Controller->is_mobile == 1) {
                    $check_file = ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Default' . DS . 'View' . DS . 'Node' . DS . $type . '_list_m.ctp';

                    if (file_exists($check_file))
                        $this->Controller->view = $type . '_list_m';
                }

                $flag = TRUE;
            }

            $flag = TRUE;
        }

        if ($flag == FALSE) {
            $view = $this->Controller->cActionName;
            $file = $check . strtolower($this->Controller->cModalName) . '.ctp';

            if (file_exists($file))
                $layout = strtolower($this->Controller->cModalName);

            if (isset($this->Controller->is_mobile) && $this->Controller->is_mobile == 1) {
                $check_file = ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Default' . DS . 'View' . DS . 'Node' . DS . $view . '_m.ctp';
                if (file_exists($check_file))
                    $view = $view . '_m';
            }

            if (
                isset($this->Controller->currentCategory['Category']['type']) && $this->Controller->currentCategory['Category']['type'] == 'news'
                && isset($this->Controller->currentCategory['Category']['template']) && $this->Controller->currentCategory['Category']['template'] != ''
            ) {
                $temp = $this->Controller->currentCategory['Category']['template'] . '_detail';
                $check_file = ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Default' . DS . 'View' . DS . 'Node' . DS . $temp . '.ctp';

                if (file_exists($check_file))
                    $view = $temp;

                if (isset($this->Controller->is_mobile) && $this->Controller->is_mobile == 1) {
                    $check_file = ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Default' . DS . 'View' . DS . 'Node' . DS . $temp . '_detail_m.ctp';

                    if (file_exists($check_file))
                        $view = $temp . '_detail_m';
                }
            }

            $this->Controller->view = $view;
        }


        $this->Controller->layout = $layout;
    }
}
