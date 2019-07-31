<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Contactbox;
use App\Mail\ContactUs;

class ContactController extends Controller
{
    public function contactStore(Request $request)
    {
        $messages = [
            'message.not_regex' => 'This contact form does not accept the Bible as the Word of God.',
            'g-recaptcha-response.required' => 'Please check the CAPTCHA box and follow any additional instructions.',
        ];
        if (Auth::check()) {
            $camper = Auth::user()->camper;
            $request["name"] = $camper->firstname . " " . $camper->lastname;
            $request["email"] = $camper->email;
        }
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'mailbox' => 'required|exists:contactboxes,id',
            'message' => 'required|min:5|not_regex:/scripture/i|not_regex:/gospel/i|not_regex:/infallible/i',
            'g-recaptcha-response' => '',
        ], $messages);
        $emails = explode(',', Contactbox::findOrFail($request->mailbox)->emails);
        Mail::to($emails)->send(new ContactUs($request));
        $request->session()->flash('success', 'Message sent! Please expect a response in 1-3 business days.');
        return redirect()->action('ContactController@contactIndex');
    }

    public function contactIndex()
    {
        $camper = null;
        if (Auth::check()) {
            $camper = Auth::user()->camper;
        }
        return view('contactus', ['mailboxes' => Contactbox::orderBy('id')->get(), 'camper' => $camper]);
    }
}
