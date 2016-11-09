<?
class CCustomInit {
    private $path;
    private $folders = array();
    private $files = array();

    public function __construct() {
        $this->path = $_SERVER['DOCUMENT_ROOT']."/local/php_interface/include";
        if (!is_dir($this->path)) {
            throw new Exception('Строчка:'.__LINE__.", файл".__FILE__.": Директории include не существует");
        }
    }
    public function Init () {
        if (count($this->folders) > 0) {
            $this->scandirs();
            $this->include_files();
        }
    }
    public function addFolder($folder_name) {
        if (is_string($folder_name)) {
            if (!strlen($folder_name)) {
                $dir_path = $this->path;
            } else {
                $dir_path = $this->path.'/'.$folder_name;
            }
            $this->folders[] = $dir_path;
            if (is_dir($dir_path.$folder_name)) return true;
            return false;
        }
        return false;
    }
    private function scandirs() {
        $result = false;
        foreach ($this->folders as $abs_path) {
            $names = scandir($abs_path);
            foreach ($names as $name) {
                if (!in_array($name,array(".","..")) && is_file($abs_path."/".$name) && pathinfo($abs_path."/".$name, PATHINFO_EXTENSION) == 'php') {
                    $this->files[] = $abs_path."/".$name;
                    if (!$result) $result = true;
                }
            }
        }
        return $result;
    }
    private function include_files () {
        if (count($this->files) > 0) {
            foreach ($this->files as $file_name) {
                require_once($file_name);
            }
        }
    }
}
?>