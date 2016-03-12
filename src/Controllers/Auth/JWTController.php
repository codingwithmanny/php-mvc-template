<?php

/**
 * Created by PhpStorm.
 * User: manuelpineault
 * Date: 2016-03-09
 * Time: 11:40 PM
 */
namespace App\Controllers\Auth;

use App\Core\Controller;

class JWTController extends Controller
{
    /**
     * JWTController constructor.
     */
    public function __construct()
    {
        $this->model_name = 'users';
        $model = new \App\Models\UsersModel();
        parent::__construct($model);
    }

    /**
     * @param bool $token string
     * @return bool
     */
    public function validate_token($token = false) {
        if($token == false) {
            unset($_SESSION['WEBTOKEN']);
            return false;
        }

        $jwt = explode('.', $token);
        if(count($jwt) == 0 || count($jwt) < 2) {
            unset($_SESSION['WEBTOKEN']);
            return false;
        }
        $payload = json_decode(base64_decode($jwt[1]), true);

        if(gettype($payload) != 'array' || !array_key_exists('iss', $payload)) {
            unset($_SESSION['WEBTOKEN']);
            return false;
        }

        if($payload['exp'] > time()) { //valid time
            //request
            $query = [
                'where' => [
                    ['email', '=', $payload['email']]
                ]
            ];
            $user = $this->model->read($query);
            if(array_key_exists('data', $user)) { //user exists
                if(array_key_exists('role', $payload) && $user['data']['role'] == $payload['role']) { //admin roles
                    $encoded_string = $jwt[0] . '.' . $jwt[1];
                    $encoded_string = hash_hmac('sha256', $encoded_string, SECRET);
                    if($encoded_string == $jwt[2]) { //same token
                        return true;
                    }
                }
            }
        }

        unset($_SESSION['WEBTOKEN']);
        return false;
    }

    /**
     * @param bool $token
     * @return array|bool
     */
    public function get_email($token = false)
    {
        $jwt = explode('.', $token);
        if(count($jwt) == 3) {
            $payload = json_decode(base64_decode($jwt[1]), true);
            if(array_key_exists('email', $payload)) {
                return explode(' ', $payload['email']);
            }
        }

        return false;
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
            $payload = base64_encode(json_encode(['iss' => ISSUER, 'exp' => (time() + (30 * 24 * 60 * 60)), 'email' => $user['data']['email'], 'role' => $user['data']['role']]));
            $encoded_string = $header . '.' . $payload;
            $encoded_string = hash_hmac('sha256', $encoded_string, SECRET);

            return $header . '.' . $payload . '.' . $encoded_string;
        }
        return false;
    }
}