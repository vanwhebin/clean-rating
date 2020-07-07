<?php
/**
 * Created by PhpStorm.
 * User: a2
 * Date: 2020/1/3
 * Time: 21:21
 */
return [
    'access_token_salt' => 'vEu8aiCGv9SOLLET83wq1eGnSm0XZ09q',
    'access_token_expire' => (3600 * 2),
    'userinfo_expire' => (3600 * 2),
    'refresh_token_salt' => 'VrC0KkvNyYhIEMebXAJM2jXmF4JY1rZ2',
    'refresh_token_expire' => (3600 * 6),
    'auth_salt' => 'c0G41BQ$YLwH',
    'token_salt' => 'c041241B412Q$YLwH',
    'white' => [
        '/api/v1/login',
        '/api/v1/campaign/latest',
    ],
    'program_salt' => 'hackthon_program',
    'campaign_salt' => 'hackthon_campaign',
];