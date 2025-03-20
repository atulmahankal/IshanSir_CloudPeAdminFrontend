<?php
  $this->assign('title', 'HostBill Users');
?>

<!-- <nav class="w-full p-2 text-center font-bold shadow-md z-20 flex items-center justify-between gap-x-4 bg-white">
  <label class="flex items-center gap-x-2">
    <span>Per Page:</span>
    <select>
      <option <?= ($sorter['perpage'] === 10 ? 'selected' : '') ?>>10</option>
      <option <?= ($sorter['perpage'] === 25 ? 'selected' : '') ?>>25</option>
      <option <?= ($sorter['perpage'] === 50 ? 'selected' : '') ?>>50</option>
      <option <?= ($sorter['perpage'] === 100 ? 'selected' : '') ?>>100</option>
    </select>
  </label>
  <div>Display <?= ($sorter['perpage'] * $page) + 1 ?> to <?= ($sorter['perpage'] * ($page + 1)) ?> of <?= $sorter['sorterrecords'] ?> records</div>
</nav> -->

<div style="max-height: calc(100vh - 0 /*173px*/);" class="overflow-auto shadow-md sm:rounded-lg">
  <table class="border w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
    <thead class="sticky top-0 bg-gray-300 dark:bg-gray-800 text-xs text-gray-700 uppercase dark:text-gray-400">
      <tr class="text-center text-gray dark:text-white">
        <th scope="col" class="border px-4 py-3">#</th>
        <th scope="col" class="border px-4 py-3">ID</th>
        <th scope="col" class="border px-4 py-3">First Name</th>
        <th scope="col" class="border px-4 py-3">Last Name</th>
        <th scope="col" class="border px-4 py-3">Email</th>
        <th scope="col" class="border px-4 py-3" style="min-width: 110px;">Since Date (UTC)</th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($records)): ?>
        <?php foreach ($records as $index => $record): ?>
          <tr class="border-b border-gray-200 dark:border-gray-700">
            <td class="border px-4 py-3 bg-gray-200 text-gray dark:bg-gray-800 dark:text-white text-right" >
              <?= h($index + 1) ?>
            </td>
            <td class="border px-4 py-3">
              <?= h($record['id']) ?>
            </td>
            <td class="border px-4 py-3">
              <?= h($record['firstname']) ?>
            </td>
            <td class="border px-4 py-3">
              <?= h($record['lastname']) ?>
            </td>
            <td class="border px-4 py-3">
              <?= h($record['email']) ?>
            </td>
            <td class="border px-4 py-3 text-right">
              <?= h($record['datecreated']) ?>
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

<!-- <div class="w-full bg-white shadow-md p-2 flex justify-center space-x-4 border-t">
  <button class="px-4 border rounded">&lt;&lt; First</button>
  <button class="px-4 border rounded">&lt; Previous</button>
  <select class="border rounded ring-0">
    <option>1</option>
    <option>2</option>
    <option>3</option>
    <option>4</option>
  </select>
  <button class="px-4 border rounded">Next &gt;</button>
  <button class="px-4 border rounded">Last &gt;&gt;</button>
</div> -->
