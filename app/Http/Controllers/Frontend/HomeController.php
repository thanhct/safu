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

    public function updateAddress($address, $score)
    {
        Address::Where('hash_address', $address)
                ->update(['score' => $score + 1,
                        'updated_date' => now()
                        ]);
    }

    public function updateUserRep($userId)
    {
        $userRep = UserRep::Where('user_id', $userId);
        if($userRep) {
            UserRep::update(['reps' => $userRep->reps + 1,
                             'balance' => $userRep->balance + 1
                            ])
                            ->Where('user_id', $userId);
        } else {
            UserRep::create(['reps' => 1,
                             'balance' => 1,
                             'user_id' => $userId
                            ]);
        }
    }

    public function submitAddress(Request $request){
        $userId = $request->userId;
        if(!empty($request->address) && !empty($userId)) {
            $hash_address = Address::Where('hash_address', $request->address)->get();
            $score = Address::find($request->address)->score;
            if($hash_address->count() === 0) {
                Address::create(['hash_address' => $request->address, 'score' => 1]);
            } else {
                if($score !== 100) {
                    $this->updateAddress($request->address, $score);
                }
            }
            $data = $request->all();
            $data['user_id'] = $userId;
            Submission::create($data);
            if($request->approved === 1){
                if($score !== 100) {
                    $this->updateAddress($request->address, $score);
                    $this->updateUserRep($userId);
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
            $userId = $request->userId;
            $dataScore = Address::Select('score', 'updated_date', 'hash_address')
                ->Where('hash_address', $request->address)
                ->get();
            $score = ($dataScore->count() > 0) ? $dataScore[0]->score : null;
            $date = ($dataScore->count() > 0) ? $dataScore[0]->updated_date : null;
            $hashddress = ($dataScore->count() > 0) ? $request->address : null;
            $data = Submission::with('user:id,first_name,last_name')->Where('address', $request->address)
                ->Where('approved', 1)
                ->get()->toArray();
            // get userRep
            $userRep = UserRep::Where('user_id', $userId)->first();
            if($userRep->balance >= 2) {
                UserRep::Where('user_id', $userId)
                    ->update(['balance' => $userRep->balance - 2
                    ]);

                $submisson = Submission::Select('id')->Where('address', $request->address)->Where('approved', 1)
                ->get()->toArray();

                $scorePush = (2*0.8)/$submisson->count();

                for($i = 0; $i < $submisson->count(); $i++) {
                    $result = UserRep::where('user_id', $submisson[$i]['id'])->get();
                    UserRep::Where('user_id', $submisson[$i]['id'])->update(['reps'=>$result->reps+1, 'balance'=>$result->balance+$scorePush]);
                }

                return response()->json(['status' => 200, 
                'score' => $score,
                'address' => $hashddress,
                'data' => $data,
                'updated_date' => $date, 
                ]);
            }
            else {
                return response()->json(['status' => 400], 400);
            }
            return response()->json(['status' => 200, 
                'score' => $score,
                'address' => $hashddress,
                'data' => $data,
                'updated_date' => $date, 
                ]);
        }
        return response()->json(['status' => 404], 404);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus(Request $request){
        $userId = $request->userId;
        if(!empty($request->id)) {
            $submission = Submission::find($request->id);
            if($submission->approved === 0) {
                $score = Address::find($request->address)->score;            
                if($score !== 100) {
                    Address::update(['score' => $score + 1,
                                'updated_date' => now()
                                ])
                        ->Where('hash_address', $request->address);
                    Submission::update([
                                        'approved' => 1,
                                        'appr_user' => $userId,
                                        'appr_date' => now()
                                        ])
                                ->Where('id', $request->id);
                    $this->updateUserRep($userId);
                }
                return response()->json(['status' => 200]);       
            }
            
        }
        return response()->json(['status' => 404]);
    }
}
