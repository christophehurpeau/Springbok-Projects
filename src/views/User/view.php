<?php new AjaxContentView($user->name()) ?>
<h1>{$user->name()}</h1>
<br/>
<div class="floatL wp50">
	{t 'Registered on:'} <? HTime::nice($user->created) ?>
	
	<h3 class="mt10 noclear">{t 'Projects'}</h3>
	{ife $user->projects}
	<p class="mWarning">{t 'No projects.'}</p>
	{else}
	<ul>
	{f $user->projects as $project}
		<li>{link $project->name,$project->link()}</li>
	{/f}
	</ul>
	{/if}
</div>
<div class="floatL wp50">
	<h3 class="noclear">{t 'Activity'}</h3>
	
	{if $user->history->isEmptyResults()}
	<p class="mWarning">{t 'No activity yet.'}</p>
	{else}
		<? HPagination::simple($user->history) ?>
		<ul class="compact">
		<?php $lastDay=null ?>
		{f $user->history->getResults() as $h}
			<?php $day=HTime::nice($h->created,false); ?>
			{if $lastDay!==$day}{if $lastDay!==null}</ul>{/if}<?php $lastDay=$day ?><li class="bold biginfo">{$day}</li><ul>{/if}
			<li class="mb6"><i><? HTime::hoursAndMinutes($h->created) ?></i> - <b>{$h->detailOperation()}</b>
					{if $h->hasDetails()}{=$h->detailsFromUser($h->project)}{if $h->hasMoreDetails()}<div class="smallinfo">{$h->moreDetails()}</div>{/if}{/if}</li>
		{/f}
		</ul></ul>
	{/if}

</div>
<br class="clear"/>