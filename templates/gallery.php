
        <div class="row" style="padding-top:2em;">
 
        <?php foreach ($medias as $media): ?>
            
            <div class="col-lg-3 col-md-4 col-xs-6 ">
                
                    
                    <?php if ($media['extension'] == "mp4" || $media['extension'] == "mov" || $media['extension'] == "avi"):?>    
                    <div class="thumbnail">
                       <div class="embed-responsive embed-responsive-16by9">
                            <video class="embed-responsive-item" preload="metadata">
                                <source src="http://final_project/<?= $media['file']?>" type="video/mp4">
                                <p><?= LABEL_NO_HTML5_VIDEO ?> <a href="<?= $media['file'] ?>"><?= LABEL_HERE ?></a></p>
                            </video>  
                          
                        </div>
                        <a href="<?= $media['file'] ?>"><?= $media['name'] . " " . $media['last_name'] . " @ " . $media['project_name']?> (vidéo)</a> 
                    </div>
                    <?php elseif($media['extension'] == "jpeg" || $media['extension'] == "jpg" || $media['extension'] == "png" || $media['extension'] == "gif"): ?>
                    <div class="thumbnail">    
                        <img class="img-responsive" src="<?= $media['thumbnail'] ?>" alt="<?= $media['name'] ?> /">
                        <a  href="<?= $media['file'] ?>"><?= $media['name'] . " " . $media['last_name'] . " @ " . $media['project_name']?></a>
                    </div>
                    <?php endif ?>
               
                
            </div>
        <?php endforeach ?>
        </div>


