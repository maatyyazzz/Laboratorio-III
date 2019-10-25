<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once './vendor/autoload.php';
require_once './clases/usuario.php';
require_once './clases/AccesoDatos.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

/*
�La primera l�nea es la m�s importante! A su vez en el modo de 
desarrollo para obtener informaci�n sobre los errores
 (sin �l, Slim por lo menos registrar los errores por lo que si est� utilizando
  el construido en PHP webserver, entonces usted ver� en la salida de la consola 
  que es �til).

  La segunda l�nea permite al servidor web establecer el encabezado Content-Length, 
  lo que hace que Slim se comporte de manera m�s predecible.
*/

$app = new \Slim\App(["settings" => $config]);

$app->group("/usuario", function(){

    $this->get("/", \Usuario::class . ":TraerTodos");
    $this->get("/{id}", \Usuario::class . ":TraerUno");

    $this->delete("/", \Usuario::class . ":deleteUsuario");

    $this->put("/", \Usuario::class . ":ModifiUsuario");

    $this->post("/", \Usuario::class . ":AgregarUsu");

});

$app->group("/Validacion",function(){ 

    $this->post("/", function(Request $request, Response $response){

        $datos = $request->getParsedBody(); //OBTENGO LOS VALORES ENVIADOS POR POST

        $usuario = json_decode($datos["informacion"]); //RECONOZCO ESOS DATOS POR CLAVE VALOR
        $retorno = " ";
        $validacion = Usuario::validarBD($usuario->correo, $usuario->clave);

        if($validacion->existe)
        {
            $retorno = $response->withJson($validacion, 200);
        }
        else
        {
            $retorno = $response->withJson($validacion, 200);
        }

        return $retorno;

    });

});


$app->run(); //SI ESTA NO SE EJECUTA NO FUNCIONA NADA, SE BORRA A VECES