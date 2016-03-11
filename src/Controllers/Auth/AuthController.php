<?php

/**
 * Created by PhpStorm.
 * User: manuelpineault
 * Date: 2016-03-09
 * Time: 11:41 PM
 */
namespace App\Controllers\Auth;

use App\Core\Controller;
use Mailgun\Mailgun;

class AuthController extends Controller
{
    /**
     * @var
     */
    private $jwt;

    /**
     * @var
     */
    private $reset;

    /**
     * @var
     */
    private $mailgun;

    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->template_dir = 'auth';
        $this->model_name = 'auth';
        $model = new \App\Models\UsersModel();
        $this->jwt = new \App\Controllers\Auth\JWTController;
        $this->reset = new \App\Models\ResetModel();
        $this->mailgun = new Mailgun(MAILGUN_KEY);
        parent::__construct($model);
    }

    /**
     *
     */
    public function register_form()
    {
        $this->is_loggedin();

        //load view
        $this->load_view($this->template_dir . '/register', $this->parent_template, ['fields' => $this->model->helper_required_options(true)]);
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
        $post['created'] = $post['modified'] = date('Y-m-d H:i:s', time());
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
        $this->is_loggedin();

        //load view
        $this->load_view($this->template_dir . '/login', $this->parent_template, ['fields' => $this->model->helper_required_options(true)]);
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
        $webtoken = false;
        if($this->jwt->json_request()) {
            $headers = getallheaders();
            if(!array_key_exists('WEBTOKEN', $headers) || !$this->jwt->validate_token($headers['WEBTOKEN'])) {
                return false;
            } else {
                $webtoken = $headers['WEBTOKEN'];
            }
        } else {
            if(!isset($_SESSION) || !array_key_exists('WEBTOKEN', $_SESSION) || !$this->jwt->validate_token($_SESSION['WEBTOKEN'])) {
                unset($_SESSION['WEBTOKEN']);
                return false;
            } else {
                $webtoken = $_SESSION['WEBTOKEN'];
            }
        }

        if($webtoken != false) {
            $email = $this->jwt->get_email($webtoken);
            $query = [
                'where' => [
                    ['email', '=', $email[0]]
                ]
            ];

            $results = $this->model->read($query);
            if (!array_key_exists('errors', $results)) {
                return $results;
            }

        }

        return false;
    }

    /**
     *
     */
    public function forgot_form()
    {
        $this->is_loggedin();

        //load view
        $this->load_view($this->template_dir . '/forgot', $this->parent_template, ['fields' => $this->model->helper_required_options(true)]);
    }

    /**
     *
     */
    public function forgot()
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
        $email = (array_key_exists('email', $post)) ? $post['email'] : null;
        $query = [
            'where' => [
                ['email', '=', $email]
            ]
        ];

        $results = $this->model->read($query);

        if(array_key_exists('data', $results) && count($results['data']) > 1) {
            $query = [
                'where' => [
                    ['user_id', '=', $results['data']['id']]
                ]
            ];
            $this->reset->delete($query);

            $args = [
                'user_id' => $results['data']['id'],
                'token' => hash_hmac('sha256', $results['data']['email'] . date('Y-m-d H:i:s', time()), SECRET)
            ];

            //mailgun
            $this->mailgun->sendMessage(MAILGUN_DOMAIN,
                array('from'    => 'Automatic Message <' . MAILGUN_FROM . '>',
                    'to'      => $results['data']['first_name'].' <'.$results['data']['email'].'>',
                    'subject' => 'Password Reset',
                    'text'    => 'Please follow this link for a password reset: '. HOST_URL . '/auth/resetpassword/' . $args['token']));

            $this->reset->create($args);
        }

        if($this->json_request()) {
            header('HTTP/1.1 200 OK');
            $this->load_view(null, $parent_template, ['data' => 'Email sent with reset instructions.']);
        } else {
            header('Location: /auth/forgotpassword?success=reset');
        }
    }

    /**
     *
     */
    public function reset_form($token)
    {
        $data = ['errors' => [['Token' => 'Invalid or expired token.']]];
        $this->is_loggedin();

        $query = [
            'where' => [
                ['token', '=', $token]
            ]
        ];
        $results = $this->reset->read($query);

        if(array_key_exists('data', $results) && count($results['data']) > 1) {
            if($this->date_diff($results['data']['created'], date('Y-m-d H:i:s', time())) < 5) {
                $data = ['token' => $results['data']['token']];
            } else {
                $this->reset->delete($query);
            }
        }

        $this->load_view($this->template_dir . '/reset', $this->parent_template, $data);
    }

    /**
     *
     */
    public function reset()
    {
        $data = ['errors' => [['Password' => 'Missing data arguments.']]];
        $parent_template = $this->parent_template;
        $post = $_POST;
        $token = (array_key_exists('token', $_POST)) ? $_POST['token'] : null;

        if($this->json_request()) {
            $parent_template = 'json';
            $request_body = file_get_contents('php://input');
            $post = json_decode($request_body, true);
            $token = (array_key_exists('token', $post)) ? $post['token'] : null;
        }

        $post = $this->model->helper_fieldscleanup($post);

        $query = [
            'where' => [
                ['token', '=', $token]
            ]
        ];
        $results = $this->reset->read($query);

        if(array_key_exists('data', $results) && $this->date_diff($results['data']['created'], date('Y-m-d H:i:s', time())) < 5) {
            $query = [
                'where' => [
                    ['id', '=', $results['data']['user_id']]
                ]
            ];

            if(array_key_exists('password', $post) && strlen($post['password']) > 0) {
                $args = [
                    'password' => hash_hmac('sha256', $post['password'], SECRET)
                ];

                $data = $this->model->update($query, $args);
            }
        } else {
            $data = ['errors' => [['Token' => 'Invalid or expired token.']]];
        }

        $query = [
            'where' => [
                ['token', '=', $token]
            ]
        ];
        $this->reset->delete($query);

        if(array_key_exists('errors', $data) && !$this->json_request()) {
            header('Location: /auth/resetpassword/' . $token);
        } else if(array_key_exists('data', $data) && $data['data'] == true && !$this->json_request()) {
            header('Location: /auth/login');
        } else {
            $this->load_view(null, $parent_template, $data);
        }
    }

    /**
     *
     */
    public function is_loggedin()
    {
        if(array_key_exists('WEBTOKEN', $_SESSION)) {
            if($this->jwt->validate_token($_SESSION['WEBTOKEN'])) {
                header('Location: /' . $this->admin_route($this->self()['data']));
            }
        }
    }

    /**
     * @param $action
     * @param $access
     */
    public function authorize($action, $access)
    {
        $self = $this->self();
        if($self == false) {
            header('HTTP/1.1 401 Unauthorized');
            exit();
        }

        foreach($access as $key => $value) {
            if(!array_key_exists($key, $self['data'])
                || (array_key_exists($key, $self['data']) && $self['data'][$key] != $value)) {
                unset($access[$key]);
            }
        }

        if(count($access) == 0) {
            header('HTTP/1.1 401 Unauthorized');
            exit();
        }
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