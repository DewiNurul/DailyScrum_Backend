<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\DailyScrum;
use DB;
use Illuminate\Support\Facades\Validator;

class DailyScrumController extends Controller
{
    
    public function index()
    {
        try{
	        $data["count"] = DailyScrum::count();
          $daily_scrum = array();
          $dataDailyScrum = DB::table('daily_scrum')->join('users','users.id','=','daily_scrum.id_users')
          ->select('daily_scrum.id', 'users.firstname','users.lastname','users.email','daily_scrum.id_users','daily_scrum.team', 'daily_scrum.activity_yesterday', 'daily_scrum.activity_today','daily_scrum.problem_yesterday','daily_scrum.solution')
          ->get();
          

	        foreach ($dataDailyScrum as $p) {
	            $item = [
                    "id"          		      => $p->id,
                    "firstname"             => $p->firstname,
                    "lastname"              => $p->lastname,
                    "email"                 => $p->email,
                    "id_users"              => $p->id_users,
	                  "team"                  => $p->team,
	                  "activity_yesterday"  	=> $p->activity_yesterday,
                    "activity_today"    	  => $p->activity_today,
                    "problem_yesterday"    	=> $p->problem_yesterday,
                    "solution"    	  		  => $p->solution,
	            ];

	            array_push($daily_scrum, $item);
	        }
	        $data["daily_scrum"] = $daily_scrum;
	        $data["status"] = 1;
	        return response($data);

	    } catch(\Exception $e){
			return response()->json([
			  'status' => '0',
			  'message' => $e->getMessage()
			]);
      	}
    }

  
    public function getAll($limit = 10, $offset = 0)
    {
    	try{
	        $data["count"] = DailyScrum::count();
          $daily_scrum = array();
          $dataDailyScrum = DB::table('daily_scrum')->join('users','users.id','=','daily_scrum.id_users')
          ->select('daily_scrum.id','daily_scrum.team', 'daily_scrum.activity_yesterday', 'daily_scrum.activity_today','daily_scrum.problem_yesterday','daily_scrum.solution')
          ->skip($offset)
          ->take($limit)
          // ->where('daily_scrum.id_users', $id_user)
          ->get();

	        foreach ($dataDailyScrum as $p) {
	            $item = [
                    "id"          		      => $p->id,
                    "team"                  => $p->team,
	                  "activity_yesterday"  	=> $p->activity_yesterday,
                    "activity_today"      	=> $p->activity_today,
                    "problem_yesterday"    	=> $p->problem_yesterday,
                    "solution"    	  		  => $p->solution,
	            ];

	            array_push($daily_scrum, $item);
	        }
	        $data["daily_scrum"] = $daily_scrum;
	        $data["status"] = 1;
	        return response($data);

	    } catch(\Exception $e){
			return response()->json([
			  'status' => '0',
			  'message' => $e->getMessage()
			]);
      	}
    }

 
    public function store(Request $request)
    {
        try{
    		$validator = Validator::make($request->all(), [
          // 'id_users'                    => 'required',
    			'team'                        => 'required|string|max:255',
				  'activity_yesterday'			  	=> 'required|string|max:255',
          'activity_today'			  		  => 'required|string|max:500',
          'problem_yesterday'			  		=> 'required|string|max:500',
          'solution'			  		        => 'required|string|max:500',
    		]);

    		if($validator->fails()){
    			return response()->json([
    				'status'	=> 0,
    				'message'	=> $validator->errors()
    			]);
    		}

          // $data = new DailyScrum();
          // $data->id_users              = $request->input('id_users');
	        // $data->team                  = $request->input('team');
	        // $data->activity_yesterday    = $request->input('activity_yesterday');
          // $data->activity_today        = $request->input('activity_today');
          // $data->problem_yesterday     = $request->input('problem_yesterday');
          // $data->solution              = $request->input('solution');
	        // $data->save();

    		//cek apakah ada id user tersebut
    		if(User::where('id', $request->input('id_users'))->count() > 0){
    				  $data = new DailyScrum();
				    	$data->id_users             = $request->input('id_users');
			        $data->team                 = $request->input('team');
			        $data->activity_yesterday   = $request->input('activity_yesterday');
              $data->activity_today       = $request->input('activity_today');
              $data->problem_yesterday    = $request->input('problem_yesterday');
			        $data->solution             = $request->input('solution');
			        $data->save();


		    		return response()->json([
		    			'status'	=> '1',
		    			'message'	=> 'Data daily scrum berhasil ditambahkan!'
		    		], 201);
    		  	} else {
    				return response()->json([
		                'status' => '0',
		                'message' => 'Data daily scrum tidak ditemukan.'
		            ]);
    		   	}

      } catch(\Exception $e){
            return response()->json([
                'status' => '0',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete($id)
    {
        try{

            $delete = DailyScrum::where("id", $id)->delete();

            if($delete){
              return response([
                "status"  => 1,
                  "message"   => "Data DailyScrum berhasil dihapus."
              ]);
            } else {
              return response([
                "status"  => 0,
                  "message"   => "Data DailyScrum gagal dihapus."
              ]);
            }
            
        } catch(\Exception $e){
            return response([
            	"status"	=> 0,
                "message"   => $e->getMessage()
            ]);
        }
    }

   
}