<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    // Redirigir si el usuario no está logueado
    echo "Debe iniciar sesión para publicar una entrada.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fiestaId = $_POST['fiesta'];
    $tipoEntrada = $_POST['tipo_entrada'];
    $precio = $_POST['precio'];
    $qr = $_FILES['qr'];
    $usuarioId = $_SESSION['usuario_id']; // ID del usuario logueado

    // Procesar el archivo QR
    $directorio = "qr/";
    $nombreArchivo = $directorio . basename($qr['name']);

    if (move_uploaded_file($qr['tmp_name'], $nombreArchivo)) {
        // Insertar la entrada en la base de datos
        $stmt = $conn->prepare("INSERT INTO entradas (fiesta, tipo_entrada, precio, qr, creadoPor) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issds", $fiestaId, $tipoEntrada, $precio, $nombreArchivo, $usuarioId);

        if ($stmt->execute()) {
            echo "Entrada publicada con éxito.";
        } else {
            echo "Hubo un error al guardar la entrada en la base de datos.";
        }
    } else {
        echo "Hubo un error al subir el archivo QR.";
    }
} else {
    echo "Método no permitido.";
}
?>
