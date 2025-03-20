<?php

declare(strict_types=1);

namespace App\Service;

use Cake\Log\Log;
use Cake\Http\Client;
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;

class HostBillService
{
  protected Client $http;
  protected string $baseUrl;
  protected array $headers;

  public function __construct()
  {
    $this->http = new Client();
    $this->baseUrl = Configure::read('Api.HOSTBILL.base_url');
    $this->headers = Configure::read('Api.HOSTBILL.headers');
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

  public function getData(array $params): array
  {
    $url = $this->baseUrl;

    $defaultParams = [
      'api_id' => Configure::read('Api.HOSTBILL.API_ID'),
      'api_key' => Configure::read('Api.HOSTBILL.API_KEY'),
    ];
    $body = array_merge($defaultParams, $params);

    // Log::write('debug', "--------------------------------------------");
    // Log::write('debug', $url);
    // Log::write('debug', json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    try {
      // Send request
      $response = $this->http->get($url, $body, ['headers' => $this->headers]);

      // Check response
      if ($response->isOk()) {
        $resData = $response->getJson();
        // Log::write('debug', json_encode($resData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        // header('Content-Type: application/json; charset=utf-8');
        // die(json_encode($resData));

        if (!empty($resData['error'])) {
          return ['success' => false, 'error' => $resData['error']];
        }

        if(empty($resData['success'] || !$resData['success'])){
          throw new InternalErrorException("API Request Failed with unknown status.");
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
