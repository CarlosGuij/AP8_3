<?php
require_once 'autoloader.php';

$modelo = new Modelo();

$productos = $modelo->getAllProductos("ASC");
$modelo->showPaginator(1, 5);

echo "<table>";
echo "<tr>";
$modelo->showOrderAction();
echo "</tr>";
foreach ($productos as $producto) {
    echo "<tr>";
    echo "<td>" . $producto['id'] . "</td>";
    echo "<td>" . $producto['descripcion'] . "</td>";
    echo "<td>" . $producto['precio'] . "</td>";
    echo "</tr>";
}
echo "</table>";
?>
