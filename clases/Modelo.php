<?php

require_once 'Connection.php';

class Modelo extends Connection
{
    public function __construct()
    {
        parent::__construct();
    }

    // Ejercicio 2
    public function getAllProductos()
    {
        $query = "SELECT * FROM PRODUCTO";
        $result = $this->conn->query($query);

        $productos = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $productos[] = $row;
            }
        }

        return $productos;
    }

    // Método para mostrar todos los productos en una tabla
    public function showAllProductos()
    {
        $productos = $this->getAllProductos();

        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Nombre</th><th>Precio</th></tr>";
        
        foreach ($productos as $producto) {
            echo "<tr>";
            echo "<td>{$producto['id']}</td>";
            echo "<td>{$producto['nombre']}</td>";
            echo "<td>{$producto['precio']}</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    }
    // Ejercicio 3
    public function getAllEmp()
{
    $query = "SELECT EMP_NO, APELLIDOS, DEPT_NO, FORMAT(SALARIO, 2, 'es_ES') AS SALARIO FROM EMP";
    $result = $this->conn->query($query);
    $empleados = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $empleados[] = $row;
        }
    }
    return $empleados;
}



    // Método para mostrar todos los empleados en una tabla
    public function showAllEmp()
    {
        $empleados = $this->getAllEmp();
        echo "<table border='1'>";
        echo "<tr><th>Número de Empleado</th><th>Apellidos</th><th>Salario</th></tr>";
        foreach ($empleados as $empleado) {
            $deptNo = $empleado['DEPT_NO'];
            $deptColor = $this->getDeptColor($deptNo);
            echo "<tr><td>{$empleado['EMP_NO']}</td><td>{$empleado['APELLIDOS']}</td><td style='background-color: $deptColor;'>{$empleado['SALARIO']}</td></tr>";
        }
        echo "</table>";
    }


    // Ejercicio 4
    public function getAllCliente($order)
    {
        $query = "SELECT CLIENTE_COD, NOMBRE, CIUDAD FROM CLIENTE ORDER BY NOMBRE $order";
        $result = $this->conn->query($query);
        $clientes = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $clientes[] = $row;
            }
        }
        return $clientes;
    }

    public function showAllCliente($order)
    {
        $clientes = $this->getAllCliente($order);
        echo "<table border='1'>";
        echo "<tr><th>Código de Cliente</th><th>Nombre</th><th>Ciudad</th></tr>";
        foreach ($clientes as $cliente) {
            echo "<tr><td>{$cliente['CLIENTE_COD']}</td><td>{$cliente['NOMBRE']}</td><td>{$cliente['CIUDAD']}</td></tr>";
        }
        echo "</table>";
    }

    // Métodos adicionales

    // Ejercicio 5
    public function getPedidoOver($total)
    {
        $query = "SELECT PEDIDO_NUM, CLIENTE_COD, TOTAL FROM PEDIDO WHERE TOTAL >= $total";
        $result = $this->conn->query($query);
        $pedidos = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $pedidos[] = $row;
            }
        }
        return $pedidos;
    }

    public function showPedidoOver($total)
    {
        $pedidos = $this->getPedidoOver($total);
        echo "<table border='1'>";
        echo "<tr><th>Número de Pedido</th><th>Código de Cliente</th><th>Total</th></tr>";
        foreach ($pedidos as $pedido) {
            echo "<tr><td>{$pedido['PEDIDO_NUM']}</td><td>{$pedido['CLIENTE_COD']}</td><td>{$pedido['TOTAL']}</td></tr>";
        }
        echo "</table>";
    }

    // Ejercicio 6
    public function getLineasPedido($pedido)
    {
        $query = "SELECT PEDIDO_NUM, DETALLE_NUM, IMPORTE FROM DETALLE WHERE PEDIDO_NUM = $pedido";
        $result = $this->conn->query($query);
        $lineasPedido = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $lineasPedido[] = $row;
            }
        }
        return $lineasPedido;
    }

    public function getLineasPedidoMayor($pedido)
    {
        $query = "SELECT MAX(IMPORTE) AS MAX_IMPORTE FROM DETALLE WHERE PEDIDO_NUM = $pedido";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row['MAX_IMPORTE'];
    }

    public function showLineasPedido($pedido)
    {
        $lineasPedido = $this->getLineasPedido($pedido);
        $maxImporte = $this->getLineasPedidoMayor($pedido);
        echo "<table border='1'>";
        echo "<tr><th>Número de Pedido</th><th>Número de Detalle</th><th>Importe</th></tr>";
        foreach ($lineasPedido as $linea) {
            $importe = $linea['IMPORTE'];
            if ($importe == $maxImporte) {
                echo "<tr><td>{$linea['PEDIDO_NUM']}</td><td>{$linea['DETALLE_NUM']}</td><td>{$importe} *</td></tr>";
            } else {
                echo "<tr><td>{$linea['PEDIDO_NUM']}</td><td>{$linea['DETALLE_NUM']}</td><td>{$importe}</td></tr>";
            }
        }
        echo "</table>";
    }

    // Ejercicio 7
    public function showPaginator()
    {
        $query = "SELECT COUNT(*) AS total FROM PRODUCTO";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        $totalProductos = $row['total'];
        $productosPorPagina = 3;
        $totalPaginas = ceil($totalProductos / $productosPorPagina);

        echo "<div class='paginator'>";
        echo "<a href='?page=1'>Primera</a> ";
        for ($i = 1; $i <= $totalPaginas; $i++) {
            $activeClass = (isset($_GET['page']) && $i == $_GET['page']) ? 'active' : '';
            echo "<a href='?page={$i}' class='{$activeClass}'>{$i}</a> ";
        }
        echo "<a href='?page={$totalPaginas}'>Última</a>";
        echo "</div>";
    }

    // Ejercicio 8
    public function showOrderAction()
    {
        $currentOrder = isset($_GET['order']) ? $_GET['order'] : 'ASC';
        $nextOrder = $currentOrder === 'ASC' ? 'DESC' : 'ASC';
        $url = $_SERVER['PHP_SELF'] . "?order=$nextOrder";
        $linkText = $currentOrder === 'ASC' ? '↓' : '↑';
        return "<a href='$url'>Descripción $linkText</a>";
    }
}

?>
