<?php

/** @var \Slim\App $app */

$app->post('/sign-in', \LCI\ModxSlim\Users::class .':signIn');

$app->get('/sign-out', \LCI\ModxSlim\Users::class .':signOut');

// Add more routes here:
