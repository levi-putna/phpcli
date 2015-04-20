<?php
require __DIR__ . '/../vendor/autoload.php';

\PHPCli\Cli::write("\n Tables: ");
\PHPCli\Cli::write("----------------------------------------\n");

$table = new \PHPCli\Table();
$data = include('data.php');

$table->setTableColor(\PHPCli\Color::$blue);
$table->setHeaderColor(\PHPCli\Color::$light_blue);
$table->addField('First Name', 'firstName', false);
$table->addField('Last Name', 'lastName', false);
$table->addField('DOB', 'dobTime', new \PHPCli\Cell\DateCell('Y M D'));
$table->addField('Admin', 'isAdmin', new \PHPCli\Cell\YesNoCell(), \PHPCli\Color::$yellow);
$table->addField('Last Seen', 'lastSeenTime', new \PHPCli\Cell\CountDownCell(), \PHPCli\Color::$red);
$table->addField('Expires', 'expires', new \PHPCli\Cell\DateCell(), \PHPCli\Color::$green);
$table->addField('Active', 'active', new \PHPCli\Cell\BoolCell(), \PHPCli\Color::$purple);
$table->injectData($data);
$table->display();


// Play sound to let the user know the example has finished.
//\PHPCli\Cli::beep(1);