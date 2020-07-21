<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

session_start();

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
$dependencies = require __DIR__ . '/../src/dependencies.php';
$dependencies($app);

// Register middleware
$middleware = require __DIR__ . '/../src/middleware.php';
$middleware($app);

// Register routes
$routes = require __DIR__ . '/../src/routes.php';
$routes($app);

// MySQL Connection
function getConnection() {
    $dbhost="127.0.0.1";
    $dbuser="sparkit_23people";
    $dbpass="Asturias.171*";
    $dbname="sparkit_23people";
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}
//Obtener listado personas
function ObtenerPersonas($response) {
    $sql = "SELECT nationalId, name, lastname, age, originPlanet, pictureUrl FROM people";
    try {
        $stmt = getConnection()->query($sql);
        $personas = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        
        return json_encode($personas);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}


// Run app
$app->run();
