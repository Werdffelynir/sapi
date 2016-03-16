#!/usr/local/bin/php
<?php

?>

/**
 * Example: "php sapi command opt1 opt2 opt3 opt4"
 * $argc - 6 (args num)
 * $argv - [sapi, command, op...] array input words
 */

// ========================================== ==========================================

====
system — Выполняет внешнюю программу и отображает её вывод
==========================================
string system ( string $command [, int &$return_var ] )


====
exec() - Исполняет внешнюю программу
==========================================
string exec ( string $command [, array &$output [, int &$return_var ]] )
echo exec('whoami');


====
passthru() - Выполняет внешнюю программу и отображает необработанный вывод
==========================================
void passthru ( string $command [, int &$return_var ] )
Функция passthru() похожа на функцию exec() в том, что она выполняет команду command.
Должна быть использована вместо функции exec() или system() когда вывод команды Unix является двоичными данными, которые необходимо передать непосредственно в браузер.
<?php
passthru ('echo $PATH');
?>


====
popen() - Открывает файловый указатель процесса
==========================================

resource popen ( string $command , string $mode )
$handle = popen("/bin/ls", "r");
<?php
error_reporting(E_ALL);

/* Добавляем перенаправление, чтобы прочитать stderr. */
$handle = popen('/path/to/executable 2>&1', 'r');
echo "'$handle'; " . gettype($handle) . "\n";
$read = fread($handle, 2096);
echo $read;
pclose($handle);
?>


====
escapeshellarg() Экранирует строку для того, чтобы она могла быть использована как аргумент командной строки
==========================================
string escapeshellarg ( string $arg )
system('ls '.escapeshellarg($dir));


====
escapeshellcmd() - Экранирует метасимволы командной строки
==========================================




====
shell_exec — Выполняет команду через шелл и возвращает полный вывод в виде строки
==========================================
string shell_exec ( string $cmd )

<?php
$output = shell_exec('ls -lart');
echo "<pre>$output</pre>";
?>


Эта функция недоступна в безопасном режиме.

<?php
$cmd = 'set';
echo "<pre>".shell_exec($cmd)."</pre>";
?>


If you're trying to run a command such as "gunzip -t" in shell_exec and getting an empty result,
you might need to add 2>&1 to the end of the command, eg:

Won't always work:
echo shell_exec("gunzip -c -t $path_to_backup_file");

Should work:
echo shell_exec("gunzip -c -t $path_to_backup_file 2>&1");


====

==========================================


====

==========================================


====
About the problem of zombies, you may call a bash script like this:
==========================================
--------------------------
#! /bin/bash
ulimit -t 60

< your command here >
--------------------------















//if(@mkdir('./sapidir/')){
//    print_r("dir 'sapi' created");
//}

//exec('');
//
//print_r("\n-------------------\n");
//echo exec('sudo /etc/init.d/apache2 reload');
//print_r("\n-------------------\n");


//$command = 'git --help';
//system(escapeshellcmd($command));

//$dir = "/var/www";
//system('ls -ls '.escapeshellarg($dir));

/*
ob_start();

system("echo 'Ill catch the buffer'", $retval);
print_r("$retval\n-------------------\n");

system("ls -ls /var/www", $retval);
print_r("$retval\n-------------------\n");

$return = ob_get_contents();
ob_end_clean();
print_r($return);*/

//system("sudo -i", $retval);
//print_r("$retval\n-------------------\n");

// Эта функция недоступна в безопасном режиме.
//$output = shell_exec('ls -lart');
//echo "$output";

// выводит имя пользователя, от имени которого запущен процесс php/httpd
// (применимо к системам с командой "whoami" в системном пути)
//echo exec('whoami')."\n";


//header("Content-Type: application/octet-stream");
//header("Content-Disposition: attachment; filename=\"myfile.zip\"");
//header("Content-Length: 11111");
//passthru("cat myfile.zip",$err);
//echo $err."\n";


//passthru ('echo $PATH');

//function my_exec($cmd, $input='')
//{$proc=proc_open($cmd, array(0=>array('pipe', 'r'), 1=>array('pipe', 'w'), 2=>array('pipe', 'w')), $pipes);
//    fwrite($pipes[0], $input);fclose($pipes[0]);
//    $stdout=stream_get_contents($pipes[1]);fclose($pipes[1]);
//    $stderr=stream_get_contents($pipes[2]);fclose($pipes[2]);
//    $rtn=proc_close($proc);
//    return array('stdout'=>$stdout,
//        'stderr'=>$stderr,
//        'return'=>$rtn
//    );
//}
//var_export(my_exec('echo -e $(</dev/stdin) | wc -l', 'h\\nel\\nlo'));
//var_export(my_exec('echo $PATH'));


//$handle = popen("tail -f /var/log/apache2/access.log 2>&1", 'r');
//while(!feof($handle)) {
//    $buffer = fgets($handle);
//    echo "$buffer<br/>\n";
//    ob_flush();
//    flush();
//}
//pclose($handle);


//define('RUNCMDPATH', 'c:\\htdocs\\nonwebspace\\runcmd.bat');
//function runCmd($cmd) {
//    $externalProcess=popen(RUNCMDPATH.' '.$cmd, 'r');
//    pclose($externalProcess);
//}









/*
// Выводит весь результат шелл-команды "ls", и возвращает
// последнюю строку вывода в переменной $last_line. Сохраняет код возврата
// шелл-команды в $retval.
$last_line = system('ls', $retval);

escapeshellcmd() чистит весь текст команды
escapeshellarg() чистит аргумент

string exec ( string $command [, array &$output [, int &$return_var ]] )
exec() исполняет команду command.
Если параметр output указан, то массив будет заполнен строками вывода программы.
Если заданы оба параметра return_var и output, то при выходе эта переменная будет
содержать статус завершения внешней программы.
Возвращаемые значения Последняя строка вывода при исполнении заданной команды.

// Alternative to $last_line = system('ls', $retval);
$result = array();
exec( $cmd, &$result);
foreach ( $result as $v ){// parse, or do cool stuff}


To have system output from both the STDERR and STDOUT, I\'ve modified the function posted above by lowery@craiglowery.com

function mysystem($command) {
  if (!($p=popen("($command)2>&1","r"))) {
    return 126;
  }

  while (!feof($p)) {
    $line=fgets($p,1000);
    $out .= $line;
  }
  pclose($p);
  return $out;
}

Now you can use mysystem() like;

$var = "cat ".$file;
echo mysystem($var);



function syscall($command){
    if ($proc = popen("($command)2>&1","r")){
        while (!feof($proc)) $result .= fgets($proc, 1000);
        pclose($proc);
        return $result;
        }
    }










 If you can't see any output or error from system(), shell_exec() etc, you could try this:

<?php
function my_exec($cmd, $input='')
         {$proc=proc_open($cmd, array(0=>array('pipe', 'r'), 1=>array('pipe', 'w'), 2=>array('pipe', 'w')), $pipes);
          fwrite($pipes[0], $input);fclose($pipes[0]);
          $stdout=stream_get_contents($pipes[1]);fclose($pipes[1]);
          $stderr=stream_get_contents($pipes[2]);fclose($pipes[2]);
          $rtn=proc_close($proc);
          return array('stdout'=>$stdout,
                       'stderr'=>$stderr,
                       'return'=>$rtn
                      );
         }
var_export(my_exec('echo -e $(</dev/stdin) | wc -l', 'h\\nel\\nlo'));
?>

For example, "echo shell_exec('ls');" will get nothing output,
"my_exec('ls');" will get "sh: ls: command not found",
"my_exec('/bin/ls');" will maybe get "sh: /bin/ls: Permission denied",
and the permission may be caused by selinux.

another reason to use shell_exec instead of system is when the result is multiple lines such as grep or ls

<?php

// this correctly sets answer string to all lines found
//$answer = shell_exec ("grep 'set of color names' *.php ");
//echo "answer = $answer";

// this passes all lines to output (they  show on page)
// and sets answer string to the final line
$sys = system ("grep 'set of color names' *.php ");
echo "sys =(($sys))";

?>

here is view/source resulting from system call

setprefs.php:// The standard set of color names is:
setprefs.php:// Most browsers accept a wider set of color names
silly.php:  //$answer = shell_exec ("grep 'set of color names' *.php ");
silly.php: $sys = system ("grep 'set of color names' *.php ");
sys =((silly.php: $sys = system ("grep 'set of color names' *.php ");))

and here is view source from using shell_exec instead

answer = setprefs.php:// The standard set of color names is:
setprefs.php:// Most browsers accept a wider set of color names
silly.php:  $answer = shell_exec ("grep 'set of color names' *.php ");
silly.php:// $sys = system ("grep 'set of color names' *.php ");































print_r($argv);
print_r($argc);


start:

print("Введите номер записи и нажмите Enter. \n Номер записи: ");
$stdin = fopen("php://stdin", "r");
$record_num = fgets($stdin);
fclose($stdin);
print_r("Введите номер: " . $record_num);

goto start;
*/

            /*system("sudo cat $confFile.conf > ", $result);
127.0.0.1 $hostName www.$hostName
4 активировать хост
sudo a2ensite test.loc.conf

5 restart
sudo /etc/init.d/apache2 restart

           stdout = fopen('php://stdout', 'w');
           $stderr = fopen('php://stderr', 'w');

           fwrite($stdout, 'asdada');
           //fclose($stdout);
           print_r($stderr);
           */
            /*system("sudo touch $confFile.conf", $result);

            print_r(is_file("$confFile.conf"));
            sleep(1);
            if(!is_file("$confFile.conf")){
                print(">> Файл небыл создан.\n");
                goto error;
            }else{

                $result = file_put_contents($confFile.'.conf', $conf);
                if(!$result){
                    print(">> Не удалось записать в фалй $confFile.conf\n");
                    goto error;
                }
            }*/

//            $maskWords = ["{ServerName}","{DocumentRoot}","{Directory}"];
//            $maskVars = [$hostName,$sitePath,$sitePath];
//            $conf = str_replace($maskWords, $maskVars, self::$self->tpl['conf']);

            //chmod($confFile.'.conf', 0777);
            //file_put_contents($confFile.'.conf', self::$self->tpl['conf']);

            //chmod('/etc/hosts', 0777);
            //file_put_contents('/etc/hosts', "\n127.0.0.1\t".$hostName."\twww.".$hostName, FILE_APPEND);
            //chmod('/etc/hosts', 0755);

            //system('a2ensite '.$confFile.'.conf');

            //system('/etc/init.d/apache2 restart');


//system('mkdir '.$sitePath, $result);

// "create_intro"    => "Введите имя нового хоста: ",
// "create_cancel"   => "Отмена операции!\n",
// "create_success"  => "Хост был создан!\n",
// "remove_intro"    => "Введите имя хоста удаления: ",

// $this->sapi->confirm($message, function(){
//   print();
// }, function(){
//   print();
// });





    public function createIni($file, array $iniData, $i = 0, $iKey = null){

        if(!$this->iniTmp && $i===0){
            $this->iniTmp.= "; Sapi generator ini configs. ".PHP_EOL;
            $this->iniTmp.= "; Activator `$this->programName`, last change: " . date('m.d.Y H:i:s').PHP_EOL;
        }



    // v 1
        foreach ($iniData as $k => $v){
            if (is_array($v)){
                if($i == 0) $this->iniTmp.= PHP_EOL.PHP_EOL."[$k]".PHP_EOL;
                $this->createIni(false, $v, $i+1, $k);
            }else{
                if($i == 1) $this->iniTmp.= $k." = '$v'".PHP_EOL;
                if($i == 2) $this->iniTmp.= $iKey . "[".$k."] = '$v'".PHP_EOL;
            }
        }

    // v2
    foreach ($iniData as $k => $v){
        if (is_array($v)){
            $this->iniTmp.= PHP_EOL.PHP_EOL."[$k]".PHP_EOL;
            foreach($v as $k2 => $v2){
                if (is_array($v2))
                    foreach ($v2 as $k3 => $v3)
                        $this->iniTmp .= "$k2"."[$k3] = '$v3'" . PHP_EOL;
                else
                    $this->iniTmp.="$k2 = '$v2'".PHP_EOL;
            }
        }
    }




    if($file){
            if(is_file($file) && file_put_contents($file, $this->iniTmp))
                return $this->iniTmp;
            else
                self::console("# Sapi createIni message:\n\t1. - file not exist: $file.
                                \n\t2. - or problem with function file_put_contents()");
        } else
            return $this->iniTmp;
    }







$iniUpdate = $this->sapi->createIni(false, $this->ini);

print_r("\n# ini: \n");
print_r($iniUpdate);

$ini2 = $this->ini;
$ini2['projects']['add_pp'] = [
    'path' => '/qwe/asd',
    'branch' => 'dev',
];

$iniUpdate = $this->sapi->createIni(false, $ini2);
//print_r("\n# ini: \n");
//print_r($this->ini);
print_r("\n# iniUpdate: \n");
print_r($iniUpdate);
print_r("\n\n");

//$this->cmd_add('new project', 'my/path', 'master');



//$CONFIG_INI = parse_ini_file('../config.ini', TRUE);
//$CUSTOM_INI = parse_ini_file('ini/custom.ini', TRUE);

//$INI = $this->sapi->iniMerge($CONFIG_INI, $CUSTOM_INI);



//$ini = $this->parseIniNamespace('gitok.ini');














