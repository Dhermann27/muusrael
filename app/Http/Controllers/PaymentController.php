<?php

namespace App\Http\Controllers;

use App\Charge;
use App\Enums\Chargetypename;
use App\Family;
use App\PayPalClient;
use App\Province;
use App\ThisyearCharge;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use PayPalCheckoutSdk\Orders\OrdersPatchRequest;
use Sample\PatchOrder;
use function date;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $messages = [
            'donation.max' => 'Please use the Contact Us form at the top of the screen (Subject: Treasurer) to commit to a donation above $100.00.',
        ];

        $this->validate($request, [
            'donation' => 'min:0|max:100',
            'amount' => 'required|min:0'
        ], $messages);

        $thiscamper = Auth::user()->camper;
        if ($thiscamper !== null) {
            if ($request->input('donation') > 0) {
                Charge::updateOrCreate(
                    ['camper_id' => $thiscamper->id, 'chargetype_id' => Chargetypename::Donation,
                        'year_id' => $this->year->id],
                    ['camper_id' => $thiscamper->id,
                        'amount' => $request->input('donation'), 'memo' => 'MUUSA Scholarship Fund',
                        'chargetype_id' => Chargetypename::Donation, 'year_id' => $this->year->id,
                        'timestamp' => date("Y-m-d")]
                );
            }

            if (!empty($request->input('orderid'))) {

                $txn = $request->input('orderid');
                $charge = Charge::where(['camper_id' => $thiscamper->id,
                    'chargetype_id' => Chargetypename::PayPalPayment, 'order_id' => $txn])->first();
                if (!$charge) {
                    $charge = new Charge();
                    $charge->camper_id = $thiscamper->id;
                    $charge->chargetype_id = Chargetypename::PayPalPayment;
                    $charge->order_id = $txn;
                }
                $charge->amount = $request->input('amount');
                $charge->year_id = $this->year->id;
                $charge->timestamp = date("Y-m-d");
                $charge->created_at = Carbon::now();
                $charge->save();

                $charge->memo = 'PayPal Invoice ID #' . $charge->id;
                $charge->save();

                //$this->patchOrder($txn, $charge->id);

                if (!empty($request->input('addthree'))) {
                    Charge::updateOrCreate(
                        ['camper_id' => $thiscamper->id, 'memo' => 'Optional payment to offset PayPal ' . $txn],
                        ['camper_id' => $thiscamper->id, 'amount' => ($request->input('amount') / 1.03 * .03),
                            'memo' => 'Optional payment to offset PayPal Invoice #' . $charge->id,
                            'chargetype_id' => Chargetypename::PayPalServiceCharge,
                            'year_id' => $this->year->id, 'timestamp' => date("Y-m-d"),
                            'created_at' => DB::raw('CURRENT_TIMESTAMP')]
                    );

                }

                $family = Family::findOrFail($thiscamper->family_id);
                if ($family->address1 == "NEED ADDRESS") {
                    $family->address1 = $request->input('address1');
                    $family->address2 = $request->input('address2');
                    $family->city = $request->input('city');
                    $family->province_id = Province::where('code', $request->input('province'))->first()->id;
                    $family->zipcd = $request->input('zipcd');
                    $family->is_address_current = 1;
                }

                $success = 'Payment received! You should receive a receipt via email for your records.';

//                $campers = Byyear_Camper::where('familyid', $thiscamper->familyid)
//                    ->where('year', ((int)$year->year) - 1)->where('is_program_housing', '0')->get();
//                if (!$year->is_live && count($campers) > 0 && \App\Thisyear_Charge::where('familyid', $thiscamper->familyid)
//                        ->where(function ($query) {
//                            $query->where('chargetype_id', 1003)->orWhere('amount', '<', '0');
//                        })->get()->sum('amount') <= 0) {
//                    foreach ($campers as $camper) {
//                        \App\Yearattending::where('camper_id', $camper->id)->where('year', $year->year)
//                            ->whereNull('roomid')->update(['roomid' => $camper->roomid]);
//                    }
//                    DB::statement('CALL generate_charges(' . $year->year . ');');
//
//                    $success = 'Payment received! By paying your deposit, your room from ' . ((int)($year->year) - 1)
//                        . ' has been assigned. You should receive a receipt via email for your records.';
//                }

                $request->session()->flash('success', $success);
            }

        } else {
            $request->session()->flash('error', 'Payment was not processed by MUUSA. If you believe that PayPal has transmitted funds, please contact the Treasurer so we can confirm and update your account.');
        }

        return redirect()->action('PaymentController@index');

    }

    public function index(Request $request)
    {
        $token = env('PAYPAL_CLIENT');
        $deposit = 0.0;

        if (!isset(Auth::user()->camper)) {
            $request->session()->flash('warning', 'Please fill out your camper information to continue.');
            return redirect()->action('CamperController@index');
        } else {
            $charges = ThisyearCharge::where('family_id', Auth::user()->camper->family_id)->orderBy('timestamp')->orderBy('amount', 'desc')->get();
            if (count($charges) == 0) {
                $request->session()->flash('warning', 'Please choose which campers are attending this year.');
                return redirect()->action('CamperController@index');
            }
            foreach ($charges as $charge) {
                if ($charge->amount < 0 || $charge->chargetype_id == Chargetypename::Deposit) {
                    $deposit += $charge->amount;
                }
            }
        }

        return view('payment', ['token' => $token, 'charges' => $charges, 'deposit' => $deposit]);
//            'steps' => $this->getSteps()]);
    }

//    public function write(Request $request, $id)
//    {
//        $thiscamper = \App\Camper::where('familyid', $id)->first();
//        $year = \App\Year::where('is_current', '1')->first();
//
//        foreach ($request->all() as $key => $value) {
//            $matches = array();
//            if (preg_match('/(\d+)-(delete|chargetype_id|amount|timestamp|memo)/', $key, $matches)) {
//                $charge = \App\Charge::findOrFail($matches[1]);
//                if ($matches[2] == 'delete') {
//                    if ($value == 'on') {
//                        $charge->delete();
//                    }
//                } else {
//                    $charge->{$matches[2]} = $value;
//                    $charge->save();
//                }
//            }
//        }
//
//        if ($request->input('amount') != '') {
//            $charge = new \App\Charge();
//            $charge->camper_id = $thiscamper->id;
//            $charge->chargetype_id = $request->input('chargetype_id');
//            $charge->amount = (float)$request->input('amount');
//            $charge->timestamp = $request->input('date');
//            $charge->memo = $request->input('memo');
//            $charge->year = $year->year;
//            $charge->save();
//        }
//
//        $success = "";
//        if (!$year->is_live && \App\Thisyear_Charge::where('familyid', $id)->where('chargetype_id', 1003)
//                ->orWhere('amount', '<', '0')->get()->sum('amount') <= 0) {
//            $campers = \App\Byyear_Camper::where('familyid', $id)->where('year', ((int)$year->year) - 1)
//                ->where('is_program_housing', '0')->get();
//            foreach ($campers as $camper) {
//                \App\Yearattending::where('camper_id', $camper->id)->where('year', $year->year)
//                    ->whereNull('roomid')->update(['roomid' => $camper->roomid]);
//            }
//            DB::statement('CALL generate_charges(' . $year->year . ');');
//
//            $success .= 'Your room from ' . ((int)($year->year) - 1) . ' been assigned. ';
//        }
//
//        $request->session()->flash('success', $success . 'Rocking it today! But what about their <a href="' . url('/workshopchoice/f/' . $id) . '">workshops</a>?');
//
//        return redirect()->action('PaymentController@read', ['i' => 'f', 'id' => $id]);
//
//    }
//
//    public function read($i, $id)
//    {
//        $readonly = \Entrust::can('read') && !\Entrust::can('write');
//        $family = \App\Family::find($this->getFamilyId($i, $id));
//        $years = \App\Byyear_Charge::where('familyid', $family->id)
//            ->orderBy('year')->orderBy('timestamp')->orderBy('amount', 'desc')->get()->groupBy('year');
//
//        return view('admin.payment', ['chargetypes' => \App\Chargetype::where('is_shown', '1')->orderBy('name')->get(),
//            'years' => $years, 'readonly' => $readonly, 'family' => $family, 'steps' => $this->getSteps()]);
//    }

    protected function patchOrder($orderId, $invoiceId)
    {

        $client = PayPalClient::client();
        $request = new OrdersPatchRequest($orderId);
        $request->body = [ ['op' => 'replace', 'path' => '/intent', 'value' => 'CAPTURE'],
                                ['op' => 'add',
                                'path' => '/purchase_units/@reference_id==\'default\'/invoice_id',
                                'value' => $invoiceId] ];
        $client->execute($request);
    }

    private function getFamilyId($i, $id)
    {
        return $i == 'c' ? \App\Camper::find($id)->familyid : $id;
    }
}
