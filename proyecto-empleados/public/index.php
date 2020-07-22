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
//Obtener 1 persona
function ObtenerPersona($request) {
          $nationalId = $request->getAttribute('nationalId');
      $sql = "SELECT nationalId, name, lastname, age, originPlanet, pictureUrl FROM people where nationalId = '$nationalId';";
    try {
        $stmt = getConnection()->query($sql);
        $personas = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
	if ($personas == null) {return header("Status: 404 Not Found"); }
        return json_encode($personas);
    } catch(PDOException $e) {
                return header("Status: 500 Error");
    }
}
//Agregar Persona
function AgregarPersona($request) {
    if ($request->getContentType() !== "application/json"){
        return header("Status: 400 Content-Type should be application/json");
    }
    else{
    $emp = json_decode($request->getBody());
    $sql = "INSERT INTO people (nationalId, name, lastname, age, originPlanet, pictureUrl) VALUES (:nationalId,:name,:lastname,:age,:originPlanet,:pictureUrl)";

    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("nationalId", $emp->nationalId);
        $stmt->bindParam("name", $emp->name);
        $stmt->bindParam("lastname", $emp->lastname);
        $stmt->bindParam("age", $emp->age);
        $stmt->bindParam("originPlanet", $emp->originPlanet);
        $stmt->bindParam("pictureUrl", $emp->pictureUrl);
        $stmt->execute();
        $db = null;
        echo json_encode($emp);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
        header("Status: 500 Error");
    }
    }
}
//Actualizar Persona
function ActualizarPersona($request)
{
    if ($request->getContentType() !== "application/json") {
        return header("Status: 400 Content-Type should be application/json");
    } else {
        $emp = json_decode($request->getBody());
        $nationalId = $request->getAttribute('nationalId');
        $sql = "SELECT nationalId, name, lastname, age, originPlanet, pictureUrl FROM people where nationalId = '$nationalId';";
        $stmt = getConnection()->query($sql);
        $personas = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        if ($personas == null) {return header("Status: 404 People Not Found"); }
        $sql = "UPDATE people SET nationalId = :nationalId, name = :name, lastname = :lastname, age = :age, originPlanet = :originPlanet, pictureUrl = :pictureUrl where nationalId = :nationalId";
		
        try {
            $db = getConnection();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("nationalId", $emp->nationalId);
            $stmt->bindParam("name", $emp->name);
            $stmt->bindParam("lastname", $emp->lastname);
            $stmt->bindParam("age", $emp->age);
            $stmt->bindParam("originPlanet", $emp->originPlanet);
            $stmt->bindParam("pictureUrl", $emp->pictureUrl);
            $stmt->execute();
            $db = null;
            echo json_encode($emp);
        } catch (PDOException $e) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
            header("Status: 500 Error");
        }
    }
}
//Eliminar 1 persona
function EliminarPersona($request) {
          $nationalId = $request->getAttribute('nationalId');
    $sql = "SELECT nationalId, name, lastname, age, originPlanet, pictureUrl FROM people where nationalId = '$nationalId';";
    $stmt = getConnection()->query($sql);
    $personas = $stmt->fetchAll(PDO::FETCH_OBJ);
    $db = null;
    if ($personas == null) {return header("Status: 404 People Not Found"); }
      $sql = "DELETE FROM people where nationalId = '$nationalId'";

  try {
        $stmt = getConnection()->query($sql);
        $personas = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        if ($personas == null)
	{ return $response->withStatus(404);  } else {
        	return json_encode($personas); }
    }  catch(PDOException $e) {
        //return $response->withStatus(404);
    }
}
// Run app
$app->run();
