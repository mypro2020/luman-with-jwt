<?php
namespace App\Http\Helper;

class ResponseBuilder{
    public static function result($code=200,$status="",$info="",$data=""){
        return [
            'code'=>$code,
            'success'=>$status,
            'information'=>$info,
            'data'=>$data
        ];
    }
}