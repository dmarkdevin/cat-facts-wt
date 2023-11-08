<?php

namespace App\Services;

class ApiService
{

    /**
     * Calls the Cat Facts API to retrieve data.
     *
     * @param string $_id ID for a specific Cat Fact (optional)
     * @return array|bool Retrieved data from the API or false if an error occurs
     */
    public function callCatFactsAPI($_id = '')
    {
        $httpCURL = \Config\Services::curlrequest();

        try {
            $segment = !empty($_id) ? '/'.$_id : '/random?amount=5';
            $apiUrl = getenv('CAT_FACTS_API_ENDPOINT_URL') . $segment;

            $response = $httpCURL->request('GET', $apiUrl);

            // Check the status code for a successful response
            if ($response->getStatusCode() === 200) {
                $apiData = $response->getBody();

                $jsonDecode = json_decode($apiData, true);

                            // Function to sort keys within each object
                // function sortKeys($array) {
                //     foreach ($array as &$object) {
                //         ksort($object);
                //     }
                //     return $array;
                // }

                // // Sort keys for each object in the array
                // $sortedData = sortKeys($jsonDecode);
                // return $sortedData;
                return $jsonDecode;

            } else {
                // Non-successful response, return false
                return false;
            }
        } catch (\Exception $e) {
            // Log the error or handle it accordingly
            error_log('Error in callCatFactsAPI method: ' . $e->getMessage());
            return false; // Return false for any exception caught
        }
    }
}
