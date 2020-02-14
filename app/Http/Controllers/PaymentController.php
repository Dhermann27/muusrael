<?php

namespace App\Http\Controllers;

use App\ByyearCharge;
use App\Camper;
use App\Charge;
use App\Chargetype;
use App\Enums\Chargetypename;
use App\Family;
use App\Mail\Confirm;
use App\PayPalClient;
use App\Province;
use App\ThisyearCamper;
use App\ThisyearCharge;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
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

        if (Gate::allows('is-super') && $request->session()->has('camper_id')) {
            foreach ($request->all() as $key => $value) {
                $matches = array();
                if (preg_match('/(\d+)-(delete|chargetype_id|amount|timestamp|memo)/', $key, $matches)) {
                    $charge = Charge::findOrFail($matches[1]);
                    if ($matches[2] == 'delete') {
                        if ($value == 'on') {
                            $charge->delete();
                        }
                    } else {
                        $charge->{$matches[2]} = $value;
                        $charge->save();
                    }
                }
            }

            if ($request->input('amount') != '') {
                $charge = new Charge();
                $charge->camper_id = $request->session()->get('camper_id');
                $charge->chargetype_id = $request->input('chargetype_id');
                $charge->amount = (float)$request->input('amount');
                $charge->timestamp = $request->input('date');
                $charge->memo = $request->input('memo');
                $charge->year_id = $this->year->id;
                $charge->save();
            }

            $request->session()->flash('success', 'Rocking it today!');
        } else {

            $thiscamper = Auth::user()->camper;
            if ($thiscamper !== null) {
                if ($request->input('donation') > 0) {
                    $charge = Charge::where(['camper_id' => $thiscamper->id, 'chargetype_id' => Chargetypename::Donation,
                        'year_id' => $this->year->id])->first();
                    if (!$charge) {
                        $charge = new Charge();
                        $charge->camper_id = $thiscamper->id;
                        $charge->chargetype_id = Chargetypename::Donation;
                        $charge->year_id = $this->year->id;
                    }
                    $charge->amount = $request->input('donation');
                    $charge->memo = 'MUUSA Scholarship Fund';
                    $charge->timestamp = date("Y-m-d");
                    $charge->save();
                }

                if (!empty($request->input('orderid'))) {

                    $before = Gate::allows('has-paid');
                    $txn = $this->getOrder($request->input('orderid'));
                    $charge = Charge::where(['camper_id' => $thiscamper->id,
                        'chargetype_id' => Chargetypename::PayPalPayment, 'memo' => $txn])->first();
                    if (!$charge) {
                        $charge = new Charge();
                        $charge->camper_id = $thiscamper->id;
                        $charge->chargetype_id = Chargetypename::PayPalPayment;
                        $charge->memo = $txn;
                    }
                    $charge->amount = $request->input('amount') * -1;
                    $charge->year_id = $this->year->id;
                    $charge->timestamp = date("Y-m-d");
                    $charge->created_at = Carbon::now();
                    $charge->save();

                    $paid = ThisyearCharge::where('family_id', Auth::user()->camper->family_id)
                        ->where(function ($query) {
                            $query->where('chargetype_id', Chargetypename::Deposit)->orWhere('amount', '<', 0);
                        })->get()->sum('amount');
                    if (!$before && $paid <= 0) {
                        $request->session()->flash('newreg', true);
                        Mail::to(Auth::user()->email)
                            ->send(new Confirm($this->year, ThisyearCamper::where('family_id', Auth::user()->camper->family_id)->get()));
                    }


                    $family = Family::where('id', $thiscamper->family_id)->whereNull('city')->first();
                    if ($family) {
                        $family->address1 = $request->input('address1');
                        $family->address2 = $request->input('address2');
                        $family->city = $request->input('city');
                        $family->province_id = Province::where('code', $request->input('province'))->first()->id;
                        $family->zipcd = $request->input('zipcd');
                        $family->is_address_current = 1;
                        $family->save();
                    }

                    if (!empty($request->input('addthree'))) {
                        $charge = Charge::where(['camper_id' => $thiscamper->id,
                            'memo' => 'Optional payment to offset PayPal Invoice #' . $txn])->first();
                        if (!$charge) {
                            $charge = new Charge();
                            $charge->camper_id = $thiscamper->id;
                            $charge->memo = 'Optional payment to offset PayPal Invoice #' . $txn;
                        }
                        $charge->year_id = $this->year->id;
                        $charge->chargetype_id = Chargetypename::PayPalServiceCharge;
                        $charge->amount = $request->input('amount') / 1.03 * .03;
                        $charge->timestamp = date("Y-m-d");
                        $charge->save();
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
        }

        return redirect()->action('PaymentController@index');

    }

    public function index(Request $request, $id = null)
    {
        if ($id && Gate::allows('is-council')) {
            $request->session()->put('camper_id', $id);
        }
        $token = env('PAYPAL_CLIENT');
        $deposit = 0.0;

        if (!isset(Auth::user()->camper)) {
            $request->session()->flash('warning', 'Please fill out your camper information to continue.');
            return redirect()->action('CamperController@index');
        } else {
            if (Gate::allows('is-council') && $request->session()->has('camper_id')) {
                $family_id = Camper::findOrFail($request->session()->get('camper_id'))->family_id;
                $years = ByyearCharge::where('family_id', $family_id)->orderBy('timestamp')->orderBy('amount', 'desc')->get()->groupBy('year');
            } else {
                $years = ThisyearCharge::where('family_id', Auth::user()->camper->family_id)->orderBy('timestamp')->orderBy('amount', 'desc')->get();
                if (count($years) == 0) {
                    $request->session()->flash('warning', 'Please choose which campers are attending this year.');
                    return redirect()->action('CamperController@index');
                }
                foreach ($years as $charge) {
                    if ($charge->amount < 0 || $charge->chargetype_id == Chargetypename::Deposit) {
                        $deposit += $charge->amount;
                    }
                }
                $years = $years->groupBy('year');
            }
        }

        return view('payment', ['token' => $token, 'years' => $years, 'deposit' => $deposit,
            'chargetypes' => Chargetype::where('is_shown', 1)->get()]);
    }

    protected function getOrder($orderId)
    {
        $client = PayPalClient::client();
        $response = $client->execute(new OrdersGetRequest($orderId));
        return $response->result->purchase_units[0]->payments->captures[0]->id;
    }
}
