<?php

use App\Controllers\ExampleController;

// Valid Routes for Site

$router->view('/', 'pages.welcome');

$router->get(
    '/json/$test',
    function () {
        return json_encode(
            [
                'foo' => 'bar',
                'test' => $_REQUEST['test'],
            ]
        );
    }
);

$router->get('/example', [ExampleController::class, 'index']);
