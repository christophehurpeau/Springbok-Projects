<?php new AjaxContentView(_tC('Home'),'home'); HMeta::canonical('/') ?>

<div class="row">
	<div class="col">
		<?php View::includeFromData('texts/_home_text.php') ?>
	</div>
	<div class="col w200">
		<div class="content">
			<? VProjectLatest::create()->render() ?>
		</div>
	</div>
</div>
