<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function index()
    {
          if(access_level_user('view','log')=='allow')
          {

                return view('role/log');
          }
          else
          {
              activity_log(get_module_id('log'), 'View', '', 'Error 403 : Forbidden');
              abort(403);
          }
    }

    public function list_data(Request $request)
    {
            $page = $request->page;
            $description = $request->description;
            $per_page = 10;
          	if ($page != 1) $start = ($page-1) * $per_page;
          	else $start=0;

            $totalData = DB::table('log_activity')
                      ->join('module', 'log_activity.module_id', '=', 'module.modid')
                      ->join('users', 'log_activity.uid_log', '=', 'users.id')
                      ->when($request->tgl_awal, function($query) use ($request){
                        return $query->whereDate('log_date','>=',date('Y-m-d',strtotime($request->tgl_awal)));
                      })
                      ->when($request->tgl_akhir, function($query) use ($request){
                        return $query->whereDate('log_date','<=',date('Y-m-d',strtotime($request->tgl_akhir)));
                      })
                      ->count();

           $data = DB::table('log_activity')
                      ->join('module', 'log_activity.module_id', '=', 'module.modid')
                      ->join('users', 'log_activity.uid_log', '=', 'users.id')
                      ->select('log_activity.*', 'users.name','module.modid','module.modules')
                      ->when($request->tgl_awal, function($query) use ($request){
                        return $query->whereDate('log_date','>=',date('Y-m-d',strtotime($request->tgl_awal)));
                      })
                      ->when($request->tgl_akhir, function($query) use ($request){
                        return $query->whereDate('log_date','<=',date('Y-m-d',strtotime($request->tgl_akhir)));
                      })
                      ->offset($start)
                      ->limit($per_page)
                      ->orderBy('log_activity.log_date','DESC')
                      ->get();

            $numPage = ceil($totalData / $per_page);
            $page       = $page;
            $perpage    = $per_page;
            $count      = $totalData;

            $array =
            [
              'page'    => $page,
              'perpage' => $perpage,
              'count'   => $count
            ];
            $returnHTML   = view('role.log_list',['data' => $data,'array' => $array])->render();
            return response()->json(array('success' => true, 'html'=>$returnHTML,'numPage'=>$numPage,'numitem' => $count) );
    }

    function display_chart_log(Request $request)
    {
      $tgl_awal  = date('Y-m-d',strtotime($request->tgl_awal));
      $tgl_akhir = date('Y-m-d',strtotime($request->tgl_akhir));
      $count    = " SELECT COUNT(*)AS jumlah FROM log_activity
                    WHERE CAST(log_date AS DATE)>='".$tgl_awal."'
                    AND CAST(log_date AS DATE)<='".$tgl_akhir."'";
      $result   = DB::select(DB::raw($count));
      if($result>=1)
      {
            $sqlQuery = " SELECT COUNT(*)AS jumlah,uid_log,c.name FROM log_activity a
                          INNER JOIN module b ON a.module_id=b.modid
                          INNER JOIN users c ON a.uid_log= c.id
                          WHERE CAST(log_date AS DATE)>='".$tgl_awal."'
                          AND CAST(log_date AS DATE)<='".$tgl_akhir."'
                          AND a.uid_log IS NOT NULL
                          GROUP BY uid_log,c.name";

            $sqlQuery2 = "SELECT COUNT(*)AS jumlah,modules FROM log_activity a
                          INNER JOIN module b ON a.module_id=b.modid
                          INNER JOIN users c ON a.uid_log= c.id
                          WHERE CAST(log_date AS DATE)>='".$tgl_awal."'
                          AND CAST(log_date AS DATE)<='".$tgl_akhir."'
                          AND a.uid_log IS NOT NULL
                          Group By modules ";

            // $sqlQuery3 = "SELECT COUNT(*)AS jumlah,
            //               CONCAT(HOUR(a.log_date), ':00') AS hours
            //               FROM log_activity a
            //               INNER JOIN module b ON a.module_id=b.modid
            //               INNER JOIN users c ON a.uid_log= c.id
            //               WHERE CAST(log_date AS DATE)>='2019-01-01'
            //               AND CAST(log_date AS DATE)<='2019-01-31'
            //               AND a.uid_log IS NOT NULL
            //               GROUP BY DATE_FORMAT(log_date, '%H')
            //               ORDER BY  CONCAT(HOUR(a.log_date), ':00') ASC ";

            $user    = DB::select($sqlQuery);
            $module  = DB::select($sqlQuery2);
            // $data['hours']  = DB::select(DB::raw($sqlQuery3));
            $returnHTML      = view('role.chart',compact('user','module'))->render();
            $json['content'] = $returnHTML;
      }
          return response()->json($json);
    }
}
