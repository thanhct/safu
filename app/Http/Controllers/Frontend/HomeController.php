<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\System\Address;
use App\Models\System\Submission;
use App\Models\System\UserRep;
use Illuminate\Support\Facades\Auth;

/**
 * Class HomeController.
 */
class HomeController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('frontend.index');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitAddress(Request $request){
        if(!empty($request->address) && !empty(Auth::id())) {
            $hash_address = Address::Where('hash_address', $request->address)->get();
            if($hash_address->count() === 0) {
                Address::create(['hash_address' => $request->address, 'score' => 0]);
            }
            $data = $request->all();
            $data['user_id'] = Auth::id();
            Submission::create($data);
            if($request->approved === 1){
                $score = Address::find($request->address)->score;            
                Address::update(['score' => $score + 1,
                                'updated_date' => date()
                                ])
                        ->Where('hash_address', $request->address);
                $userRep = UserRep::Where('user_id', Auth::id());
                if($userRep) {
                    UserRep::update(['reps' => $userRep->reps + 1,
                                     'balance' => $userRep->balance + 1
                                    ])
                                    ->Where('user_id', Auth::id());
                } else {
                    UserRep::create(['reps' => 1,
                                     'balance' => 1,
                                     'user_id' => Auth::id()
                                    ]);
                }
            }
            return response()->json(['status' => 201]);
        }
        return response()->json(['status' => 404]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function lookupAddress(Request $request){
        if(!empty($request->address)) {
            $dataScore = Address::Select('score', 'updated_date', 'hash_address')
                ->Where('hash_address', $request->address)
                ->get();
            $score = ($dataScore->count() > 0) ? $dataScore[0]->score : null;
            $date = ($dataScore->count() > 0) ? $dataScore[0]->update_date : null;
            $hashddress = ($dataScore->count() > 0) ? $dataScore[0]->hash_address : null;
            $data = Submission::with('user:id,first_name,last_name')->Where('address', $request->address)
                ->Where('approved', 1)
                ->get()->toArray();
            dd($data);
            return response()->json(['status' => 200, 
                'score' => $score,
                'address' => $request->address,
                'data' => $data,
                'updated_date' => $date, 
                ]);
        }
        return response()->json(['status' => 404]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus(Request $request){
        if(!empty($request->id)) {
            $submission = Submission::find($request->id);
            if($submission->approved === 0) {
                $score = Address::find($request->address)->score;            
                Address::update(['score' => $score + 1,
                                'updated_date' => date()
                                ])
                        ->Where('hash_address', $request->address);
                Submission::update([
                                    'approved' => 1,
                                    'appr_user' => Auth::id(),
                                    'appr_date' => date()
                                    ])
                            ->Where('id', $request->id);
                $userRep = UserRep::Where('user_id', Auth::id());
                if($userRep) {
                    UserRep::update(['reps' => $userRep->reps + 1,
                                     'balance' => $userRep->balance + 1
                                    ])
                                    ->Where('user_id', Auth::id());
                } else {
                    UserRep::create(['reps' => 1,
                                     'balance' => 1,
                                     'user_id' => Auth::id()
                                    ]);
                }
                return response()->json(['status' => 200]);       
            }
            
        }
        return response()->json(['status' => 404]);
    }
}
