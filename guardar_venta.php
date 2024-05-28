<?php
// Conexión a la base de datos (asumiendo MySQL)
$servername = "localhost"; // Cambia según tu servidor de base de datos
$username = "root"; // Cambia al nombre de usuario de tu base de datos
$password = ""; // Cambia a la contraseña de tu base de datos
$dbname = "ventas_dodge"; // Nombre de la base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Recibir datos del formulario
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$telefono = $_POST['telefono'];
$correoElectronico = $_POST['correoElectronico'];
$direccion = $_POST['direccion'];
$referenciaCarro = $_POST['referenciaCarro'];
$fechaVenta = $_POST['fechaVenta'];
$montoTotal = $_POST['montoTotal'];

// Empezar la transacción
$conn->begin_transaction();

try {
    // Insertar datos del cliente
    $sql_cliente = "INSERT INTO clientes (nombre, apellido, telefono, correoElectronico, direccion) VALUES (?, ?, ?, ?, ?)";
    $stmt_cliente = $conn->prepare($sql_cliente);
    $stmt_cliente->bind_param("sssss", $nombre, $apellido, $telefono, $correoElectronico, $direccion);
    $stmt_cliente->execute();

    // Obtener el ID del cliente recién insertado
    $cliente_id = $conn->insert_id;

    // Insertar datos de la venta
    $sql_venta = "INSERT INTO ventas (cliente_id, referenciaCarro, fechaVenta, montoTotal) VALUES (?, ?, ?, ?)";
    $stmt_venta = $conn->prepare($sql_venta);
    $stmt_venta->bind_param("isss", $cliente_id, $referenciaCarro, $fechaVenta, $montoTotal);
    $stmt_venta->execute();

    // Confirmar la transacción
    $conn->commit();
    echo "Venta registrada exitosamente";
} catch (Exception $e) {
    // Revertir la transacción en caso de error
    $conn->rollback();
    echo "Error al registrar la venta: " . $e->getMessage();
}

// Cerrar conexión
$conn->close();
?>
