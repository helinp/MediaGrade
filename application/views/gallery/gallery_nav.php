            <aside id="tri" class="col-md-3 col-lg-2 sidebar-wrapper p_sidemenu">

                <?php if(isset($_SESSION['id'])):?>
                    <?php if( ! empty($_SESSION['avatar'])):?>
                <div style="margin: 0 auto;">
                    <img src="<?= $_SESSION['avatar']?>" alt="user_avatar" class=" center-block img-circle img-responsive" style="text-align:center;width:100px" />
                </div>
                    <?php endif ?>
                <div style="margin-top:1em;"></div>
                <?php endif ?>

                <h3><?= _('Trier'); ?></h3>
                <h4><?= _('Par projet'); ?></h4>
                <?= form_open('gallery/project')?>
                <div class="input-group">
                  <?= form_dropdown('project', $op_projects, $selected_proj_op); ?>
                  <span class="input-group-btn">
                      <button type="submit" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-filter"></span></button>
                  </span>
                </div>
                <?= form_close()?>
                <h4><?= _('Par élève'); ?></h4>
                <?= form_open('gallery/user')?>
                <div class="input-group">
                <?= form_dropdown('user', $op_users, $selected_user_op); ?>
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-filter"></span></button>
                </span>
                </div>
                <?= form_close()?>
                <br />


            </aside>
