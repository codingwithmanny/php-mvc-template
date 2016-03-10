<?php

/**
 * Created by PhpStorm.
 * User: manuelpineault
 * Date: 2016-03-09
 * Time: 11:41 PM
 */
namespace App\Controllers\Auth;

use App\Core\Controller;

class AuthController extends Controller
{
    /**
     * @var
     */
    private $jwt;

    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->model_name = 'auth';
        $model = new \App\Models\UsersModel();
        $this->jwt = new \App\Controllers\Auth\JWTController;
        parent::__construct($model);
    }

    /**
     *
     */
    public function register_form()
    {
        //load view
        $this->load_view($this->model_name . '/register', $this->parent_template, ['fields' => $this->model->helper_required_options(true)]);
    }

    /**
     *
     */
    public function register()
    {
        $parent_template = $this->parent_template;
        $post = $_POST;

        if($this->json_request()) {
            $parent_template = 'json';
            $request_body = file_get_contents('php://input');
            $post = json_decode($request_body, true);
        }
        $post = $this->model->helper_fieldscleanup($post);
        $post['created'] = $post['modified'] = date('Y-m-d H:i:s');
        if(array_key_exists('password', $post)) {
            $post['password'] = hash_hmac('sha256', $post['password'], SECRET);
        }

        $results = $this->model->create($post);

        if(array_key_exists('errors', $results) && !$this->json_request()) {
            $errors = [];
            foreach($results['errors'] as $key => $value) {
                foreach ($value as $k => $v) {
                    array_push($errors, $k);
                }
            }
            $errors = implode(',', $errors);
            header('Location: /auth/register?errors='.$errors);
        } else if(array_key_exists('data', $results) && $results['data'] == true && !$this->json_request()) {
            //request
            $query = [
                'where' => [
                    ['email', '=', $post['email']]
                ]
            ];
            $get_user = $this->model->read($query);
            $_SESSION['WEBTOKEN'] = $this->jwt->create_token($get_user);

//            @TODO: Revise for roles
            header('Location: /admin');
        } else {
            if(array_key_exists('data', $results) && $results['data'] == true) {
                //request
                $query = [
                    'where' => [
                        ['email', '=', $post['email']]
                    ]
                ];
                $get_user = $this->model->read($query);
                $results = ['data' => ['token' => $this->jwt->create_token($get_user)]];
            }
            $this->load_view(null, $parent_template, $results);
        }
    }

    /**
     *
     */
    public function login_form()
    {
        //load view
        $this->load_view($this->model_name . '/login', $this->parent_template, ['fields' => $this->model->helper_required_options(true)]);
    }

    public function login()
    {
        $parent_template = $this->parent_template;
        $post = $_POST;

        if($this->json_request()) {
            $parent_template = 'json';
            $request_body = file_get_contents('php://input');
            $post = json_decode($request_body, true);
        }
        $post = $this->model->helper_fieldscleanup($post);

        //request
        $password = (array_key_exists('password', $post)) ? hash_hmac('sha256', $post['password'], SECRET) : null;
        $email = (array_key_exists('email', $post)) ? $post['email'] : null;
        $query = [
            'where' => [
                ['password', '=', $password],
                ['email', '=', $email]
            ]
        ];

        $results = $this->model->read($query);

        if(array_key_exists('errors', $results) && !$this->json_request()) {
            $errors = [];
            foreach($results['errors'] as $key => $value) {
                foreach ($value as $k => $v) {
                    array_push($errors, $k);
                }
            }
            $errors = implode(',', $errors);
            header('Location: /auth/login?errors='.$errors);
        } else if(array_key_exists('data', $results) && $results['data'] == true && !$this->json_request()) {
            $_SESSION['WEBTOKEN'] = $this->jwt->create_token($results);

//            @TODO: Revise for roles
            header('Location: /admin');
        } else {
            $results = (!array_key_exists('errors', $results)) ? ['data' => ['token' => $this->jwt->create_token($results)]] : $results;
            $this->load_view(null, $parent_template, $results);
        }
    }

    public function self()
    {
//        @TODO
    }

    public function is_admin()
    {
//        @TODO
    }


}