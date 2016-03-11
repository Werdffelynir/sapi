<?php

class template {

    private $sapi = null;
    private $command = null;
    private $options = [];

    static private $self = null;

    public function __construct($sapi, $command, $options){
        $this->sapi = $sapi;
        $this->command = $command;
        $this->options = $options;

        if(!self::$self) self::$self = $this;
        $this->init($command, $options);
    }

    public function init($command = null, $options = null){
        if($command == null) {}
        if($options == null) {}

    }



}