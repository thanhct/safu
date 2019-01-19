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
            $address = Address::find($request->address);
            if($address) {
                Address::create(['hash_address' => $request->address]);
            }
            $data = $request->all();
            Submission::create($data);

            return response()->json(['status' => 201]);
        }
        return response()->json(['status' => 404]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function lookupAddress(Request $request){
        if(!empty($request->hash_address)) {
            $data = Submission::Where('hash_address', $request->hash_address)->get()->toArray();
            return response()->json(['status' => 200, 'data' => $data]);
        }
        return response()->json(['status' => 404]);
    }
}
