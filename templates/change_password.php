<main class="col-md-12">
    <form action="forgot.php" method="post">
        <fieldset>
            <div class="form-group">
                <input class="form-control" name="password_new" placeholder="Type Password" type="password"/>
            </div>
            <div class="form-group">
                <input class="form-control" name="password_new_confirmation" placeholder="Confirm password" type="password"/>
            </div>
            <div class="form-group">
                <input  name="hash" value="<?= $hash ?>" type="hidden"/>
                <button type="submit" class="btn btn-default">Change Password</button>
            </div>
        </fieldset>
    </form>
</main>
