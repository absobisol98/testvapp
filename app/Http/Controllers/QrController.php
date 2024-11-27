<?php

namespace App\Http\Controllers;

use App;
use App\Models\Event;
use App\Models\QrCode;
use App\Models\QrCodeClaimedLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class QrController extends Controller
{
    public function scan($event_id,$attendee_id)
    {
        // scan QR functionality
        return true;
    }
}
