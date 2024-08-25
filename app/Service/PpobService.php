<?php

namespace App\Service;

use App\Traits\RequestService;
use Illuminate\Support\Facades\Log;

class PpobService
{
    use RequestService;

    public $baseUri;


    public static $clientKey = '4384d86e25bb';
    public static $clientSecret = 'c525242a12b8';

    public function __construct()
    {
        $this->baseUri = config('services.ppob.base_url');
    }

    public function pobLoginAuth($data)
    {
        return $this->request('POST','/api/v1/auth/client/login',$data);
    }

    public function pobGetProductProvider($data)
    {
        return  $this->request('POST','/api/v1/ppob/product/get_product_provider',$data);
        
    }

    public function pobTransaksiPulsa($data)
    {
        try {
            return $this->request('POST', '/api/v1/ppob/pulsa/transaction', $data);
        } catch (\Exception $e) {
            // Log exception    
            Log::error($e);      
            $statusCode = 500; // Internal Server Error
            return $statusCode;
        }
    }


    public function pobCekSaldo($data)
    {
        try {
            return $this->request('POST', '/api/v1/ppob/check_balance', $data);
        } catch (\Exception $e) {
            // Log exception    
            Log::error($e);      
            $statusCode = 500; // Internal Server Error
            return $statusCode;
        }
    }

    public function ppobEmoneyCheck($data)
    {
        try {
            return  $this->request('POST', '/api/v1/ppob/emoney/check', $data);
        } catch (\Exception $e) {
            // Log exception    
            Log::error($e);      
            $statusCode = 500; // Internal Server Error
            return $statusCode;
        }

    }

    public function ppobEmoneyPayment($data)
    {
        try {
            return  $this->request('POST', '/api/v1/ppob/emoney/payment', $data);
        } catch (\Exception $e) {
            // Log exception    
            Log::error($e);      
            $statusCode = 500; // Internal Server Error
            return $statusCode;
        }
    }

    public function topUpSaldo($data)
    {
        return  $this->request('POST', '/api/v1/ppob/check_balance', $data);
    }

    public function ppobProductPdam($data)
    {
        return  $this->request('POST', '/api/v1/ppob/pdam', $data);
    }

    public function ppobProductPdamInquiry($data)
    {
        try {
            return  $this->request('POST', '/api/v1/ppob/pdam/inquiry', $data);
        } catch (\Exception $e) {
            // Log exception    
            Log::error($e);      
            $statusCode = 500; // Internal Server Error
            return $statusCode;
        }
    }

    public function ppobProductPdamPayment($data)
    {
        try {
            return  $this->request('POST', '/api/v1/ppob/pdam/payment', $data);
        } catch (\Exception $e) {
            // Log exception    
            Log::error($e);      
            $statusCode = 500; // Internal Server Error
            return $statusCode;
        }
    }

    public function ppobProductPlnPascaInquiry($data)
    {
        try {
            return  $this->request('POST', '/api/v1/ppob/pln/pasca/inquiry', $data);
        } catch (\Exception $e) {
            // Log exception    
            Log::error($e);      
            $statusCode = 500; // Internal Server Error
            return $statusCode;
        }
    }

    public function ppobProductPlnPascaPayment($data)
    {
        try {
            return  $this->request('POST', '/api/v1/ppob/pln/pasca/payment', $data);
        } catch (\Exception $e) {
            // Log exception    
            Log::error($e);      
            $statusCode = 500; // Internal Server Error
            return $statusCode;
        }
    }

    public function ppobProductPlnPrabayarInquiry($data)
    {
        return  $this->request('POST', '/api/v1/ppob/pln/prabayar/inquiry', $data);
    }
    
    public function ppobProductPlnPrabayarPayment($data)
    {
        try {
            return  $this->request('POST', '/api/v1/ppob/pln/prabayar/payment', $data);
        } catch  (Exception $e) {
            // log exception
            Log::error($e);
            $statusCode = 500;
            return $statusCode;
        } 
    }

    public function ppobProductBpjsInquiry($data)
    {
        try
        {
            return  $this->request('POST', '/api/v1/ppob/bpjs/kesehatan/inquiry', $data);
        } catch (\Exception $e) {
            // log exception
            Log::error($e);
            $statusCode = 500;
            return $statusCode;
        }
    }

    public function ppobProductBpjsPayment($data)
    {
        return  $this->request('POST', '/api/v1/ppob/bpjs/kesehatan/payment', $data);
    }
}
