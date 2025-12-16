<?php
date_default_timezone_set('America/Lima');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Asistencia</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            /* Fondo con imagen */
            background: url('img/fondo_index.jpg') no-repeat center center fixed;
            background-size: cover; /* Ajusta la imagen al tamaño de la pantalla */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        /* Fondo semitransparente para que se lea bien el contenido */
        .container {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 450px;
            width: 90%;
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .reloj {
            font-size: 1.2em;
            color: #16a085;
            margin: 10px 0 20px;
            font-weight: bold;
        }

        .turno-selector {
            margin: 15px 0;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .turno-label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #495057;
        }

        .turno-options {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 10px;
        }

        .turno-option {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            padding: 8px 15px;
            border-radius: 20px;
            background-color: #e9ecef;
            transition: all 0.3s;
        }

        .turno-option:hover {
            background-color: #dee2e6;
        }

        .turno-option.selected {
            background-color: #2980b9;
            color: white;
        }

        .turno-option input {
            display: none;
        }

        input[type="text"] {
            padding: 12px;
            width: 100%;
            font-size: 1em;
            border-radius: 5px;
            border: 1px solid #ccc;
            outline: none;
            text-align: center;
            margin: 10px 0;
            box-sizing: border-box;
        }

        .botones {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        button {
            padding: 12px 25px;
            border: none;
            background-color: #2980b9;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background 0.3s;
            flex: 1;
            font-weight: bold;
        }

        button:hover {
            background-color: #3498db;
        }

        button.entrada {
            background-color: #27ae60;
        }

        button.entrada:hover {
            background-color: #2ecc71;
        }

        button.salida {
            background-color: #e74c3c;
        }

        button.salida:hover {
            background-color: #c0392b;
        }

        .info-turno {
            font-size: 0.9em;
            color: #7f8c8d;
            margin-top: 8px;
            font-style: italic;
        }

        .salir {
            margin-top: 25px;
            display: inline-block;
            background-color: #ffffff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            color: #2980b9;
            font-weight: bold;
            transition: 0.3s;
            border: 2px solid #2980b9;
        }

        .salir:hover {
            background-color: #2980b9;
            color: white;
        }

        .mensaje {
            margin-top: 15px;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
        }

        .mensaje.error {
            background-color: #fdecea;
            color: #d32f2f;
            border: 1px solid #f5c6cb;
        }

        .mensaje.success {
            background-color: #e8f5e9;
            color: #388e3c;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Registro de Asistencia</h1>
        <p>Ingrese su DNI y seleccione el turno</p>

        <div class="reloj" id="reloj"></div>

        <form method="POST" action="registrar_asistencia.php" id="formAsistencia">
            <input type="text" name="dni" id="dni" placeholder="Ingrese su DNI" maxlength="8" required
                   pattern="[0-9]{8}" title="Debe ingresar 8 dígitos numéricos">
            
            <div class="turno-selector">
                <label class="turno-label">Seleccione su turno:</label>
                <div class="turno-options">
                    <label class="turno-option" id="turno-manana-label">
                        <input type="radio" name="turno" value="mañana" required> 
                        <span>Turno Mañana</span>
                    </label>
                    <label class="turno-option" id="turno-tarde-label">
                        <input type="radio" name="turno" value="tarde" required>
                        <span>Turno Tarde</span>
                    </label>
                </div>
                <div class="info-turno" id="horario-info">
                    Horario sugerido: Mañana (08:00 - 13:00), Tarde (14:00 - 22:00)
                </div>
            </div>

            <div class="botones">
                <button type="submit" name="accion" value="entrada" class="entrada">Registrar Entrada</button>
                <button type="submit" name="accion" value="salida" class="salida">Registrar Salida</button>
            </div>
        </form>

        <?php
        // Mostrar mensajes de error o éxito si existen
        if (isset($_GET['error'])) {
            $error = htmlspecialchars($_GET['error']);
            echo '<div class="mensaje error">' . $error . '</div>';
        }
        if (isset($_GET['success'])) {
            $success = htmlspecialchars($_GET['success']);
            echo '<div class="mensaje success">' . $success . '</div>';
        }
        ?>

        <a href="login.php" class="salir">Acceso Administrativo</a>
    </div>

    <script>
        function actualizarReloj() {
            const ahora = new Date();
            const opcionesFecha = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const fecha = ahora.toLocaleDateString('es-ES', opcionesFecha);
            const hora = ahora.toLocaleTimeString('es-ES');
            document.getElementById("reloj").innerHTML = fecha + " | " + hora;
        }
        
        // Actualizar reloj cada segundo
        setInterval(actualizarReloj, 1000);
        actualizarReloj();

        // Función para seleccionar turno automáticamente según la hora
        function seleccionarTurnoAutomatico() {
            const ahora = new Date();
            const hora = ahora.getHours();
            
            // Mañana: 6:00 a 13:59, Tarde: 14:00 a 21:59
            if (hora >= 6 && hora < 14) {
                document.querySelector('input[value="mañana"]').checked = true;
                document.getElementById('turno-manana-label').classList.add('selected');
                document.getElementById('horario-info').textContent = 'Horario actual sugerido: Mañana (06:00 - 14:00)';
            } else if (hora >= 14 && hora < 22) {
                document.querySelector('input[value="tarde"]').checked = true;
                document.getElementById('turno-tarde-label').classList.add('selected');
                document.getElementById('horario-info').textContent = 'Horario actual sugerido: Tarde (14:00 - 22:00)';
            }
        }

        // Manejar la selección visual de turnos
        document.querySelectorAll('.turno-option').forEach(label => {
            const radio = label.querySelector('input[type="radio"]');
            
            label.addEventListener('click', () => {
                // Remover selección de todos
                document.querySelectorAll('.turno-option').forEach(l => {
                    l.classList.remove('selected');
                });
                
                // Agregar selección al clickeado
                label.classList.add('selected');
                radio.checked = true;
                
                // Actualizar información del horario
                if (radio.value === 'mañana') {
                    document.getElementById('horario-info').textContent = 'Horario seleccionado: Mañana (06:00 - 14:00)';
                } else {
                    document.getElementById('horario-info').textContent = 'Horario seleccionado: Tarde (14:00 - 22:00)';
                }
            });
        });

        // Validación del DNI
        document.getElementById('formAsistencia').addEventListener('submit', function(e) {
            const dni = document.getElementById('dni').value;
            const turnoSeleccionado = document.querySelector('input[name="turno"]:checked');
            
            // Validar DNI (8 dígitos)
            if (!/^\d{8}$/.test(dni)) {
                e.preventDefault();
                alert('El DNI debe tener exactamente 8 dígitos numéricos.');
                return;
            }
            
            // Validar turno seleccionado
            if (!turnoSeleccionado) {
                e.preventDefault();
                alert('Por favor, seleccione un turno.');
                return;
            }
            
            // Confirmación de registro
            const accion = e.submitter.value;
            const turno = turnoSeleccionado.value;
            const confirmacion = confirm(`¿Está seguro de registrar la ${accion} para el turno de ${turno}?`);
            
            if (!confirmacion) {
                e.preventDefault();
            }
        });

        // Limpiar mensajes después de 5 segundos
        setTimeout(() => {
            const mensajes = document.querySelectorAll('.mensaje');
            mensajes.forEach(mensaje => {
                mensaje.style.display = 'none';
            });
        }, 5000);

        // Ejecutar selección automática de turno al cargar la página
        window.addEventListener('load', seleccionarTurnoAutomatico);
    </script>
</body>
</html>