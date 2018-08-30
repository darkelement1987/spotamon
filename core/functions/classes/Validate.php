<?php
Namespace Spotamon;
use \voku\helper\antiXSS;
class Validate
{

    public $aXss;
    public $data;
    public function __construct()
    {
        $this->aXss = new antiXSS;

    }

    public function clean($input)
    {

        $result = $this->aXss->xss_clean($input);
        return $result;

    }

    public function getPost($key, $filter = null, $sanitized = true, $options = null, $default = null)
    {
        if (filter_has_var(INPUT_POST, $key)) {
            $data = $_POST[$key];
            if ($sanitized == true) {
                $data = $this->clean($data);
            }
            if ($filter !== null) {
                $data = $this->validate($data, $filter, $options);
            }
            return $data;
        } else {
            $data = $default;
        }
        return $data;
    }

    public function getGet($key, $filter = null, $sanitized = true, $options = null, $default = null)
    {
        if (filter_has_var(INPUT_GET, $key)) {
            $key = $_GET[$key];
            if ($sanitized === true) {
                $key = $this->clean($key);
            }
            if ($filter !== null) {
                $key = $this->validate($key, $filter, $options);
            }
            return $key;
        } else {
            $key = $default;
        }
        return $key;
    }

    public function setGet($key, $value = Null)
    {
        if (is_array($key) && is_array($value)) {
            $array = array_combine($key, $value);
            foreach( $array as $k => $v) {
                $_GET[$k] = $v;
            }
        } else if (is_array($key) && !is_array($value)) {
            foreach ($key as $k) {
                $_GET[$k] = $value;
            }
        } else {
            $_GET[$key] = $value;
        }
    }
    public function setPost($key, $value = Null)
    {
        if (is_array($key) && is_array($value)) {
            $array = array_combine($key, $value);
            foreach( $array as $k => $v) {
                $_POST[$k] = $v;
            }
        } else if (is_array($key) && !is_array($value)) {
            foreach ($key as $k) {
                $_POST[$k] = $value;
            }
        } else {
            $_POST[$key] = $value;
        }
    }
    public function getSession($key, $sanitize = null, $default = null)
    {
        if (isset($_SESSION[$key]) && !empty($_SESSION[$key])) {

            $key = $_SESSION[$key];

            if ($sanitize !== null) {
                $key = $this->clean($key);
            }
            return $key;
        }
        $key = $default;
        return $key;
    }

    public function setSession($key, $value = Null)
    {
        if ($value === null) {
            if (is_array($key)) {
                foreach($key as $k) {
                    if (isset($_SESSION[$k])) {
                        unset($_SESSION[$k]);
                        return true;
                    }
                }
            }
            if (isset($_SESSION[$key])) {
                unset($_SESSION[$key]);
                return true;
            }
        }
        if (is_array($key) && is_array($value)) {
            if (count($key) === count($value)) {
            $array = array_combine($key, $value);
            foreach( $array as $k => $v) {
                $_SESSION[$k] = $v;
            
            }}else{
                var_dump(['key' => $key, 'value' => $value]);
            }
        } else if (is_array($key) && !is_array($value)) {
            foreach ($key as $k) {
                $_SESSION[$k] = $value;
            }
        } else {
            $_SESSION[$key] = $value;
        }
    }
    public function validate($data, $filter, $options)
    {
        $origional = $data;
        switch ($filter) {
            case 'username':
            $regexp = "/^[a-zA-Z0-9-_#]{8,20}$|(?i)admin(?-i)/";
                if (filter_var($data, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => $regexp)))) {
                    $data = $data;
                    break;
                } else {
                    $data = "your username doesn't seem to meet standards.\nUsernames may be between 8 and 20 characters";
                    break;
                }
            case 'email':
                if (filter_var($data, FILTER_VALIDATE_EMAIL)) {
                    $data = $data;
                    break;
                }
                $data = $data . 'is not a valid ' . $filter;
                break;
            case 'url':
                if (filter_var($data, FILTER_VALIDATE_URL)) {
                    $data = $data;
                    break;
                }
                $data = $data . 'is not a valid ' . $filter;
                break;
            case 'bool':
                if (filter_var($data, FILTER_VALIDATE_BOOLEAN)) {
                    $data = $data;
                    break;
                }
                $data = $data . 'is not a valid ' . $filter;
                break;
            case 'int':
                if ($data === 0) {
                    $data = 0;
                    break;
                }
                if ($options = null) {
                    if (filter_var($data, FILTER_VALIDATE_INT)) {
                        $data = $data;
                        break;
                    }
                } elseif (is_array($options) && count($options) == 2) {
                    $options = ['min_range' => $options[0], 'max_range' => $options[1]];
                    if (filter_var($data, FILTER_VALIDATE_INT)) {
                        if (filter_var($data, FILTER_VALIDATE_INT, $options)) {
                            $data = $data;
                            break;
                        }
                        $data = $data . ' is an interger, but outside the range';
                        break;
                    }
                    $data = $data . 'is not an interger';
                    break;
                }
                $data = $data . 'is not a valid ' . $filter;
                break;
            case 'float':
                if (is_array($options) && count($options) == 2) {
                    $data = filter_var($data, FILTER_VALIDATE_FLOAT);
                    if ($data > $options[0] && $data < $options[1]) {
                        $data = $data;
                        break;
                    } else {
                        $data = $data . 'is a float, but outside the range';
                        break;
                    }
                } else {
                    $data = filter_var($data, FILTER_VALIDATE_FLOAT);
                    $data = $data;
                    break;
                }
            case 'password':
                if ($options !== null) {
                    if (count($options) == 2) {
                        if (password_verify($data, $options[1])) {
                            $data = $data;
                            break;
                        } else {
                            $data = 'password does not match hash';
                            break;
                        }
                    } else if (count($options) == 1) {
                        if ($data === $options) {
                            $data = $data;
                            break;
                        } 
                    }
                } else {
                    $passregexp = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[A-Za-z\d!$%@#£€*?&]{8,20}$|^admin$/";
                    if (filter_var($data, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => $passregexp)))) {
                        $data = $data;
                        break;
                    } else {
                        $data = "password does not meet standards.\nPlease include at least:\n 1 Capital Letter\n1 Lowercase Letter\n1 number\nSymbols are optional, but password must total 8 characters";
                        break;
                    }
                }
            default:
                $data = 'That is not a valid verification type';
                break;
        }
        if ($data != $origional) {
            $this->$data = $data;
            return false;
        } else {
            return $data;
        }
    }
}
