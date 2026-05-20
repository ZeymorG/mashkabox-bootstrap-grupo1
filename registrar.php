<?php
// ============================
//  registrar.php
//  Recibe el formulario de contacto y guarda en la BD
// ============================

require_once "conn.php";

// Solo aceptar POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.html");
    exit();
}

// --- Recoger y limpiar datos ---
$nombre   = trim($_POST["nombre"]   ?? "");
$telefono = trim($_POST["telefono"] ?? "");
$email    = trim($_POST["email"]    ?? "");
$mensaje  = trim($_POST["mensaje"]  ?? "");

// --- Validar que los campos obligatorios no estén vacíos ---
if (empty($nombre) || empty($email)) {
    header("Location: index.html?status=vacio#contacto");
    exit();
}

// --- Validar formato de email ---
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: index.html?status=error#contacto");
    exit();
}

// --- Insertar en la tabla clientes (incluye mensaje) ---
$sql  = "INSERT INTO clientes (cli_nombre, cli_telefono, cli_correo, cli_mensaje) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $nombre, $telefono, $email, $mensaje);

if ($stmt->execute()) {
    header("Location: index.html?status=ok#contacto");
} else {
    header("Location: index.html?status=error#contacto");
}

$stmt->close();
$conn->close();
exit();
?>
