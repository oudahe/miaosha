<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Front controller for config view / download and clear
 *
 * @package PhpMyAdmin-Setup
 */
use PMA\libraries\config\FormDisplay;
use PMA\setup\lib\ConfigGenerator;
use PMA\libraries\URL;
use PMA\libraries\Response;

/**
 * Core libraries.
 */
require './lib/common.inc.php';

require './libraries/config/setup.forms.php';

$form_display = new FormDisplay($GLOBALS['ConfigFile']);
$form_display->registerForm('_config.php', $forms['_config.php']);
$form_display->save('_config.php');

$response = Response::getInstance();

if (isset($_POST['eol'])) {
    $_SESSION['eol'] = ($_POST['eol'] == 'unix') ? 'unix' : 'win';
}

if (PMA_ifSetOr($_POST['submit_clear'], '')) {
    //
    // Clear current config and return to main page
    //
    $GLOBALS['ConfigFile']->resetConfigData();
    // drop post data
    $response->generateHeader303('index.php' . URL::getCommonRaw());
    exit;
} elseif (PMA_ifSetOr($_POST['submit_download'], '')) {
    //
    // Output generated config file
    //
    PMA_downloadHeader('config.inc.php', 'text/plain');
    echo ConfigGenerator::getConfigFile($GLOBALS['ConfigFile']);
    exit;
} else {
    //
    // Show generated config file in a <textarea>
    //
    $response->generateHeader303('index.php' . URL::getCommonRaw(array('page' => 'config')));
    exit;
}
