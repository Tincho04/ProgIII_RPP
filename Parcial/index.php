<?php

require_once './Class/Usuario.php';
require_once './Class/Turno.php';
require_once './Class/Service.php';
require_once './Class/Auto.php';
require_once './Controllers/LoginController.php';
require_once './Controllers/UserController.php';
require_once './Controllers/TurnoController.php';
require_once './Controllers/AutoController.php';
require_once './Controllers/ServiceController.php';
require_once './Components/JWT.php';
require_once './Components/GenericResponse.php';
require_once './Components/PassManager.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'] ?? 0;

try {
    $fixedPath = explode('/', $path)[1];

    switch ('/' . $fixedPath) {
            /* Punto 1 - */
            // POST: Registro
            // -Se registrará el mail, tipo de usuario y password 
        case '/registro':
            if ($method == 'POST')
                UserController::Create();

            break;
            /* Punto 2 - */
            // POST: Login 
            // - Se deberan loguear los usuarios con mail y password, en base a eso se obtendrta un token.
        case '/login':
            if ($method == 'POST')
                LoginController::Login();
            break;
            /* Punto 3  */
            // POST: vehiculo
            // - Se guardan en un archivo vehiculos.xxx los siguientes datos: patente, marca, modelo y precio
            // No se requiere validación de tipo de usuario
        case '/vehiculo':
            if ($method == 'POST')
                AutoController::Create();
            break;

            /* Punto 4  */
            // GET: Patente/xxx111 
            // Se traen los datos de la patente solicitada
        case '/patente':
            $patente = explode('/', $path)[2];
            if ($method == 'GET' && !empty($patente)) {
                AutoController::GetByPatente($patente);
            }
            break;
            /* Punto 5  */
            // POST: Servicio
            // - Se recibe id, tipo, precio y demora del servicio y esto se guarda en un archivo tipoServicio.xxx
        case '/servicio':
            if ($method == 'POST') {
                ServiceController::Create();
            }
            break;

            /* Punto 6  */
            // GET: Turno
            // Recibe patente del auto e id del servicio, en base a eso generará y guardará un turno con los datos del vehiculo, la fecha del registro y el tipo de servicio
        case '/turno':
            if ($method == 'POST') {
                TurnoController::Create();
            }
            break;

            /* Punto 7 */
            // POST: Stats/x
            //-Solo de acceso Admin. Si recibe el tipo de servicio filtrará un listado de estos, si no listará todos los servicios.
        case '/stats':
            if ($method == 'POST') {
                if (LoginController::IsInRole('admin')) {
                    $tipo = explode('/', $path)[2];
                    if (($tipo == '10.000Km') || ($tipo == '20.000Km') || ($tipo == '50.000Km'))
                        ServiceController::GetTotalByTipo($tipo);
                    else {
                        ServiceController::GetServicios();
                    }
                } else {
                    echo GenericResponse::obtain(false, 'USUARIO NO AUTORIZADO.');
                }
            }
            break;

        default:
            echo GenericResponse::obtain(false, 'Opción inválida');
            break;
    }
} catch (Exception $e) {
    echo GenericResponse::obtain(false, 'Internal Server Error.');
}
