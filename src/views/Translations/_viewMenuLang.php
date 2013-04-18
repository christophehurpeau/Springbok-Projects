{include _viewMenu.php}
<?php HBreadcrumbs::add($lang,$project->translationLink($lang)); ?>

<div class="content alignRight">
	Translation : {* <? HHtml::select($repository->branches(),array()) ?> *}
</div>