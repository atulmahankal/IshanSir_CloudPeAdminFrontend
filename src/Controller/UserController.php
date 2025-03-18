<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Log\Log;
use Cake\Http\Client;
use Cake\Core\Configure;

class UserController extends AppController
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

  public function index()
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
