<?php
include 'include/conexion.php';
date_default_timezone_set('America/Lima');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = trim($_POST['dni']);
    $accion = $_POST['accion']; // entrada o salida
    $fecha = date('Y-m-d');
    $hora_actual = date('H:i:s');

    // Verificar si el DNI pertenece a un empleado
    $stmt = $pdo->prepare("SELECT id_empleado, nombres, apellidos FROM empleado WHERE dni = :dni AND estado = 'Activo'");
    $stmt->execute([':dni' => $dni]);
    $empleado = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$empleado) {
        echo "<script>alert('❌ El DNI ingresado no existe o el empleado está inactivo.'); window.history.back();</script>";
        exit;
    }

    $id_empleado = $empleado['id_empleado'];
    $nombre_completo = $empleado['nombres'] . ' ' . $empleado['apellidos'];

    // Verificar si ya existe un registro de asistencia para el día actual
    $stmt = $pdo->prepare("SELECT * FROM asistencia WHERE id_empleado = :id AND fecha = :fecha");
    $stmt->execute([':id' => $id_empleado, ':fecha' => $fecha]);
    $asistencia = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($accion === 'entrada') {
        if ($asistencia) {
            echo "<script>alert('⚠️ Ya registraste tu entrada hoy.'); window.history.back();</script>";
            exit;
        }

        // Registrar nueva entrada
        $stmt = $pdo->prepare("INSERT INTO asistencia (id_empleado, fecha, hora_entrada, estado) VALUES (:id, :fecha, :hora, 'Presente')");
        $stmt->execute([':id' => $id_empleado, ':fecha' => $fecha, ':hora' => $hora_actual]);

        echo "<script>alert('✅ Entrada registrada correctamente para $nombre_completo a las $hora_actual'); window.history.back();</script>";
        exit;
    }

    if ($accion === 'salida') {
        if (!$asistencia) {
            echo "<script>alert('⚠️ No se encontró registro de entrada hoy.'); window.history.back();</script>";
            exit;
        }

        if ($asistencia['hora_salida'] !== null) {
            echo "<script>alert('⚠️ Ya registraste tu salida hoy.'); window.history.back();</script>";
            exit;
        }

        // Registrar salida
        $stmt = $pdo->prepare("UPDATE asistencia SET hora_salida = :hora WHERE id_asistencia = :id");
        $stmt->execute([':hora' => $hora_actual, ':id' => $asistencia['id_asistencia']]);

        echo "<script>alert('✅ Salida registrada correctamente para $nombre_completo a las $hora_actual'); window.history.back();</script>";
        exit;
    }
}
?>
