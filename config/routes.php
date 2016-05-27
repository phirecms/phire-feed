<?php
/**
 * phire-feed routes
 */
return [
    '/feed[/]' => [
        'controller' => 'Phire\Feed\Controller\IndexController',
        'action'     => 'feed'
    ]
];
