<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Auth\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\System\Address;
use App\Models\System\Submission;
use App\Models\System\UserRep;

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
        if(!empty($request->address)) {
            $hash_address = Address::Where('hash_address', $request->address)->get();
            if($hash_address->count() === 0) {
                //TODO - Up to now we hardcode approval = 1
                Address::create(['hash_address' => $request->address, 'score' => 1]);
            } else {
                $currentScore = Address::find($request->address)->score;
//                return response()->json(['currentScore' => $currentScore]);
                Address::Where('hash_address', $request->address)->update([
                    'score' => ($currentScore + 1),
                    'updated_date' => now()
                ]);
            }
            $data = $request->all();
            $data['user_id'] = 1;
            Submission::create($data);
            if($request->approved === 1){
                $score = Address::find($request->address)->score;            
                Address::update(['score' => ($score + 1),
                                'updated_date' => date()
                                ])
                        ->Where('hash_address', $request->address);
                $userRep = UserRep::Where('user_id', 1);
                if($userRep) {
                    UserRep::update(['reps' => $userRep->reps + 1,
                                     'balance' => $userRep->balance + 1
                                    ])
                                    ->Where('user_id', 1);
                } else {
                    UserRep::create(['reps' => 1,
                                     'balance' => 1,
                                     'user_id' => 1
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
            $updatedDate = ($dataScore->count() > 0) ? $dataScore[0]->updated_date : null;
//            $hashddress = ($dataScore->count() > 0) ? $dataScore[0]->hash_address : null;
            $data = Submission::Where('address', $request->address)
                ->Where('approved', 1)
                ->get()->toArray();
            return response()->json(['status' => 200, 
                'score' => $score,
                'address' => $request->address,
                'data' => $data,
                'updated_date' => $updatedDate,
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
