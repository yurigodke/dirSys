<?php

class dirSys {

    const DIRSYSNAME = '_dirSys';

    private $basePath;
    private $path;

    public function __construct() {
        $this->basePath = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);

        $this->rewriterTest();

        $this->setHeader();

        $this->showFiles();
    }

    private function listFiles() {
        $this->setPath();
        if (is_dir($this->path)) {
            if ($dirObj = opendir($this->path)) {
                $files = array();
                while (($file = readdir($dirObj)) !== false) {
                    if ($this->path == $this->getBasePath()) {
                        if (
                                (self::DIRSYSNAME != $file) &&
                                ($file != '.') &&
                                ($file != '..') &&
                                ($file != 'index.php') &&
                                ($file != '.htaccess')
                        ) {
                            array_push($files, $file);
                        }
                    } else if ($file != '.') {
                        array_push($files, $file);
                    }
                }
                return $files;
            }
        }
    }

    private function showFiles() {
        $files = $this->listFiles();
        echo '<ul class="sysDirList">' . "\n";

        foreach ($files as $file) {
            $element;
            if (is_dir($this->path . $file)) {
                if ($file == '..') {
                    $element = $this->setElement($file, 'folderBack');
                } else {
                    $element = $this->setElement($file, 'folder');
                }
            } else {
                $ext = end(explode('.', $file));
                $element = $this->setElement($file, $ext);
            }
            echo $element;
        }

        echo '</ul>';
    }

    private function setElement($name, $type) {

        $variables = array(
            'icon' => $this->getIcon($type),
            'name' => $name
        );

        $element = $this->getSysFile('element.htm', $variables);

        echo $element;
    }

    private function setPath() {
        $this->path = $_SERVER['DOCUMENT_ROOT'] . $this->getActualPath();
    }

    private function getBasePath() {
        return $_SERVER['DOCUMENT_ROOT'] . $this->basePath;
    }

    private function getActualPath() {
        $urlRequest = $_SERVER['REQUEST_URI'];
        return $urlRequest;
    }

    private function getUrlBase() {
        return 'http://' . $_SERVER['HTTP_HOST'] .
                $this->basePath . self::DIRSYSNAME;
    }

    private function rewriterTest() {
        $rewriterFile = $this->getBasePath() . '.htaccess';

        $variables = array(
            'path' => $this->basePath
        );

        $rewriterText = $this->getSysFile('htaccess', $variables);

        $rf = fopen($rewriterFile, 'w');

        if ($rf) {
            fwrite($rf, $rewriterText);
            fclose($rf);
            return true;
        } else {
            return false;
        }
    }

    private function getIcon($icon) {
        $icons = array(
            'default' => 'fa-file-o',
            'folder' => 'fa-folder-o',
            'folderBack' => 'fa-level-up'
        );

        if (isset($icons[$icon])) {
            $iconClass = $icons[$icon];
        } else {
            $iconClass = $icons['default'];
        }
        return $iconClass;
    }

    private function setHeader() {
        $variables = array(
            'path' => $this->getUrlBase()
        );
        $header = $this->getSysFile('header.htm', $variables);

        echo $header;
    }

    private function getSysFile($name, $variables = array()) {
        $fileContent = file_get_contents(
                $this->getBasePath() . self::DIRSYSNAME . '/includes/' . $name);
        
        if(!function_exists('maskit')){
            function maskit($val) {
                return '{{'.$val.'}}';
            }
        }
        $fileContent = str_replace(array_map('maskit',array_keys($variables)),array_values($variables),$fileContent);
        return $fileContent;
    }

}

?>