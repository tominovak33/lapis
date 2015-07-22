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

        // Are there any parametes - if yes, add them. If not select everything
        if (count($parameters) > 0) {
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
        }
        else {
          $sql = "SELECT * FROM patterns WHERE 1 ";
        }

        $sql = $this->add_options_to_query($sql);

        $result = database_query($sql);

        while ($row = db_fetch_assoc($result)) {
            $returned_items[] = $row;
        }

        return $returned_items;
    }

    function add_options_to_query($query) {
        if ($this->get_query_options('ORDER_BY')) {
            $query .= sprintf("ORDER BY patterns.%s %s ",
                db_escape_string($this->get_query_options('ORDER_BY')),
                db_escape_string($this->get_query_options('ORDER'))
            );
        }

        $query .= sprintf("LIMIT
            0, %s
            ",
            db_escape_string($this->get_query_options('LIMIT'))
        );

        return $query;
    }

    function insert($parameters) {
        if (count($parameters) < 4) {
            return false;
            //add to error feedback
        }
        $sql = sprintf("INSERT INTO
        patterns (
          %s, %s, %s, %s
        ) VALUES (
          '%s', '%s', '%s', '%s'
        )",
            db_escape_string($parameters[0]),
            db_escape_string($parameters[1]),
            db_escape_string($parameters[2]),
            db_escape_string($parameters[3]),
            db_escape_string($this->get_parameter($parameters[0])),
            db_escape_string($this->get_parameter($parameters[1])),
            db_escape_string($this->get_parameter($parameters[2])),
            db_escape_string($this->get_parameter($parameters[3]))
        );

        $result['successful'] = database_query($sql);
        $result['insert_id'] = db_last_ai_id();

        return $result;
    }

}
