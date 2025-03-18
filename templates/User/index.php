<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
  <table class="border w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
    <thead class="text-xs text-gray-700 uppercase dark:text-gray-400">
      <tr class="text-center">
        <th scope="col" class="border px-6 py-3 bg-gray-50 dark:bg-gray-800">ID</th>
        <th scope="col" class="border px-6 py-3">Email</th>
        <th scope="col" class="border px-6 py-3">Group</th>
        <th scope="col" class="border px-6 py-3">Balance</th>
        <th scope="col" class="border px-6 py-3">Signup Date (UTC)</th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($records)): ?>
        <?php foreach ($records as $record): ?>
          <tr class="border-b border-gray-200 dark:border-gray-700">
            <td class="border px-6 py-4 bg-gray-50 dark:bg-gray-800">
              <?= h($record['id']) ?>
            </td>
            <td class="border px-6 py-4">
              <?= h($record['email']) ?>
            </td>
            <td class="border px-6 py-4">
              <?= h($record['group']['type']) ?>
            </td>
            <td class="border px-6 py-4 text-right">
              <?= h($record['balance']) ?>
            </td>
            <td class="border px-6 py-4">
              <?= h($record['createdOn']) ?>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php else: ?>
          <tr class="text-center"><td colspan="5" class="px-6 py-3">No record found</td></tr>
        <?php endif; ?>
    </tbody>
  </table>
</div>
