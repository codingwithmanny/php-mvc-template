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
        if(array_key_exists('WEBTOKEN', $_SESSION)) {
            if($this->jwt->validate_token($_SESSION['WEBTOKEN'])) {
                header('Location: /' . $this->admin_route($this->self()['data']));
            }
        }

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

            header('Location: /' . $this->admin_route($get_user['data']));
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
        if(array_key_exists('WEBTOKEN', $_SESSION)) {
            if($this->jwt->validate_token($_SESSION['WEBTOKEN'])) {
                header('Location: /' . $this->admin_route($this->self()['data']));
            }
        }

        //load view
        $this->load_view($this->model_name . '/login', $this->parent_template, ['fields' => $this->model->helper_required_options(true)]);
    }

    /**
     *
     */
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

            header('Location: /' . $this->admin_route($results['data']));
        } else {
            $results = (!array_key_exists('errors', $results)) ? ['data' => ['token' => $this->jwt->create_token($results)]] : $results;
            $this->load_view(null, $parent_template, $results);
        }
    }

    /**
     * @return string
     */
    public function logout()
    {
        if(array_key_exists('WEBTOKEN', $_SESSION)) {
            unset($_SESSION['WEBTOKEN']);
        }

        if($this->json_request()) {
            return json_encode(['data' => true]);
        } else {
            header('Location: /auth/login');
        }
    }

    /**
     * @return bool
     */
    public function self()
    {
        if(array_key_exists('WEBTOKEN', $_SESSION)) {
            if($this->jwt->validate_token($_SESSION['WEBTOKEN'])) {
                $name = $this->jwt->get_name($_SESSION['WEBTOKEN']);
                $query = [
                    'where' => [
                        ['first_name', '=', $name[0]],
                        ['last_name', '=', $name[1]]
                    ]
                ];

                $results = $this->model->read($query);
                if (!array_key_exists('errors', $results)) {
                    return $results;
                }
            }
        }
        return false;
    }

    public function is_admin()
    {
//        @TODO
    }

    /**
     * @param null $user
     * @return string
     */
    public function admin_route($user = null)
    {
        switch($user['role']) {
            default:
                return 'admin';
            break;
        }
    }

}