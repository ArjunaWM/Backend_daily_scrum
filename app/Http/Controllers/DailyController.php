<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use JWTAuth;

use App\Daily;
use App\User;
use DB;

class DailyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $data["count"] = Daily::count();
	        $daily = array();
            $dataDaily = DB::table('daily_scrum')->join('user','user.id','=','daily_scrum.id_user')
                                                 ->select('daily_scrum.id', 'user.firstname', 'user.lastname',
                                                         'user.email', 'daily_scrum.id_user', 'daily_scrum.team',
                                                         'daily_scrum.activity_yesterday', 'daily_scrum.activity_today',
                                                         'daily_scrum.problem_yesterday', 'daily_scrum.solution',
                                                         'daily_scrum.created_at')                                                   
	                                             ->get();

	        foreach ($dataDaily as $p) {
	            $item = [
	            	"id"          		 		                        => $p->id,
                  	"id_user"         			                        => $p->id_user,
	                "firstname"  		 		                        => $p->firstname,
	                "lastname"  			     	                    => $p->lastname,
	                "email"    	  		   		                        => $p->email,
                  	"team"    		                                    => $p->team,
	                "activity_yesterday"  		                        => $p->activity_yesterday,
	                "activity_today"  		   		                    => $p->activity_today,
	                "problem_yesterday"  			      	            => $p->problem_yesterday,
                    "solution"        		                            => $p->solution,
                    "created_at"                                        => $p->created_at,
                    "tanggal" 			                                => date('j F Y', strtotime($p->created_at)),
	            ];

	            array_push($daily, $item);
	        }
	        $data["daily"] = $daily;
	        $data["status"] = 1;
            return response($data);
            
	    } catch(\Exception $e){
			return response()->json([
			  'status' => '0',
			  'message' => $e->getMessage()
			]);
      	}
    }

    public function getAll($limit = 10, $offset = 0, $id_user)
    {
    	try{
	        $data["count"] = Daily::count();
	        $daily = array();
	        $dataDaily = DB::table('daily_scrum')->join('user','user.id','=','daily_scrum.id_user')
                                                 ->select('daily_scrum.id', 'user.firstname','user.lastname','user.email', 
                                                 'daily_scrum.team','daily_scrum.id_user','daily_scrum.activity_yesterday',
                                                 'daily_scrum.activity_today','daily_scrum.problem_yesterday',
                                                 'daily_scrum.solution','daily_scrum.created_at')
                                                 ->skip($offset)
                                                 ->take($limit)
                                                 ->where('daily_scrum.id_user', '=', $id_user)
	                                             ->get();

	        foreach ($dataDaily as $p) {
	            $item = [
                    "id"          		    => $p->id,
                    "id_user"               => $p->id_user,
	                "firstname"  		    => $p->firstname,
	                "lastname"  			=> $p->lastname,
	                "email"    	  		    => $p->email,
	                "team"  		        => $p->team,
                    "activity_yesterday"  	=> $p->activity_yesterday,
                    "activity_today"  	    => $p->activity_today,
	                "problem_yesterday"	    => $p->problem_yesterday,
                    "solution"              => $p->solution,
                    "created_at"            => $p->created_at,
                    "tanggal" 			    => date('j F Y', strtotime($p->created_at)),
	            ];

	            array_push($daily, $item);
	        }
	        $data["daily"] = $daily;
	        $data["status"] = 1;
	        return response($data);

	    } catch(\Exception $e){
			return response()->json([
			  'status' => '0',
			  'message' => $e->getMessage()
			]);
      	}
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
    		$validator = Validator::make($request->all(), [
                'team'    	            => 'required|string|max:255',           
	            'activity_yesterday'    => 'required|string|max:255',
	            'activity_today'  		=> 'required|string|max:255',
	            'problem_yesterday'     => 'required|string|max:255',
                'solution'              => 'required|string|max:255',
    		]);

    		if($validator->fails()){
    			return response()->json([
    				'status'	=> 0,
    				'message'	=> $validator->errors()
    			]);
    		}

            $data = new Daily();  
	        $data->id_user = JWTAuth::user()->id;
	        $data->team = $request->input('team');
	        $data->activity_yesterday = $request->input('activity_yesterday');
            $data->activity_today = $request->input('activity_today');
	        $data->problem_yesterday = $request->input('problem_yesterday');
	        $data->solution = $request->input('solution');
	        $data->save();

    		return response()->json([
    			'status'	=> '1',
    			'message'	=> 'Data Daily Scrum berhasil ditambahkan!'
    		], 201);

      } catch(\Exception $e){
            return response()->json([
                'status' => '0',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
    		$validator = Validator::make($request->all(), [
                'team'    	            => 'required|string|max:255',           
	            'activity_yesterday'    => 'required|string|max:255',
	            'activity_today'  		=> 'required|string|max:255',
	            'problem_yesterday'     => 'required|string|max:255',
                'solution'              => 'required|string|max:255',
    		]);

    		if($validator->fails()){
    			return response()->json([
    				'status'	=> 0,
    				'message'	=> $validator->errors()
    			]);
    		}
  
            $data = Daily::where('id', $id)->first();
	        $data->team = $request->input('team');
	        $data->activity_yesterday = $request->input('activity_yesterday');
            $data->activity_today = $request->input('activity_today');
	        $data->problem_yesterday = $request->input('problem_yesterday');
	        $data->solution = $request->input('solution');
	        $data->save();
  
            return response()->json([
                'status'	=> '1',
                'message'	=> 'Data Daily Scrum berhasil diubah'
            ]);
          
        } catch(\Exception $e){
            return response()->json([
                'status' => '0',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{

            $delete = Daily::where("id", $id)->delete();

            if($delete){
              return response([
                "status"  => 1,
                  "message"   => "Data Daily Scrum berhasil dihapus."
              ]);
            } else {
              return response([
                "status"  => 0,
                  "message"   => "Data Daily Scrum gagal dihapus."
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
