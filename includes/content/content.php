<?php
/**
 * Created by PhpStorm.
 * User: tomi
 * Date: 07/07/15
 * Time: 21:33
 */

class Content {

    var $parameters = [];
    var $query_options = [];

    function __construct() {
    }

    function set_parameter($name, $value) {
        $this->parameters[$name] = $value;
    }

    function set_query_options($option_name, $option_value) {
        $this->query_options[$option_name] = $option_value;
    }

    function get_parameter($name) {
        return $this->parameters[$name];
    }

    function get_query_options($option_name) {
        return $this->query_options[$option_name];
    }

    function search_by($parameters) {
        $returned_items = [];

        $sql = sprintf("SELECT * FROM
            patterns WHERE
            %s = '%s'
            ",
            $parameters[0],
            db_escape_string($this->get_parameter($parameters[0]))
        );

        array_shift($parameters); //so the first item doesn't get added again

        if (count($parameters) > 0) {
            foreach($parameters as $parameter) {
                $sql .= sprintf(" AND
                    %s = '%s'
                    ",
                    $parameter,
                    db_escape_string($this->get_parameter($parameter))
                );
            }
        }

        $result = database_query($sql);

        while ($row = db_fetch_assoc($result)) {
            $returned_items[] = $row;
        }

        return $returned_items;
    }


}
