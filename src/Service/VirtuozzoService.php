<?php

declare(strict_types=1);

namespace App\Service;

use Cake\Log\Log;
use Cake\Http\Client;
use Cake\Core\Configure;
use Cake\Http\ServerRequest;
use Cake\Http\Exception\InternalErrorException;

class VirtuozzoService
{
  protected Client $http;
  protected string $baseUrl;
  protected array $headers;
  protected ?string $session;

  public function __construct(ServerRequest $request)
  {
    $this->http = new Client();
    $this->baseUrl = Configure::read('Api.VIRTUZZO.base_url');
    $this->headers = Configure::read('Api.VIRTUZZO.headers');

    // Retrieve session data
    $auth = $request->getSession()->read('Auth.User');
    $this->session = $auth['session'] ?? null;
  }

  /**
   * Fetch accounts from Virtuozzo API
   *
   * @param array $params Query parameters for the API request.
   * @return array Result data with 'success' flag and 'data' or 'error' message.
   */
  // public function getAccounts(array $params): array
  // {
  //     $url = $this->baseUrl . "billing/account/rest/getaccounts";

  public function getData(string $url, array $params): array
  {
    $fullurl = $this->baseUrl . $url;

    $defaultParams = [
      'appid' => 'cluster',
    ];

    // Add session only if it's available
    if (!empty($this->session)) {
      $defaultParams['session'] = $this->session;
    }

    $body = array_merge($defaultParams, $params);

    // Log::write('debug', "--------------------------------------------");
    // Log::write('debug', $url);
    // Log::write('debug', json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    try {
      // Send request
      $response = $this->http->get($fullurl, $body, ['headers' => $this->headers]);

      // Check response
      if ($response->isOk()) {
        $resData = $response->getJson();
        // Log::write('debug', json_encode($resData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        // header('Content-Type: application/json; charset=utf-8');
        // die(json_encode($resData));

        if (!empty($resData['error'])) {
          return ['success' => false, 'error' => $resData['error']];
        }

        return ['success' => true, 'data' => $resData];
      } else {
        throw new InternalErrorException("API Request Failed with status: " . $response->getStatusCode());
      }
    } catch (\Exception $e) {
      Log::write('error', $e->getMessage());
      return ['success' => false, 'error' => $e->getMessage()];
    }
    // Log::write('debug', "--------------------------------------------");
  }
}
