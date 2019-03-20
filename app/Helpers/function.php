<?php

use Illuminate\Support\Facades\DB;





function convertdate()
{
        date_default_timezone_set('Asia/Jakarta');
        $date = date('dmy');
        return $date;
}

function autonumber($table,$primary,$prefix)
{
        $q=DB::table($table)->select(DB::raw('MAX(RIGHT('.$primary.',5)) as kd_max'));
        $prx=$prefix;
        if($q->count()>0)
        {
            foreach($q->get() as $k)
            {
                $tmp = ((int)$k->kd_max)+1;
                $kd = $prx.sprintf("%05s", $tmp);
            }
        }
        else
        {
            $kd = $prx."00001";
        }

        return $kd;
}

function autonumber_transaction($table,$primary,$prefix,$where)
{
        $q=DB::table($table)->select(DB::raw('MAX(RIGHT('.$primary.',5)) as kd_max'))
               ->whereYear($where, '=', date('Y'))
               ->whereMonth($where, '=', date('m'));;
        $prx=$prefix.convertdate();
        if($q->count()>0)
        {
            foreach($q->get() as $k)
            {
                $tmp = ((int)$k->kd_max)+1;
                $kd = $prx.sprintf("%05s", $tmp);
            }
        }
        else
        {
            $kd = $prx."00001";
        }

        return $kd;
}

function autonumber_transaction_line($table,$primary,$prefix,$field,$value)
{
        $q=DB::table($table)->select(DB::raw('MAX(RIGHT('.$primary.',5)) as kd_max'))
               ->where($field,$value);
        $prx=$prefix;
        if($q->count()>0)
        {
            foreach($q->get() as $k)
            {
                $tmp = ((int)$k->kd_max)+1;
                $kd = $prx.sprintf("%05s", $tmp);
            }
        }
        else
        {
            $kd = $prx."00001";
        }

        return $kd;
}

function get_module_id($alias)
{
		// return $this->db->select('alias')->where('modid', $role)->get('module')->row()->alias;
    return DB::table('module')
               ->where('alias','=',$alias)
               ->WhereNull('deleted_at')
               ->value('modid');

}

function get_module_alias($role)
{
		// return $this->db->select('alias')->where('modid', $role)->get('module')->row()->alias;
    return DB::table('module')
               ->where('modid',$role)
               ->WhereNull('deleted_at')
               ->first();

}
function access_level_user($title, $page = '', $access = TRUE)
{
    $user_id = Auth::user()->id;
    $data_user    =  DB::table('users')
               ->join('user_group', 'users.access_id', '=', 'user_group.group_id')
               ->where('id',$user_id)
               ->whereNull('users.deleted_at')
               ->first();

     if( empty($page))
     {
    		$page =  Request::segment(1);
     }

     # Role User
     if( $title == 'view' )
		 {
			    // View
              if(!empty($data_user->role_view))
              {
                  $status_view = 0;

                  $expld_view = explode(',',$data_user->role_view);

                  foreach($expld_view  as $key_role_view => $val_role_view )
                  {

                      $alias_view = get_module_alias($val_role_view)->alias;


            					if( $alias_view == $page)
            					{
            						      $status_view = $status_view + 1;
            					}

            					if( $status_view == 0 )
            					{
            						      $grant_access = 'deny';
            					}
            					else
            					{
            						      $grant_access = 'allow';
            					}

                  }
              }
              else
              {
                  $grant_access = 'deny';
              }
		 }
     if( $title == 'create' )
		 {
			   // Create
               if(!empty($data_user->role_create))
               {

                    $status_create = 0;

                    $expld_create = explode(',',$data_user->role_create);

                    foreach($expld_create  as $key_role_create => $val_role_create )
                    {
                        $alias_create = get_module_alias($val_role_create)->alias;

              					if( $alias_create == $page)
              					{
              						$status_create = $status_create + 1;
              					}

              					if( $status_create == 0 )
              					{
              						$grant_access = 'deny';
              					}
              					else
              					{
              						$grant_access = 'allow';
              					}

                    }
               }
               else
               {
                   $grant_access = 'deny';
               }

		}

		if( $title == 'alter' )
		{
			    // Alter
                if(!empty($data_user->role_alter))
                {
					          $status_alter = 0;
                    $expld_alter = explode(',',$data_user->role_alter);

                    foreach($expld_alter  as $key_role_alter => $val_role_alter )
                    {

                  			$alias_alter = get_module_alias($val_role_alter)->alias;

              					if( $alias_alter == $page)
              					{
              						$status_alter = $status_alter + 1;
              					}

              					if( $status_alter == 0 )
              					{
              						$grant_access = 'deny';
              					}
              					else
              					{
              						$grant_access = 'allow';
              					}

                    }
                }
                else
                {
                    $grant_access = 'deny';
                }
		}

		if( $title == 'drop' )
		{
			   // Drop
               if(!empty($data_user->role_drop))
               {
					          $status_drop = 0;
                    $expld_drop = explode(',',$data_user->role_drop);

                    foreach($expld_drop  as $key_role_drop => $val_role_drop )
                    {

                        $alias_drop = get_module_alias($val_role_drop)->alias;

                        if( $alias_drop == $page)
              					{
              						$status_drop = $status_drop + 1;
              					}

              					if( $status_drop == 0 )
              					{
              						$grant_access = 'deny';
              					}
              					else
              					{
              						$grant_access = 'allow';
              					}

                    }
               }
               else
               {
                  $grant_access = 'deny';
               }
		}


		if( $access == TRUE)
		{
			return $grant_access;
		}
}

function activity_log($module_id='', $activity='', $id_key='', $desc_log='')
{
      DB::table('log_activity')->insert([
        'module_id'  => $module_id,
        'activity'   => $activity,
        'uid_log'    => Auth::user()->id,
        'id_key'     => $id_key,
        'log_date'   => Carbon\Carbon::now(),
        'desc_log'   => $desc_log,
        'ip_address' => Request::ip(),
        'browser'    => Browser::browserName(),
        'platform'   => Browser::platformFamily().' '.Browser::platformVersion()
      ]);
}


function get_masehi($tgl_lahir)
{
    $tgl=explode("-",$tgl_lahir);
    $cek_jmlhr1=cal_days_in_month(CAL_GREGORIAN,$tgl['1'],$tgl['2']);
    $cek_jmlhr2=cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
    $sshari=$cek_jmlhr1-$tgl['0'];
    $ssbln=12-$tgl['1']-1;
    $hari=0;
    $bulan=0;
    $tahun=0;
//hari+bulan
    if($sshari+date('d')>=$cek_jmlhr2){
        $bulan=1;
        $hari=$sshari+date('d')-$cek_jmlhr2;
    }else{
        $hari=$sshari+date('d');
    }
    if($ssbln+date('m')+$bulan>=12){
        $bulan=($ssbln+date('m')+$bulan)-12;
        $tahun=date('Y')-$tgl['2'];
    }else{
        $bulan=($ssbln+date('m')+$bulan);
        $tahun=(date('Y')-$tgl['2'])-1;
    }

      $selisih=$tahun." Tahun ".$bulan." Bulan ".$hari." Hari";
    return $selisih;
}

function get_umur($tgl_lahir)
{
    $tgl=explode("-",$tgl_lahir);
    $cek_jmlhr1=cal_days_in_month(CAL_GREGORIAN,$tgl['1'],$tgl['2']);
    $cek_jmlhr2=cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
    $sshari=$cek_jmlhr1-$tgl['0'];
    $ssbln=12-$tgl['1']-1;
    $hari=0;
    $bulan=0;
    $tahun=0;
//hari+bulan
    if($sshari+date('d')>=$cek_jmlhr2){
        $bulan=1;
        $hari=$sshari+date('d')-$cek_jmlhr2;
    }else{
        $hari=$sshari+date('d');
    }
    if($ssbln+date('m')+$bulan>=12){
        $bulan=($ssbln+date('m')+$bulan)-12;
        $tahun=date('Y')-$tgl['2'];
    }else{
        $bulan=($ssbln+date('m')+$bulan);
        $tahun=(date('Y')-$tgl['2'])-1;
    }

      $selisih=$tahun." Tahun ";
    return $selisih;
}
