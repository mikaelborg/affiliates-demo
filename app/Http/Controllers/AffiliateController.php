<?php

namespace App\Http\Controllers;

use App\Services\AffiliateService;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class AffiliateController extends Controller
{
  public function list(AffiliateService $service): View
  {
      $affiliates = $service->readFileAndFilter('affiliates.txt');
      $sortedAffiliates = $service->sort($affiliates);

      return view('affiliates', ['affiliates' => $sortedAffiliates]);
  }
}
