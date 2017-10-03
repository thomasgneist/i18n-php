<?php
/**
 * i18n - PHP internationalization class.
 *
 * @see https://github.com/thomasgneist/i18n-php GitHub project
 *
 * @author Thomas Gneist <contact@thomasgneist.com>
 * @copyright 2017 Thomas Gneist
 * @License MIT License
 *
 * @version 1.0
 */

/** Class i18n */
class i18n
{
    /**
     * MySQL host.
     * @var string
     */
    public $Host = 'localhost';

    /**
     * MySQL database.
     * @var string
     */
    public $Database = '';

    /**
     * MySQL user.
     * @var string
     */
    public $Username = '';

    /**
     * MySQL password.
     * @var string
     */
    public $Password = '';

    /**
     * Fallback language.
     * @var string
     */
    public $Fallback = 'EN';

    /**
     * Webmaster email address.
     * @var string
     */
    public $Webmaster = '';

    /**
     * Enable or disable error logging.
     * @var bool
     */
    protected $ErrorLog = true;

    /**
     * Enable or disable the fallback language.
     * @var bool
     */
    protected $NoFallback = false;


    /**
     * Connect to the MySQL database with PDO
     * @since 1.0 Sept 28th, 2017
     *
     * @return PDO
     */
    private function connect()
    {
        try {
            // Connect to the MySQL database.
            $pdo = new PDO('mysql:host='.$this->Host.';dbname='.$this->Database, $this->Username, $this->Password);
        } catch (PDOException $e) {
            $this->error($e);
        }

        if(!isset($pdo)) {
            $pdo = NULL;
        }

        return $pdo;
    }

    /**
     * Log an error.
     * @since 1.0 Sept 28th, 2017
     * @param string $message
     */
    private function error($message)
    {
        if($this->ErrorLog) {
            // Log the error in your log file.
            $message = str_replace(array("\r", "\n"), "", $message);
            error_log($message, 0);

            // Send and email to the webmaster.
            if(!empty($this->Webmaster)) {
                error_log($message, 1, $this->Webmaster);
            }
        }
    }

    /**
     * Enable error logging.
     * @since 1.0 Sept 30th, 2017
     * @param bool $enable
     */
    public function ErrorLog($enable = true)
    {
        if($enable) {
            $this->ErrorLog = true;
        } else {
            $this->ErrorLog = false;
        }
    }

    /**
     * Enable or disable fallback language.
     * @since 1.0 Oct 1st, 2017
     * @param bool $value
     */
    public function NoFallback($value = false)
    {
        if($value) {
            $this->NoFallback = true;
        } else {
            $this->NoFallback = false;
        }
    }

    /**
     * Get and return the selected language
     * @since 1.0 Oct 1st, 2017
     * @return string
     */
    public function getLanguage()
    {
        // Check if a GET parameter named 'lang' exists
        if(isset($_GET['lang']) && is_string($_GET['lang'])) {
            $lang = strtoupper($_GET['lang']);
            // Check if a SESSION parameter named 'lang' exists
        } else if(isset($_SERVER['lang']) && is_string($_SESSION['lang'])) {
            $lang = strtoupper($_SESSION['lang']);
            // Get the language from the domain (e.g. de.example.com or fr.example.com)
        } else {
            $lang = strtoupper(explode('.', $_SERVER['SERVER_NAME'])[0]);
        }

        // Return the fallback language if nothing worked
        if($lang == 'WWW' || $lang == 'DEV') {
            $lang = $this->Fallback;
        }

        return $lang;
    }

    /**
     * Get and return fallback language.
     * @since 1.0 Oct 1st, 2017
     * @return string
     */
    public function getFallbackLanguage()
    {
        return $this->Fallback;
    }

    /**
     * Get and return a message from the database
     *
     * msg() returns the selected message in the right language. To
     * do so it connects to the database via connect() and selects the right
     * table using getCurrentLanguage(). Then it selects the correct row using
     * the given key and returns the message. If no message was found, the
     * function returns the message in the fallback language or writes
     * "Oops! Something went wrong."
     *
     * @since 1.0 Sept 28th, 2017
     *
     * @param $id
     * @return string
     */
    public function msg($id)
    {
        $table = 'lang_'.$this->getLanguage();
        $pdo = $this->connect();

        // Connect to the MySQL database and get the requested message.
        try {
            $stmt = $pdo->prepare("SELECT `message` FROM `".$table."` WHERE `id` = '".$id."' LIMIT 1");
            $stmt->execute();
            $message = $stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->error($e);
        }

        // Connect to the MySQL database and get the requested message from the fallback table.
        if(empty($message) && !$this->NoFallback) {
            try {
                $stmt = $pdo->prepare("SELECT `message` FROM `lang_".$this->Fallback."` WHERE `id` = '".$id."' LIMIT 1");
                $stmt->execute();
                $message = $stmt->fetchColumn();
            } catch (PDOException $e) {
                $this->error($e);
            }
        }

        // Set message to "Oops! Something went wrong." if message was empty.
        if(empty($message)) {
            $message = 'Oops! Something went wrong.';
        }

        return $message;
    }
}
