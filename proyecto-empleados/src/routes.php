<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
// rutas
$app->group('/api', function () use ($app) {
  // Version
  $app->group('/v1', function () use ($app) {
    $app->get('/people', 'ObtenerPersonas');
    $app->get('/people/{nationalId}', 'ObtenerPersona');
    $app->post('/people', 'AgregarPersona');
    $app->put('/people/{nationalId}', 'ActualizarPersona');
    $app->delete('/people/{nationalId}', 'EliminarPersona');
  });
});



    $container = $app->getContainer();

    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });
};
