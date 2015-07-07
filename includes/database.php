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

    public function connect () {

        $database_connection=mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if (mysqli_errno($database_connection)) {
            die("Failed to connect to database: " . mysqli_connect_error($database_connection));
        }

        return $database_connection;
    }

    public function database_query($database_connection, $sql_query) {
        $query_result=mysqli_query($database_connection, $sql_query);
        return $query_result;
    }

    /**
     * get the query column by its ID
     * if $column is set to (bool)true then entire row is returned
     */
    public function query_row_by_id($query_result, $column) {

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
    public function query_row_by_name($query_result, $column) {
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

    public function db_number_of_rows($query_result) {
        $rows=mysqli_num_rows($query_result);
        return $rows;
    }

    public function db_escape_string($database_connection, $string) {
        $escaped_string=mysqli_real_escape_string($database_connection, $string);
        return $escaped_string;
    }

    public function db_affected_rows($database_connection) {
        return mysqli_affected_rows($database_connection);
    }

    public function db_returned_rows($query_result){
        return mysqli_num_rows($query_result);
    }


}