<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Log\Log;
use Cake\Http\Client;
use Cake\Http\Response;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use App\Controller\AppController;

class AuthController extends AppController
{
  public function initialize(): void
  {
    parent::initialize();
    $this->viewBuilder()->setLayout('auth');
    $this->loadComponent('Flash'); // For flash messages
  }

  public function beforeFilter(\Cake\Event\EventInterface $event)
  {
    parent::beforeFilter($event);
  }

  public function login()
  {
    // Redirect if not logged in
    if ($this->request->getSession()->check('Auth.User')) {
      return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
    }

    if ($this->request->is('post')) {
      $login = $this->request->getData('login');
      $password = $this->request->getData('password');

      $url = Configure::read('Api.VIRTUZZO.base_url') . "users/authentication/rest/signin";
      $headers = Configure::read('Api.VIRTUZZO.headers');
      $body = [
        'login' => $login,
        'password' => $password
      ];
      // debug($baseUrl);

      $http = new Client();
      $response = $http->post($url, $body, ['headers' => $headers]);
      // Log::write('debug', "--------------------------------------------");
      // Log::write('debug', $url);
      // Log::write('debug', json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

      if ($response->isOk()) {
        $resData = $response->getJson();
        // Log::write('debug', json_encode($resData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        // debug($resData);

        if (!empty($resData['error'])) {
          $this->Flash->error(__($resData['error']));
        }

        if (!empty($resData['session'])) {
          $this->request->getSession()->write('Auth.User', $resData);
          return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        }
      } else {
        $resErr = $response->getStatusCode();
        Log::write('error', json_encode($resErr, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $this->Flash->error(__('API Request Failed: ' . $resErr));
        // debug($resErr);
        // throw new \Exception('API Request Failed: ' . $resErr);
      }
      // Log::write('debug', "--------------------------------------------");
    }
  }

  public function logout()  //: Response
  {
    $this->autoRender = false;

    $auth = $this->request->getSession()->read('Auth.User');
    $session = $auth['session'];

    $url = Configure::read('Api.VIRTUZZO.base_url') . "users/authentication/rest/signout";
    $headers = Configure::read('Api.VIRTUZZO.headers');
    $body = [
      'session' => $session,
      // 'appid' => 'cluster',
    ];

    $http = new Client();
    $response = $http->post($url, $body, ['headers' => $headers]);
    // Log::write('debug', "--------------------------------------------");
    // Log::write('debug', $url);
    // Log::write('debug', json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    if ($response->isOk()) {
      $resData = $response->getJson();
      // Log::write('debug', json_encode($resData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
      // debug($resData);

      if (!empty($resData['error'])) {
        $this->Flash->error(__($resData['error']));
      }

      $this->request->getSession()->destroy();
      return $this->redirect(['action' => 'login']);
    } else {
      $resErr = $response->getStatusCode();
      Log::write('error', json_encode($resErr, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
      $this->Flash->error(__('API Request Failed: ' . $resErr));
      // debug($resErr);
      // throw new \Exception('API Request Failed: ' . $resErr);
    }
    // Log::write('debug', "--------------------------------------------");
  }
}
