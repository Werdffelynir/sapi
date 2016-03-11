<?php

/**
 * php sapi host create /var/www/site.loc
 * Class host
 */
class host
{

    private $sapi;
    private $command;
    private $options;

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

    public function __construct(Sapi $sapi, $command, $options)
    {
        $this->sapi = $sapi;
        $this->command = $command;
        $this->options = $options;

        self::$self = $this;
    }


    public function init()
    {
        print(">> Консольная утилита 'SAPI' программа 'host' запущенна...\n");

        $command = "command_" . $this->command;
        if (method_exists($this, $command)) {
            $this->$command();
        }
    }


    public function command_create()
    {

        Sapi::input(">> Введите имя нового хоста: ", function ($response) {
            $hostName = trim($response);
            $sitePath = self::$self->conf['site_path'] . $hostName;
            $confFile = self::$self->conf['conf_path'] . $hostName;

            print(">> Проверка крневой директории веб-сайта.\n");
            print(">> $sitePath\n>> $confFile \n");

            if (!is_dir($sitePath)) {
                print(">> Попытка создания файлов веб-сайта.\n");
                $result = mkdir($sitePath);
                if (!$result) goto error;

                $result = file_put_contents($sitePath . '/index.html', self::$self->tpl['html']);
                if (!$result) goto error;

                print(">>>> Успешно.\n");
            } else {
                print(">> Похоже веб-сайт существует.\n");
                Sapi::confirm(">> Продолжить? Y/n: ", function ($confirm) {
                    if (!$confirm)
                        exit(">> Программа завершена.\n");
                });
            }

            print(">> Попытка создания $confFile.conf \n");

            $conf = str_replace("{ServerName}", $hostName, self::$self->tpl['conf']);
            $conf = str_replace("{DocumentRoot}", $sitePath, $conf);
            $conf = str_replace("{Directory}", $sitePath, $conf);

            $tmp = self::$self->conf['site_path'] . $hostName . '.conf';

            $result = file_put_contents($tmp, $conf);
            if (!$result) {
                print(">> Не удалось временный файл\n");
                goto error;
            } else {
                system("sudo mv $tmp $confFile.conf");
            }

            Sapi::confirm(">> Добавить в hosts '127.0.0.1 $hostName www.$hostName'\n>> Открыть редактор Y/n: ",
                function ($confirm) {
                    if ($confirm)
                        system("sudo gedit /etc/hosts");
                    else
                        exit(">> Программа завершена.\n");
                });

            print(">> Активаыия хоста ...\n");
            sleep(1);

            system("sudo a2ensite $hostName.conf");
            sleep(1);

            print(">> Перезагрузка сервера ...\n");
            system("sudo /etc/init.d/apache2 restart");

            print(">> Сайт должен быть доступен на хосте 'http://$hostName'\n");

            print(">> ");
            goto end;
            error:
            print(">> Ошибка. ");
            end:
            print("Программа завершена.\n");

        });
    }


    public function command_remove()
    {

        Sapi::input(">> Имя хоста что будет удален: ", function ($response) {
            $hostName = trim($response);
            $sitePath = self::$self->conf['site_path'] . $hostName;
            $confFile = self::$self->conf['conf_path'] . $hostName;

            if (is_dir($sitePath)) {
                Sapi::confirm(">> Найдены файлы ваб-сайта $sitePath\n>> Удалить все? Y/n: ",
                    function ($yes) use ($sitePath) {
                        if ($yes) {
                            print(">> Попытка удаления ваб-сайта ...\n");
                            system("sudo rm -r $sitePath");
                        }
                    });
            } else {
                print(">>>> Файлы ваб-сайта не найдены, удалять нечего.\n");
            }


            print(">> Попытка удаления $confFile.conf \n");
            system("sudo rm $confFile.conf");

            print(">> ");
            print("Программа завершена.\n");
        });
    }


}

?>