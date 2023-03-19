<?php 
// --
require __DIR__ . '/../../src/core/app.php';
// --
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
// --
$request = $_SERVER['REQUEST_METHOD'];
$response = array();

// --
switch ($request) {
    case 'GET':
        // --
        $key_pair = sodium_crypto_sign_keypair();
        // --
        $private_key = base64_encode(sodium_crypto_sign_secretkey($key_pair));
        $public_key = base64_encode(sodium_crypto_sign_publickey($key_pair));

        // --
        $response = array(
            'status' => 'OK',
            'data' => array(
                'private_key' => $private_key,
                'public_key' => $public_key
            ),
            'msg' => 'Key generadas.'
        );
        // --
        break;
    case 'POST':
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