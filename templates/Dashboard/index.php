<?php
  $this->assign('title', 'Dashboard');
?>
<div class="flex flex-col items-center justify-center bg-gray-100">
<div class="bg-white p-6 rounded-lg shadow-lg w-96 text-center">
    <h2 class="text-2xl font-bold mb-4">Welcome, <?= h($auth['name']) ?></h2>
    <p>You have successfully logged in.</p>
    <a href="<?= $this->Url->build([
                'controller' => 'Auth',
                'action' => 'logout',
              ]) ?>"
      class="mt-4 inline-block text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
      Logout
    </a>
  </div>
</div>
