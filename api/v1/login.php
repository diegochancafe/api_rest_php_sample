<?php 
// --
require __DIR__ . '/../../src/core/app.php';
// --
$request = $_SERVER['REQUEST_METHOD'];
$response = array();

// --
switch ($request) {
    case 'GET':
        // --

        break;
    case 'POST':
        // --
        $input = json_decode(file_get_contents('php://input'), true); // -- Raw
        if (empty($input)) {
            $input = filter_input_array(INPUT_POST); // -- FormData / x-wwww-form-urlencoded
        }
        // --
        if (isset($input['username']) && isset($input['password'])) {
            // --
            $username = trim($input['username']); // -- Trim, para limpiar inciando y finalizando la cadena de texto.
            $password = trim($input['password']); // -- Encriptar, opcional
            // --
            $bind = array(
                'username' => $username,
                'password' => $password,
                'status' => 1
            );
            // --
            try {
                // --
                $sql = 'SELECT 
                    id, 
                    first_name, 
                    last_name, 
                    username, 
                    status 
                FROM user 
                WHERE 
                    username = :username AND 
                    password = :password AND 
                    status = :status
                ';
                // --
                $result = $pdo->fetchOne($sql, $bind); // -- FetchOne, trae un registro (Propia del a libreria aura/sql)
                // --
                if ($result) {
                    // -- JWT
                    $time = time();
                    // --
                    $payload = array(
                        'iat' => $time, // -- Tiempo que inicia el token
                        'exp' => $time + (60 * 60), // -- Tiempo que éxpira el token (+1 Hora),
                        'data' => $result
                    );

                    // --
                    $token = $functions->encode_token($jwt, $payload, $config['jwt']['private_key']);
                    // --
                    $response = array(
                        'status' => 'OK',
                        'data' => array(
                            'user' => $result,
                            'token' => $token
                        ),
                        'msg' => 'Verificación correcta.'
                    );

                } else {
                    // --
                    $response = array('status' => 'ERROR', 'msg' => 'Verificar credenciales', 'data' => array());
                }
            } catch (PDOException $e) {
                // --
                $response = array('status' => 'ERROR', 'msg' => $e->getMessage(), 'data' => array());
            }
        } else {
            $response = array('status' => 'ERROR', 'msg' => 'No se enviaron los parámetros necesarios, verificar.', 'data' => array());
        }
        // --
        break;
    case 'PUT':
        # code...
        break;
    case 'DELETE':
        # code...
        break;
    default:
        # code...
        break;
}

// --
header('Content-Type: application/json');
echo json_encode($response);

?>