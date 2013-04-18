<?php new AjaxContentView(_t('Members')) ?>

<?php $current='members'; $isAdmin=$project->isAdmin(); ?>
{include _viewMenu.php}

	<div class="row">
		<div class="col">
		<table class="table">
			<thead><tr><th>User / Group</th><th>Roles</th>{if $isAdmin}<th>Actions</th>{/if}</tr></thead>
			<tbody>
				{ife $project->members}<tr><td colspan="3"><div class="mGray">{t 'No members'}</div></td></tr>
				{else}
					{f $project->members as $member}<tr>
						<td>{=$member->user->linkedName()}</td><td>{$member->roles()}</td>
						{if $isAdmin}<td>{link 'Edit',$project->link('editMember','/'.$member->id)} | Delete</td>{/if}
					</tr>{/f}
				{/if}
			</tbody>
		</table>
		</div>
		{if $isAdmin && !empty($potentialNewMembers)}
		<div class="col w280">
			<?php $form=HForm::create(null,array('action'=>$project->getLink('addMembers',''))) ?>
			{=$form->fieldsetStart(_t('New member'))}
			{=$form->multiple('user_id',$potentialNewMembers,array('style'=>'checkbox','label'=>false))}
			{=$form->multiple('role_id',AclGroup::findListName(),array('style'=>'checkbox','label'=>_t('Roles')))}
			{=$form->end()}
		</div>
		{/if}
	</div>
</div>