<?php
/**
 * i18n - PHP internationalization class.
 *
 * @see https://github.com/thomasgneist/i18n-php GitHub project
 *
 * @author Thomas Gneist <thomas@thomasgneist.com>
 * @copyright 2017 Thomas Gneist
 * @License MIT License
 *
 * @version v1.3
 */
class i18n
{
    /**
     * @since v1.3 Nov 13th, 2017
     * @var bool
     */
    protected $enable_error_log = true;

    /**
     * @since v1.3 Nov 13th, 2017
     * @var bool
     */
    protected $enable_lang_fallback = true;

    /**
     * @since v1.3 Nov 13th, 2017
     * @var string
     */
    protected $lang_detection_type = 'cookie';

    /**
     * @since v1.3 Nov 13th, 2017
     * @var string
     */
    protected $lang_db_table = 'messages';

    /**
     * @since v1.3 Nov 13th, 2017
     * @var string
     */
    protected $lang_fallback = 'en';

    /**
     * @since v1.3 Nov 13th, 2017
     * @var PDO
     */
    protected $pdo;

    /**
     * i18n constructor.
     * @since v1.2 Oct 10th,2017
     *
     * @param $username
     * @param $password
     * @param $database
     * @param string $host
     */
    function __construct($username, $password, $database, $host = 'localhost')
    {
        try {
            $this->pdo = new PDO('mysql:host=' . $host . ';dbname=' . $database, $username, $password);
        } catch (PDOException $exception) {
            $this->display_error('dbconnection', $exception);
        }
    }

    /**
     * Enable or disable fallback language.
     * @since v1.3 Nov 13th, 2017
     * @param bool $enable
     * @return bool
     */
    public function enableFallbackLanguage($enable = false)
    {
        $this->enable_lang_fallback = $enable;
        return true;
    }

    /**
     * Get the fallback language.
     * @since v1.0 Oct 1st, 2017
     * @return string
     */
    public function getFallbackLanguage()
    {
        if($this->enable_lang_fallback) {
            return $this->lang_fallback;
        }

        return "Fallback language disabled!";
    }

    /**
     * Get and return the selected language
     * @since v1.0 Oct 1st, 2017
     * @return string
     */
    public function getLanguage()
    {
        switch ($this->lang_detection_type):
            case 'cookie':
                if(isset($_COOKIE['lang']) && is_string($_COOKIE['lang'])) {
                    $lang = $_COOKIE['lang'];
                }
                break;
            case 'domain':
                $lang = explode('.', $_SERVER['SERVER_NAME'])[0];
                if($lang == 'www' || $lang = 'dev') { $lang = ''; }
                break;
            case 'fallback':
                $lang = $this->lang_fallback;
                break;
            case 'session':
                if(isset($_SESSION['lang']) && is_string($_SESSION['lang'])) {
                    $lang = $_SESSION['lang'];
                }
                break;
        endswitch;

        if(empty($lang)) {
            if($this->lang_fallback) {
                $lang = $this->lang_fallback;
            } else {
                $this->display_error('nodetection', $this->lang_detection_type);
                return null;
            }
        }

        return $lang;
    }

    /**
     * Get and return a message from the database
     * @since v1.0 Sept 28th, 2017
     *
     * @param string $id
     * @param array $replace
     *
     * @return string
     */
    public function msg($id, $replace = array())
    {
        $column = $this->getLanguage();
        $table = $this->lang_db_table;
        $pdo = $this->pdo;

        try {
            $stmt = $pdo->prepare("SELECT `" . $column . "` FROM `" . $table . "` WHERE `id` = '" . $id . "' LIMIT 1");
            $stmt->execute();
            $message = $stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->display_error('mysql', $e);
        }

        if(empty($message) && $this->enable_lang_fallback) {
            try {
                $stmt = $pdo->prepare("SELECT `" . $this->lang_fallback . "` FROM `" . $table . "` WHERE `id` = '".$id."' LIMIT 1");
                $stmt->execute();
                $message = $stmt->fetchColumn();
            } catch (PDOException $e) {
                $this->display_error('mysql', $e);
            }
        }

        if(empty($message)) {
            $message = ' <b>i18n-php</b>: Invalid ID <i>' . $id . '</i>!';
        }

        if(is_array($replace) && !empty($replace)) {
            $message = vsprintf($message, $replace);
        }

        return $message;
    }

    /**
     * Set a language detection type.
     * @since v1.3 Nov 13th, 2017
     * @param $type
     * @return bool|null
     */
    public function setLangDetectionType($type)
    {
        $allowed_types = array('cookie', 'domain', 'fallback', 'session');
        if(in_array($type, $allowed_types)) {
            $this->lang_detection_type = $type;
            return true;
        }

        $this->display_error('langdetection', 'Unknown type <i>' . $type . '</i>!');
        return null;
    }

    /**
     * Set a database table name.
     * @since v1.3 Nov 13th, 2017
     * @param $table
     * @return bool
     */
    public function setDatabaseTable($table)
    {
        $this->lang_db_table = $table;
        return true;
    }

    /**
     * Set the fallback language.
     * @since v1.3 Nov 13th, 2017
     * @param $lang
     * @return bool
     */
    public function setFallbackLanguage($lang)
    {
        $this->lang_fallback = $lang;
        return true;
    }

    /**
     * Display an error and prevent everything after.
     * @since v1.3 Nov 13th, 2017
     *
     * @param string $type
     * @param string $exception
     */
    private function display_error($type, $exception = '')
    {
        $error = '<p><b>i18n-php</b>: An error occurred!<br>';

        switch ($type):
            case 'dbconnection':
                $error .= 'Unable to connect to the database.';
                $error .= '</p>';
                $error .= '<p>' . $exception . '</p>';
                break;
            case 'langdetection':
                $error .= 'Invalid language detection type set.';
                $error .= '</p>';
                $error .= '<p>' . $exception . '<br>';
                $error .= '<b>Available types</b>: cookie, domain, fallback, session</p>';
                break;
            case 'mysql':
                $error .= 'MySQL error occurred.';
                $error .= '</p>';
                $error .= '<p>' . $exception . '</p>';
                break;
            case 'nodetection':
                $error .= 'Could not detect a language.';
                $error .= '</p>';
                $error .= '<p><i>i18n-php</i> could not detect a language<br>';
                $error .= 'Set language detection type: <i>' . $exception . '</i></p>';
                break;
        endswitch;

        $error .= '<hr>';
        $error .= '<p>Report an error at <a href="https://github.com/thomasgneist/i18n-php">https://github.com/thomasgneist/i18n-php</a> if the problem was caused by <i>i18n-php</i>.</p>';

        die($error);
    }
}