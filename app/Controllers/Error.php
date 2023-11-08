<?php
namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;

class Error extends BaseController
{
    use ResponseTrait;

    public function error_404()
    {
        return $this->showErrorPage('Error 404', 'Oops! The page you are looking for could not be found., Perhaps you\'d like to go <a href="/">back to the home page</a>');
    }
    public function showErrorPage($errorCode, $message)
    {
        $data = [
            'title' => $errorCode,
            'message' => $message
            // You can add more data to pass to the view if needed
        ];

        echo view('header');
        echo view('errors/error_404', $data);
        echo view('footer');

    }
    public function error_404x()
    {
        $session = session();
        $catchError = $session->get('catchError');

        if ($catchError) {
            $response = service("response");

            // Clear session first
            $session->remove('catchError');

            if ( !empty($catchError["status"]) && $catchError["status"] == 2  ) { // if status is equal to "2" then use to 200 status code
                $response->setJSON($catchError);
                $response->setStatusCode(200);
            } else {
                $response->setJSON($catchError);
                $response->setStatusCode($catchError["error"]);
            }

            return $response;
        }

        // Show forbidden error
        return $this->failForbidden("Invalid request");
    }
}
