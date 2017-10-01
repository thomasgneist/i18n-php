<?php
/**
 * i18n PHP class
 *
 * i18n is a class that lets you make your website bilingual. You need a
 * proper MySQL database and a website that is able to run PHP code, and
 * you're ready to go. Connect to the database using connect() and get
 * a message using msg().
 *
 * Example usage:
 *
 * require_once 'path/to/i18n.php';
 * $i18n = new i18n();
 * $i18n->Database = 'DATABASE';
 * $i18n->Username = 'USER';
 * $i18n->Password = 'PASSWORD';
 * $i18n->msg('hello_world');
 *
 * @author Thomas Gneist <contact@thomasgneist.com>
 * @version 1.0
 * @access public
 * @see https://www.thomasgneist.com
 *
 * @copyright 2017 Thomas Gneist
 */

class i18n
{
    /**
     * MySQL host.
     * @var string
     */
    public $Host = 'localhost';

    /**
     * MySQL database
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
    public $Fallback = 'DE';

    /**
     * Enable or disable error logging.
     * @var bool
     */
    private $ErrorLog = true;

    /**
     * Webmaster email address.
     * @var string
     */
    public $Webmaster = '';


    /**
     * Connect to the MySQL database with PDO
     *
     * connect() is a function to connect to the MySQL database using PDO.
     * If a variable isn't set the function returns to the default global
     * mysql_ variables.
     *
     * @since 1.0 Sept 28th, 2017
     *
     * @see i18n::$Host
     * @see i18n::$Database
     * @see i18n::$Username
     * @see i18n::$Password
     * @return PDO
     */
    private function connect()
    {
        try {
            $pdo = new PDO('mysql:host='.$this->Host.';dbname='.$this->Database, $this->Username, $this->Password);
        } catch (PDOException $e) {
            $this->error($e);
        }

        return $pdo;
    }

    /**
     * Log a message.
     * @since 1.0 Sept 28th, 2017
     * @param string $message
     */
    private function error($message)
    {
        if($this->ErrorLog) {
            $message = str_replace(array("\r", "\n"), "", $message);
            error_log($message, 0);

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
     * Get and return the selected language
     *
     * getCurrentLanguage() uses the server name to get the current
     * language selected by the user.
     *
     * @since 1.0 Sept 28th, 2017
     *
     * @return string
     */
    public function getCurrentLanguage()
    {
        $lang = strtoupper(explode(".", $_SERVER['SERVER_NAME'])[0]);
        if($lang == 'WWW' || $lang == 'DEV' ) { $lang = 'DE'; }
        return $lang;
    }

    /**
     * Get a message from the database
     *
     * msg() returns the selected message in the right language. To
     * do so, it connects to the database via connect() and selects the right
     * table using getCurrentLanguage(). Then it selects the correct row using
     * the given key and returns the message. If no message was found, the
     * function returns the message in the default language or writes
     * "Oops! Something went wrong."
     *
     * @since 1.0 Sept 28th, 2017
     *
     * @see i18n::connect()
     * @see i18n::getCurrentLanguage()
     *
     * @param $id
     * @return string
     */
    public function msg($id)
    {
        $table = 'lang_'.$this->getCurrentLanguage();
        $pdo = $this->connect();

        try {
            $stmt = $pdo->prepare("SELECT `message` FROM `".$table."` WHERE `id` = '".$id."' LIMIT 1");
            $stmt->execute();
            $message = $stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->error($e);
        }

        if(empty($message)) {
            try {
                $stmt = $pdo->prepare("SELECT `message` FROM `lang_".$this->Fallback."` WHERE `id` = '".$id."' LIMIT 1");
                $stmt->execute();
                $message = $stmt->fetchColumn();
            }
            catch (PDOException $e) {
                $this->error($e);
            }
        }

        if(empty($message)) {
            $message = 'Oops! Something went wrong.';
        }

        return $message;
    }
}