<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace AbraFlexi\Processor;

/**
 * Description of Meta
 *
 * @author vitex
 */
class Meta extends Engine {

    /**
     * We work with table meta
     * @var string
     */
    public $myTable = 'meta';

    /**
     * Webhook Processor lockfile
     * @var string 
     */
    protected $lockfile = '/tmp/meta.lock';

    public function unprocessed() {
        return $this->listingQuery()->where('processed IS NULL')->orderBy('id');
    }

    public function firstUnprocessed() {
        return $this->unprocessed()->limit(1);
    }

    public function handle($meta) {
        $result = [];
        $this->setMyKey($meta['id']);
        $this->setObjectName();
        $components = parse_url($meta['uri']);
        $pathParts = explode('/', $components['path']);
        $meta['documentID'] = urldecode($pathParts[4]);
        $meta['subject'] = $pathParts[3];
        $meta['company'] = $pathParts[2];
        $meta['companyuri'] = $components['scheme'] . '://' . $components['host'] . ':' . $components['port'] . '/c/' . $meta['company'];
        $meta['url'] = $components['scheme'] . '://' . $components['host'] . ':' . $components['port'];
        $meta = array_merge($meta, $components);

        if (array_key_exists($meta['companyuri'], $this->credentials)) {
            $meta['login'] = $this->credentials[$meta['companyuri']]['login'];
            $meta['password'] = $this->credentials[$meta['companyuri']]['password'];
            $meta['doneid'] = $this->credentials[$meta['companyuri']]['doneid'];
            $meta['sourceid'] = $this->credentials[$meta['companyuri']]['id'];
        }

        $rules = $this->getRulesFor($meta);

        $commands = $this->getCommandsFor($rules);

        if ($commands) {
            foreach ($commands as $command) {
                $result[$command] = $this->executeCommand($command, $meta);
            }
        }

        $this->getFluentPDO()->update($this->getMyTable())->set('processed', new \Envms\FluentPDO\Literal('NOW()'))->where('id', $meta['id'])->execute();

        return $result;
    }

    public function processMetas() {
        foreach ($this->unprocessed() as $meta) {
            $this->handle($meta);
        }
    }

    public function getRulesFor($meta) {

//    [id] => 1
//    [uri] => https://flexibee-dev.spoje.net:5434/c/spoje_net_s_r_o_/faktura-vydana/code:VF1-4698%2F2022
//    [meta] => penalised
//    [discovered] => 2022-09-29 01:25:55
//    [processed] => 
//    [documentID] => code:VF1-4698/2022
//    [subject] => faktura-vydana
//    [company] => spoje_net_s_r_o_
//    [scheme] => https
//    [host] => flexibee-dev.spoje.net
//    [port] => 5434
//    [path] => /c/spoje_net_s_r_o_/faktura-vydana/code:VF1-4698%2F2022
//+----+---------------------+------+--------------------+--------+-------------------------+
//| id | company             | host | subject            | meta   | command                 |
//+----+---------------------+------+--------------------+--------+-------------------------+
//|  1 | *                   | *    | adresar            | SMS    | abraflexi-send-sms      |
//|  2 | image_office_s_r_o_ | *    | objednavka-prijata | NOTE   | image-office-note-state |
//|  3 | *                   | *    | adresar            | create | abraflexi2office365     |
//|  4 | *                   | *    | adresar            | update | abraflexi2office365     |
//+----+---------------------+------+--------------------+--------+-------------------------+

        $rules = $this->getFluentPDO()->from('rules')->select('command', true)
                        ->where("host", [$meta['host'], '-'])
                        ->where("company", [$meta['company'], '-'])
                        ->where("subject", [$meta['subject'], '-'])->where('meta', $meta['meta'])->disableSmartJoin();

        return $rules;
    }

    public function getCommandsFor($rules) {
        $commands = [];
        while ($command = $rules->fetch()) {
            $commands[] = $command['command'];
        }
        return $commands;
    }

    /**
     * Run Command
     * 
     * @param type $command
     * @param array $meta
     * 
     * @return type
     */
    public function executeCommand($command, $meta) {

        $meta['email'] = '';

        $envNames = [
            'ABRAFLEXI_URL' => $meta['url'],
            'ABRAFLEXI_LOGIN' => $meta['login'],
            'ABRAFLEXI_PASSWORD' => $meta['password'],
            'ABRAFLEXI_COMPANY' => $meta['company'],
            'EASE_MAILTO' => $meta['email'],
            'EASE_LOGGER' => empty($meta['email']) ? 'syslog' : 'syslog|email',
        ];

        foreach (array_merge($meta, $envNames) as $envName => $sqlValue) {
            $this->addStatusMessage(sprintf(_('Setting Environment %s to %s'), strtoupper($envName), $sqlValue), 'debug');
            putenv(strtoupper($envName) . '=' . $sqlValue);
        }

        $exec = $command;
        $cmdparams = '';
        $this->addStatusMessage('start: ' . $exec . ' ' . $cmdparams);

        foreach (explode("\n", shell_exec($exec . ' ' . $cmdparams)) as $row) {
            $this->addStatusMessage($row, 'debug');
        }

        $this->addStatusMessage('end: ' . $exec);

        return $command;
    }

}