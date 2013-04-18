<?php new AjaxPageView($layout_title,'') ?>
<div class="col variable"><?php HBreadcrumbs::display(_tC('Home'),$layout_title) ?><? CSession::flash() ?><? $layout_content ?></div>