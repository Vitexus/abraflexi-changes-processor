<?php

namespace AbraFlexi\Processor;

/**
 * Meta State Emitor.
 *
 * @author     Vítězslav Dvořák <vitex@arachne.cz>
 * @copyright  2022 VitexSoftware
 */
const APP_NAME = 'AbraFlexiIncomeConfirm';

require_once __DIR__.'/../vendor/autoload.php';

if (file_exists('../.env')) {
    \Ease\Shared::singleton()->loadConfig('../.env', true);
}

foreach (['DB_CONNECTION', 'DB_DATABASE'] as $cfgKey) {
    if (empty(\Ease\Functions::cfg($cfgKey))) {
        echo 'Requied configuration '.$cfgKey.' is not set.';
        exit(1);
    }
}

if ($argv > 2) {

    if (array_key_exists(1, $argv)) {
        $uri = $argv[1];
    } else {
        $uri = \Ease\Functions::cfg('URI');
    }

    if (array_key_exists(2, $argv)) {
        $meta = $argv[2];
    } else {
        $meta = \Ease\Functions::cfg('META');
    }

    if (array_key_exists(3, $argv)) {
        $after = $argv[3];
    } else {
        $after = \Ease\Functions::cfg('AFTER');
    }

    $metaData = ['uri' => $uri, 'meta' => $meta];
    if (empty($after) || ($timestamp = strtotime($after)) === false) {
        die("The string ($after) is bogus");
    } else {
        $metaData['after'] = date("Y-m-d H:i:s", $timestamp);
    }
    $engine = new Meta();
    $engine->addStatusMessage($uri.'#'.$meta.' '.$after,
        $engine->insertItem($metaData) ? 'success' : 'error');
} else {
    echo "usage: $argv[0] [record URI] [meta state] <after timestamp>\n";
}