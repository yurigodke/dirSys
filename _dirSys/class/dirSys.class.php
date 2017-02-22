<?php

class dirSys {

    const DIRSYSNAME = '_dirSys';

    private $basePath;
    private $path;
    private $excludePath = array();

    public function __construct() {
        $this->basePath = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);

        $this->rewriterTest();
        
        $this->addExcludePath('.');
        $this->addExcludePath('..');
        $this->addExcludePath(self::DIRSYSNAME);
        $this->addExcludePath('index.php');
        $this->addExcludePath('.htaccess');
    }
    
    public function addExcludePath($path){
        if(preg_match('/[áàãâäéèêëíìîïóòõôöúùûüç\w\/\.]+/i', $path ) !== false){
            array_push($this->excludePath, $path);
        }
    }
    
    public function getFiles(){
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
                        if (!$this->isExclude($file)) {
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
    
    private function isExclude($file){
        $uri = str_replace($this->getBasePath(), '', $this->path).$file;
        if(array_search($uri, $this->excludePath) !== false){
            return true;
        } else {
            return false;
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
				$fileSplit = explode('.', $file);
                $ext = end($fileSplit);
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
            'folderBack' => 'fa-level-up',
            'xls' => 'fa-file-excel-o',
            'xlsx' => 'fa-file-excel-o',
            'csv' => 'fa-file-excel-o',
            'doc' => 'fa-file-word-o',
            'docx' => 'fa-file-word-o',
            'txt' => 'fa-file-text-o',
            'pdf' => 'fa-file-pdf-o',
            'zip' => 'fa-file-archive-o',
            'jpg' => 'fa-file-image-o',
            'jpeg' => 'fa-file-image-o',
            'png' => 'fa-file-image-o',
            'bmp' => 'fa-file-image-o',
            'png' => 'fa-file-image-o',
            'gif' => 'fa-file-image-o',
            'wav' => 'fa-file-audio-o',
            'mp3' => 'fa-file-audio-o',
            'flac' => 'fa-file-audio-o',
            'm4a' => 'fa-file-audio-o',
            'php' => 'fa-file-code-o',
            'htm' => 'fa-file-code-o',
            'html' => 'fa-file-code-o',
            'js' => 'fa-file-code-o',
            'css' => 'fa-file-code-o',
            'xml' => 'fa-file-code-o',
            'ppt' => 'fa-file-powerpoint-o',
            'pptx' => 'fa-file-powerpoint-o',
            'mp4' => 'fa-file-video-o',
            'avi' => 'fa-file-video-o'
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