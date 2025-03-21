<?php

declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;
// use Cake\View\StringTemplateTrait;

class MyPaginatorHelper extends Helper
{
  // use StringTemplateTrait;

  public function render(array $options = [])
  {
    $defaults = [
      'perpage' => 10,
      'from' => 1,
      'total_records' => 0,
      'page' => 1,
      'total_pages' => 1
    ];
    $options += $defaults;

    extract($options);

    // Calculate previous and next pages
    $options['prevPage'] = max(1, $page - 1);
    $options['nextPage'] = min($total_pages, $page + 1);

    // Calculate display range
    $options['start'] = $from + 1;
    $options['end'] = min($total_records, $from + $perpage);

    // Render an element and pass variables
    return $this->_View->element('custom_paginator', $options);
  }
}
