
           </div> <!-- Container-->

           <?php if(isset($_SESSION['id'])):?>
       <footer class="footer">
                <div class="container text-center">
                   <p><small><a href="https://github.com/helinp/MediaGrade">MediaGrade</a> (<?= VERSION ?>)<br />Copyright 2015-<?= date('Y'); ?>, Pierre Hélin</small></p>
                    <a rel="license" href="http://www.gnu.org/licenses/agpl-3.0.html"><img alt="GNU Affero General Public License" style="border-width:0;width: 100px;" src="/assets/img/AGPLv3.png" /></a>
                </div>
           </footer>
         <?php endif?>
    </body>
</html>
