<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2);

// Function to safely append current query params to any pagination URL
function appendQuery(string $uri): string
{
	$query = $_GET ?? [];
	unset($query['page']);

	if (empty($query)) {
		return $uri;
	}

	// Parse existing query from URI (if any)
	$parsedUrl = parse_url($uri);
	$existingQuery = [];

	if (isset($parsedUrl['query'])) {
		parse_str($parsedUrl['query'], $existingQuery);
	}

	// Merge existing and new query params (existing has priority)
	$merged = array_merge($query, $existingQuery);

	$finalQuery = http_build_query($merged);
	$baseUri = strtok($uri, '?'); // remove existing query string

	return $baseUri . '?' . $finalQuery;
}
?>

<ul class="pagination pagination-sm m-0 float-end" aria-label="<?= lang('Pager.pageNavigation') ?>">

	<?php if ($pager->hasPrevious()) : ?>
		<li class="page-item">
			<a class="page-link" href="<?= appendQuery($pager->getFirst()) ?>" aria-label="<?= lang('Pager.first') ?>">
				&laquo;&laquo;
			</a>
		</li>
		<li class="page-item">
			<a class="page-link" href="<?= appendQuery($pager->getPrevious()) ?>" aria-label="<?= lang('Pager.previous') ?>">
				&laquo;
			</a>
		</li>
	<?php endif; ?>

	<?php foreach ($pager->links() as $link) : ?>
		<li class="page-item <?= $link['active'] ? 'active' : '' ?>">
			<a class="page-link" href="<?= appendQuery($link['uri']) ?>">
				<?= $link['title'] ?>
			</a>
		</li>
	<?php endforeach; ?>

	<?php if ($pager->hasNext()) : ?>
		<li class="page-item">
			<a class="page-link" href="<?= appendQuery($pager->getNext()) ?>" aria-label="<?= lang('Pager.next') ?>">
				&raquo;
			</a>
		</li>
		<li class="page-item">
			<a class="page-link" href="<?= appendQuery($pager->getLast()) ?>" aria-label="<?= lang('Pager.last') ?>">
				&raquo;&raquo;
			</a>
		</li>
	<?php endif; ?>
</ul>