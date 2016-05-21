<?
class CStorage {
    public static $dump = array();
    public static function getVar ($id) {
        if (isset(static::$dump[$id])){
            return static::$dump[$id];
        }
        return false;
    }
    public static function setVar ($var, $id = false) {
        if (!$id) {
            $id = uniqid();
        }
        static::$dump[$id] = $var;
        return $id;
    }
    public static function setParam ($id, $key, $value) {
        if (is_array(static::$dump[$id])) {
            $dump[$id][$key] = $value;
        }
    }
    public static function getParam ($id, $key) {
        if (is_array(static::$dump[$id])) {
            return static::$dump[$id][$key];
        } else {
            return false;
        }
    }
    public static function removeParam ($id, $key) {
        if (is_array(static::$dump[$id])) {
            unset (static::$dump[$id][$key]);
            return true;
        } else {
            return false;
        }
    }
    public static function getNames () {
        if (count(static::$dump) > 0) {
            return array_keys(static::$dump);
        } else {
            return NULL;
        }
    }

    public static function setCookieParam ($keycode, $value) {
        return setcookie($keycode,$value,86400+time());
    }

    public static function getCookieParam ($keycode, $default_value = false) {
        if (isset($_COOKIE[$keycode]))
            return $_COOKIE[$keycode];
        else return $default_value;
    }

    public static function getUserInput($keycode, $default = false) {
        if (isset($_REQUEST[$keycode])) {
            static::setCookieParam($keycode,$_REQUEST[$keycode]);
            return $_REQUEST[$keycode];
        } else if (isset($_COOKIE[$keycode])) {
            return $_COOKIE[$keycode];
        } else {
            if ($default && is_scalar($default)) {
                static::setCookieParam($keycode,$default);
                return $default;
            }
            return false;
        }
    }
}
?>