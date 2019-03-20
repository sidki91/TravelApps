<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Formulir;
use App\Models\Pemesanan;
use App\Models\Pembayaran;
use Validator;
use Response;
use View;
use Auth;

class ReportController extends Controller
{
      public function __construct()
      {
          $this->middleware('auth');
      }

      public function report_jamaah()
      {
          if(access_level_user('view','report_jamaah')=='allow')
          {
              activity_log(get_module_id('report_jamaah'), 'View', '', '');
              return view('report/jamaah/report_jamaah');
          }
          else
          {
              activity_log(get_module_id('report_jamaah'), 'View', '', 'Error 403 : Forbidden');
              abort(403);
          }

      }
}
