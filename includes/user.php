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

  function __construct() {
    // coming soon
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

  function get_password_hash() {
    return $this->first_name;   
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

    $sql = 'SELECT * FROM api_users WHERE user_id=\'' . db_escape_string($this->user_id) . '\'';

    $result = database_query($sql);
    $row = db_fetch_assoc($result);

    return $this->populate_user_object($row);;
  }

  function populate_user_object($user_details) {
    if(isset($user_details["user_id"])) $this->set_user_id($user_details['user_id']);
    if(isset($user_details["username"])) $this->set_username($user_details['username']);
    if(isset($user_details["first_name"])) $this->set_first_name($user_details['first_name']);
    if(isset($user_details["last_name"])) $this->set_last_name($user_details['last_name']);
    if(isset($user_details["password"])) $this->set_password_hash($user_details['password']);

    return true;
  }

}
