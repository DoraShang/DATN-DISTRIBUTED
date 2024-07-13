<?php

class CKEditor {

    const version = '3.4.2';
    const timestamp = 'AA4E4NT';

    public $basePath;
    public $config = array();
    public $initialized = false;
    public $returnOutput = true;
    public $textareaAttributes = array("rows" => 8, "cols" => 60);
    public $timestamp = "AA4E4NT";
    private $events = array();
    private $globalEvents = array();

    function __construct($basePath = null) {
        if (!empty($basePath)) {
            $this->basePath = DOMAIN . "js/ckeditor";
        }
    }

    public function editor($name, $value = "", $option, $config = array(), $events = array()) {
        $attr = "";
        foreach ($this->textareaAttributes as $key => $val) {
            $attr.= " " . $key . '="' . str_replace('"', '&quot;', $val) . '"';
        }
        $out = "<textarea name=\"" . $name . "\"" . $attr . ">" . htmlspecialchars($value) . "</textarea>\n";
        if (!$this->initialized) {
            $out .= $this->init();
        }
        
        switch ($option) {
            case 'extra':
                $config['toolbar'] = array(
                    array('Source'),
                    array('PasteFromWord', '-'),
                    array('Undo', 'Redo', '-', 'Find', 'Replace', '-', 'RemoveFormat'),
                    array('Bold', 'Italic', 'Underline', 'Strike', '-', 'Subscript', 'Superscript'),
                    array('NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'),
                    array('JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'),
                    array('youtube', 'Link', 'Anchor', 'RichCombo'), 
                    array('Image', 'MultiUpload', 'Flash', 'Youtube', 'Table', 'HorizontalRule', 'SpecialChar', 'PageBreak', 'lineheight'),
                    array('Styles', 'Format', 'Font', 'FontSize'),
                    array('TextColor', 'BGColor'),
                    array('ShowBlocks', 'Maximize')
                );
                break;

            case 'compact':
                $config['toolbar'] = array(
                    array('Bold', 'Italic', 'Underline', 'Strike'),
                    array('NumberedList', 'BulletedList', '-'),
                    array('JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'),
                    array('Format', 'Font', 'FontSize'),
                    array('TextColor', 'BGColor'),
                    array('Image', 'Link', 'Unlink', '-', 'RemoveFormat')
                );
                // $config['filebrowserBrowseUrl'] = '';
                $config['filebrowserImageBrowseUrl'] = '';
                $config['filebrowserFlashBrowseUrl'] = '';
                $config['filebrowserImageUploadUrl'] = DOMAIN . 'upload_image';
                break;
            default :
                break;
        }
        $_config = $this->configSettings($config, $events);
        
        $js = $this->returnGlobalEvents();
        if (!empty($_config))
            $js .= "CKEDITOR.replace('" . $name . "', " . $this->jsEncode($_config) . ");";
        else
            $js .= "CKEDITOR.replace('" . $name . "');";

        $out .= $this->script($js);

        if (!$this->returnOutput) {
            print $out;
            $out = "";
        }

        return $out;
    }

    public function replace($id, $config = array(), $events = array()) {
        $out = "";
        if (!$this->initialized) {
            $out .= $this->init();
        }

        $_config = $this->configSettings($config, $events);

        $js = $this->returnGlobalEvents();
        if (!empty($_config)) {
            $js .= "CKEDITOR.replace('" . $id . "', " . $this->jsEncode($_config) . ");";
        } else {
            $js .= "CKEDITOR.replace('" . $id . "');";
        }
        $out .= $this->script($js);

        if (!$this->returnOutput) {
            print $out;
            $out = "";
        }

        return $out;
    }

    public function replaceAll($className = null) {
        $out = "";
        if (!$this->initialized) {
            $out .= $this->init();
        }

        $_config = $this->configSettings();

        $js = $this->returnGlobalEvents();
        if (empty($_config)) {
            if (empty($className)) {
                $js .= "CKEDITOR.replaceAll();";
            } else {
                $js .= "CKEDITOR.replaceAll('" . $className . "');";
            }
        } else {
            $classDetection = "";
            $js .= "CKEDITOR.replaceAll( function(textarea, config) {\n";
            if (!empty($className)) {
                $js .= "	var classRegex = new RegExp('(?:^| )' + '" . $className . "' + '(?:$| )');\n";
                $js .= "	if (!classRegex.test(textarea.className))\n";
                $js .= "		return false;\n";
            }
            $js .= "	CKEDITOR.tools.extend(config, " . $this->jsEncode($_config) . ", true);";
            $js .= "} );";
        }

        $out .= $this->script($js);

        if (!$this->returnOutput) {
            print $out;
            $out = "";
        }

        return $out;
    }

    public function addEventHandler($event, $javascriptCode) {
        if (!isset($this->events[$event])) {
            $this->events[$event] = array();
        }

        if (!in_array($javascriptCode, $this->events[$event])) {
            $this->events[$event][] = $javascriptCode;
        }
    }

    public function clearEventHandlers($event = null) {
        if (!empty($event)) {
            $this->events[$event] = array();
        } else {
            $this->events = array();
        }
    }

    public function addGlobalEventHandler($event, $javascriptCode) {
        if (!isset($this->globalEvents[$event])) {
            $this->globalEvents[$event] = array();
        }

        if (!in_array($javascriptCode, $this->globalEvents[$event])) {
            $this->globalEvents[$event][] = $javascriptCode;
        }
    }

    public function clearGlobalEventHandlers($event = null) {
        if (!empty($event)) {
            $this->globalEvents[$event] = array();
        } else {
            $this->globalEvents = array();
        }
    }

    private function script($js) {
        $out = "<script type=\"text/javascript\">";
        $out .= "//<![CDATA[\n";
        $out .= $js;
        $out .= "\n//]]>";
        $out .= "</script>\n";

        return $out;
    }

    private function configSettings($config = array(), $events = array()) {
        $_config = $this->config;
        $_events = $this->events;

        if (is_array($config) && !empty($config)) {
            $_config = array_merge($_config, $config);
        }

        if (is_array($events) && !empty($events)) {
            foreach ($events as $eventName => $code) {
                if (!isset($_events[$eventName])) {
                    $_events[$eventName] = array();
                }
                if (!in_array($code, $_events[$eventName])) {
                    $_events[$eventName][] = $code;
                }
            }
        }

        if (!empty($_events)) {
            foreach ($_events as $eventName => $handlers) {
                if (empty($handlers)) {
                    continue;
                } else if (count($handlers) == 1) {
                    $_config['on'][$eventName] = '@@' . $handlers[0];
                } else {
                    $_config['on'][$eventName] = '@@function (ev){';
                    foreach ($handlers as $handler => $code) {
                        $_config['on'][$eventName] .= '(' . $code . ')(ev);';
                    }
                    $_config['on'][$eventName] .= '}';
                }
            }
        }

        return $_config;
    }

    private function returnGlobalEvents() {
        static $returnedEvents;
        $out = "";

        if (!isset($returnedEvents)) {
            $returnedEvents = array();
        }

        if (!empty($this->globalEvents)) {
            foreach ($this->globalEvents as $eventName => $handlers) {
                foreach ($handlers as $handler => $code) {
                    if (!isset($returnedEvents[$eventName])) {
                        $returnedEvents[$eventName] = array();
                    }

                    if (!in_array($code, $returnedEvents[$eventName])) {
                        $out .= ($code ? "\n" : "") . "CKEDITOR.on('" . $eventName . "', $code);";
                        $returnedEvents[$eventName][] = $code;
                    }
                }
            }
        }

        return $out;
    }

    private function init() {
        static $initComplete;
        $out = "";

        if (!empty($initComplete)) {
            return "";
        }

        if ($this->initialized) {
            $initComplete = true;
            return "";
        }

        $args = "";
        $ckeditorPath = $this->ckeditorPath();

        if (!empty($this->timestamp) && $this->timestamp != "%" . "TIMESTAMP%") {
            $args = '?t=' . $this->timestamp;
        }


        if (strpos($ckeditorPath, '..') !== 0) {
            $out .= $this->script("window.CKEDITOR_BASEPATH='" . $ckeditorPath . "';");
        }

        // $out .= "<script type=\"text/javascript\" src=\"" . $ckeditorPath . 'ckeditor.js' . $args . "\"></script>\n";

        $extraCode = "";
        if ($this->timestamp != self::timestamp) {
            $extraCode .= ($extraCode ? "\n" : "") . "CKEDITOR.timestamp = '" . $this->timestamp . "';";
        }
        if ($extraCode) {
            $out .= $this->script($extraCode);
        }

        $initComplete = $this->initialized = true;

        return $out;
    }

    private function ckeditorPath() {
        if (!empty($this->basePath)) {
            return $this->basePath;
        }


        if (isset($_SERVER['SCRIPT_FILENAME'])) {
            $realPath = dirname($_SERVER['SCRIPT_FILENAME']);
        } else {

            $realPath = realpath('./');
        }


        $selfPath = dirname($_SERVER['PHP_SELF']);
        $file = str_replace("\\", "/", __FILE__);

        if (!$selfPath || !$realPath || !$file) {
            return "/ckeditor/";
        }

        $documentRoot = substr($realPath, 0, strlen($realPath) - strlen($selfPath));
        $fileUrl = substr($file, strlen($documentRoot));
        $ckeditorUrl = str_replace("ckeditor_php5.php", "", $fileUrl);

        return $ckeditorUrl;
    }

    private function jsEncode($val) {
        if (is_null($val)) {
            return 'null';
        }
        if ($val === false) {
            return 'false';
        }
        if ($val === true) {
            return 'true';
        }
        if (is_scalar($val)) {
            if (is_float($val)) {

                $val = str_replace(",", ".", strval($val));
            }


            if (strpos($val, '@@') === 0) {
                return substr($val, 2);
            } else {
                static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'),
                array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));

                $val = str_replace($jsonReplaces[0], $jsonReplaces[1], $val);

                return '"' . $val . '"';
            }
        }
        $isList = true;
        for ($i = 0, reset($val); $i < count($val); $i++, next($val)) {
            if (key($val) !== $i) {
                $isList = false;
                break;
            }
        }
        $result = array();
        if ($isList) {
            foreach ($val as $v)
                $result[] = $this->jsEncode($v);
            return '[ ' . join(', ', $result) . ' ]';
        } else {
            foreach ($val as $k => $v)
                $result[] = $this->jsEncode($k) . ': ' . $this->jsEncode($v);
            return '{ ' . join(', ', $result) . ' }';
        }
    }

}

?>