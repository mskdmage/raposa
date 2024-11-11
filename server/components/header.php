<nav class="navbar is-fixed-top is-warning">
    <div class="container">
        <div class="navbar-brand">
            <a class="navbar-logo" href="/">
                <img src="<?= "$WEBROOT/assets/images/logo.png" ?>" alt="Logo de XVLLMWA">
            </a>
        </div>
        <div id="navbarNav" class="navbar-menu">
            <div class="navbar-end">
                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link" href="#"><strong>Start Session</strong></a>
                    <div class="navbar-dropdown p-2">
                        <form class="form" method="POST" id="formLogin" action="<?= "$WEBROOT/auth/login.php" ?>">
                            <div class="field">
                                <div class="control">
                                    <input name="username" id="username" class="input" placeholder="User" type="text" required>
                                </div>
                            </div>
                            <div class="field">
                                <div class="control">
                                    <input name="password" id="password" class="input" placeholder="Password" type="password" required>
                                </div>
                            </div>
                            <div class="field">
                                <div class="control">
                                    <button type="submit" id="btnLogin" class="button is-primary is-fullwidth">Login</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
