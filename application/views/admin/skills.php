<main class="col-md-8 text-left" id="content">
    <h2 class=" text-left"> <?= _('Liste des compétences')?></h2>
    <hr style="margin-top:0;" />
          <form action="/admin/skills/add_skill" method="post" role="form">
              <table id="rows" class="table">
                   <col width="5%">
                   <col width="90%">
                   <col width="5%">
                   <thead>
                       <tr>
                           <th>ID</th>
                           <th><?= _('Compétence')?></th>
                           <th></th>
                       </tr>
                   </thead>
                   <tbody>
                       <tr>
                           <td><input name="skill_id" class="form-control input-sm"></td>
                           <td><textarea name="skill" class="form-control input-sm" cols="50" rows="3" fixed></textarea></td>
                           <td><button type="submit" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-plus"></span></button></td>
                       </tr>
                   </tbody>
               </table>
          </form>

          <form action="/admin/skills/del_skill" method="post" role="form">
              <table id="rows" class="table table-striped">
                       <col width="5%">
                       <col width="90%">
                       <col width="5%">
                   <tbody>
              <?php foreach($skills as $skill): ?>
                       <tr>
                           <td><?= $skill->skill_id ?></td>
                           <td><?= $skill->skill ?></td>
                           <td>
                               <button type="submit" class="btn btn-danger btn-xs" name="skill_id" value="<?=  $skill->skill_id  ?>"><span class="glyphicon glyphicon-trash"></span></button>
                           </td>
                       </tr>
              <?php endforeach?>
                   </tbody>
              </table>
          </form>


   </main>
