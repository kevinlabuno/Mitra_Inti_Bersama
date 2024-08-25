<?php

namespace App\Service;

use App\Models\ProductProviders;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProductProviderServices
{
    public static function saveProducts($dataArray)
    {

        foreach ($dataArray['content'] as $data) {
            ProductProviders::create($data);
        }

        return true;
    }
}

 // return DB::table('products_provider_indosats')->insert([
        //     'provider'            => $request['provider'],
        //     'product_code'        => $request['productCode'],
        //     'product_name'        => $request['productName'],
        //     'price'               => $request['price'],
        //     // 'feeAgen'             => $request['feeAgen'],
        //     // 'sellingPrice'        => $request['message'],
        //     'type'                => 1,
        //     'status'              => $request['type'],
        //     'created_at'          => Carbon::now()
        // ]);
