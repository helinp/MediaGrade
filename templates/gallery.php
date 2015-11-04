
        <div class="row" style="padding-top:2em;">
 
        <?php foreach ($medias as $media): ?>
            
            <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                
                    
                    <?php if ($media['extension'] == "mp4" || $media['extension'] == "mov" || $media['extension'] == "avi"):?>    
                    <div class="thumbnail">
                        <video class="img-responsive" controls preload="none">
                            <source src="http://final_project/<?= $media['file']?>" type="video/mp4">
                            <source src="mov_bbb.ogg" type="video/ogg">
                            <p><?= $lang['NO_HTML5_VIDEO'] ?> <a href="<?= $media['file'] ?>"><?= $lang['HERE'] ?></a></p>
                        </video>  
                        <a href="<?= $media['file'] ?>"><?= $media['name'] . " " . $media['last_name'] . " @ " . $media['project_name']?></a>
                    </div>    
                    <?php elseif($media['extension'] == "jpg" || $media['extension'] == "png" || $media['extension'] == "gif"): ?>
                        <a class="thumbnail" href="<?= $media['file'] ?>">
                        <img class="img-responsive" src="<?= $media['file'] ?>" alt="<?= $media['name'] ?>">
                        <?= $media['name'] . " " . $media['last_name'] . " @ " . $media['project_name']?></a>
                    <?php endif ?>
               
                
            </div>
        <?php endforeach ?>
        </div>


