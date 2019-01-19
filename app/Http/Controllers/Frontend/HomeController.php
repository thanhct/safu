<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\System\Address;
use App\Models\System\Submission;

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
            if($hash_address->count() == 0) {
                Address::create(['hash_address' => $request->address, 'score' => 0]);
            } 
            // else {
            //     Address::update(['score' => $hash_address + 1])
            // }
            $data = $request->all();
            $data['user_id'] = 5;
            Submission::create($data);
            return response()->json(['status' => 201]);
        }
        return response()->json(['status' => 404]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function lookupAddress(Request $request){
        if(!empty($request->address)) {
            $dataScore = Address::Select('score')
                ->Where('hash_address', $request->address)
                ->get();
            $score = ($dataScore->count() > 0) ? $dataScore[0]->score : null;
            $data = Submission::Where('address', $request->address)
                ->Where('approved', 1)
                ->get()->toArray();
            return response()->json(['status' => 200, 'score' => $score, 'data' => $data]);
        }
        return response()->json(['status' => 404]);
    }
}
