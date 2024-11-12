<?php
session_start();
require('../config/config.php');

// Verificar si el usuario está intentando iniciar sesión
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $conn = connect_to_db();
    $query = "SELECT password FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();
    $stmt->close();
    $conn->close();

    if ($hashed_password && password_verify($password, $hashed_password)) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Invalid username or password.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Extreme Vulnerable LLM Web Application">
    <meta name="author" content="">
    <title>RAPOSA</title>
    <link href="<?= "$web_root/assets/css/bulma.min.css"; ?>" rel="stylesheet">
    <link href="<?= "$web_root/assets/css/custom.css"; ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<?php if (!isset($_SESSION['logged_in'])): ?>
    <!-- Mostrar solo el formulario de inicio de sesión si el usuario no está logueado -->
    <div class="columns is-centered is-vcentered" style="min-height: 100vh;">
        <div class="column is-one-third">
            <?php if (isset($error_message)): ?>
                <div class="notification is-danger">
                    <?= htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            <form method="post" action="index.php">
                <div class="field">
                    <label class="label">Username</label>
                    <div class="control">
                        <input class="input" type="text" name="username" placeholder="Username" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Password</label>
                    <div class="control">
                        <input class="input" type="password" name="password" placeholder="Password" required>
                    </div>
                </div>
                <div class="field">
                    <div class="control">
                        <button class="button is-primary is-fullwidth" type="submit">Login</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php else: ?>
    <!-- Mostrar el contenido principal si el usuario está logueado -->
    <nav class="navbar is-dark is-fixed-top" role="navigation">
        <div class="navbar-brand">
            <?php require("$document_root/components/header.php"); ?>
        </div>
    </nav>

    <div class="container mt-6">
        <div class="columns mt-6">
            <div class="column">
                <?php require("home.php"); ?>
            </div>
            <div class="column is-one-quarter">
                <?php require("$document_root/components/sidepanel.php"); ?>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="content has-text-centered">
            <?php require("$document_root/components/footer.php"); ?>
        </div>
    </footer>
<?php endif; ?>

<script src="<?= "$web_root/assets/js/jquery.js" ?>"></script>

</body>
</html>
