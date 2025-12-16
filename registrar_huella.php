<?php
include 'include/conexion.php';
session_start();
date_default_timezone_set('America/Lima');

// Guardar huella si se envía el formulario
$mensaje = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_empleado = $_POST['id_empleado'] ?? '';
    $huella_template = $_POST['huella_template'] ?? '';

    if (!empty($id_empleado) && !empty($huella_template)) {
        $stmt = $pdo->prepare("INSERT INTO huellas (id_empleado, huella_template) VALUES (?, ?)");
        $stmt->execute([$id_empleado, $huella_template]);
        $mensaje = "✅ Huella registrada correctamente.";
    } else {
        $mensaje = "⚠️ Debes seleccionar un empleado y capturar la huella.";
    }
}

// Obtener lista de empleados
$stmt = $pdo->query("SELECT id_empleado, CONCAT(nombres, ' ', apellidos) AS nombre FROM empleado WHERE estado='Activo'");
$empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Huella Dactilar</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #043927, #0f4c75, #2c5364);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
        }

        .container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 30px;
            width: 420px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.4);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #48e5c2;
        }

        select, input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            outline: none;
            font-size: 15px;
        }

        select {
            background: rgba(255,255,255,0.2);
            color: #000000ff;
        }

        button {
            width: 95%;
            padding: 12px;
            background: linear-gradient(90deg, #00b09b, #009ffd);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: linear-gradient(90deg, #11998e, #38ef7d);
        }

        .mensaje {
            margin-top: 15px;
            font-weight: bold;
        }

        .volver {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #48e5c2;
            font-weight: 600;
        }

        .volver:hover {
            color: #38ef7d;
        }

        .finger-box {
            border: 2px dashed #48e5c2;
            padding: 25px;
            margin-top: 15px;
            border-radius: 10px;
            background: rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Registro de Huella Dactilar</h2>

    <form method="POST">
        <label for="id_empleado">👤 Seleccione empleado:</label>
        <select name="id_empleado" required>
            <option value="">-- Seleccione --</option>
            <?php foreach ($empleados as $e): ?>
                <option value="<?= htmlspecialchars($e['id_empleado']) ?>"><?= htmlspecialchars($e['nombre']) ?></option>
            <?php endforeach; ?>
        </select>

        <div class="finger-box" id="fingerBox">
            <p>🖐️ Coloca tu dedo en el lector para capturar la huella.</p>
            <input type="hidden" name="huella_template" id="huella_template">
            <button type="button" onclick="capturarHuella()">📸 Capturar Huella</button>
        </div>

        <button type="submit">💾 Guardar Huella</button>

        <?php if (!empty($mensaje)): ?>
            <p class="mensaje"><?= $mensaje ?></p>
        <?php endif; ?>
    </form>

    <a href="../vista/dashboard.php" class="volver">⬅ Volver al Panel</a>
</div>

<script>
function capturarHuella() {
    // ⚠️ Esta función se reemplaza cuando conectes el SDK real
    const fakeTemplate = btoa("huella_simulada_" + new Date().getTime());
    document.getElementById('huella_template').value = fakeTemplate;
    document.getElementById('fingerBox').innerHTML = "<p>✅ Huella capturada correctamente.</p>";
}
</script>
</body>
</html>
