<?php

use Cake\Log\Log;
use App\Service\HostBillService;
use App\Service\VirtuozzoService;

$records = [];
$virtuozzoData = [];
$hbData = [];

$vtStatusMap = [
  1 => 'Active',
  2 => 'Inactive',
  3 => 'Destroyed',
  4 => 'Deleted'
];

// Call VirtuozzoService
$virtuozzoService = new VirtuozzoService($this->request);
$virtuozzoResponse = $virtuozzoService->getData("billing/account/rest/getaccounts", []);

if ($virtuozzoResponse['success']) {
  $virtuozzoRecords = $virtuozzoResponse['data']['array'] ?? [];

  foreach ($virtuozzoResponse['data']['array'] as $item) {
      $virtuozzoData[] = [
          'email'  => $item['email'] ?? null,
          'status' => isset($item['status']) ? $vtStatusMap[$item['status']] : null,  //isset($item['status']) ? (int) $item['status'] : null, // Explicit type casting for consistency
          'group'  => $item['group']['name'] ?? null,
          'virtuozzo'    => $item['createdOn'] ?? null,
          'hostbill'    => null
      ];
  }

  // header('Content-Type: application/json; charset=utf-8');
  // die(json_encode($virtuozzoData));
} else {
  $this->Flash->error(__($virtuozzoResponse['error']));
  Log::write('error', json_encode($virtuozzoResponse['error'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

// Call HostBillService
$hostBillService = new HostBillService();
$queryParams = [
  'call' => 'getClients',
  'filter[brand]'=> 'CloudPe',
  'perpage' =>  count($virtuozzoData) * 2
];
$hostBillResponse = $hostBillService->getData($queryParams);
if ($hostBillResponse['success']) {
  $hbRecords = $hostBillResponse['data']['clients'] ?? [];

  // header('Content-Type: application/json; charset=utf-8');
  // die(json_encode($hbRecords));

  foreach ($hbRecords as $item) {
      $hbData[] = [
          'email'  => $item['email'] ?? null,
          'status' => $item['status'] ?? null,
          'group'  => $item['group_name'] ?? null,
          'hostbill'    => $item['datecreated'] ?? null,
          'virtuozzo'    => null
      ];
  }

  // header('Content-Type: application/json; charset=utf-8');
  // die(json_encode($hbData));
} else {
  $this->Flash->error(__($hostBillResponse['error']));
  Log::write('error', json_encode($hostBillResponse['error'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}


// Extract email lists
$virtEmails = array_column($virtuozzoData, 'email');
$hbEmails = array_column($hbData, 'email');

// Find common emails
$commonEmails = array_intersect($virtEmails, $hbEmails);

// Filter out common emails
$virtuozzoData = array_filter($virtuozzoData, function ($item) use ($commonEmails) {
    return !in_array($item['email'], $commonEmails, true);
});

$hbData = array_filter($hbData, function ($item) use ($commonEmails) {
    return !in_array($item['email'], $commonEmails, true);
});

// Re-index arrays
$virtuozzoData = array_values($virtuozzoData);
$hbData = array_values($hbData);

// **Merge Remaining Data**
$mergedData = array_merge($virtuozzoData, $hbData);

// header('Content-Type: application/json; charset=utf-8');
// die(json_encode($mergedData));

$filteredGroups = ['post', 'beta'];
$filteredStatuses = ['Active'];

// **Filter array
$records = array_filter($mergedData, function ($item) use ($filteredGroups, $filteredStatuses) {
  return in_array($item['group'], $filteredGroups, true) && in_array($item['status'], $filteredStatuses, true);
});

// **Re-index array
$records = array_values($records);

// header('Content-Type: application/json; charset=utf-8');
// die(json_encode($records));


// **BEST PRACTICE: Use array_multisort() for performance**
if (!empty($records)) {
  $emails = array_column($records, 'email');
  array_multisort($emails, SORT_ASC, SORT_STRING | SORT_FLAG_CASE, $records);
}


// header('Content-Type: application/json; charset=utf-8');
// die(json_encode($records));

if ($this->request->getQuery('download') !== null) {

    // Decode JSON to PHP array
    $dataArray = $records;

    if (empty($dataArray)) {
      $this->Flash->error(__('Invalid JSON data.'));
      return $this->redirect($this->referer());
    }

    // Set Headers to force download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="missmatch_users.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Open the output buffer to prevent any prior output issues
    ob_clean();
    flush();

    // Open output stream for writing
    $output = fopen('php://output', 'w');

    // Add headers (Extract keys from the first element)
    fputcsv($output, array_keys($dataArray[0]));

    // Add rows
    foreach ($dataArray as $row) {
        fputcsv($output, $row);
    }

    // Close the file pointer
    fclose($output);

    // Ensure no further output is sent
    exit;
}

$this->assign('title', 'Missmatch Users');
?>

<div class="w-full px-4 py-2 text-center font-bold shadow-md z-20 flex items-center justify-between gap-x-4 bg-white">
  <h3>Missmatch User</h3>
  <?= $this->Html->link(__('Download'), ['controller' => 'Pages', 'action' => 'display', 'missmatch_users', '?' => ['download' => 'csv']], ['target' => '_blank', 'class' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800']) ?>
</div>

<div style="max-height: calc(100vh - 0);" class="overflow-auto shadow-md sm:rounded-lg">
  <table class="border w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
    <thead class="sticky top-0 bg-gray-300 dark:bg-gray-800 text-xs text-gray-700 uppercase dark:text-gray-400">
      <tr class="text-center text-gray dark:text-white">
        <th scope="col" class="border px-4 py-3">#</th>
        <th scope="col" class="border px-4 py-3">Email</th>
        <th scope="col" class="border px-4 py-3">group</th>
        <th scope="col" class="border px-4 py-3">Status</th>
        <th scope="col" class="border px-4 py-3">Virtuozzo</th>
        <th scope="col" class="border px-4 py-3">Hostbill</th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($records)): ?>
        <?php foreach ($records as $index => $record): ?>
          <tr class="border-b border-gray-200 dark:border-gray-700">
            <td class="border px-4 py-3 bg-gray-200 text-gray dark:bg-gray-800 dark:text-white text-right">
              <?= h($index + 1) ?>
            </td>
            <td class="border px-4 py-3"><?= h($record['email']) ?></td>
            <td class="border px-4 py-3"><?= h($record['group']) ?></td>
            <td class="border px-4 py-3"><?= h($record['status']) ?></td>
            <td class="border px-4 py-3"><?= h($record['virtuozzo']) ?></td>
            <td class="border px-4 py-3"><?= h($record['hostbill']) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr class="text-center">
          <td colspan="6" class="border px-6 py-3">No record found</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
