<main class="col-md-12">
    <form action="login.php" method="post">
        <fieldset>
            <div class="form-group">
                <input autofocus class="form-control" name="username" placeholder="<?= LABEL_USERNAME ?>" type="text"/>
            </div>
            <div class="form-group">
                <input class="form-control" name="password" placeholder="<?= LABEL_PASSWORD ?>" type="password"/>
                <br /><small><a href="forgot.php"><?= LABEL_FORGOT_PASS ?></a></small>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-default"><?= LABEL_LOGIN ?></button>
            </div>
        </fieldset>
        
    </form>
        <h5 onclick="return confirm('Are you sure you want to do that?');"><?= LABEL_DEMO_ACCOUNTS ?></h5>
        <code>student 123456</code><br /> <code>teacher 123456</code>
</main>
