<?php
declare(strict_types=1);

namespace App\Controller;

class DashboardController extends AppController
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
        $this->set(compact('auth'));
    }
}
