<?php

/**
 * php var/sapi/sapi gitok project -a -c push pull
 *
 * add project 'path' 'branch'
 * delete project
 * project -a
 * project add
 * project -c
 * project commit
 * project -c -m  'text'
 * project push
 * project pull
 * project -f push
 * project -f pull
 *
 */
class gitok
{

    private $sapi;
    private $command;
    private $options;

    private $ini;
    private $iniPath;

    /**
     * gitok constructor.
     * @param Sapi $sapi
     * @param string $command
     * @param array $options
     */
    public function __construct(Sapi $sapi, $command, $options)
    {
        $this->sapi = $sapi;
        $this->command = $command;
        $this->options = $options;
        $this->iniPath = __DIR__.'/config/gitok.ini';
        $this->ini = parse_ini_file($this->iniPath, true);
    }

    /**
     * @param null|string $p1
     * @param null|string $p2
     * @param null|string $p3
     * @param null|string $p4
     */
    public function init($p1 = null, $p2 = null, $p3 = null, $p4 = null)
    {
        if(method_exists($this, "cmd_$this->command")){
            call_user_func_array([$this, "cmd_$this->command"],[$p1,$p2,$p3,$p4]);
        }
    }

    public function cmd_add($project, $path, $branch = '', $url = '') {
        $ini = $this->ini;
        $self = $this;
        $branch = $branch ? $branch : 'master';

        if(isset($ini['projects'][$project])) {
            Sapi::confirm("[gitok:add] Project exists! Overwrite? Y/n: ", function($yes, $inst = null) use ($self) {
                if(!$yes) $self->close("[gitok:add] Cancel the operation.");
            }, $this);
        }


        $log = "[gitok:add] Create [$project] \n - path: $path, \n - branch: $branch, \n - url: $url\n";

        $ini['projects'][$project]['path'] = $path;
        $ini['projects'][$project]['branch'] = $branch ? $branch : 'master';
        $ini['projects'][$project]['url'] = $url;


        $u = $this->sapi->createIni($this->iniPath, $ini);
        $log .= "[gitok:add] Project [$project] added to register!\n";
        console($log);

        $this->close();
    }

    public function cmd_list() {
        $log = "[gitok:list] Projects list is empty!";
        if(!empty($this->ini['projects'])){
            $len = $i = count($this->ini['projects']);
            $log = "[gitok:list] List of all ($len) registered projects:\n";
            foreach($this->ini['projects'] as $name => $p){
                $log .= ($len - (--$i))." [$name]\n - path: {$p['path']}\n - branch: {$p['branch']}\n - url: {$p['url']}\n";
            }
        }
        console($log);
    }

    public function cmd_delete($name) {
        $log = "[gitok:delete] Project [$name] not exist! nothing to remove.\n";
        if(!empty($this->ini['projects'][$name])){
            $self = $this;
            Sapi::confirm("Confirm delete the project [$name]? Y/n: ", function($yes) use ($self) {
                if(!$yes) $self->close("Cancel the operation");
            }, $this);

            $ini = $this->ini;
            unset($ini['projects'][$name]);
            $log = "[gitok:delete] Project [$name] deleted completed!\n";
            $u = $this->sapi->createIni($this->iniPath, $ini);
        }
        console($log);
    }

    public function close($message = null) {
        $message = $message ? "$message\n" : "";
        Sapi::console($message . "[gitok:close] The program is completed, goodbye.\n", true);
    }
}