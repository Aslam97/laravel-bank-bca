<?php

/**
 * BCA Helpers
 *
 * @author     Aslam H
 * @license    MIT
 * @copyright  (c) 2021, Aslam H
 */
use Aslam\Bca\Bca;

if (!function_exists('bcaapi')) {

    /**
     * bcaapi
     *
     * @return Bca
     */
    function bcaapi()
    {
        return app(Bca::class);
    }
}

if (!function_exists('bca_signature')) {

    /**
     * Generate signature
     *
     * @param  string $url
     * @param  string $accessToken
     * @param  string $apiSecret
     * @param  string $timestamp
     * @param  array  $requestBody
     * @return string
     */
    function bca_signature(string $url, string $accessToken, string $apiSecret, string $timestamp, array $requestBody = [])
    {
        if (is_array($requestBody) && !empty($requestBody)) {
            $requestBody = json_encode($requestBody, JSON_UNESCAPED_SLASHES);
        } else {
            $requestBody = '';
        }

        $requestBody = hash('sha256', $requestBody);
        $stringToSign = sprintf('%s:%s:%s:%s', $url, $accessToken, $requestBody, $timestamp);

        $signature = hash_hmac('sha256', $stringToSign, $apiSecret, false);
        // dd($url, $accessToken, $apiSecret, $timestamp, $requestBody, $signature);
        return $signature;
    }
}

if (!function_exists('bca_timestamp')) {

    function bca_timestamp()
    {
        $dateTime = new DateTime();
        return $dateTime->format('Y-m-d\TH:i:s.') . substr(microtime(), 2, 3) . $dateTime->format('P');
    }
}

if (!function_exists('build_url')) {

    /**
     * build_url
     *
     * @param  array $parts
     * @return void
     */
    function build_url(array $parts)
    {
        return (isset($parts['scheme']) ? "{$parts['scheme']}:" : '') .
            ((isset($parts['user']) || isset($parts['host'])) ? '//' : '') .
            (isset($parts['user']) ? "{$parts['user']}" : '') .
            (isset($parts['pass']) ? ":{$parts['pass']}" : '') .
            (isset($parts['user']) ? '@' : '') .
            (isset($parts['host']) ? "{$parts['host']}" : '') .
            (isset($parts['port']) ? ":{$parts['port']}" : '') .
            (isset($parts['path']) ? "{$parts['path']}" : '') .
            (isset($parts['query']) ? "?{$parts['query']}" : '') .
            (isset($parts['fragment']) ? "#{$parts['fragment']}" : '');
    }
}

if (!function_exists('url_sort_lexicographically')) {

    /**
     * url_sort_lexicographically
     *
     * @param  string $string
     * @return build_url
     */
    function url_sort_lexicographically(string $string)
    {
        $path = parse_url($string);

        $url_query = parse_url($string, PHP_URL_QUERY);
        $query_to_rray = parse_str($url_query, $result);
        ksort($result);

        $query_sorted = http_build_query($result);

        if ($query_sorted) {
            $path['query'] = $query_sorted;
            $reverse_url = build_url($path);

            return $reverse_url;
        }

        return $string;
    }
}
