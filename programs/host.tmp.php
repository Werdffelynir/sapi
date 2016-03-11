<?php
/**
 * php sapi host create /var/www/site.loc
 */
class host {

  private $sapi = null;
  private $command = null;
  private $options = [];


  public function __construct(Sapi $sapi, $command, $options = []){
    $this->sapi = $sapi;
    $this->command = $command;
    $this->options = $options;
    $this->init();
  }


  public function init(){
      print("Program host is run...\n");
      $command = "command_".$this->command;
      if(method_exists($this, $command)) {
        $this->$command();
      }
  }


  public function command_create(){
    $message = "Был вызван метод 'create'.\nВведите имя нового хоста: ";
    $this->sapi->input($message, function($response){

        $hostname = trim($response);
        $message = "Подтвердите запись для '" . $hostname . "' Y/n: ";

        $this->sapi->confirm($message, function(){
          print("Хост был создан!\n");
        },function(){
          print("Отмена операции!\n");
        });

      });
  }


  public function command_remove(){
    $message = "Был вызван метод 'remove'.\nНажмите Enter для продолжения\n";
    $this->sapi->input($message, function($response){
        print("Program host is close...\n");
      });
  }


}