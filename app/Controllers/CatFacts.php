<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\CatFactsModel;
use App\Services\ApiService;
use App\Services\CsrfService;
use App\Services\UtilService;
use \App\Controllers\Error;

class CatFacts extends BaseController
{
    use ResponseTrait;
    protected $httpCurl;
    protected $catFacts;
    protected $apiService;
    protected $CsrfService, $UtilService;
    protected $Error,$session;

    public function __construct()
    {
        $this->httpCurl = \Config\Services::curlrequest();
        $this->catFacts = new CatFactsModel();
        $this->apiService = new ApiService();
        $this->CsrfService = new CsrfService();
        $this->UtilService = new UtilService();
        $this->Error = new Error();
    }

    public function retrieveCatFactsFromAPI()
    {
        $facts = $this->apiService->callCatFactsAPI() ?? [];

        if (empty($facts)) {
            return 'Unable to retrieve cat facts from the API.';
        }

        if ($this->isXMLHttpRequest()) {
            $html = $this->generateFactsTable($facts);
            echo $html;
        } else {
            return $this->displayInvalidRequestError();
        }
    }

    private function generateFactsTable($facts)
    {
        // Define columns to display in the table
        $columns = ["status", "_id", "user", "text", "type", "deleted", "updatedAt", "createdAt","__v","used"];

        // Initialize the HTML content for the table
        $html = '<table><tr>';

         // Create table header with column names
        foreach ($columns as $column) {
            $html .= '<th>' . $column . '</th>';
        }
        $html .= '<th>Action</th></tr>';

        // Loop through each fact to populate table rows
        foreach ($facts as $fact) {
            $html .= '<tr>';
            foreach ($columns as $column) {
                if (array_key_exists($column, $fact)) {
                     // Check if the column value is an array
                    if (is_array($fact[$column])) {
                        $html .= '<td>' . json_encode($fact[$column]) . '</td>';
                    }else{
                        // Modify boolean values and format date-time columns
                        if(is_bool($fact[$column])){
                            if($column == 'deleted' || $column == 'used'){
                                $fact[$column] = $fact[$column] ? 'true' : 'false';
                            }
                        }
                        $fact[$column] = ($column == 'createdAt' || $column == 'updatedAt') ? $this->UtilService->standardDateTimeFormat($fact[$column]) : $fact[$column];

                        // Escape HTML entities and truncate long text
                        $html .=  '<td>' . $this->UtilService->truncateTextByCharacters(esc($fact[$column])). '</td>';
                    }
                } else {
                    $html .=  '<td></td>'; // Empty cell if the key is missing
                }
            }

            // Add an action column with a link to view more details for the fact
            $html .= '<td><a class="btn btn-outline-dark text" href="fact/' . $fact['_id'] . '">show</a></td>';
            $html .= '</tr>';
        }

        $html .= '</table>';
        return $html;
    }

    public function retrieveSpecificCatFactFromAPI($_id)
    {
        $fact = $this->apiService->callCatFactsAPI($_id) ?? [];

        if (empty($fact)) {
            return 'Unable to fetch the cat fact from the API.';
        }

        if ($this->isXMLHttpRequest()) {
            $html = $this->generateFactDetailsHTML($fact);
            echo $html;
        } else {
            return $this->displayInvalidRequestError();
        }
    }

    private function generateFactDetailsHTML($fact)
    {
        $html = '<table>';
        foreach ($fact as $key => $value) {
            $html .= '<tr>';
            if (is_array($value)) {
                $html .= "<td>".$key."</td>";
                $html .= "<td>";
                    foreach ($value as $subKey => $subValue) {
                        if (is_array($subValue)) {
                            foreach ($subValue as $subKey2 => $subValue2) {
                                $html .= "".$subKey2.": <b>".$subValue2."</b><br><br>";
                            }
                        }else{

                            if($subKey=="photo"){
                                $html .= "<img src='".$subValue."'><br><br>";
                            }else{
                                $html .= "".$subKey.": <b>".$subValue."</b><br><br>";
                            }
                        }
                    }
                $html .= "</td>";

            }else{

                if(is_bool($value)){
                    $value = $value ? 'true' : 'false';
                }

                $value = ($key == 'createdAt' || $key == 'updatedAt') ? $this->UtilService->standardDateTimeFormat($value) : $value;

                $html .= '<td>' . $key . '</td>';
                $html .= '<td><b>' . esc($value) . '</b></td>';

            }
            $html .= '</tr>';
        }
        $html .= '</table>';


        return $html;
    }

    public function handleSyncConfirmation()
    {
        $postData = $this->request->getPost();

        if(!isset($postData['token']) OR !$this->CsrfService->validateToken($postData['token'])){

            $response = [
                "status" => 2,
                "error" => 'Forbidden',
                "message" => 'Invalid or missing CSRF token.'
            ];

            return $this->respond($response, 200);
        }

        $apiResponse = $this->apiService->callCatFactsAPI();

        if (empty($apiResponse)) {
            // No data to sync
            $this->session->setFlashdata('status_error', 'No data available for synchronization.');
            return redirect()->to('/sync');
        }

        $syncAPIResponse = $this->catFacts->syncAPI($apiResponse ?? []);

        if($syncAPIResponse){
            $this->session->setFlashdata('status_success', 'API data synced successfully to the database.');
        } else {
            $this->session->setFlashdata('status_error','API data failed to sync with the database.');
        }

        return redirect()->to('/sync');
    }

    public function retrieveCatFactsFromData()
    {
        $facts = $this->catFacts->fetchAll();
        $count = $this->catFacts->countAll();

        if ($count==0) {
            return 'No data found in the database.';
        }

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {

            $html = '';

            $html .= '<tr>';

            foreach ($facts[0] as $key => $value) {
                $html .= '<th>' . $key . '</th>';
            }

            $html .= '</tr>';

            foreach ($facts as $fact) {
                $html .= '<tr>';

                foreach ($fact as $key => $value) {

                    $value = ($key == 'deleted' && is_bool($value)) ? ($value ? 'true' : 'false') : $value;

                    $html .= '<td>' . esc($value) . '</td>';   

                }
            }
            $html .= '</tr>';
            echo $html;

        }else{

            return $this->Error->showErrorPage('Error 400', 'The request you made is invalid. <br> Perhaps you\'d like to go <a href="/">back to the home page</a>');

        }
    }

    public function handleDataClearance()
    {
        $postData = $this->request->getPost();

        if(!isset($postData['token']) OR !$this->CsrfService->validateToken($postData['token'])){

            $response = [
                "status" => 2,
                "error" => 'Forbidden',
                "message" => 'Invalid or missing CSRF token.'
            ];

            return $this->respond($response, 200);
        }

        $this->catFacts->deleteAll();

        $this->session->setFlashdata('status_success', 'The <b>facts</b> table has been successfully emptied.');

        return redirect()->to('/databasedata');
    }

    private function isXMLHttpRequest()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    private function displayInvalidRequestError()
    {
        return $this->Error->showErrorPage(
            'Error 400',
            'The request you made is invalid. <br> Perhaps you\'d like to go <a href="/">back to the home page</a>'
        );
    }
}
