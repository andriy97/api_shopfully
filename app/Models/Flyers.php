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
        $notAllowedFilters=isset(request()->query()['filter']) ? array_diff_key( request()->query()['filter'], array_flip($allowedFilters)) : array();
    

        $flyers = QueryBuilder::for(Flyers::class)
        //default params
        ->where('start_date', '<=', $now)
        ->where('end_date', '>=', $now)
        //filters
        ->allowedFilters($allowedFilters)
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
            ], 200);;
        
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
                    "debug"=>"No data found with these filters"
                )
            ],200);
    }


}
