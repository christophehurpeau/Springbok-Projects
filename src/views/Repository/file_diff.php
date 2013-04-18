<?php new AjaxContentView($path.' - '._t('Diff')) ?>
{include _viewMenu.php}
	{include _viewMenuFile.php}
	<pre class="clear block1 brush:{$brush}">{$content}</pre>
</div>