<?php
$this->assign('title', 'Virtuozzo Users');
?>

<nav class="w-full h-14 p-2 text-center font-bold shadow-md z-20 flex items-center justify-between gap-x-4 bg-white">
  <div><?= __('Virtuozzo Users') ?></div>
</nav>

<div style="max-height: calc(100vh - 173px);" class="overflow-auto shadow-md sm:rounded-lg">
  <table class="border w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
    <thead class="sticky top-0 bg-gray-300 dark:bg-gray-800 text-xs text-gray-700 uppercase dark:text-gray-400">
      <tr class="text-center text-gray dark:text-white">
        <th scope="col" class="border px-4 my-3">#</th>
        <th scope="col" class="border px-4 my-3">User ID</th>
        <th scope="col" class="border">
          <?= $this->Html->link(
            __('<div class="w-auto">Email ').
            ((isset($queryParams['orderField']) && $queryParams['orderField'] == 'email')
            ? (isset($queryParams['orderDirection']) && $queryParams['orderDirection'] == 'asc'
              ? '&darr;'
              : '&uarr;')
            : '')
             .
            __('</div>'),
            ['?' => [
              'orderField' => 'email',
              'orderDirection' => isset($queryParams['orderField']) && $queryParams['orderField'] == 'email'
                ? (
                  isset($queryParams['orderDirection']) && $queryParams['orderDirection'] == 'asc'
                  ? 'desc'
                  : 'asc')
                : 'asc'
            ]],
            ['class' => 'mx-4 my-3', 'escape' => false]
          ) ?>
        </th>
        <th scope="col" class="border px-4 my-3">
          <?= $this->Html->link(
            // __('<div class="w-auto">Phone Number</div>'),
            __('<div class="w-auto">Phone Number ').
            ((isset($queryParams['orderField']) && $queryParams['orderField'] == 'phoneNumber')
            ? (isset($queryParams['orderDirection']) && $queryParams['orderDirection'] == 'asc'
              ? '&darr;'
              : '&uarr;')
            : '')
             .
            __('</div>'),
            ['?' => [
              'orderField' => 'phoneNumber',
              'orderDirection' => isset($queryParams['orderField']) && $queryParams['orderField'] == 'phoneNumber'
                ? (
                  isset($queryParams['orderDirection']) && $queryParams['orderDirection'] == 'asc'
                  ? 'desc'
                  : 'asc')
                : 'asc'
            ]],
            ['class' => 'mx-4 my-3', 'escape' => false]
          ) ?>
        </th>
        <th scope="col" class="border">
          <?= $this->Html->link(
            __('<div class="w-auto">Group ').
            ((isset($queryParams['orderField']) && $queryParams['orderField'] == 'group')
            ? (isset($queryParams['orderDirection']) && $queryParams['orderDirection'] == 'asc'
              ? '&darr;'
              : '&uarr;')
            : '')
             .
            __('</div>'),
            ['?' => [
              'orderField' => 'group',
              'orderDirection' => isset($queryParams['orderField']) && $queryParams['orderField'] == 'group'
                ? (
                  isset($queryParams['orderDirection']) && $queryParams['orderDirection'] == 'asc'
                  ? 'desc'
                  : 'asc')
                : 'asc'
            ]],
            ['class' => 'mx-4 my-3', 'escape' => false]
          ) ?>
        </th>
        <th scope="col" class="border">
          <?= $this->Html->link(
            __('<div class="w-auto">Balance ').
            ((isset($queryParams['orderField']) && $queryParams['orderField'] == 'balance')
            ? (isset($queryParams['orderDirection']) && $queryParams['orderDirection'] == 'asc'
              ? '&darr;'
              : '&uarr;')
            : '')
             .
            __('</div>'),
            ['?' => [
              'orderField' => 'balance',
              'orderDirection' => isset($queryParams['orderField']) && $queryParams['orderField'] == 'balance'
                ? (
                  isset($queryParams['orderDirection']) && $queryParams['orderDirection'] == 'asc'
                  ? 'desc'
                  : 'asc')
                : 'asc'
            ]],
            ['class' => 'mx-4 my-3', 'escape' => false]
          ) ?>
        </th>
        <th scope="col" class="border">
          <?= $this->Html->link(
            __('<div class="w-auto">Since Date (UTC) ').
            ((isset($queryParams['orderField']) && $queryParams['orderField'] == 'createdOn')
            ? (isset($queryParams['orderDirection']) && $queryParams['orderDirection'] == 'asc'
              ? '&darr;'
              : '&uarr;')
            : '')
             .
            __('</div>'),
            ['?' => [
              'orderField' => 'createdOn',
              'orderDirection' => isset($queryParams['orderField']) && $queryParams['orderField'] == 'createdOn'
                ? (
                  isset($queryParams['orderDirection']) && $queryParams['orderDirection'] == 'asc'
                  ? 'desc'
                  : 'asc')
                : 'asc'
            ]],
            ['class' => 'mx-4 my-3', 'escape' => false]
          ) ?>
        </th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($records)): ?>
        <?php foreach ($records as $index => $record): ?>
          <tr class="border-b border-gray-200 dark:border-gray-700">
            <td class="border px-4 py-3 bg-gray-200 text-gray dark:bg-gray-800 dark:text-white text-right">
              <?= h($index + 1) ?>
            </td>
            <td class="border px-4 py-3">
              <?= h($record['uid']) ?>
            </td>
            <td class="border px-4 py-3">
              <?= h($record['email']) ?>
            </td>
            <td class="border px-4 py-3">
              <?= h($record['phoneNumber'] ?? null) ?>
            </td>
            <td class="border px-4 py-3">
              <?= h($record['group']['name']) ?>
            </td>
            <td class="border px-4 py-3 text-right">
              <?= h($record['balance']) ?>
            </td>
            <td class="border px-4 py-3 text-right">
              <?= h($record['createdOn']) ?>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr class="text-center">
          <td colspan="5" class="px-6 py-3">No record found</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?= $this->MyPaginator->render($paginator) ?>

<!-- <div class="w-full bg-white shadow-md p-2 flex justify-center space-x-4 border-t">
  <?php
  $page = (int) $this->request->getQuery('page', 1);
  $isFirst = ($page == 1) ? 'pointer-events-none opacity-50 disable' : '';
  $islast = ($page == $totalPages) ? 'pointer-events-none opacity-50 disable' : '';
  ?>

  <?= $this->Html->link(
    "&lt;&lt; First",
    $page > 1 ? ['?' => array_merge($this->request->getQuery(), ['page' => 1])] : 'javascript:void(0);',
    ['escape' => false, 'class' => 'px-4 py-2 border rounded ' . $isFirst]
  ) ?>

  <?= $this->Html->link(
    "&lt; Previous",
    $page > 1 ? ['?' => array_merge($this->request->getQuery(), ['page' => $this->request->getQuery('page') - 1])] : 'javascript:void(0);',
    ['escape' => false, 'class' => 'px-4 py-2 border rounded ' . $isFirst]
  ) ?>

  <select class="border rounded ring-0" id="pageSelector">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <option <?= ($this->request->getQuery('page') == $i ? 'selected' : '') ?>><?= $i ?></option>
    <?php endfor; ?>
  </select>

  <?= $this->Html->link(
    "Next &gt;",
    $page < $totalPages ? ['?' => array_merge($this->request->getQuery(), ['page' => $this->request->getQuery('page') + 1])] : 'javascript:void(0);',
    ['escape' => false, 'class' => 'px-4 py-2 border rounded ' . $islast]
  ) ?>

  <?= $this->Html->link(
    "Last &gt;&gt;",
    $page < $totalPages ? ['?' => array_merge($this->request->getQuery(), ['page' => $totalPages])] : 'javascript:void(0);',
    ['escape' => false, 'class' => 'px-4 py-2 border rounded ' . $islast]
  ) ?>
</div> -->








<?php $this->start('script'); ?>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    let pageSelector = document.getElementById('pageSelector');
    if (pageSelector) { // Ensure the element exists before using it
      pageSelector.addEventListener('change', function() {
        let selectedPage = this.value;
        let url = new URL(window.location.href);
        url.searchParams.set('page', selectedPage);
        window.location.href = url.toString();
      });
    }

    let countSelector = document.getElementById('countSelector');
    if (countSelector) { // Ensure the element exists before using it
      countSelector.addEventListener('change', function() {
        let selectedPage = this.value;
        let url = new URL(window.location.href);
        url.searchParams.set('resultCount', selectedPage);
        window.location.href = url.toString();
      });
    }
  });
</script>
<?php $this->end(); ?>
