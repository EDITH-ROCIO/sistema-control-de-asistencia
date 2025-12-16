<?php
session_start();
include 'include/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // 🔐 Comparar usando SHA2 como se registró en la base de datos
    $stmt = $pdo->prepare("
        SELECT * 
        FROM usuario 
        WHERE username = :u 
        AND password_hash = SHA2(:p, 256) 
        AND activo = 1
    ");
    $stmt->execute([':u' => $username, ':p' => $password]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Guardar sesión
        $_SESSION['usuario'] = $usuario['username'];
        $_SESSION['rol'] = $usuario['rol'];
        $_SESSION['id_empleado'] = $usuario['id_empleado'];

        // Redirección por rol
        if ($usuario['rol'] === 'Administrador') {
            header('Location: vista/dashboard.php');
        } else {
            header('Location: vista/dashboard_empleado.php');
        }
        exit;
    } else {
        $_SESSION['error'] = "Usuario o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistema de Asistencia</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #00b09b, #96c93d, #00c9ff);
            background-size: 300% 300%;
            animation: gradientShift 10s ease infinite;
            overflow: hidden;
            position: relative;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Efectos de luz */
        .light {
            position: absolute;
            width: 180px;
            height: 180px;
            background: radial-gradient(circle, rgba(255,255,255,0.25), transparent 70%);
            border-radius: 50%;
            filter: blur(50px);
            animation: moveLights 8s infinite ease-in-out alternate;
        }

        .light:nth-child(1) { top: 10%; left: 15%; animation-delay: 0s; }
        .light:nth-child(2) { top: 70%; left: 70%; animation-delay: 2s; background: rgba(0,255,200,0.2); }
        .light:nth-child(3) { top: 50%; left: 30%; animation-delay: 4s; background: rgba(0,180,255,0.2); }

        @keyframes moveLights {
            from { transform: translate(0, 0); opacity: 0.7; }
            to { transform: translate(50px, -50px); opacity: 0.4; }
        }

        /* Caja de login */
        .login-box {
            position: relative;
            z-index: 2;
            background: rgba(0, 0, 0, 0.55);
            backdrop-filter: blur(18px);
            width: 370px;
            padding: 35px 25px;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 255, 200, 0.3);
            text-align: center;
            color: #e6fff5;
            border: 1px solid rgba(0, 255, 200, 0.2);
        }

        h2 {
            margin-bottom: 20px;
            font-weight: 600;
            color: #e6fff5;
            text-shadow: 0 0 5px rgba(0,255,200,0.6);
        }

        form input {
            width: 85%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            background: rgba(255,255,255,0.2);
            color: #fff;
            outline: none;
            transition: 0.3s;
        }

        form input:focus {
            background: rgba(255,255,255,0.3);
            box-shadow: 0 0 10px rgba(0,255,200,0.5);
        }

        form input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        form button {
            width: 90%;
            background: linear-gradient(135deg, #00c9ff, #00b09b);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            margin-top: 10px;
            transition: 0.3s;
        }

        form button:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(0,255,200,0.6);
        }

        .error {
            color: #ff4d4d;
            font-size: 14px;
            margin-top: 12px;
            background: rgba(255, 0, 0, 0.1);
            padding: 8px;
            border-radius: 6px;
        }
    </style>
</head>

<body>
    <div class="light"></div>
    <div class="light"></div>
    <div class="light"></div>

    <div class="login-box">
        <h2>Iniciar Sesión</h2>

        <form method="POST">
            <input type="text" name="username" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Ingresar</button>

            <?php if (isset($_SESSION['error'])): ?>
                <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
