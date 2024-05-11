<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Services\DataListService;
use App\Services\DatatableService;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    function __construct(
        public DataListService $dataListService,
        public DatatableService $datatableService
    ){}
}
