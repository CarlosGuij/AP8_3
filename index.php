<?php
require_once 'autoloader.php';

$modelo = new Modelo();

$productos = $modelo->getAllProductos();
$modelo->showPaginator();

echo "<table>";
echo "<tr>";
echo "<th>" . $modelo->showOrderAction() . "</th>";
echo "</tr>";
foreach ($productos as $producto) {
    echo "<tr>";
    echo "<td>" . $producto['PROD_NUM'] . "</td>";
    echo "<td>" . $producto['DESCRIPCION'] . "</td>";

    echo "</tr>";
}
echo "</table>";
?>
