<?php
/**
 * Created by PhpStorm.
 * User: tomi
 * Date: 07/07/15
 * Time: 20:47
 */

class Database {

    function __construct() {
        //nothing yet - maybe put code from connect() here later
    }

    static $_connection;

    public static function connect () {


        if (!self::$_connection) {
            self::$_connection = new mysqli(DB_HOST,DB_USER,DB_PASSWORD, DB_NAME);
        }
        if (!self::$_connection) {
            trigger_error('DB ERROR connecting to database: '.mysqli_connect_error(), E_USER_ERROR);
        }
        if (mysqli_connect_errno(self::$_connection)) {
            trigger_error('DB ERROR selecting database: '.mysqli_connect_error(), E_USER_ERROR);
        }

        return self::$_connection;
    }

    public static function query($sql_query) {
        $_connection = self::connect();

        $query_result=$_connection->query($sql_query);
        return $query_result;
    }

}

function database_query($sql_query) {
    return Database::query($sql_query);
}


/**
 * get the query column by its ID
 * if $column is set to (bool)true then entire row is returned
 */
function query_row_by_id($query_result, $column) {

    $result_row=mysqli_fetch_row($query_result);
    if ($column === true) {
        return $result_row;
    }

    if (isset($result_row[$column])) {
        return $result_row[$column];
    }
    else {
        return false;
    }
}

/**
 * get the query column by its name
 * if $column is set to (bool)true then entire row is returned
 */
function query_row_by_name($query_result, $column) {
    $result_row=mysqli_fetch_assoc($query_result);
    if ($column === true) {
        return $result_row;
    }

    if (isset($result_row[$column])) {
        return $result_row[$column];
    }
    else {
        return false;
    }
}

function db_fetch_array($query_result) {
    return mysqli_fetch_array($query_result);
}

function db_fetch_assoc($query_result) {
    return mysqli_fetch_assoc($query_result);
}

function db_fetch_row($query_result) {
    return mysqli_fetch_row($query_result);
}

function db_number_of_rows($query_result) {
    return mysqli_num_rows($query_result);
}

function db_escape_string($string) {
    $escaped_string=mysqli_real_escape_string(Database::connect(), $string);
    return $escaped_string;
}

function db_affected_rows() {
    return mysqli_affected_rows(Database::connect());
}

function db_returned_rows($query_result){
    return mysqli_num_rows($query_result);
}