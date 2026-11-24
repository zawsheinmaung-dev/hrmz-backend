<?php

namespace App\Helpers;

class CodeGenerate
{
    static function generate(string $name,int $id,int $prefix) :string
    {
        return $name . str_pad($id,$prefix,'0',STR_PAD_LEFT);
    }
}