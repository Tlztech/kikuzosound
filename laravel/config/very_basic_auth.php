<?php
    $env = '';
    if(env('BASIC_AUTH_ENABLE')){
        $env = env('APP_ENV');
    }
    /**
     * Configuration for the "HTTP Very Basic Auth"-middleware
     */
    return [
        // Username
        'user'              => env('BASIC_AUTH_ID'),

        // Password
        'password'          => env('BASIC_AUTH_PASSWORD'),

        // Environments where the middleware is active. Use "*" to protect all envs
        'envs'              => [
            $env
        ],

        // Message to display if the user "opts out"/clicks "cancel"
        'error_message'     => "
            <div style='padding:30px;align-text:center'>
                <h1>This page isn't working</h1>
                <p>If the problem continues, contact the site owner</p>
                <p>HTTP ERROR 401</p>
            </div>
        ",

        // Message to display in the auth dialiog in some browsers (mainly Internet Explorer).
        // Realm is also used to define a "space" that should share credentials.
        'realm'             => 'Basic Auth',

        // If you prefer to use a view with your error message you can uncomment "error_view".
        // This will superseed your default response message
        // 'error_view'        => 'very_basic_auth::basic_auth_error'
    ];