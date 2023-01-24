<?php

class Attendees {

    public $response_code = 400;
    public $errors = [];
    public $response = [];

    public function __construct($sql) {
        $this->getList($sql);
    }

    private function getList($sql) {

        $query = "SELECT * FROM `attendee` ORDER BY timestamp ASC";

        if ($result = $sql->query($query)) {

            if ($result->num_rows > 0) {

                while ($row = $result->fetch_array()) {

                    $attendee = array(
                        "name" => $row['name'],
                        "email" => $row['email'],
                        "town" => $row['town'],
                        "timestamp" => $row['timestamp'],
                        "comment" => html_entity_decode($row['comment'], ENT_QUOTES, 'UTF-8')
                    );

                    array_push($this->response, $attendee);
                }

                $result->free();
            }

            $this->response_code = 200;

        } else {
            array_push($this->errors, "Couldn't execute query.");
        }
        $sql->close();
    }

}

?>