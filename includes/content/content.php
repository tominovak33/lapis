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
        $this->database_table = $this->set_content_db_table();
        $this->query_options['ORDER_BY'] = false;
        $this->query_options['ORDER'] = 'ASC';
        $this->query_options['LIMIT'] =  50;
        $this->strict_columns = unserialize (CONTENT_STRICT_PROPERTIES_ARRAY); // Constant cannot be an array (in PHP < 5.6) so we store it serialised and then unserialise it
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

    function remove_parameter($name) {
        unset($this->parameters[$name]);
    }

    function get_query_options($option_name) {
        return $this->query_options[$option_name];
    }

    function get_possible_parameters() {
      return $this->options(); // Currenty just return the table cols, but later when multiple tables are involved it will be more complex - so making new functio
    }

    function set_content_db_table() {
      $content_table = CONTENT_TABLE_NAME;

      if (isset($_GET['CONTENT_TABLE'])) {
        $content_table = $_GET['CONTENT_TABLE'];
        unset($_GET['CONTENT_TABLE']);
      }
      elseif (isset($_POST['CONTENT_TABLE'])) {
        $content_table = $_POST['CONTENT_TABLE'];
        unset($_POST['CONTENT_TABLE']);
      }

      return $content_table;
    }

    function valid_content_parameters($parameters) {
      $counter = -1; // Due to possible early skip, counter is incremented at start of loop, before logic so this way the counter will be 0 (for 0 indexed array work) in the first loop
      $possible_parameters = $this->get_possible_parameters();

      foreach ($parameters as $param) {
          $counter++;
          // Is the parameter a possible parameter for the current content?
          if (!in_array($param, $possible_parameters)) {
              // If not, remove it as otherwise it breaks the query as nothing would match
              unset($parameters[$counter]); // Get rid of the option so it doesn't interfere later
              continue; // Skip next part of the foreach as the param is now irrelevant
          }
      }
      return $parameters;
    }

    function search_by($parameters) {
        $returned_items = [];

        // Most params should be loosely checked, so:  WHERE `col_name` LIKE  '%value%' however if the column is something like an ID (eg: id or author id) then preform an exact match
        // So check for parameters containing ID and add the exact match for those.
        // Columns to perform strict matches on should be defined as constants or added as object properties
        $strict_sql = '';

        $parameters = $this->valid_content_parameters($parameters);

        foreach ($parameters as $strict_column) {
            if ((in_array($strict_column, $this->strict_columns)) && ($this->get_parameter($strict_column) != false)) {
                //unset($parameters[$counter]); //Get rid of the option so it doesn't interfere later
                $strict_sql .= sprintf(" AND
                      %s = '%s'
                      ",
                    $strict_column,
                    db_escape_string($this->get_parameter($strict_column))
                );
            }
        }

        // Are there any parameters - if yes, add them. If not select everything
        if (count($parameters) > 0) {
            $sql = "SELECT * FROM " . $this->database_table . " WHERE";

            // todo stop that sprintf from looking so ridiculous
            $sql .= sprintf(" %s LIKE '%%%s%%' ",
              $parameters[0],
              db_escape_string($this->get_parameter($parameters[0]))
          );

          array_shift($parameters); //so the first item doesn't get added again

          if (count($parameters) > 0) {
              foreach($parameters as $parameter) {
                  // todo stop that sprintf from looking so ridiculous
                  $sql .= sprintf(" AND
                      %s LIKE '%%%s%%'
                      ",
                      $parameter,
                      db_escape_string($this->get_parameter($parameter))
                  );
              }
          }
        }
        else {
          $sql = "SELECT * FROM $this->database_table WHERE 1 ";
        }

        $sql .= $strict_sql;

        $sql = $this->add_options_to_query($sql);

        $result = database_query($sql);

        while ($row = db_fetch_assoc($result)) {
            $returned_items[] = $row;
        }

        return $returned_items;
    }

    function add_options_to_query($query) {
        if ($this->get_query_options('ORDER_BY') != false) {
            $query .= sprintf("ORDER BY $this->database_table.%s %s ",
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

    /*
    Checks to see if an item in a query already exists in the DB or not
    */
    function item_exists() {
      if ($this->get_parameter('id') != false) {
        //item id specified
        $sql = sprintf("SELECT * FROM
            $this->database_table WHERE
            id = '%s'
            ",
            db_escape_string($this->get_parameter('id'))
        );

        $result = database_query($sql);

        if (db_number_of_rows($result) > 0) {
          return true;
        }
        else {
            $this->set_parameter('id', false); // The id was specified but an item with that Id doesn't exist so remove the ID from the list of parameters so it doesn't interfere (this situation should not really happen) and insert it with an AI id
        }
      }
      return false;
    }

    function insert($parameters) {
        // If the ID was set, see if an item with that ID exists or not
        // If it does then update that item rather than inserting a new one
        if ($this->item_exists() !== false ) {
            $result = $this->update($parameters); //coming soon
            return $result;
        }

        $parameters = $this->valid_content_parameters($parameters);

        if (count($parameters) < 4) {
            return false;
            //add to error feedback
        }

        $sql = sprintf("INSERT INTO
        $this->database_table (
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

    function update($parameters) {
        $id = $this->get_parameter('id');
        unset($parameters['id']); //Get rid of the ID so it doesn't interfere later

        $sql = "UPDATE $this->database_table SET ";

        if (count($parameters) > 0) {
            foreach($parameters as $parameter) {
              if ($parameter == 'id') {
                continue;
              }
              $sql .= sprintf("%s = '%s', ",
                  $parameter,
                  db_escape_string($this->get_parameter($parameter))
              );
            }
            $sql = rtrim($sql, ', ');
        }

        $sql .= " WHERE id = $id ";
        
        $result['successful'] = database_query($sql);

    }

    function options() {
        return db_table_column_names($this->database_table);
    }

    function get_strict_columns() {
      if (isset($_GET['STRICT'])) {
          $strict_string = $_GET['STRICT'];
          $strict_string = rtrim($strict_string, ',');
          $strict_column_names = explode(',', $strict_string);
          foreach ($strict_column_names as $strict_column_name) {
              $this->strict_columns[] = $strict_column_name;
          }
      }
    }

    function count_matching_items($parameters) {
      $matching_items = $this->search_by($parameters);
      return count($matching_items);
    }

}
