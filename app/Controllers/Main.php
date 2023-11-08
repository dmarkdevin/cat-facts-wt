<?php

namespace App\Controllers;

use App\Models\CatFactsModel;
use App\Services\ApiService;
use App\Services\CsrfService;
use App\Services\UtilService;
use App\Controllers\Error;

class Main extends BaseController
{
    protected $catFacts;
    protected $apiService;
    protected $CsrfService, $UtilService;
    protected $error;

    public function __construct()
    {
        $this->catFacts = new CatFactsModel();
        $this->apiService = new ApiService();
        $this->CsrfService = new CsrfService();
        $this->UtilService = new UtilService();
        $this->error = new Error();
    }

     public function showHomepage()
    {
        $this->renderViews('showHomepage');
    }

    public function showFactsPage()
    {
        $this->renderViews('showFactsPage');
    }

    public function showFactDetail($_id)
    {
        $data['_id'] = $_id;
        $this->renderViews('showFactDetail', $data);
    }

    public function showSyncPage()
    {
        $facts = $this->apiService->callCatFactsAPI() ?? [];
        $count = $this->catFacts->countAll();

        $data['count'] = $count;
        $data['facts'] = $facts;
        $data['token'] = $this->CsrfService->generateToken();

        $this->renderViews('showSyncPage', $data);
    }

    public function showDatabaseDataPage()
    {
        $count = $this->catFacts->countAll();

        $data['count'] = $count;
        $data['token'] = $this->CsrfService->generateToken();

        $this->renderViews('showDatabaseDataPage', $data);
    }

    private function renderViews($page, $data = [])
    {
        echo view('header');
        echo view($page, $data);
        echo view('footer');
    }


}
