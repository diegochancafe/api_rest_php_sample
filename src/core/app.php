<?php 
// --
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../config/config.php';
require __DIR__ . '/../core/functions.php';

// --
use Aura\Sql\ExtendedPdo;
use Firebase\JWT\Key;

// --
$pdo = new ExtendedPdo(
    'mysql:host=' . $config['database']['db_host'] . 
    ';port=' . $config['database']['db_port'] . 
    ';dbname=' . $config['database']['db_name'] . 
    ';charset=utf8',
    '' . $config['database']['db_user'] . '',
    '' . $config['database']['db_pass'] . '',
    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'")
);

// --
$functions = new Functions();

// --
$jwt = new Firebase\JWT\JWT;

// --
$jwt_public_key = new Key($config['jwt']['public_key'], 'EdDSA');

?>