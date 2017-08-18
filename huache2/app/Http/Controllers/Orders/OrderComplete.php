<?php

namespace App\Http\Controllers\Orders;

use App\Models\HgCartLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderComplete extends Controller
{

    public function getSellRecord($order)
    {
        return $this->getSellAppraise($order);
    }
}
