<?php 
// --
require __DIR__ . '/../../src/core/app.php';

use Spipu\Html2Pdf\Html2Pdf;

$html2pdf = new Html2Pdf();

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
$result = $pdo->fetchAll($sql); 
$html = "";
// -- header
$html = "<table border='1'>
            <tr>
                <th>id</th>
                <th>nombre</th>
                <th>descripción</th>
            </tr>";

// -- body
foreach ($result as $item) {
 // --
 $html .= " <tr>
                <td>".$item['id_product']."</td>
                <td>".$item['product']."</td>
                <td>".$item['product_description']."</td>
            </tr>";
}
// -- cierre de tabla
$html .= "</table>";

$html2pdf->writeHTML($html);
$html2pdf->output();

?>