<?
class Process{
    protected $pid;
    protected $command;
    protected $log_file;
    protected $param;

    protected $proc_collection = null;
    protected $entry_id = false;

    public function __construct($cl=false, $log_file = false, $use_store = false, $entry_id = false, $output = false){
        if ($cl != false){
            $this->command = $cl;
            $this->log_file = $log_file;
            $this->runCom();
            $this->entry_id = $entry_id;
            $this->param = 0;
            $this->output = $output;
            if ($use_store) {
                $this->proc_collection =  $this->initProcessDB();
                $this->proc_collection->insert(array("pid" => $this->pid, "log_file" => $this->log_file, "entry_id" => $this->entry_id,"param" => $this->param, "output" => $this->output));
            }
        }
    }
    private function runCom(){
        $command = "nohup setsid ".$this->command." > $this->log_file 2>&1 & echo $!";
        exec($command ,$op);
        $this->pid = (int)$op[0];
    }

    private function initProcessDB() {
        $mongo = new MongoClient();
        $mongo_db = $mongo->test;
        return $mongo_db->processes;
    }

    public function setPid($pid){
        $this->pid = $pid;
    }

    public function getPid(){
        return $this->pid;
    }

    public function getEntryId () {
        if (!$this->entry_id && $this->proc_collection instanceof MongoCollection) {
            $arProcEntry = $this->proc_collection->find(array("pid" => $this->pid))->getNext();
            $this->entry_id = $arProcEntry['_id'];
        }
        return $this->entry_id;
    }

    public function getLog(){
        return $this->log_file;
    }

    public function getInstance ($params) {
        $this->proc_collection = $this->initProcessDB();
        $cursor = $this->proc_collection->find($params);
        $ar_proc = $cursor->getNext();
        if (!empty($ar_proc)) {
            $this->setPid($ar_proc['pid']);
            $this->command = $ar_proc['command'];
            $this->log_file = $ar_proc['log_file'];
            $this->param = $ar_proc['param'];
            $this->output = $ar_proc['output'];
            $this->entry_id = $ar_proc['entry_id'];
        }
        return $this;
    }

    public function status(){
        $command = 'ps -p '.$this->pid;
        exec($command,$op);
        if (!isset($op[1]))return false;
        else return true;
    }

    public function getStatus ($params, $force_stop = true) {
        if (count($params)) {
            $this->getInstance($params);
        }
        if ($this->status()) {
            return $this->status();
        } else if ($force_stop) {
            $this->stop();
            return false;
        }
    }

    public function start(){
        if ($this->command != '')$this->runCom();
        else return true;
    }

    public function getProcInfo () {
        if (is_object($this->proc_collection)) {
            $cursor = $this->proc_collection->find(array("pid" => $this->pid));
            return $cursor->getNext();
        }
        return false;
    }

    public function setProcParam ($value) {
        if (is_object($this->proc_collection)) {
            $this->proc_collection->update(array("pid" => $this->pid), array('$set' => array("param" => $value)));
        }
        return false;
    }

    public function getOutput ($params) {
        if (is_array($params) && count($params)) {
            $this->getInstance($params);
        }
        return $this->output;
    }


    public function removeLog() {
        unlink($this->log_file);
    }
    public function removeOutput() {
        unlink($this->output);
    }

    public function removeEntry($remove_output = true) {
        $this->removeLog();
        if (is_object($this->proc_collection)) {
            $this->removeLog();
            if ($remove_output) $this->removeOutput();
            $this->proc_collection->remove(array("pid" => $this->pid));
        }
    }

    public function stop($delete_entry = true, $delete_output = true){
        //fileDump(BXHelper::trace(true, false, true), true);
        $command = 'kill '.$this->pid;
        exec($command);
        if ($this->status() == false) {
            foreach (GetModuleEvents("main", "OnBeforeProcessStop", true) as $arEvent)
                ExecuteModuleEventEx($arEvent, array(get_object_vars($this)));
            if ($delete_entry) {
                $this->removeEntry($delete_output);
            }
            foreach (GetModuleEvents("main", "OnAfterProcessStop", true) as $arEvent)
                ExecuteModuleEventEx($arEvent, array(get_object_vars($this)));
            return true;
        } else {
            return false;
        }
    }
}
?>