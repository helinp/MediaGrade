<main class="col-md-12">
    <form action="register.php" method="post">
        <fieldset>
            <div class="form-group">
                <input autofocus class="form-control" name="name" placeholder="<?= $lang['NAME'] ?>" type="text"/>
            </div>
            <div class="form-group">
                <input autofocus class="form-control" name="lastname" placeholder="<?= $lang['LAST_NAME'] ?>" type="text"/>
            </div>
            <div class="form-group">
                <input autofocus class="form-control" name="username" placeholder="<?= $lang['USERNAME'] ?>" type="text"/>
            </div>
            <div class="form-group">
                <input class="form-control" name="email" placeholder="<?= $lang['EMAIL'] ?>" type="text"/>
            </div>
            <div class="form-group">
                <input class="form-control" name="password" placeholder="<?= $lang['PASSWORD'] ?>" type="password"/>
            </div>
            <div class="form-group">
                <input class="form-control" name="confirmation" placeholder="<?= $lang['CONFIRM_PASSWORD'] ?>" type="password"/>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-default"><?= $lang['REGISTER'] ?></button>
            </div>
        </fieldset>
    </form>
    <div>
        or <a href="login.php">login</a>
    </div>
</main>
