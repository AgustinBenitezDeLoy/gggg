<?php
session_start();
include 'conexion.php';

if (!isset($_GET['estado']) || !isset($_GET['entradaId']) || !isset($_SESSION['usuario_id'])) {
    echo "Error en el proceso de pago.";
    exit;
}

$estado = $_GET['estado'];
$entradaId = $_GET['entradaId'];
$compradorId = $_SESSION['usuario_id'];

if ($estado == 'success') {
    // Actualizar la entrada como vendida y registrar el comprador.
    $stmt = $conn->prepare("UPDATE entradas SET vendida = 'Sí', comprador_id = ? WHERE id = ?");
    $stmt->bind_param("ii", $compradorId, $entradaId);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo "¡Gracias por tu compra!";
        // Aquí puedes redirigir al usuario o mostrar más detalles.
    } else {
        echo "Error al actualizar la entrada.";
    }
} else {
    echo "El pago no se completó. Por favor, intenta nuevamente.";
}

$conn->close();
?>
