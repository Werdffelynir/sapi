<?php

class git {

    private $sapi = null;
    private $com = null;
    private $opt = ['key'=>null, 'branch'=>'origin master'];

    private $gitreg = [
        "banking" => "/var/www/banking.loc",
        "chart" => "/var/www/owncloud.loc/apps/owncollab_chart",
    ];

    static private $self = null;

    public function __construct($sapi, $com, $opt){
        $this->sapi = $sapi;
        $this->com = $com;
        $this->opt = $opt;

        if(!self::$self) self::$self = $this;
        $this->init();
    }

    public function init(){
        print(">> Консольная утилита 'SAPI' программа 'git' запущенна...\n");
        if(empty($this->com)) {
            $this->ask_com();
        }
        if(empty($this->opt)) {
            $this->ask_opt_key();
        }else{
            $this->opt['key'] = (!empty($this->opt[0])) ? $this->opt[0] : null ;
            $this->opt['branch'] = (!empty($this->opt[1])) ? $this->opt[1] : 'origin master' ;
        }

        $this->start($this->com, $this->opt['key']);
    }

    public function ask_com(){
        Sapi::input(">> Введите комманду типа операции pull/push: ", function($response){
            $com = trim($response);
            if($com == 'pull' || $com == 'push') {
                $this->com = $com;
            }else{
                print(">>>> Неизвестный тип операции!\n");
                self::$self->ask_com();
                return false;
            }
        });
    }

    public function ask_opt_key(){
        $list = "";
        $keys = [];
        foreach ($this->gitreg as $key => $value) {
            array_push($keys, $key);
            $list .= "\n>> '$key' = $value";
        }
        Sapi::input(">> Список всех зарегестрированых впрограмме $list\n>> Репозиторий: ", function($response) use ($keys) {
            $key = trim($response);
            if(!in_array($key, $keys)){
                print(">> Неизвестный ключ репозитория! Повторите попытку.\n");
                self::$self->ask_opt_key();
                return false;
            }else{
                $this->opt['key'] = $key;
            }
        });
    }

    public function start($com, $key){
        if(empty($com)){
            $this->ask_com();
            return null;
        }
        if(empty($key)){
            $this->ask_opt_key();
            return null;
        }

        print(">> Операция '{$this->com}' для '$key' началась\n");

        system("cd ".$this->gitreg[$key]);
        if($com == 'pull'){
            system("git status");
            system("git add .");
            system("git commit -m 'build'");
            system("git pull origin master");

            exit;
        }
        else if($com == 'push'){
            system("git status");
            system("git add .");
            system("git commit -m 'build'");
            system("git push origin master");

            exit;
        }

    }

}