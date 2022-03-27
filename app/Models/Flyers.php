<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\QueryBuilder;



class Flyers extends Model
{
    use HasFactory;
    
    static function getFlyers(){
        
        $now=date('Y-m-d');
        $limit=isset(request()->query()['limit']) ? (int)request()->query()['limit'] : 100; //default 100
        $allowedFilters=array('is_published', 'category');
        $allowedFields=array('id', 'title', 'start_date', 'end_date', 'is_published','retailer', 'category');
        //check if query contains not allowed filters
        $notAllowedFilters=isset(request()->query()['filter']) ? array_diff_key( request()->query()['filter'], array_flip($allowedFilters)) : array();
        //check if query contains not allowed fields
        $notAllowedFields=isset(request()->query()['fields']['flyers']) ? array_diff_key( array_flip(explode(",", request()->query()['fields']['flyers'])), array_flip($allowedFields)) : array();
    

        //response for not allowed fields
        if(sizeOf($notAllowedFields)>0)
        return response()->json([
            "success"=> false,
            "code"=> 400,
            "error"=> array(
                "message"=>"Bad Request",
                "debug"=>"Not allowed fields: ".implode(", ",array_keys($notAllowedFields))
            )
        ], 200);

        $flyers = QueryBuilder::for(Flyers::class)
        //default params
        ->where('start_date', '<=', $now)
        ->where('end_date', '>=', $now)
        //filters
        ->allowedFilters($allowedFilters)
        //request specific fields
        ->allowedFields($allowedFields)
        ->paginate($limit)
        ->appends(request()->query());

        //response for not allowed filters
        if(sizeOf($notAllowedFilters)>0)
            return response()->json([
                "success"=> false,
                "code"=> 400,
                "error"=> array(
                    "message"=>"Bad Request",
                    "debug"=>"Not allowed filters: ".implode(", ",array_keys($notAllowedFilters))
                )
            ], 200);

       
        
        //successful response
        $is_empty=$flyers->getCollection()->isEmpty();
        if(!$is_empty)
            return response()->json([
                "success"=> true,
                "code"=> 200,
                "results"=> $flyers->getCollection()
            ],200);
        else//no matching
            return response()->json([
                "success"=> false,
                "code"=> 404,
                "error"=> array(
                    "message"=>"Not Found",
                    "debug"=>"No data found"
                )
            ],200);
    }


}
