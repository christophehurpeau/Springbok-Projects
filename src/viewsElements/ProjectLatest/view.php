<h5>{t 'Latest projects'}</h5>
{ife $projects}
	{t 'No projects.'}
{else}
	<ul>
	{f $projects as &$project}
		<li>{link $project->name,$project->link()}</li>
	{/f}
	</ul>
{/if}