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
        $input = filter_input_array(INPUT_GET); // -- Recibe parámetros por la URL
        $response = $functions->verified_token($jwt, $_SERVER['HTTP_AUTHORIZATION'], $jwt_public_key);
        // --
        if ($response['status'] === 'OK') {
            // --
            if (isset($input['id'])) { // -- Filtrar por ID
                // --
                $id = intval($input['id']); // -- Poner todo a número (INTVAL)
                $bind = array('id' => $id);
                // --
                try {
                    // --
                    $sql = 'SELECT 
                            p.id as "id_product", 
                            p.name as "product", 
                            p.description as "product_description", 
                            p.price, c.id as "id_category", 
                            c.name as "category", 
                            c.description as "category_description"
                        FROM product p
                        INNER JOIN category c on p.id_category = c.id
                        WHERE p.id = :id AND p.status = 1
                    ';
                    // --
                    $result = $pdo->fetchAll($sql, $bind); // -- FetchOne, trae un registro (Propia del a libreria aura/sql)
                    // --
                    if ($result) {
                        // --
                        $response = array('status' => 'OK', 'data' => $result, 'msg' => 'Registros encontrados en el sistema.');
                    } else {
                        // --
                        $response = array('status' => 'ERROR', 'data' => array(), 'msg' => 'No se encontraron registros en el sistema.');
                    }

                } catch (PDOException $e) {
                    // --
                    $response = array('status' => 'ERROR', 'msg' => $e->getMessage(), 'data' => array());
                }

            } else { // -- Traer todos los productos
                // --
                try {
                    // --
                    $sql = 'SELECT 
                            p.id as "id_product", 
                            p.name as "product", 
                            p.description as "product_description", 
                            p.price, c.id as "id_category", 
                            c.name as "category", 
                            c.description as "category_description"
                        FROM product p
                        INNER JOIN category c on p.id_category = c.id
                        WHERE p.status = 1
                    ';
                    // --
                    $result = $pdo->fetchAll($sql); // -- FerchAll, trae todos los registros (Propia del a libreria aura/sql)
                    // --
                    if ($result) {
                        // --
                        $response = array('status' => 'OK', 'data' => $result, 'msg' => 'Registros encontrados en el sistema.');
                    } else {
                        // --
                        $response = array('status' => 'ERROR', 'data' => array(), 'msg' => 'No se encontraron registros en el sistema.');
                    }

                } catch (PDOException $e) {
                    // --
                    $response = array('status' => 'ERROR', 'msg' => $e->getMessage(), 'data' => array());
                }
                
            }
        }

        break;
    case 'POST':
        // --
        $input = json_decode(file_get_contents('php://input'), true); // -- Raw
        if (empty($input)) {
            $input = filter_input_array(INPUT_POST); // -- FormData / x-wwww-form-urlencoded
        }
        // --
        $response = $functions->verified_token($jwt, $_SERVER['HTTP_AUTHORIZATION'], $jwt_public_key);

        // --
        if ($response['status'] === 'OK') {
            // --
            if (!empty($input['id_category']) &&
                !empty($input['name']) &&
                !empty($input['description']) &&
                !empty($input['price'])
            ) {
                // --
                $id_category = intval($input['id_category']);
                $name = $functions->clean_string(strtoupper($input['name']));
                $description = $functions->clean_string(strtoupper($input['description']));
                $price = $functions->clean_string($input['price']);
                // --
                $bind = array(
                    'id_category' => $id_category,
                    'name' => $name,
                    'description' => $description,
                    'price' => $price
                );

                try {
                    // --
                    $pdo->beginTransaction(); // -- Se recomienda usar cuando se realizan varios registros en diferentes tablas
                    // --
                    $sql = 'INSERT INTO product 
                        (
                            id_category,
                            name,
                            description,
                            price
                        ) 
                        VALUES 
                        (
                            :id_category,
                            :name,
                            :description,
                            :price
                        )';

                    // --
                    $result = $pdo->perform($sql, $bind);
                    $lastInsertId = $pdo->lastInsertId(); // -- último id insertado
                    $status_transaction = false;
                    // --
                    if ($result) {
                        $status_transaction = true;
                    }

                    // --
                    if ($status_transaction) {
                        // --
                        $response = array('status' => 'OK', 'data' => array(), 'msg' => 'Registro guardo en el sistema con éxito.');
                        $pdo->commit(); // -- Aplica los cambios a la BD

                    } else {
                        // --
                        $response = array('status' => 'ERROR', 'data' => array(), 'msg' => 'No se guardo el registro.');
                        $pdo->rollBack(); // -- Revierte los cambios en la BD
                    }

                } catch (PDOException $e) {
                    // --
                    $response = array('status' => 'ERROR', 'data' => array(), 'msg' => $e->getMessage());
                }
            } else {
                // --
                $response = array(
                    'status' => 'ERROR',
                    'msg' => 'No se enviaron los campos necesarios, verificar.',
                    'data' => array()
                );
            }
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