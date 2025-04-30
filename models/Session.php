<?php

namespace uranium\model;

use uranium\core\Model;
use uranium\core\databaseDataTypes as type;
// use uranium\model\UserDetailsModel;

class Session extends Model{

    protected $tableName = "Session";

    public function __construct(){

        $this->addPrimary("id");
        
        $this->addCol("token", [
            "type"   => type::VARCHAR,
            "length" => 100,
            "null"   => false,
            "unique" => true
        ]);

        $this->addCol("userAgent",[
            "type"   => type::TEXT,
            "null"   => false
        ]);

        $this->addCol("userId", [
            "type"   => type::VARCHAR,
            "length" => 10,
            "null"   => false
        ]);

        $this->addCol("created", [
            "type"   => type::TIMESTAMP
        ]);

    }
}