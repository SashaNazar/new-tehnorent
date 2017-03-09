<?php

/**
 * ����� ���������� ������
 *
 */

class Template {
    /**
     * ������ ����������
     *
     * @var array
     */
    var $vars = array();

    /**
     * ������� � ������� ��������
     *
     * @var string
     */
    var $template_dir = '';

    /**
     * ������������
     *
     * @return Template
     */

    /*
     *  ���� �������������� �������  
     *  �� ��������� �������
     */
    private $lang;
    private $langDefault =  'ru';
    /*
     *  �������������� �����
     */
    private $langSteach = 'ru|ua|en';

    function Template($template_dir = '.') {
        $this->setTemplateDir($template_dir);
    }
    /*
     *  ����� ����� ��������������
     */
    function setLang ($lang) {
        $this->lang = $lang;
    }

    /**
     * ��������� �������� �������� ��������
     *
     * @param string $template_dir
     */
    function setTemplateDir($template_dir) {
        if (substr($template_dir, -1) != '/') {
            $template_dir .= '/';
        }
        if (!is_dir($template_dir)) {
            die('Directory '.$template_dir.' does not exist');
        }

        $this->template_dir = $template_dir;
    }
    /*
     *  �������� ��� ������
     */
    public function getVars () {
        return $this->vars;
    }
    /*
     *  ���������� �������� �� ������� ������ ����������
     */
    public function insetVars ($vars) {
        $this->vars = array_merge ($this->vars, $vars);
    }

    /**
     * ����������� ����������
     *
     * @param array $vararray
     */
    function addVars($vararray) {
        reset($vararray);
        foreach ($vararray as $key => $val) {
            $this->vars[$key] = $val;
        }
    }

    function addVar($key, $val) {
        $this->vars[$key] = $val;
    }

    // ��������� �������
    function getVarsCount ($key) {
        return is_array($this->vars[$key]) ? count($this->vars[$key]) : 0;
    }

    /**
     * ����������� ����� ����������
     *
     * @param string $blockname
     * @param array $vararray
     */
    function addBlock($blockname, $vararray)
    {
        /*******************************************************
         * �������� �� ����������� �����
         *******************************************************/
        if (strstr($blockname, '.')) {
            $blocks = explode('.', $blockname);
            $blockcount = sizeof($blocks) - 1;

            $array_d = &$this->vars;
            for ($i = 0; $i < $blockcount; $i++) {
                if (!isset($array_d[$blocks[$i]])) {
                    $array_d[$blocks[$i]] = array();
                }
                $block_size = sizeof($array_d[$blocks[$i]]) - 1;
                $array_d = &$array_d[$blocks[$i]][$block_size > 0 ? $block_size : 0];
            }
            $array_d[$blocks[$blockcount]][] = $vararray;

        } else {
            $this->vars[$blockname][] = $vararray;

        }
    }

    /**
     * ��������� �����
     *
     * @param string $blockname
     * @param string $include_varname
     * @return string
     */
    function genBlock($blockname, $include_varname = false) {
        $blocks = explode('.', $blockname);
        $blockcount = sizeof($blocks) - 1;

        $varref = '$this->vars';
        if (!empty($blockname)) {
            for ($i = 0; $i < $blockcount; $i++) {
                $varref .= '[\''.$blocks[$i].'\'][$_'.$blocks[$i].'_i]';
            }

            $varref .= '[\''.$blocks[$blockcount].'\']';
            if ($include_varname) {
                $varref .= '[$_'.$blocks[$blockcount].'_i]';
            }
        }

        return $varref;
    }
    /*
     *  ����������� ������ � ������� ������������� �������
     */
    static public function multilang ($code, $lang = null) {
        if ($lang === null) $lang = Lang::getSiteLang ();
        $obj = new Template ();
        $obj->setLang($lang);
        return $obj->langParse($code);
    }
    /*
     *  ������� ������ ������� �������
     */
    public function langParse ($code) {
        $r = preg_match_all('/\{(\s*)(\"('.$this->langSteach.')\"\:\"(.[^\}]*)\")+(\s*)\}/s', $code, $found); // \s - �� ��������� �������� �����
        if ($r) {
            $n = count ($found[0]);
            for ($i=0;$i<$n;$i++) {

                $ins = iconv('cp1251', 'utf-8', $found[0][$i]);
                $ins = str_replace(array("\n","\r","\r\n"), "", $ins);
                $arr = json_decode ($ins, true);
                if (!array_key_exists ($this->lang, $arr)) {
                    $insert = $arr[$this->langDefault]; // ���� ���� �� ������ - �� �������� �������
                }
                else {
                    $insert =  $arr[$this->lang]; // ������� � ����������� �� �����
                }
                $code = str_replace ($found[0][$i], iconv('utf-8', 'cp1251', $insert), $code);
            }
        }
        return $code;
    }

    /**
     * ������� ������
     *
     * @param string $code
     */
    function parse($code) {
        /*
         *  ������ ������������� �������
         */
        $code = $this->langParse($code);

        // Lang
        preg_match_all('#\{\{([a-z0-9\-_\.]+)\}\}#is', $code, $langs);
        if (count($langs[1])>0) {
            for($k=0; $k<count($langs[1]); $k++) {
                $code = preg_replace("#\{\{".$langs[1][$k]."\}\}#is", _e($langs[1][$k]), $code);
            }
        }
        // End Lang;

        $code = str_replace('\\', '\\\\', $code);
        $code = str_replace('\'', '\\\'', $code);
        $code_lines = explode("\n", $code);

        for ($i = 0, $line_count = sizeof($code_lines); $i < $line_count; $i++) {
            $code_lines[$i] = chop($code_lines[$i]);

            $varrefs = array();
            preg_match_all('#\{([a-z0-9\-_\.]*?)[\.]*([a-z0-9\-_ ]+?)\}#is', $code_lines[$i], $varrefs);

            $varcount = sizeof($varrefs[1]);
            for ($k = 0; $k < $varcount; $k++) {
                $varref = $this->genBlock($varrefs[1][$k], true);
                $varref .= '[\''.$varrefs[2][$k].'\']';
                $new = '\'.(isset('.$varref.') ? '.$varref.' : \'\').\'';
                $code_lines[$i] = str_replace($varrefs[0][$k], $new, $code_lines[$i]);
            }

            if (false === strpos($code_lines[$i], '<!-- TPL_')) {
                $code_lines[$i] = 'echo \''.$code_lines[$i].'\'."\\n";';
                continue;
            }

            $code_lines_i = explode("\n", str_replace('<!-- TPL_', "\n<!-- TPL_", $code_lines[$i]));

            for ($k = 0, $line_count_i = sizeof($code_lines_i); $k < $line_count_i; $k++) {
                if ($k == 0) {
                    $code_lines_i[$k] = trim($code_lines_i[$k]);
                }
                if (empty($code_lines_i[$k])) {
                    unset($code_lines_i[$k]);
                    continue;
                }

                $code_lines_addon = '';

                $block_names = array();
                if (preg_match('#<!-- TPL_BEGIN ([a-z0-9\-_\.]*?([a-z0-9\-_ ]+?)) -->(.*)#is', $code_lines_i[$k], $block_names)) {
                    $varref = $this->genBlock($block_names[1]);
                    $code_lines_i[$k] = '$_'.$block_names[2].'_count = (isset('.$varref.')) ? sizeof('.$varref.') : 0;'."\n";
                    $code_lines_i[$k] .= 'for ($_'.$block_names[2].'_i = 0; $_'.$block_names[2].'_i < $_'.$block_names[2].'_count; $_'.$block_names[2].'_i++) {';
                    $code_lines_addon = $block_names[3];

                } elseif (preg_match('#<!-- TPL_IF (.*?) -->(.*)#is', $code_lines_i[$k], $block_names)) {
                    $varref = $this->genBlock($block_names[1]);
                    $code_lines_i[$k] = 'if (isset('.$varref.') && !empty('.$varref.')) {';
                    $code_lines_addon = $block_names[2];

                } elseif (preg_match('#<!-- TPL_IF_NOT (.*?) -->(.*)#is', $code_lines_i[$k], $block_names)) {
                    $varref = $this->genBlock($block_names[1]);
                    $code_lines_i[$k] = 'if (!isset('.$varref.') || empty('.$varref.')) {';
                    $code_lines_addon = $block_names[2];

                } elseif (preg_match('#<!-- TPL_ELSE (.*?)-->(.*)#is', $code_lines_i[$k], $block_names)) {
                    $code_lines_i[$k] = '} else {';
                    $code_lines_addon = $block_names[2];

                } elseif (preg_match('#<!-- TPL_END (.*?)-->(.*)#is', $code_lines_i[$k], $block_names)) {
                    $code_lines_i[$k] = '}';
                    $code_lines_addon = $block_names[2];

                } else {
                    $code_lines_i[$k] = 'echo \''.$code_lines_i[$k].'\';';
                }

                if (!empty($code_lines_addon)) {
                    $code_lines_i[$k] .= "\n".'echo \''.$code_lines_addon.'\';';
                }
            }

            $code_lines[$i] = implode("\n", $code_lines_i);
        }

        return implode("\n", $code_lines);
    }

    /**
     * ������� �����
     *
     * @param string $file
     * @param string $from_template_dir
     * @return string
     */
    function parseFile($file, $display = true, $from_template_dir = true) {

        //ar($file);

        if ($from_template_dir) {
            $file = $this->template_dir.$file;
        }

        if (!file_exists($file)) {
            echo('File '.$file.' does not exist!');
            return false;
        }

        $code = file_get_contents($file);
        if (empty($code)) {
            return '';
        }

        $code = $this->parse($code);
        if ($display) {
            eval($code);
            return true;
        } else {
            ob_start();
            eval($code);
            $code = ob_get_clean();
        }

        return $code;
    }
    /*
     *  �������� ��� �� ��������
     */
    public function addHtml ($html) {
        echo $html;
        return true;
    }
}