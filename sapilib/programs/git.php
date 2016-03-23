<?php

/** 
php /var/sapi git chart [pull [master]]
php /var/sapi git chart [push [branch]]
*/
class git {

    private $sapi = null;
    private $comand = null;
    private $opt = [];

    private $reg = [
        "banking" => "/var/www/banking.loc",
        "chart" => "/var/www/owncloud.loc/apps/owncollab_chart",
    ];

    static private $self = null;

    public function __construct($sapi, $com, $opt){
        $this->sapi = $sapi;
        $this->comand = $com;
        $this->opt = $opt;

        if(!self::$self) 
            self::$self = $this;

        $this->init();
    }

    public function help(){ 
        print(">> Вызвана помощь.\n");
        print("Комманда должна иметь синтаксис 'php /var/sapi git [reg_rep [operation [branch] ] ]'\n");
        print("  'reg_rep' - комманда, зарегестрированый в программе ключ для репозитория. \n");
        print("  'operation' - опция, типа операции pull/push. \n");
        print("  'branch' - опция, ветка репозитория, по умолчанию 'master'. Приставку 'origin' вписывать не нужно. \n");
        Sapi::confirm(">> Продолжить? Y/n: ", function($yas){
            if(!$yas)
                exit(">> Программа завершена. Всего доброго.\n");
        });
    }

    public function init(){
        print(">> 'SAPI' Консольная утилита 'git' программа запущенна...\n");
        if($this->comand == '--help' || $this->comand == '-h'){
            $this->help();
        }
        if(empty($this->reg[$this->comand])){
            $this->get_repositor();
        }


        if(empty($this->opt[0])){
            $this->get_operation();
        }
        //$this->opt['operation'] = (!empty($this->opt[0])) ? $this->opt[0] : '';
        //$this->opt['branch'] = (!empty($this->opt[1])) ? $this->opt[1] : 'master';

        print(">> END:\n");
    }

    public function get_repositor($error = false){
        if(!$error){
            $list = "";
            foreach ($this->reg as $key => $value) {
                $list .= "  '$key' = $value\n";
            }
            print("Зарегестрированые репозитории:\n".$list);
        }
        Sapi::input(">> Репозиторий: ", function($response){
            if(!empty(self::$self->reg[trim($response)])){
                self::$self->comand = trim($response);
            } else {
                print(">> Ошибка. Неверный ключ репозитория. Повторите попытку\n");
                self::$self->get_repositor(true);
            }
        });
    }



    public function get_operation($error = false){

        Sapi::input(">> Операция push/pull: ", function($response){
            $operation = trim($response);
            if($operation == 'push' || $operation == 'pull'){
                self::$self->opt['operation'] = $operation;
            } else {
                print(">> Ошибка. Неверный тип операции. Повторите попытку\n");
                self::$self->get_operation(true);
            }
        });
    }


    public function start(){

        /*Sapi::input(">> Введите комманду типа операции pull/push: ", function($response){
            $com = trim($response);
            if($com == 'pull' || $com == 'push') {
                $this->com = $com;
            }else{
                print(">>>> Неизвестный тип операции!\n");
                self::$self->ask_com();
                return false;
            }
        });*/
        /*$list = "";
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
        });*/
                /*if(empty($com)){
            $this->ask_com();
        }else{
            $this->help($com);
            $this->com = $com; 
        }

        if(empty($opt)){
            $this->ask_opt_key();
            $this->ask_opt_branch();
        }else{
            $this->opt['key'] = (!empty($opt[0])) ? $opt[0] : null;
            $this->opt['branch'] = (!empty($opt[1])) ? 'origin '.$opt[1] : 'origin master' ;
        }
        if(empty($this->com)) {
            $this->ask_com();
        }
        if(empty($this->opt)) {
            $this->ask_opt_key();
        }else{
            $this->opt['key'] = (!empty($this->opt[0])) ? $this->opt[0] : null ;
            $this->opt['branch'] = (!empty($this->opt[1])) ? $this->opt[1] : 'origin master' ;
        }*/

        //$this->start($this->com, $this->opt['key']);
        /*
        if(empty($this->com) || empty($this->opt['key']) || empty($this->opt['branch'])){
            print(">> Ошибка параметров. Программа завершена.\n");
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
        }*/

    }


    public function ask_opt_branch(){

    }

}