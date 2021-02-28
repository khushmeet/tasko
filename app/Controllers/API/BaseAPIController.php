<?php

namespace App\Controllers\API;

use CodeIgniter\RESTful\ResourceController;

use CodeIgniter\API\ResponseTrait;

use App\Controllers\BaseController;

use App\Traits\ValidationTrait;

use Exception;

use \Firebase\JWT\JWT;


// headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control");

/**
 * Base API Controller.
 * This class is utilized by all of the controllers that are callable by API end points.
 */
class BaseAPIController extends BaseController
{

    use ResponseTrait;

    // Validate login, register etc
    use ValidationTrait;

    // JWT TOKEN EXPIRE TIME
    const TOKEN_EXPIRE = 3600;

    // JWT TOKEN KEY
    const KEY  = 'MtZUqbz4JD';

	/**
	 * On __construct
     * @TODO create transactionLog
	 */
	public function __construct()
	{
		
    }

    /**
    * Create Token 
    * @param  array $data
    * @return string Token 
    */
    protected function createToken(array $data) : string
    {

        // secret key
        $key = self::KEY;

        // token created timestamp
        $init = time();
        
        // token expiry time
        $exp = $init + self::TOKEN_EXPIRE;

        $payload = [
                "created" => $init,
                "exp" => $exp,
                "data" => $data,
            ];

        return JWT::encode($payload, $key);

    }

    /**
    * Decode Token exit if invalid or expired token
    * @param  array $data
    * @return string Token 
    */
    protected function decodeToken(string $token) 
    {

        // secret key
        $key = self::KEY;

        try {
            
            // decode the toekn
            $decoded = JWT::decode($token, $key, array("HS256"));
            
            return $decoded; 

        } catch (Exception $e) {
  
            $response = [
                    
                    'messages' => $e->getMessage(),
                    'status' => 400
                ];

            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }

    }

    /**
    * Get the token and check if its expired or not 
    */
    protected function fetchHeaders() 
    {

        $authHeader = $this->request->getHeader("Authorization");
        
        if(!$authHeader) {

            return $this->failValidationError('Request Failed');

        }

        // get token value from header
        $token = $authHeader->getValue();

        return  $this->decodeToken( $token );

    }

}
