<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>

<ul class="pagination pagination-sm m-0 float-end" aria-label="<?= lang('Pager.pageNavigation') ?>">

	<?php if ($pager->hasPrevious()) : ?>
		<!-- First Page -->
		<li class="page-item">
			<a class="page-link" href="<?= $pager->getFirst() ?>" aria-label="<?= lang('Pager.first') ?>">
				&laquo;&laquo;
			</a>
		</li>

		<!-- Previous Page -->
		<li class="page-item">
			<a class="page-link" href="<?= $pager->getPrevious() ?>" aria-label="<?= lang('Pager.previous') ?>">
				&laquo;
			</a>
		</li>
	<?php endif; ?>

	<!-- Page Number Links -->
	<?php foreach ($pager->links() as $link) : ?>
		<li class="page-item <?= $link['active'] ? 'active' : '' ?>">
			<a class="page-link" href="<?= $link['uri'] ?>">
				<?= $link['title'] ?>
			</a>
		</li>
	<?php endforeach; ?>

	<?php if ($pager->hasNext()) : ?>
		<!-- Next Page -->
		<li class="page-item">
			<a class="page-link" href="<?= $pager->getNext() ?>" aria-label="<?= lang('Pager.next') ?>">
				&raquo;
			</a>
		</li>

		<!-- Last Page -->
		<li class="page-item">
			<a class="page-link" href="<?= $pager->getLast() ?>" aria-label="<?= lang('Pager.last') ?>">
				&raquo;&raquo;
			</a>
		</li>
	<?php endif; ?>
</ul>