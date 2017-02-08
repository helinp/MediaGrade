            </div> <!-- ROW-->

            <?php if(isset($_SESSION['id'])):?>
			<footer class="footer" style="padding:1em 0;">
                <div class="container text-center">
			<?php if(! DEMO_VERSION): ?>
                 	<p><small>Les médias présents sur ce site sont soumis au droit d'auteur. Toute utilisation non expressement autorisée est strictement interdite.<br />
			<?php endif ?>			
			<a href="https://github.com/helinp/MediaGrade">MediaGrade</a> (<?= RELEASE_VERSION ?>) | Copyright 2015-<?= date('Y'); ?>, Pierre Hélin
			</small></p>                	
			<a class="hidden-print" rel="license" href="http://www.gnu.org/licenses/agpl-3.0.html"><img alt="GNU Affero General Public License"  style="border-width:0;width: 75px;" src="/assets/img/AGPLv3.png" /></a>
            	</div>
			</footer>
        	<?php endif?>
 		</div> <!-- Contener-->

    <script src="/assets/js/bootstrap.min.js"></script>
 	<script src="https://twitter.github.io/typeahead.js/js/handlebars.js"></script>

    </body>
</html>
