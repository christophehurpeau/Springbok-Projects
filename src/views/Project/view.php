<?php new AjaxContentView(_t('Project:').' '.$project->name); HMeta::canonical($project->link()) ?>

<?php HBreadcrumbs::set(array(_t('Projects')=>'/project'),$project->name); ?>

<?php $current='overview' ?>
{include _viewMenu.php}
	<div class="floatR w280">
		<div class="block2">
			<h5>{t 'Latest activity'}</h5>
			{ife $project->history}
			<p class="mGray">{t 'No activity yet.'}</p>
			{else}
				<ul class="compact">
				<?php $lastDay=null ?>
				{f $project->history as $h}
					<?php $day=HTime::nice($h->created,false); ?>
					{if $lastDay!==$day}{if $lastDay!==null}</ul>{/if}<?php $lastDay=$day ?><li class="bold">{$day}</li><ul class="compact">{/if}
					<li class="mb6"><i><? HTime::hoursAndMinutes($h->created) ?></i> - {$h->detailOperation()}</li>
				{/f}
				</ul></ul>
			{/if}
		</div>
	</div>
</div>