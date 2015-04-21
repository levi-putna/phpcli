<?php

namespace PHPCli;

class Cli
{

    protected static $STDOUT = STDERR;
    protected static $STDERR = STDOUT;

    public static $left = 0;
    public static $center = 1;
    public static $right = 2;

    /**
     * Beeps a certain number of times.
     *
     * @param    int $num the number of times to beep
     */
    public static function beep($num = 1)
    {
        echo str_repeat("\x07", $num);
    }

    /**
     * Outputs a string to the cli.     If you send an array it will implode them
     * with a line break.
     *
     * @param    string|array $text the text to output, or array of lines
     */
    public static function write($text = '', $foreground = null, $background = null, $align = null)
    {


        if (is_array($text)) {
            $text = implode(PHP_EOL, self::align($align, $text));
        }else{
            $text = self::align($align, $text);
        }

        if ($foreground or $background) {
            $text = static::color($text, $foreground, $background);
        }

        fwrite(static::$STDOUT, $text . PHP_EOL);
    }

    private static function align($align, $text){
        $display = self::display();

        $text_len = strlen($text);
        $width = $display['width'];
        $padding = ($width / 2) - ($text_len / 2);

        switch ($align) {
            case 1:
                return str_pad('', $padding , ' ') . $text;
                break;
            case 2:
                return str_pad('', $width - $text_len , ' ') . $text;
                break;
        }

        return $text;
    }

    public static function out($text = '')
    {
        fwrite(static::$STDOUT, $text);
    }

    public static function heading($heading)
    {
        $display = self::display();

        $heading = ' ' . strtoupper($heading);
        $title_len = strlen($heading);
        $width = $display['width'] - 2;
        $padding = ($width / 2) - ($title_len / 2);


        self::write(str_pad('', $display['width'], "#"), Color::$blue);
        self::out(self::color('#', Color::$blue));
        self::out(str_pad('', $padding , ' '));
        self::out($heading);

        //Add extra padding when width is not even.
        if (fmod($padding, 1) != 0){
            self::out(' ');
        }

        self::out(str_pad('', $padding , ' '));
        self::write(self::color('#', Color::$blue));
        self::write(str_pad('', $display['width'], "#"), Color::$blue);
    }

    public static function line($color = null)
    {

        if($color){
            $color = Color::$white;
        }

        $display = self::display();
        self::write(str_pad('', $display['width'], "-"), $color);
    }

    /**
     * Outputs an error to the CLI using STDERR instead of STDOUT
     *
     * @param    string|array $text the text to output, or array of errors
     */
    public static function error($text = '', $foreground = null, $background = null)
    {
        if (is_null($foreground)) {
        $foreground = Color::$red;
    }

        if (is_array($text)) {
            $text = implode(PHP_EOL, $text);
        }

        if ($foreground OR $background) {
            $text = static::color($text, $foreground, $background);
        }

        fwrite(static::$STDERR, $text . PHP_EOL);
    }

    /**
     * Returns the given text with the correct color codes for a foreground and
     * optionally a background color.
     *
     * @param    string $text the text to color
     * @param    string $foreground the foreground color
     * @param    string $background the background color
     * @param    string $format other formatting to apply. Currently only 'underline' is understood
     * @return    string    the color coded string
     */
    public static function color($text, $foreground, $background = null, $format = null)
    {

        $string = "\033[" . $foreground . "m";

        if ($background !== null) {
            $string .= "\033[" . $background . "m";
        }

        if ($format === 'underline') {
            $string .= "\033[4m";
        }

        $string .= $text . "\033[0m";

        return $string;
    }

    /**
     * Asks the user for input.  This can have either 1 or 2 arguments.
     *
     * Usage:
     *
     * // Waits for any key press
     * CLI::prompt();
     *
     * // Takes any input
     * $color = CLI::prompt('What is your favorite color?');
     *
     * // Takes any input, but offers default
     * $color = CLI::prompt('What is your favourite color?', 'white');
     *
     * // Will only accept the options in the array
     * $ready = CLI::prompt('Are you ready?', array('y','n'));
     *
     * @return    string    the user input
     */
    public static function prompt()
    {
        $args = func_get_args();

        $options = array();
        $output = '';
        $default = null;

        // How many we got
        $arg_count = count($args);

        // Is the last argument a boolean? True means required
        $required = end($args) === true;

        // Reduce the argument count if required was passed, we don't care about that anymore
        $required === true and --$arg_count;

        // This method can take a few crazy combinations of arguments, so lets work it out
        switch ($arg_count) {
            case 2:

                // E.g: $ready = CLI::prompt('Are you ready?', array('y','n'));
                if (is_array($args[1])) {
                    list($output, $options) = $args;
                } // E.g: $color = CLI::prompt('What is your favourite color?', 'white');
                elseif (is_string($args[1])) {
                    list($output, $default) = $args;
                }

                break;

            case 1:

                // No question (probably been asked already) so just show options
                // E.g: $ready = CLI::prompt(array('y','n'));
                if (is_array($args[0])) {
                    $options = $args[0];
                }

                // Question without options
                // E.g: $ready = CLI::prompt('What did you do today?');
                elseif (is_string($args[0])) {
                    $output = $args[0];
                }

                break;
        }

        // If a question has been asked with the read
        if ($output !== '') {
            $extra_output = '';

            if ($default !== null) {
                $extra_output = ' [ Default: "' . $default . '" ]';
            } elseif ($options !== array()) {
                $extra_output = ' [ ' . implode(', ', $options) . ' ]';
            }

            fwrite(static::$STDOUT, $output . $extra_output . ': ');
        }

        // Read the input from keyboard.
        $input = trim(static::input()) ?: $default;

        // No input provided and we require one (default will stop this being called)
        if (empty($input) and $required === true) {
            static::write('This is required.');
            static::new_line();

            $input = forward_static_call_array(array(__CLASS__, 'prompt'), $args);
        }

        // If options are provided and the choice is not in the array, tell them to try again
        if (!empty($options) and !in_array($input, $options)) {
            static::write('This is not a valid option. Please try again.');
            static::new_line();

            $input = forward_static_call_array(array(__CLASS__, 'prompt'), $args);
        }

        return $input;
    }

    /**
     * Get input from the shell, using readline or the standard STDIN
     *
     * Named options must be in the following formats:
     * php index.php user -v --v -name=John --name=John
     *
     * @param    string|int $name the name of the option (int if unnamed)
     * @return    string
     */
    public static function input($prefix = '')
    {
        echo $prefix;
        return fgets(STDIN);
    }

    /**
     * Clears the screen of output
     *
     * @return    void
     */
    public static function clear_screen()
    {

        // Anything with a flair of Unix will handle these magic characters
        fwrite(static::$STDOUT, chr(27) . "[H" . chr(27) . "[2J");
    }

    /**
     * Enter a number of empty lines
     *
     * @param    integer    Number of lines to output
     * @return    void
     */
    public static function new_line($num = 1)
    {
        // Do it once or more, write with empty string gives us a new line
        for ($i = 0; $i < $num; $i++) {
            static::write();
        }
    }

    /**
     * Waits a certain number of seconds
     * waiting for a key press.
     *
     * @param    int $seconds number of seconds
     * @param    bool $countdown show a countdown or not
     */
    public static function wait($seconds = 0, $countdown = false)
    {
        if ($countdown === true) {
            $time = $seconds;

            while ($time > 0) {
                fwrite(static::$STDOUT, $time . '... ');
                sleep(1);
                $time--;
            }
        } else {
            if ($seconds > 0) {
                sleep($seconds);
            }
        }
    }

    /**
     * Redirect STDERR writes to this file or fh
     *
     * Call with no argument to retrieve the current filehandle.
     *
     * Is not smart about opening the file if it's a string. Existing files will be truncated.
     *
     * @param  resource|string $fh Opened filehandle or string filename.
     *
     * @return resource
     */
    public static function stderr($fh = null)
    {
        $orig = static::$STDERR;

        if (!is_null($fh)) {
            if (is_string($fh)) {
                $fh = fopen($fh, "w");
            }
            static::$STDERR = $fh;
        }

        return $orig;
    }

    /**
     * Redirect STDOUT writes to this file or fh
     *
     * Call with no argument to retrieve the current filehandle.
     *
     * Is not smart about opening the file if it's a string. Existing files will be truncated.
     *
     * @param  resource|string|null $fh Opened filehandle or string filename.
     *
     * @return resource
     */
    public static function stdout($fh = null)
    {
        $orig = static::$STDOUT;

        if (!is_null($fh)) {
            if (is_string($fh)) {
                $fh = fopen($fh, "w");
            }
            static::$STDOUT = $fh;
        }

        return $orig;
    }

    public static function display()
    {
        $screen['width'] = exec('tput cols');
        $screen['height'] = exec('tput lines');

        return $screen;
    }

}