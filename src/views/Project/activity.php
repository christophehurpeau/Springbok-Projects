<?php new AjaxContentView(_t('Activity')); ?>

<?php $current='activity' ?>
{include _viewMenu.php}

{if $project->history->isEmptyResults()}
<p class="mGray">{t 'No activity yet.'}</p>
{else}
	<? HPagination::simple($project->history) ?>
	<ul>
	<?php $lastDay=null ?>
	{f $project->history->getResults() as $h}
		<?php $day=HTime::nice($h->created,false); ?>
		{if $lastDay!==$day}{if $lastDay!==null}</ul>{/if}<?php $lastDay=$day ?><li class="bold biginfo">{$day}</li><ul class="compact">{/if}
		<li class="mb6"><i><? HTime::hoursAndMinutes($h->created) ?></i> - <b>{$h->detailOperation()}</b>
				{if $h->hasDetails()}{=$h->detailsFromProject($project)}{if $h->hasMoreDetails()}<div class="smallinfo">{$h->moreDetails()}</div>{/if}{/if}</li>
	{/f}
	</ul></ul>
{/if}
