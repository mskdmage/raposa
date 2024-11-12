<?php
require('../config/config.php');

if (isset($_POST['name'])) {
    $machine = $_POST['name'];
    $conn = connect_to_db();

    if ($conn) {
        // Obtener `command` y `command_buffer` de la máquina especificada
        $sql = "SELECT command, command_buffer FROM machines WHERE name = ? ORDER BY id DESC LIMIT 1";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param('s', $machine);
            $stmt->execute();
            $stmt->bind_result($command, $command_buffer);
            $stmt->fetch();
            $stmt->close();

            // Inicializar la variable de salida
            $output = "no_command";

            if (!empty($command_buffer)) {
                // Si `command_buffer` tiene contenido, lo dividimos
                $commands = array_map('trim', explode('&&', $command_buffer));
                $output = array_shift($commands);  // Tomamos el primer comando

                // Volver a unir los comandos restantes si hay más de uno
                $remaining_commands = !empty($commands) ? implode('&&', $commands) : '';

                // Actualizar `command_buffer` con el resto o vacío
                $update_sql = "UPDATE machines SET command_buffer = ? WHERE name = ?";
                $update_stmt = $conn->prepare($update_sql);
                if ($update_stmt) {
                    $update_stmt->bind_param('ss', $remaining_commands, $machine);
                    $update_stmt->execute();
                    $update_stmt->close();
                }
            } elseif (!empty($command)) {
                // Si `command_buffer` está vacío pero `command` tiene contenido
                $output = $command;

                // Limpiar el campo `command` después de usarlo
                $update_sql = "UPDATE machines SET command = '' WHERE name = ?";
                $update_stmt = $conn->prepare($update_sql);
                if ($update_stmt) {
                    $update_stmt->bind_param('s', $machine);
                    $update_stmt->execute();
                    $update_stmt->close();
                }
            }

            echo $output;  // Mostrar el resultado final
        } else {
            echo "no_command";
        }

        $conn->close();
    } else {
        echo "no_command";
    }
} else {
    echo "no_command";
}
