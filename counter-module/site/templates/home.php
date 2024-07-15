<?php

namespace ProcessWire;

?>

<div id="content">
	<h1><?php echo $page->title; ?></h1>
	<hr>
	<div class="space-y-8">
		<div id="flipped" class="border-2 p-4 rounded-md" hx-trigger="click" hx-get="/api/up/" hx-target="#flipped" hx-swap="outerHTML">
			<span hx-get="/api/count/" hx-trigger="load" hx-swap="innerHTML">0</span>
		</div>
		<div id="flopped" class="border-2 p-4 rounded-md" hx-trigger="click" hx-get="/api/down/" hx-target="#flopped" hx-swap="outerHTML">
			<span hx-get="/api/count/" hx-trigger="load" hx-swap="innerHTML">0</span>
		</div>
		<div id="alert"></div>
	</div>
</div>