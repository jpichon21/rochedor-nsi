<?php

use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;

// If you don't want to setup permissions the proper way, just uncomment the following PHP line
// read https://symfony.com/doc/current/setup.html#checking-symfony-application-configuration-and-setup
// for more information
//umask(0000);

require __DIR__.'/../vendor/autoload.php';
Debug::enable();

$kernel = new AppKernel('dev', true);
if (PHP_VERSION_ID < 70000) {
    $kernel->loadClassCache();
}

/**
 * Affiche une page de maintenance pour toutes les IP non listées
 * IP fournies par LRDO :
 *     109.17.154.34
 *     84.17.52.250
 *     213.223.118.234
 *     212.83.177.79
 */
if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || in_array(@$_SERVER['REMOTE_ADDR'], ['localhost', '127.0.0.1', '192.168.10.1', '::1', '109.190.146.61', '92.91.97.230', '78.206.160.170', '213.223.118.234', '84.17.52.250', '109.17.154.34', '212.83.177.79', '91.164.251.133', '90.100.153.71', '109.190.146.61', '92.184.124.207', '212.83.170.21'], true)
) {
    $request = Request::createFromGlobals();
    $response = $kernel->handle($request);
    $response->send();
    $kernel->terminate($request, $response);
} else {
    header('HTTP/1.0 403 Forbidden');
    exit('Maintenance en cours, merci de ré-essayer ultérieurement.');
}
