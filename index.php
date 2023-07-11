<?php

$request = $_SERVER['REQUEST_URI'];
$viewDir = '/views/';

switch ($request) {
    case '':
    case '/':
        require __DIR__ . $viewDir . 'home.php';
        break;

    case '/techcast':
        require __DIR__ . $viewDir . 'techcast.php';
        break;

    case '/article':
        require __DIR__ . $viewDir . 'article.php';
        break;

    case '/team':
        require __DIR__ . $viewDir . 'team.php';
        break;

    case '/contact':
        require __DIR__ . $viewDir . 'contact.php';
        break;

    default:
        http_response_code(404);
        require __DIR__ . $viewDir . 'error.php';
}
