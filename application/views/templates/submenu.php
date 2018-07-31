<?php if(isset($submenu)): ?>
<nav id="submenu" class="affix-top hidden-print" data-spy="affix" data-offset-top="90">
	<ul class="nav nav-tabs">
		<?php foreach ($submenu as $entry): ?>
			<li role="presentation" <?=  ( '/' . $this->uri->uri_string() == $entry['url']  ? ' class="active"' : '') ?>><a href="<?= $entry['url'] ?>"><?= $entry['title'] ?></a></li>
		<?php endforeach; ?>
	</ul>
</nav>
<?php endif ?>
