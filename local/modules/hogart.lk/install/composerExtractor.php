<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 04/08/16
 * Time: 02:22
 */
define('EXTRACT_DIRECTORY', dirname(__FILE__) . "/tmp");

if (!file_exists(dirname(__FILE__) . "/../composer.phar")) {
    $context = stream_context_create(array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    ));
    file_put_contents(dirname(__FILE__) . "/../composer.phar", file_get_contents("https://getcomposer.org/composer.phar", null, $context));
}

if (file_exists(EXTRACT_DIRECTORY . '/vendor/autoload.php') == true) {
    echo "Extracted autoload already exists. Skipping phar extraction as presumably it's already extracted.";
} else {
    $composerPhar = new Phar(dirname(__FILE__) . "/../composer.phar");
    //php.ini setting phar.readonly must be set to 0
    $composerPhar->extractTo(EXTRACT_DIRECTORY);
}
//This requires the phar to have been extracted successfully.
require_once(EXTRACT_DIRECTORY . '/vendor/autoload.php');

//Use the Composer classes
use Composer\Console\Application;
use Composer\Command\InstallCommand;
use Symfony\Component\Console\Input\ArrayInput;

// change out of the webroot so that the vendors file is not created in
// a place that will be visible to the intahwebz
chdir(dirname(__FILE__) . '/../');

//Create the commands
$input = new ArrayInput(array('command' => 'install'));

//Create the application and run it with the commands
$application = new Application();
$application->run($input);