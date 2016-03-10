<?php

/**
 * Created by PhpStorm.
 * User: manuelpineault
 * Date: 2016-03-09
 * Time: 11:40 PM
 */
namespace App\Controllers\Auth;

class JWTController
{
    /**
     * JWTController constructor.
     */
    public function __construct()
    {
        $this->model_name = 'users';
        $this->model = new \App\Models\UsersModel();
    }

    /**
     * @param bool $token string
     * @return bool
     */
    public function validate_token($token = false) {
        /*$header_request = false;
        $header_response = 'HTTP/1.1 401 Unauthorized';

        if($header) {
            $token = getallheaders()['WEBTOKEN'];
            $header_request = true;
        }

        if($token == null || $token == false) {
            return false;
        }

        $jwt = explode('.', $token);
        if(count($jwt) == 0 || count($jwt) < 2) return false;
        $payload = json_decode(base64_decode($jwt[1]), true);

        if(gettype($payload) != 'array' || !array_key_exists('iss', $payload)) {
            return false;
        }

        if($payload['exp'] > time()) { //valid time
            $user = $this->userModel->getUserByEmail($payload['email']);
            if($user) { //user exists
                if($user['data']['admin'] == $payload['admin']) { //admin roles
                    $encodedString = $jwt[0] . '.' . $jwt[1];
                    $encodedString = hash_hmac('sha256', $encodedString, SECRET);
                    if($encodedString == $jwt[2]) { //same token
                        $header_response = 'HTTP/1.1 200 OK';
                        if(!$header_request) {
                            return true;
                        }
                    }
                }
            }
        }

        if($header_response == 'HTTP/1.1 401 Unauthorized' && $header_request) {
            header($header_response);
            exit();
        } else {
            if(!$header_request) {
                return false;
            }
        }*/
    }

    /**
     * @param null $id int
     * @return bool|string
     */
    public function create_token($user = null)
    {
        if($user == null) return false;

        if(array_key_exists('data', $user)) {
            $header = base64_encode(json_encode(['type' => 'JWT', 'alg' => 'HS256']));
            $payload = base64_encode(json_encode(['iss' => ISSUER, 'exp' => (time() + (30 * 24 * 60 * 60)), 'name' => $user['data']['first_name'] . ' ' . $user['data']['last_name'], 'admin' => $user['data']['role']]));
            $encodedString = $header . '.' . $payload;
            $encodedString = hash_hmac('sha256', $encodedString, SECRET);

            return $header . '.' . $payload . '.' . $encodedString;
        }
        return false;
    }
}