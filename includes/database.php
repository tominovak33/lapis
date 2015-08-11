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
    global $db_query_count;
    $db_query_count++;
    query_log($sql_query);
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

/*
 * Returns the auto incremented ID of the last insert or update query
 */
function db_last_ai_id() {
    return mysqli_insert_id(Database::connect());
}

function db_table_column_names($table_name) {
    $table_rows = false;
    $result = database_query("SHOW COLUMNS FROM " . $table_name);

    while ($row = db_fetch_assoc($result)) {
        $table_rows[] = $row['Field']; //Add the actual field name of the column
    }
    return $table_rows;
}

function add_table_column($table, $column, $type, $length) {
    $sql = "ALTER TABLE `$table` ADD `$column` $type($length) NOT NULL";
    
    $result = database_query($sql);
}

function list_tables() {
    $tables = false;
    $result = database_query("SHOW TABLES");

    while ($row = db_fetch_assoc($result)) {
        foreach ($row as $table_name) {
            $tables[] = $table_name;
        }
    }

    return $tables;
}
