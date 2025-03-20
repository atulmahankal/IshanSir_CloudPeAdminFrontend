<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\StringTemplateTrait;

class MyPaginatorHelper extends Helper
{
    use StringTemplateTrait;

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
        $prevPage = max(1, $page - 1);
        $nextPage = min($total_pages, $page + 1);

        // Calculate display range
        $start = $from;
        $end = min($total_records, $from + $perpage - 1);

        // Build paginator elements
        $html = '<div class="paginator-container">';

        // Left div: Navigation
        $html .= '<div class="paginator-left">';
        $html .= $page > 1 ? "<a href='?page=1'>&laquo; First</a> | " : "<span>&laquo; First</span> | ";
        $html .= $page > 1 ? "<a href='?page=$prevPage'>&lt; Previous</a> | " : "<span>&lt; Previous</span> | ";
        $html .= "<span>Page $page of $total_pages</span> | ";
        $html .= $page < $total_pages ? "<a href='?page=$nextPage'>Next &gt;</a> | " : "<span>Next &gt;</span> | ";
        $html .= $page < $total_pages ? "<a href='?page=$total_pages'>Last &raquo;</a>" : "<span>Last &raquo;</span>";
        $html .= '</div>';

        // Center div: Displaying records info
        $html .= '<div class="paginator-center">';
        $html .= "Displaying $start - $end of $total_records";
        $html .= '</div>';

        // Right div: Per page dropdown
        $html .= '<div class="paginator-right">';
        $html .= '<label for="perpage">Show </label>';
        $html .= '<select id="perpage" onchange="changePerPage(this.value)">';
        foreach ([10, 20, 30, 50, 100] as $limit) {
            $selected = $limit == $perpage ? 'selected' : '';
            $html .= "<option value='$limit' $selected>$limit</option>";
        }
        $html .= '</select>';
        $html .= '</div>';

        $html .= '</div>';

        // JavaScript for changing per page
        $html .= "<script>
            function changePerPage(perPage) {
                const url = new URL(window.location.href);
                url.searchParams.set('perpage', perPage);
                url.searchParams.set('page', 1);
                window.location.href = url.toString();
            }
        </script>";

        return $html;
    }
}
