<?php

class Validation
{

    public $aXss;

    public function __construct()
    {

        $this->aXss = new AntiXSS;
    }

    public function clean($input)
    {

        $result = $this->aXss->xss_clean($input);
        return $result;

    }

    public function getPost($key, $sanitized = true, $validation = null, $options = null)
    {
        if (filter_has_var(INPUT_POST, $key)) {
            $data = $_POST[$key];
            if ($sanitized == true) {
                $data = $this->clean($data);
                return $data;
            }
            if ($validation !== null) {
                $data = $this->validate($data, $validation, $options);
            }
            return $data;
        } else {
            $data = $default;
        }
        return $data;
    }

    public function getGet($key, $sanitized = true, $validation = null, $options = null, $default = null)
    {
        if (filter_has_var(INPUT_GET, $key)) {
            $key = $_GET[$key];
            if ($sanitized === true) {
                $key = $this->clean($key);
            }
            if ($validation !== null) {
                $key = $this->validate($key, $validation, $options);
            }
            return $key;
        } else {
            $key = $default;
        }
        return $key;
    }

    public function setGet($key, $value)
    {
        $_GET[$key] = $value;
    }
    public function getSession($key, $sanitized = true, $default = null, $validation = null, $options = null)
    {
        if (isset($_SESSION[$key]) && !empty($_SESSION[$key])) {
            $key = $_SESSION[$key];
            if ($sanitized === true) {
                $key = $this->clean($key);
            }
            if ($validation !== null) {
                $key = $this->validate($key, $validation, $options);
            }
            return $key;
        }
        $key = $default;
        return $key;
    }

    public function setSession($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    public function validate($data, $filter, $options)
    {

        switch ($filter) {
            case 'username':
                if (filter_var($data, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "^[a-zA-Z0-9]([._](?![._])|[a-zA-Z0-9]){6,18}[a-zA-Z0-9]$")))) {
                    return $data;
                    break;
                } else {
                    return "your username doesn't seem to meet standards.\nUsernames may be between 8 and 20 characters";
                    break;
                }
            case 'email':
                if (filter_var($data, FILTER_VALIDATE_EMAIL)) {
                    return $data;
                    break;
                }
                return $data . 'is not a valid ' . $filter;
                break;
            case 'url':
                if (filter_var($data, FILTER_VALIDATE_URL)) {
                    return $data;
                    break;
                }
                return $data . 'is not a valid ' . $filter;
                break;
            case 'bool':
                if (filter_var($data, FILTER_VALIDATE_BOOLEAN)) {
                    return $data;
                    break;
                }
                return $data . 'is not a valid ' . $filter;
                break;
            case 'int':
                if ($data === 0) {
                    return 0;
                    break;
                }
                if ($options = null) {
                    if (filter_var($data, FILTER_VALIDATE_INT)) {
                        return $data;
                        break;
                    }
                } elseif (is_array($options) && count($options) == 2) {
                    $options = ['min_range' => $options[0], 'max_range' => $options[1]];
                    if (filter_var($data, FILTER_VALIDATE_INT)) {
                        if (filter_var($data, FILTER_VALIDATE_INT, $options)) {
                            return $data;
                            break;
                        }
                        return $data . ' is an interger, but outside the range';
                        break;
                    }
                    return $data . 'is not an interger';
                    break;
                }
                return $data . 'is not a valid ' . $filter;
                break;
            case 'float':
                if (is_array($options) && count($options) == 2) {
                    $data = filter_var($data, FILTER_VALIDATE_FLOAT);
                    if ($data > $options[0] && $data < $options[1]) {
                        return $data;
                        break;
                    } else {
                        return $data . 'is a float, but outside the range';
                        break;
                    }
                } else {
                    $data = filter_var($data, FILTER_VALIDATE_FLOAT);
                    return $data;
                    break;
                }
            case 'password':
                if ($options !== null) {
                    if (count($options) == 2) {
                        if (password_verify($data, $options[1])) {
                            return $data;
                            break;
                        } else {
                            return 'password does not match hash';
                            break;
                        }
                    } else if (count($options) == 1) {
                        if ($data === $options) {
                            return $data;
                            break;
                        } else {
                            if (filter_var($data, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "^\S*(?=\S{8,})(?=\S*[a-z])(?=[\S\W]*)(?=\S*[A-Z])(?=\S*[\d])\S*$")))) {
                                return $data;
                                break;
                            } else {
                                return 'password does not meet standards.\nPlease include at least:\n 1 Capital Letter\n1 Lowercase Letter\n1 number\nSymbols are optional, but password must total 8 characters';
                                break;
                            }
                        }
                    }
                }
            default:
                return 'That is not a valid verification type';
                break;
        }
    }

}
