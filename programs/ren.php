<?php

/**
 * php var/sapi/sapi template create /var/www/site.loc
 */
class ren
{

    private $sapi;
    private $command;
    private $options;

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
        Sapi::$renderPath = __DIR__.'/';
    }
/*    ServerName <?=$serverName?>
    DocumentRoot <?=$documentRoot?>
    <Directory <?=$directory?>>*/
    /**
     * @param null|string $p1
     * @param null|string $p2
     * @param null|string $p3
     * @param null|string $p4
     */
    public function init($p1 = null, $p2 = null, $p3 = null, $p4 = null)
    {
        /*
        print_r(Sapi::renderPHP('host_conf', [
            'ServerName' => 'XXX',
            'DocumentRoot' => 'OOO',
            'Directory' => 'ZZZ',
        ]));
        */

        print_r(Sapi::render('host_conf', [
            'ServerName' => 'XXX',
            'DocumentRoot' => 'OOO',
            'Directory' => 'ZZZ',
        ]));
    }



    public function close($message = null) {
        $message = $message ? "$message/n" : "";
        Sapi::console($message . "[sapi]: The program is completed, goodbye\n");
    }
}