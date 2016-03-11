<?php
/**
 * php sapi host create /var/www/site.loc
 */
class host {

  private $sapi = null;
  private $command = null;
  private $options = [];
  private $message = [
    'hello' => "Консольная утилита 'SAPI' программа 'host' запущенна...\n",
    'rcancel' => "Отмена операции!\n",
    'success' => "Операця успешно завершена!\n"
  ];

  // 
  private $conf = [
    'site_path' => '/var/www/',
    'conf_path' => '/etc/apache2/sites-available/',
  ];

  // {ServerName}{DocumentRoot}{Directory}
  private $tpl = [
    'html' => "<!DOCTYPE html>\n<html lang=\"en\">\n<head>\n\t<meta charset=\"UTF-8\">\n\t<title>Document</title>\n</head>\n<body>\n\t<h1>Demo page</h1>\n</body>\n</html>",
    'conf' => "<VirtualHost *:80>\n\tServerName {ServerName}\n\tDocumentRoot {DocumentRoot}\n\t<Directory {Directory}>\n\t\tAllowOverride All\n\t</Directory>\n</VirtualHost>",
  ];

  public static $self = null;

  public function __construct(Sapi $sapi, $command, $options = []){
    $this->sapi = $sapi;
    $this->command = $command;
    $this->options = $options;

    self::$self = $this;
    $this->init();
  }


  public function init(){
      print($this->message['hello']);

      $command = "command_".$this->command;
      if(method_exists($this, $command)) {
        $this->$command();
      }
  }

  public function command_create(){

    Sapi::input("Имя нового хоста: ", function($response){
        $hostName = trim($response);  
        $sitePath = self::$self->conf['site_path'] . $hostName ;
        $confFile = self::$self->conf['conf_path'] . $hostName ;

        if(!is_dir($sitePath)) {
          mkdir('mkdir '.$sitePath);

          chmod($sitePath, 0777);
          file_put_contents($sitePath.'/index.html', self::$self->tpl['html']);
        }

      });
  }


  public function command_remove(){
    $message = "Был вызван метод 'remove'.\nНажмите Enter для продолжения\n";
    Sapi::input($message, function($response){
        print("Program host is close...\n");
      });
  }


}

?>


