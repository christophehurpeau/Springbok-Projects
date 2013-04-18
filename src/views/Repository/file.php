<?php new AjaxContentView($path); ?>
{include _viewMenu.php}
	{include _viewMenuFile.php}
	<pre class="clear block1 brush:{$brush}">{$content}</pre>
	<? HHtml::jsReady('SyntaxHighlighter.highlight()') ?>
</div>