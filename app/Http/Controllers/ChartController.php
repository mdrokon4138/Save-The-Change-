<?php
  
namespace App\Http\Controllers;
   
use Illuminate\Http\Request;
use App\UserInfo;
use App\Charts\UserLineChart;
use DB;
use CRUDBooster;


class ChartController extends Controller
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function chartLine()
    {
        $api = url('admin');
   
        $chart = new UserLineChart;
        $chart->labels(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'])->load($api);
          
        return view('crudbooster::statistic_builder.index', compact('chart'));
    }
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function chartLineAjax(Request $request)
    {
        $year =$request->has('year') ? $request->year : date('Y');

        $users = DB::table('user_info')
                ->select(\DB::raw("COUNT(*) as count"))
                ->whereYear('updated_at', $year)
                ->groupBy(\DB::raw("Month(updated_at)"))
                ->pluck('count');

        $chart = new UserLineChart;
  
        $chart->dataset('New User Register', 'bar', $users)->options([
                    'fill' => 'true',
                    'borderColor' => '#51C1C0'
                ])->color("rgb(255, 99, 132)")
            ->backgroundcolor("rgb(255, 99, 132)");
  
        return $chart->api();
    }
    
    
    public function subscription(Request $request)
    {
        $year =$request->has('year') ? $request->year : date('Y');

        $subscrbers = DB::table('subscriptions')
                ->select(\DB::raw("COUNT(*) as count"))
                ->whereYear('updated_at', $year)
                ->groupBy(\DB::raw("Month(updated_at)"))
                ->pluck('count');

        $chart = new UserLineChart;
  
        $chart->dataset('New Subscription', 'line', $subscrbers)->options([
                    'fill' => 'true',
                    'borderColor' => '#51C1C0'
                ])->color("rgb(255, 99, 132)")
                ->backgroundcolor("rgb(255, 99, 132)");
  
        return $chart->api();
    }


    public function switch_account(Request $request){
        $user = DB::table('user_info')->where('user_id', CRUDBooster::myId())->first();
        return view('user.swtich_account', compact('user'));
    }

    public function switch(Request $request){
        
        // dd($request->swtich);

        if ($request->swtich == 'BOA') {
           $user = DB::table('user_info')->where('user_id', CRUDBooster::myId())->update([
            'user_type' => 'BOA'
            ]);
           
            DB::table('cms_users')->where('id', CRUDBooster::myId())->update([
            'id_cms_privileges' => 2
            ]);

        }else {
            $user = DB::table('user_info')->where('user_id', CRUDBooster::myId())->update([
            'user_type' => 'RA'
            ]);
            DB::table('cms_users')->where('id', CRUDBooster::myId())->update([
            'id_cms_privileges' => 3
            ]);
        }

        return redirect('switch-account')->with('status', 'Account switched succesfully. Please logout from this session. ');
        
    }

}