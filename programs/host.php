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

    public static $self = null;

    public function __construct(Sapi $sapi, $command, $options)
    {
        $this->sapi = $sapi;
        $this->command = $command;
        $this->options = $options;

        self::$self = $this;
        Sapi::$renderPath = __DIR__.'/';
    }


    public function init()
    {
        print("[host] Консольная утилита 'SAPI' программа 'host' запущенна...\n");

        $command = "command_" . $this->command;
        if (method_exists($this, $command)) {
            $this->$command();
        }
    }


    public function command_create()
    {
        $hostName = '';

        if(!empty($this->options[0]))
            $hostName = trim($this->options[0]);
        else{
            Sapi::input(
                "[host] Введите имя нового хоста: ",
                function ($resp) use (&$hostName) {
                    $hostName = trim($resp);
                }
            );
        }

        $sitePath = self::$self->conf['site_path'] . $hostName;
        $confFile = self::$self->conf['conf_path'] . $hostName;

        print("[host] Проверка крневой директории веб-сайта.\n");
        print("[host] $sitePath\n[host] $confFile \n");

        if (!is_dir($sitePath)) {
            print("[host] Попытка создания файлов веб-сайта.\n");
            $result = mkdir($sitePath);
            if (!$result) goto error;

            $siteHtml = Sapi::render('host_site.html',[
                'host' => $hostName,
                'path' => $sitePath,
                'date' => date("m.d.Y H:i"),
            ]);
            $result = file_put_contents($sitePath . '/index.html', $siteHtml);
            if (!$result) goto error;

            system("sudo chmod -R 777 $sitePath");
            print("[host] Успешно.\n");
        } else {
            print("[host] Похоже веб-сайт существует.\n");
            Sapi::confirm("[host] Продолжить? Y/n: ", function ($confirm) {
                if (!$confirm)
                    exit("[host] Программа завершена.\n");
            });
        }

        print("[host] Попытка создания $confFile.conf \n");

        $conf = Sapi::render('host_conf', [
            'ServerName' => $hostName,
            'DocumentRoot' => $sitePath,
            'Directory' => $sitePath,
        ]);

        $tmp = self::$self->conf['site_path'] . $hostName . '.conf';

        $result = file_put_contents($tmp, $conf);
        if (!$result) {
            print("[host] Не удалось временный файл\n");
            goto error;
        } else {
            sleep(1);
            system("sudo mv $tmp $confFile.conf");
            sleep(1);
            system("sudo chmod 755 $confFile.conf");
            system("sudo chown -R www-data:www-data $confFile.conf");
        }
        sleep(1);

        print("[host] Добавление в /etc/hosts: '127.0.0.1 $hostName www.$hostName'\n");
        system("sudo chmod 777 /etc/hosts");
        system("sudo echo '127.0.0.1    $hostName    www.$hostName    # sapi host' >> /etc/hosts");
        system("sudo chmod 755 /etc/hosts");
        sleep(1);

        print("[host] Активаыия хоста ...\n");
        system("sudo a2ensite $hostName.conf");
        sleep(1);

        print("[host] Перезагрузка сервера ...\n");
        system("sudo /etc/init.d/apache2 restart");

        print("[host] Сайт должен быть доступен на хосте 'http://$hostName'\n");

        print("[host] ");
        goto end;
        error:
        print("[host] Ошибка. ");
        end:
        print("Программа завершена.\n");


    }


    public function command_remove()
    {
        $hostName = '';
        if(!empty($this->options[0]))
            $hostName = trim($this->options[0]);
        else{
            Sapi::input(
                "[host] Имя хоста что будет удален: ",
                function ($resp) use (&$hostName) {
                    $hostName = trim($resp);
                }
            );
        }

        $sitePath = self::$self->conf['site_path'] . $hostName;
        $confFile = self::$self->conf['conf_path'] . $hostName;

        if (is_dir($sitePath)) {
            Sapi::confirm("[host] Найдены файлы ваб-сайта $sitePath\n[host] Удалить все? Y/n: ",
                function ($yes) use ($sitePath) {
                    if ($yes) {
                        print("[host] Попытка удаления ваб-сайта ...\n");
                        system("sudo rm -r $sitePath");
                    }
                });
        } else {
            print("[host] Файлы ваб-сайта не найдены, удалять нечего.\n");
        }
        sleep(1);

        print("[host] Деактивация хоста ...\n");
        system("sudo a2dissite $hostName.conf");
        sleep(1);

        print("[host] Попытка удаления $confFile.conf \n");
        system("sudo rm $confFile.conf");

        print("[host] ");
        print("Программа завершена.\n");

    }


}

?>