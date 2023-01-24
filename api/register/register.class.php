<?php
/**
 * Register.
 *
 * One can register for an event.
 *
 */
class Register {

    private $name;
    private $email;
    private $city;
    private $timestamp;
    private $comment;
    public $response_code = 400;
    public $errors = [];

    public function __construct($name, $email, $city, $timestamp, $comment, $sql) {

        $this->name = $this->validateName($name, 50, "Name");
        $this->email = $this->validateEmail($email, 200);
        $this->city = $this->validateName($city, 50, "City");
        $this->timestamp = $this->validateTimestamp($timestamp);
        $this->comment = $this->validateComment($comment, 200);

        if ( !count($this->errors) ) {

            //$this->response_code = 200; // tmp
            if ( $this->insertData($sql) ) {
                $this->response_code = 200;
            } else {
                $this->addError("Database query failed.");
            }
            
        }

    }

    private function insertData($sql) {
        $query = sprintf("
            INSERT INTO `attendee`
            (`name`, `email`, `town`, `timestamp`, `comment`)
            VALUES ('%s', '%s', '%s', FROM_UNIXTIME({$this->timestamp}), '%s')",
            $this->name,
            $this->email,
            $this->city,
            $this->comment);

        return $sql->query($query);
    }

    private function addError($error) {
        array_push($this->errors, $error);
        return false;
    }

    private function validateComment($comment, $max_length) {
        if ( strlen($comment) ) {
            $comment = str_replace(['<','>'], '', $comment);
            $comment_sanitized = htmlentities($comment, ENT_QUOTES, 'UTF-8');
            if ( $this->validateLength($comment_sanitized, $max_length) ) {
                return $comment_sanitized;
            }
        } else {
            return "";
        }
        return $this->addError("Comment validation error.");
    }

    private function validateName($name, $max_length, $source) {
        if ( $this->validateLength($name, $max_length) ) {
            if (preg_match ("/^[\p{L}\s]*$/u", $name) ) {     
                return trim($name);
            }
        }
        return $this->addError("{$source} validation error.");
    }

    private function validateEmail($email, $max_length) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if ( $this->validateLength($email, $max_length) ) {
                    return $email;    
            }
        }
        return $this->addError("Email validation error.");
    }

    private function validateLength($string, $max_length) {
        $strlen = strlen($string);
        return $strlen > 0 && $strlen <= $max_length;
    }

    private function validateTimestamp($timestamp) {
        if ( is_numeric($timestamp) ) {
            $current_timestamp = time();
            $five_days_from_today = strtotime('+5 days', $current_timestamp);
            if ( $timestamp >= $current_timestamp &&
                 $timestamp <= $five_days_from_today ) {
                return $timestamp;
            }
        }
        return $this->addError("Timestamp validation error.");
        
    }

}

?>