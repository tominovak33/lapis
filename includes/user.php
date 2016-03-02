<?php
/**
 * Created by PhpStorm.
 * User: tomi
 * Date: 06/06/15
 * Time: 22:30
 */

class User {

    var $user_id;
    var $username;
    var $first_name;
    var $last_name;
    var $email;
    var $password_hash;
    var $permission_level = 0; // coming soon
    var $token;

    function __construct($userID = false) {
        if ($userID) {
            $this->user_id = $userID;
            $this->get_user();
            return $this;
        }
        $auth = $this->register();
        if ($auth) {
            $this->set_access_token($auth['token']);
        } else {
            return false;
        }
    }

    function set_user_id($new_user_id) {
        $this->user_id = $new_user_id;
    }

    function get_user_id() {
        return $this->user_id;
    }

    function set_username($new_username) {
        $this->username = $new_username;
    }

    function get_username() {
        return $this->username;
    }

    function set_first_name($new_first_name) {
        $this->first_name = $new_first_name;
    }

    function get_first_name() {
        return $this->first_name;
    }

    function set_last_name($new_last_name) {
        $this->last_name = $new_last_name;
    }

    function get_last_name() {
        return $this->last_name;
    }

    function set_password_hash($new_password_hash) {
        $this->password_hash = $new_password_hash;
    }

    function set_access_token($token) {
        $this->token = $token;
    }

    function get_access_token() {
        return $this->token;
    }

    function get_password_hash() {
        return $this->password_hash;
    }

    function get_request_username() {
        return $_POST['REQUEST_USERNAME'];
    }

    function get_request_password() {
        return $_POST['REQUEST_PASSWORD'];
    }

    function get_user() {
        if (!isset($this->user_id)) {
            return false;
        }

        $sql = sprintf("SELECT * FROM " .API_USERS_TABLE. " WHERE id = '%s'",
            db_escape_string($this->user_id)
        );

        $result = database_query($sql);

        $row = db_fetch_assoc($result);

        return $this->populate_user_object($row);
    }

    static function getUserSalt($username) {
        if ($username == false) {
            return false;
        }

        $sql = sprintf("SELECT passwordExtraSalt FROM " .API_USERS_TABLE. " WHERE username = '%s'",
            db_escape_string($username)
        );

        $result = database_query($sql);

        $row = db_fetch_assoc($result);

        return $row['passwordExtraSalt'];
    }

    function register() {
        $username = $this->get_request_username();
        $password = $this->get_request_password();
        $randomSalt = generateExtraSalt();
        $pepper = PASSWORD_EXTRA_SALT;

        $salt = generateUserSalt($randomSalt, $pepper);
        $password = generateHashedPassword($password, $salt);

        $sql = sprintf("INSERT INTO " .API_USERS_TABLE. " (username, passwordExtraSalt, password) VALUES ('%s', '%s', '%s')",
            db_escape_string($username),
            db_escape_string($randomSalt),
            db_escape_string($password)
        );

        database_query($sql);
        if (db_affected_rows() == 1) {
            $this->set_user_id(db_last_ai_id());
            $this->get_user();
            $token = insertToken($this->get_user_id(), generateAccessToken());
            return array('token' => $token, 'userID' => $this->get_user_id());
        }
        return false;
    }

    function populate_user_object($user_details) {
        if(isset($user_details["id"])) $this->set_user_id($user_details['id']);
        if(isset($user_details["username"])) $this->set_username($user_details['username']);
        if(isset($user_details["first_name"])) $this->set_first_name($user_details['first_name']);
        if(isset($user_details["last_name"])) $this->set_last_name($user_details['last_name']);
        if(isset($user_details["password"])) $this->set_password_hash($user_details['password']);

        return true;
    }

}
