<main class="col-md-12">
    <form action="register.php" method="post">
        <fieldset>
            <div class="form-group">
                <input autofocus class="form-control" name="name" placeholder="<?= LABEL_NAME ?>" type="text"/>
            </div>
            <div class="form-group">
                <input autofocus class="form-control" name="lastname" placeholder="<?= LABEL_LAST_NAME ?>" type="text"/>
            </div>
            <div class="form-group">
                <input autofocus class="form-control" name="username" placeholder="<?= LABEL_USERNAME ?>" type="text"/>
            </div>
            <div class="form-group">
                <input class="form-control" name="email" placeholder="<?= LABEL_EMAIL ?>" type="text"/>
            </div>
            <div class="form-group">
                <input class="form-control" name="password" placeholder="<?= LABEL_PASSWORD ?>" type="password"/>
            </div>
            <div class="form-group">
                <input class="form-control" name="confirmation" placeholder="<?= LABEL_CONFIRM_PASSWORD ?>" type="password"/>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-default"><?= LABEL_REGISTER ?></button>
            </div>
        </fieldset>
    </form>
    <div>
        or <a href="login.php">login</a>
    </div>
</main>
