


<div class="vertical-center">

    <main class="col-md-2 col-sm-8 text-center login" >
      <h1 style="margin-bottom:0"><img alt="MediaGrade" src="/assets/img/logo_white.svg" style="width:150px"/></h1>
        <?php echo form_open('login'); ?>
            <fieldset >
                <div class="form-group">
                    <input autofocus class="form-control" name="username" placeholder="<?= LABEL_USERNAME ?>" type="text"/>
                </div>
                <div class="form-group">
                    <input class="form-control" name="password" autocomplete="off" placeholder="<?= LABEL_PASSWORD ?>" type="password"/>
                </div>
                <?php if(!empty(validation_errors())): ?>
                <div class="alert alert-danger" role="alert">
    			  <span class="sr-only">Error:</span> <?= validation_errors(); ?>
    			</div>
                <?php endif ?>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block"><?= LABEL_LOGIN ?></button>
                </div>
            </fieldset>
        </form>

        <?php if(DEMO_VERSION): ?>
        <div class="demo-version">
            <h5 style="margin-top:0"><b><?= _('Comptes de démonstration') ?></b></h5>
            <code>student 123456</code><br /> <code>teacher 123456</code>
        </div>
        <?php endif ?>
    </main>
</div>
