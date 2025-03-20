<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Log\Log;
use Cake\Http\Client;
use Cake\Core\Configure;
use App\Service\HostBillService;
use App\Service\VirtuozzoService;

class UsersController extends AppController
{
  public function initialize(): void
  {
    parent::initialize();
    $this->viewBuilder()->setLayout('default');
  }

  public function beforeFilter(\Cake\Event\EventInterface $event)
  {
    parent::beforeFilter($event);

    // Redirect if not logged in
    if (!$this->request->getSession()->check('Auth.User')) {
      return $this->redirect(['controller' => 'Auth', 'action' => 'login']);
    }
  }

  public function virtuozzoUsers()
  {
    $queryParams = $this->request->getQueryParams();
    $page = isset($queryParams['page']) ? (int)$queryParams['page'] : 1;
    // Ensure page is always 1 or greater
    if ($page < 1) {
      return $this->redirect(['?' => array_merge($queryParams, ['page' => 1])]);
    }

    $resultCount = (int) $this->request->getQuery('perpage', 100); // Number of records per page;
    $startRow = ($page - 1) * $resultCount;

    // Read query parameters
    $queryParams = [
      // 'orderField' => $this->request->getQuery('orderField', 'email'),
      // 'orderDirection' => $this->request->getQuery('orderDirection', 'asc'),
      'resultCount' => $resultCount,
      'startRow' => $startRow,
    ];

    if (
      !empty($this->request->getQuery('filterField'))
      && !empty($this->request->getQuery('filterValue'))
    ) {
      $queryParams['filterField'] = $this->request->getQuery('filterField');
      $queryParams['filterValue'] = $this->request->getQuery('filterValue');
    }

    // Call VirtuozzoService
    $virtuozzoService = new VirtuozzoService($this->request);
    $virtuozzoResponse = $virtuozzoService->getData("billing/account/rest/getaccounts", $queryParams);

    if ($virtuozzoResponse['success']) {
      $records = $virtuozzoResponse['data']['array'] ?? [];
      $totalCount = $virtuozzoResponse['data']['totalCount'] ?? 0;
      if(empty($records)){
        return $this->redirect(['?' => array_merge($queryParams, ['page' => 1])]);
      }
    } else {
      $this->Flash->error(__($virtuozzoResponse['error']));
      Log::write('error', json_encode($virtuozzoResponse['error'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
      $records = [];
      $totalCount = 0;
    }

    $totalPages = ceil($totalCount / $resultCount);

    $paginator = [
      'perpage' => $resultCount,
      'from' => $startRow,
      'total_records' => $totalCount,
      'page' => $page,
      'total_pages' => $totalPages
    ];

    // header('Content-Type: application/json; charset=utf-8');
    // die(json_encode($paginator));
    $this->set(compact('records', 'totalCount', 'resultCount', 'startRow', 'totalPages', 'paginator'));
  }

  public function hostbillUsers()
  {
    $page = $this->request->getQuery('page', 1) - 1;

    // Call HostBillService
    $hostBillService = new HostBillService();
    $queryParams = [
      'call' => 'getClients',
      'filter[brand]'=> 'CloudPe',
      'perpage' =>  $this->request->getQuery('perpage', 100),
      // 'page' =>  $page
    ];
    $hostBillResponse = $hostBillService->getData($queryParams);

    if ($hostBillResponse['success']) {
      $records = $hostBillResponse['data']['clients'] ?? [];
      $sorter = $hostBillResponse['data']['sorter'] ?? 0;
    } else {
      $this->Flash->error(__($hostBillResponse['error']));
      Log::write('error', json_encode($hostBillResponse['error'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
      $records = [];
      $sorter = [
        "perpage" => 10,
        "totalpages" => 1,
        "sorterrecords" => null,
        "sorterpage" => 1
      ];
    }

    $this->set(compact('records','sorter', 'page'));
  }

  public function index2()
  {
    $auth = $this->request->getSession()->read('Auth.User');
    $session = $auth['session'];

    $orderField = $this->request->getQuery('orderField') ?? 'email';
    $orderDirection = $this->request->getQuery('orderDirection') ?? 'asc';
    $filterField = $this->request->getQuery('filterField') ?? 'group';
    $filterValue = $this->request->getQuery('filterValue') ?? 'beta';
    $resultCount = $this->request->getQuery('resultCount') ?? 150;
    $startRow = $this->request->getQuery('startRow') ?? 0;

    $records = collection(array());
    $total = null;

    $url = Configure::read('Api.VIRTUZZO.base_url') . "billing/account/rest/getaccounts";
    $headers = Configure::read('Api.VIRTUZZO.headers');
    $body = [
      'session' => $session,
      'appid' => 'cluster',
      'orderField' => 'email',
      'orderDirection' => 'asc',
      // 'filterField' => 'group',
      // 'filterValue' => 'beta',
      'resultCount' => 150,
      'startRow' => 0
    ];

    // var_dump($body);

    $http = new Client();
    $response = $http->get($url, $body, ['headers' => $headers]);
    // Log::write('debug', "--------------------------------------------");
    // Log::write('debug', $url);
    // Log::write('debug', json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    if ($response->isOk()) {
      $resData = $response->getJson();
      // Log::write('debug', json_encode($resData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
      // debug($resData);

      if (!empty($resData['error'])) {
        $this->Flash->error(__($resData['error']));
        Log::write('error', json_encode($resData['error'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
      }
      if (!empty($resData['array'])) {
        $records = $resData['array'];
        $total = $resData['totalCount'];
      }
    } else {
      $resErr = $response->getStatusCode();
      Log::write('error', json_encode($resErr, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
      $this->Flash->error(__('API Request Failed: ' . $resErr));
    }

    // Log::write('debug', "--------------------------------------------");
    $auth = $this->request->getSession()->read('Auth.User');
    $this->set(compact('records','total'));
  }
}
