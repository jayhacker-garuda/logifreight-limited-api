<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\QuickAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class QuickAlertController extends Controller
{
    public function generate_company_tracking_number($tracking_number)
    {
        $company_tracking_number = '#'.str_pad($tracking_number, 8, "LF", STR_PAD_LEFT);

        return $company_tracking_number;
    }
    public function AddQuickAlert(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'member_umi' => 'required',
            'package_type' => 'required',
            'purchased_location' => 'required',
            'cost' => 'required',
            'weight' => 'required',
            'shipping_company' => 'required',
            'tracking_number' => 'required',
        ]);

        if($validation->fails())
        {
            return response()->json(['errors' => $validation->errors()], 202);
        }

        $alertData = $request->all();

        // dd($alertData['member_umi']);


        $quickAlert = QuickAlert::create([
            'member_umi' => $alertData["member_umi"],
            'package_type' => $alertData["package_type"],
            'purchased_location' => $alertData["purchased_location"],
            'cost' => $alertData["cost"],
            'weight' => $alertData["weight"],
            'shipping_company' => $alertData["shipping_company"],
            'tracking_number' => $alertData["tracking_number"],
        ]);

        $quickAlert->company_tracking_number = $this->generate_company_tracking_number($quickAlert->tracking_number);
        $quickAlert->update();

        $response = [];
        $response['message'] = '🤘🏾😎🤘🏾';

        return response()->json($response, 200);
    }

    public function packages(Request $request)
    {
        // dd($request->user()->umi);
        $quickAlert = [];
        $quickAlert["packages"] = QuickAlert::with('member')
        ->where('member_umi', '=', $request->user()->umi)
        ->get();

        $quickAlert["all_packages"] = count(QuickAlert::with('member')
        ->where('member_umi', '=', $request->user()->umi)
        ->get());
        // $quickAlert["all_packages"] = 10;

        $quickAlert["in_transit"] = count(QuickAlert::with('member')
        ->where('member_umi', '=', $request->user()->umi)
        ->where('status', '=', 'in_transit')
        ->get());

        $quickAlert["delivered"] = count(QuickAlert::with('member')
        ->where('member_umi', '=', $request->user()->umi)
        ->where('status', '=', 'delivered')
        ->get());

        // dd(isset($quickAlert["all_packages"]));

        if (!$quickAlert["all_packages"] > 0) {

            return response()->json(['error' => 'No Package Added'], 202);
        }

        return response()->json($quickAlert, 200);

    }
}
