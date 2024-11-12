<nav class="navbar is-fixed-top is-primary">
    <div class="container">
        <div class="navbar-brand">
            <a class="navbar-logo" href="/">
                <img src="<?= "$web_root/assets/images/logo.png" ?>" alt="Logo de XVLLMWA">
            </a>
        </div>
        <div id="navbarNav" class="navbar-menu">
            <div class="navbar-end">
                <div class="navbar-item has-dropdown is-hoverable">
                    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                        <a class="navbar-link" href="#"><strong><?= htmlspecialchars($_SESSION['username']) ?></strong></a>
                        <div class="navbar-dropdown">
                            <form method="POST" id="form_logout" action="<?= "$web_root/auth/logout.php" ?>">
                                <div class="field">
                                    <div class="control">
                                        <button type="submit" id="btn_logout" class="button is-light is-fullwidth">Logout</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php else: ?>
                        <a class="navbar-link" href="#"><strong>Start Session</strong></a>
                        <div class="navbar-dropdown">
                            <form method="POST" id="form_login" action="<?= "$web_root/auth/login.php" ?>">
                                <div class="field">
                                    <label class="label" for="username">User</label>
                                    <div class="control">
                                        <input name="username" id="username" class="input" placeholder="User" type="text" required>
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="label" for="password">Password</label>
                                    <div class="control">
                                        <input name="password" id="password" class="input" placeholder="Password" type="password" required>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="control">
                                        <button type="submit" id="btn_login" class="button is-primary is-fullwidth">Login</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</nav>
