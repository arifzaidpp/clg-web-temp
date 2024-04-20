<?php

namespace ElfsightInstagramFeedApi\Core;


class Url {
    public static function buildUrl($path, $params = [])
    {
        return $path . (!empty($params) ? '?' . http_build_query($params) : '');
    }

    public static function addQueryParams($subject, $params = [])
    {
        list($url, $query_params) = self::parseUrl($subject);

        foreach ($params as $key => $value) {
            $query_params[$key] = $value;
        }

        return self::buildUrl($url, $query_params);
    }

    public static function parseUrl($subject)
    {
        $url_data = parse_url($subject);
        $query_params = [];

        if (!empty($url_data['query'])) {
            parse_str($url_data['query'], $query_params);
        }

        $url = "{$url_data['scheme']}://{$url_data['host']}{$url_data['path']}";

        return [$url, $query_params];
    }

    public function setRequestUrl($url, $baseUrl = '', $params = array()){
        $requestUrl = $url;

        if (stripos($requestUrl, $baseUrl) === false) {
            $requestUrl = $baseUrl . preg_replace('/^\/+/', '', $requestUrl);
        }

        return self::addQueryParams($requestUrl, $params);
    }
}
