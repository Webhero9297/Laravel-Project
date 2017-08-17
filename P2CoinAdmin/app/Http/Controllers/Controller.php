<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\models\Common;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $totalusers, $volume, $revenu, $currentusers;
    public function  __construct() {
        $model = new Common();
        $this->totalusers = $model->getTotalUsers();
        $this->currentusers = $model->getCurrentUsers();
        $this->volume = $model->getVolumeValue();
        $this->revenu = $model->getRevenuValue();

        session()->put('currentusers', $this->currentusers);
    }
}
