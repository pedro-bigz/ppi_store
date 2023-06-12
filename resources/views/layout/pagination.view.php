<div class="pagination">
    <a class="link" href="javascript:void(0)">&laquo;</a>
    <?php foreach ($pages as $key => $page): ?>
        <a class="link <?= !$page['active'] ?: 'active' ?>" href="javascript:void(0)"><?= ($key + 1) ?></a>
    <?php endforeach ?>
  <a class="link" href="javascript:void(0)">&raquo;</a>
</div>