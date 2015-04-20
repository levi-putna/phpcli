<?php
require __DIR__ . '/../vendor/autoload.php';

/*
 * Output text to the console
 */

//Clear the screen before starting
\PHPCli\Cli::clear_screen();

\PHPCli\Cli::write("\n 1) Text Output ");
\PHPCli\Cli::write("----------------------------------------\n");

\PHPCli\Cli::write("This is a plain string");
\PHPCli\Cli::write("This is a string with a red foreground colour", \PHPCli\Color::$red);
\PHPCli\Cli::write("This is a string with a white foreground and Red Background", \PHPCli\Color::$white, \PHPCli\BackgroundColor::$red);

\PHPCli\Cli::write("\n 2) Text Error Output ");
\PHPCli\Cli::write("----------------------------------------\n");

\PHPCli\Cli::error("Failure: This is a plain error message");
\PHPCli\Cli::error("Failure: This is a error message with a yellow foreground colour", \PHPCli\Color::$yellow);
\PHPCli\Cli::error("Failure: This is a string with a white foreground and Red Background", \PHPCli\Color::$white, \PHPCli\BackgroundColor::$red);

\PHPCli\Cli::write("\n 2) Prompt for input: ");
\PHPCli\Cli::write("----------------------------------------\n");

//Prompt for input
$colour = \PHPCli\Cli::prompt("What is your favorite colour?");
\PHPCli\Cli::write("I also like " . $colour);

// Takes any input, but offers default
$colour = \PHPCli\Cli::prompt('What is your favorite color?', 'white');
\PHPCli\Cli::write("I also like " . $colour);

// Will only accept the options in the array
$ready = \PHPCli\Cli::prompt('Are you ready to continue?', array('y','n'));

\PHPCli\Cli::write("\n 3) Progress Bar: ");
\PHPCli\Cli::write("----------------------------------------\n");


//Simple Example
$size = 10;
\PHPCli\ProgressBar::start($size);

for ($i = 1; $i <= $size; $i++) {
    \PHPCli\ProgressBar::next();
    \PHPCli\Cli::wait(1);
}

\PHPCli\ProgressBar::finish();

//Update message
$size = 10;
\PHPCli\ProgressBar::start($size, "Starting in about 5 seconds");
\PHPCli\Cli::wait(5);
\PHPCli\ProgressBar::setMessage('Go!');
for ($i = 1; $i <= $size; $i++) {
    if ($i < 5) {
        \PHPCli\ProgressBar::next();
    } else {
        \PHPCli\ProgressBar::next(1, "made it to $i");
    }
    \PHPCli\Cli::wait(1);
}

\PHPCli\ProgressBar::finish();


//Unknown size
\PHPCli\ProgressBar::start(0, "One moment please");
usleep(10000000);
$size = 200; // Fixed here, this would be the result of some slow logic/query/api-call
\PHPCli\ProgressBar::setTotal(200);
for ($i = 1; $i <= $size; $i++) {
    \PHPCli\ProgressBar::next();
    usleep(100000);
}

\PHPCli\ProgressBar::finish();


\PHPCli\Cli::write("\n 4) Tables: ");
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