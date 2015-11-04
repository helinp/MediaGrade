<main class="col-md-12">
    <form action="login.php" method="post">
        <fieldset>
            <div class="form-group">
                <input autofocus class="form-control" name="username" placeholder="<?=$lang['USERNAME']?>" type="text"/>
            </div>
            <div class="form-group">
                <input class="form-control" name="password" placeholder="<?=$lang['PASSWORD']?>" type="password"/>
                <br /><small><a href="forgot.php"><?=$lang['FORGOT_PASS']?></a></small>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-default"><?=$lang['LOGIN']?></button>
            </div>
        </fieldset>
    </form>
    
</main>
