<?php

namespace App\Databases;

interface DatabaseInterface {
    public function read();
    public function write($data):bool;
}