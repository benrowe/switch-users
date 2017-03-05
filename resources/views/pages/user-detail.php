<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Profile: <?=$model->name?> (id #<?=$model->user_id?>)</h4>
</div>
<div class="modal-body">
    <dl>
        <dt>User ID</dt>
        <dd><?=$model->user_id?></dd>
        <dt>Name</dt>
        <dd><?=$model->name?></dd>
        <dt>Email</dt>
        <dd><a href="mailto:<?=$model->email?>"><?=$model->email?></a></dd>
        <dt>Role</dt>
        <dd><?=$model->role?></dd>
        <dt>Department</dt>
        <dd><?=$model->department?></dd>
        <dt>Date of Birth</dt>
        <dd><?=$model->dob?> (<?=$model->ageInYears?> years old)</dd>
        <dt>Street Address</dt>
        <dd><?=$model->street_address_1?><br><?=$model->street_address_2?></dd>
        <dt>Suburb</dt>
        <dd><?=$model->suburb?></dd>
        <dt>State</dt>
        <dd><?=$model->state?></dd>
        <dt>Postcode</dt>
        <dd><?=$model->postcode?></dd>
        <dt>Country</dt>
        <dd><?=$model->country?></dd>
    </dl>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
