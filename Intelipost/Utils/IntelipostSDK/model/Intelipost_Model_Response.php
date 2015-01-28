<?php
/**
 * @author Intelipost (it@intelipost.com.br)
 *
 */

class Intelipost_Model_Response {
    public $status;
    public $messages;
    public $content;
    public $time;

    public function __construct($response=false)
    {
        if ($response) {
            $response = json_decode($response);

            foreach ($response as $key => $value) {
                $this->$key = $value;
            }
        }
    }
}