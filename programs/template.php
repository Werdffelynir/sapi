<?php

/**
 * php var/sapi/sapi template create /var/www/site.loc
 */
class template
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
    }

    /**
     * @param null|string $p1
     * @param null|string $p2
     * @param null|string $p3
     * @param null|string $p4
     */
    public function init($p1 = null, $p2 = null, $p3 = null, $p4 = null)
    {
        print_r("Получина комманда: $this->command и " . func_num_args() .
            "аргументов\nDump:\n". print_r(func_get_args(), true));
    }



    public function close($message = null) {
        $message = $message ? "$message/n" : "";
        Sapi::console($message . ">> The program is completed, goodbye\n");
    }
}