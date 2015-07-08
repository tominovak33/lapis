<?php
/**
 * Created by PhpStorm.
 * User: tomi
 * Date: 07/07/15
 * Time: 21:33
 */

class Content {

    var $parameters = [];

    function __construct() {
    }

    function set_parameter($name, $value) {
        $this->parameters[$name] = $value;
    }

    function get_parameter($name) {
        return $this->parameters[$name];
    }

    function search_by($parameter_name) {
        $sql = sprintf("SELECT * FROM
            patterns WHERE
            %s = '%s'
            ;",
            $parameter_name,
            db_escape_string($this->get_parameter($parameter_name))
        );

        //var_dump($sql);
        $result = database_query($sql);

        $row = db_fetch_assoc($result);

        return $row;
    }

}
