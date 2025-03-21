<div class="paginator-container" style="display: flex; justify-content: space-between; align-items: center; padding: 10px;">

  <!-- left div: Per page dropdown -->
  <div class="paginator-left">
    <label for="perpage">Show </label>
    <select id="perpage" onchange="changePerPage(this.value)" style="border-width: 0; padding-top: 0px; padding-bottom: 0px;">
      <?php
      foreach ([10, 20, 30, 50, 100] as $limit) {
        $selected = $limit == $perpage ? 'selected' : '';
        echo "<option value='$limit' $selected>$limit</option>";
      }
      ?>
    </select>
  </div>

  <!-- center div: Navigation -->
  <div class="paginator-center font-bold">
    <?php
    $links = [
      [
        'text' => ' &laquo; First |',
        'page' => 1,
        'condition' => $page > 1
      ],
      [
        'text' => ' &lsaquo; Previous |',
        'page' => $prevPage,
        'condition' => $page > 1
      ],
      [
        'text' => " <span>Page $page of $total_pages</span> "
      ],
      [
        'text' => '| Next &rsaquo; ',
        'page' => $nextPage,
        'condition' => $page < $total_pages
      ],
      [
        'text' => '| Last &raquo; ',
        'page' => $total_pages,
        'condition' => $page < $total_pages
      ]
    ];

    foreach ($links as $link) {
      if (isset($link['page'])) {
        if ($link['condition']) {
          echo $this->Html->link(
            $link['text'],
            ['?' => array_merge($this->request->getQuery(), ['page' => $link['page']])],
            ['escape' => false, 'class' => '']
          );
        } else {
          echo "<span class='cursor-not-allowed opacity-50 disable'>{$link['text']}</span> ";
        }
      } else {
        echo $link['text'];
      }
    }

    ?>
  </div>

  <!-- right div: Displaying records info -->
  <div class="paginator-right">
    <?= "Displaying $start - $end of $total_records" ?>
  </div>

</div>

<!-- JavaScript for changing per page -->
<script>
  function changePerPage(perPage) {
    const url = new URL(window.location.href);
    url.searchParams.set('perpage', perPage);
    url.searchParams.set('page', 1);
    window.location.href = url.toString();
  }
</script>
