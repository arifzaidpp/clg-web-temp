<?php

namespace ElfsightInstagramFeedApi;


if (!defined('ABSPATH')) exit;

require_once __DIR__ . '/vendor/autoload.php';

class Api extends Core\Api {
    private $routes = array(
        '' => 'requestFallbackController',
        'auth' => 'authController',
        'auth-set' => 'setAuthController',
        'instagram' => 'requestInstagramController',
        'facebook' => 'requestFacebookController'
    );

    const AUTH_BASE_URL_INSTAGRAM = 'https://storage.elfsight.com/auth/instagram/';
    const AUTH_BASE_URL_FACEBOOK = 'https://storage.elfsight.com/auth/facebook/';

    const API_BASE_URL_INSTAGRAM = 'https://graph.instagram.com/';
    const API_BASE_URL_FACEBOOK = 'https://graph.facebook.com/';

    static $ERROR_USER_UPDATE;
    static $ERROR_USER_NOT_FOUND;
    static $ERROR_USER_NOT_AUTH;

    public function __construct($config) {
        self::$ERROR_USER_UPDATE = __('Can\'t update user data');
        self::$ERROR_USER_NOT_FOUND = __('User not found');
        self::$ERROR_USER_NOT_AUTH = __('User not authorized');

        parent::__construct($config, $this->routes);
    }

    public function setAuthController() {
        $provider = $this->input('provider');
        $providerData = $this->input('data');
        $identity = $this->input('identity');

        $data = [];
        $data[$provider] = json_decode(stripslashes($providerData), true);
        $json_data = json_encode($data);

        $status = $this->User->set($identity, $json_data);

        return array(
            'status' => $status ? 1 : 0,
            'data' => array(
                'identity' => $identity
            )
        );
    }

    public function authController() {
        $provider = $this->input('provider');
        $token = $this->input('token', false, false);
        $scopes = $this->input('scopes', null, false);
        $app_config = $this->input('app_config', null, false);

        if (empty($token)) {
            if ($provider === 'facebook') {
                $auth_url = self::AUTH_BASE_URL_FACEBOOK;
            }

            if ($provider === 'instagram') {
                $auth_url = self::AUTH_BASE_URL_INSTAGRAM;
            }

            $redirect_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

            $auth_url = $this->Helper->addQueryParam($auth_url, 'scopes', $scopes);
            $auth_url = $this->Helper->addQueryParam($auth_url, 'app_config', $app_config);
            $auth_url = $this->Helper->addQueryParam($auth_url, 'redirect_url', urlencode($redirect_url));

            header("Location: $auth_url");
            exit();
        } else {
            $identity = $this->input('identity', null, true);
            $user = $this->User->get($identity);
            $user_data = array();

            if (!empty($user)) {
                $user_data = json_decode($user['data'], true);
            }

            $user_data[$provider] = array(
                'token' => $this->input('token', false, true),
                'expiresIn' => $this->input('expiresIn', null, false)
            );

            $json_data = json_encode($user_data);
            $json_provider_data = json_encode($user_data[$provider]);

            if ($this->User->set($identity, $json_data)) {
                $response = "<script>window.opener.postMessage({'action': 'token', 'callback': 'authorize', 'status': 1, 'identity': '$identity', 'provider': '$provider', 'data': $json_provider_data}, '*'); window.close();</script>";

                return $this->response($response, array('plain' => true));
            } else {
                $this->error(400, 'invalid auth', self::$ERROR_USER_UPDATE);
            }
        }
    }

    protected function refreshInstagramToken($user_id, $user_data)
    {
        $response = $this->request('get', 'https://graph.instagram.com/refresh_access_token', array(
            'query' => array(
                'grant_type' => 'ig_refresh_token',
                'access_token' => $user_data['instagram']['token']
            )
        ));

        $data = json_decode($response['body'], true);

        $user_data['instagram'] = array(
            'token' => $data['access_token'],
            'expiresIn' => $data['expires_in']
        );

        $this->User->set($user_id, json_encode($user_data));

        return $user_data;
    }

    protected function getAccessToken($user_id, $provider) {
        $user = $this->User->get($user_id);

        if (empty($user)) {
            $this->error(400, self::$ERROR_INVALID_AUTH, self::$ERROR_USER_NOT_FOUND);
        }

        $user_data = json_decode($user['data'], true);
        $auth_data = $user_data[$provider];

        if (empty($auth_data)) {
            $this->error(400, self::$ERROR_INVALID_AUTH, self::$ERROR_USER_NOT_AUTH);
        }

        if ($auth_data && isset($auth_data['expiresIn']) && $auth_data['expiresIn'] && $provider === 'instagram') {
            $is_expire = time() > $user['updated_at'] + $auth_data['expiresIn'] - 86400;

            if ($is_expire) {
                $user_data = $this->refreshInstagramToken($user_id, $user_data);
                $auth_data = $user_data[$provider];
            }
        }

        return $auth_data['token'];
    }

    public function requestInstagramController(){
        $url = $this->input('q');
        $user_id = $this->input('user_id');

        $access_token = $this->getAccessToken($user_id, 'instagram');

        $cache_key = $this->Cache->keyFromQuery($url) . $access_token;
        $data = $this->Cache->get($cache_key);

        if (empty($data)) {
            $request_url = $this->Url->setRequestUrl($url, self::API_BASE_URL_INSTAGRAM, array('access_token' => $access_token));

            $response = $this->request('get', $request_url);

            if ($this->checkResponse($response, false)) {
                $data = $response['body'];
                $data_arr = json_decode($response['body'], true);

                if (!empty($data_arr['error'])) {
                    return $this->response($data);
                }

                if ((int) $response['http_code'] === 200) {
                    $this->Cache->set($cache_key, $data);
                }
            } else {
                return $this->error();
            }
        }

        return $this->response($data);
    }

    public function requestFacebookController(){
        $cacheBypass = filter_var($this->input('cache_bypass', false, false), FILTER_VALIDATE_BOOLEAN);

//        $url = urldecode($this->input('q'));
        $url = $this->input('q');
        $user_id = $this->input('user_id');

        $access_token = $this->getAccessToken($user_id, 'facebook');

        $cache_key = $this->Cache->keyFromQuery($url) . $access_token;
        $data = [];

        if (!$cacheBypass) {
            $data = $this->Cache->get($cache_key);
        }

        if (empty($data)) {
            $request_url = $this->Url->setRequestUrl($url, self::API_BASE_URL_FACEBOOK, array('access_token' => $access_token));

            $response = $this->request('get', $request_url);

            if ($this->checkResponse($response)) {
                $data = $response['body'];

                if (!$cacheBypass) {
                    $this->Cache->set($cache_key, $data);
                }
            } else {
                $err_response = json_decode($response['body'], true);

                if (!empty($err_response) && !empty($err_response['error'])) {
                    $error = $err_response['error'];
                    return $this->error(400, $error['message']);
                }

                return $this->error();
            }
        }

        return $this->response($data);
    }

    public function requestFallbackController(){
        $path = str_replace('/v1', '', $this->input('path', ''));

        $cache_key_patterns = array(
            '/media/shortcode/{shortcode}' => '&$1',
            '/users/{username}/media/recent' => '@$1_media',
            '/users/{username}' => '@$1_profile',
            '/tags/{tag}/media/recent' => '#$1',
            '/locations/{tag}/media/recent' => '&$1'
        );
        $cache_key = '';

        foreach ($cache_key_patterns as $route_path => $cache_key_replacement) {
            $regexp = str_replace(
                '/',
                '\/',
                preg_replace(
                    '#\{[^\{]+\}#',
                    '([^/$]+)',
                    $route_path
                )
            );

            if (preg_match(
                '#^' . $regexp . '#',
                $path,
                $params_matches
            )) {
                $handler_params = array_slice($params_matches, 1);

                if ($handler_params[0]) {
                    $handler_params[0] = urldecode($handler_params[0]);

                    $cache_key = preg_replace(
                        '#^(.*)$#',
                        $cache_key_replacement,
                        $handler_params[0]
                    );

                }
                break;
            }
        }

        $result = [];

        $cache_data = $this->Cache->get($cache_key, false);

        if (!empty($cache_data)) {
            $result = json_decode($cache_data, true);
        }

        return $this->response(array(
            'meta' => array(
                'code' => 200,
                'fallback_cache' => true
            ),
            'data' => $result
        ), array('encode' => true));
    }
}
