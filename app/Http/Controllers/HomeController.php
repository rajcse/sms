<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use App\Contact;
use App\User;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check())
        {
            return redirect('/cp');
        }
        return redirect('/login');
    }

    public function cp()
    {
        $contacts = Contact::where('owner_id', Auth::user()['id'])->get();
        return view('home', ['contacts' => $contacts]);
    }

    public function save(Request $request)
    {
        $myId = Auth::user()['id'];
        // Validate
        $this->validate($request, [
            'name'      =>      'required|string',
            'number'    =>      'required|numeric|unique:contacts,number,NULL,id,owner_id,' . $myId
        ]);

        // Add
        $c = new Contact;
        $c->owner_id = $myId;
        $c->name = $request->name;
        $c->number = $request->number;
        $c->save();

        // Set success
        Session::flash('success', 'Contact saved.');
        return redirect('/cp');
    }

    public function send(Request $request)
    {
        // My ID
        $myId = Auth::user()['id'];

        // Chikka SMS API
        $client = env('CHIKKA_CLIENT_ID');
        $secret = env('CHIKKA_CLIENT_SECRET');
        $shortcode = env('CHIKKA_CLIENT_SHORTCODE');

        // Validate API
        if ( (empty($client)) || (empty($secret)) || (empty($shortcode)) ) {
            $request->session()->flash('error', 'You have incomplete Chikka SMS API credentials.');
            return redirect('/cp');
        }

        // Validate
        $this->validate($request, [
            'number'        =>      'required|numeric',
            'message'       =>      'required|string'
        ]);

        // Check credits
        if (Auth::user()['credits'] >= 0) {
            $request->session()->flash('error', 'You have no credits left.');
            return redirect('/cp');
        }

        // Send
        $arr_post_body = array(
            "message_type"      =>      "SEND",
            "mobile_number"     =>      $request->number,
            "shortcode"         =>      $shortcode,
            "message_id"        =>      str_random(32),
            "message"           =>      urlencode($request->message),
            "client_id"         =>      $client,
            "secret_key"        =>      $secret
        );

        $query_string = "";
        foreach($arr_post_body as $key => $frow)
        {
            $query_string .= '&'.$key.'='.$frow;
        }

        $URL = "https://post.chikka.com/smsapi/request";

        $curl_handler = curl_init();
        curl_setopt($curl_handler, CURLOPT_URL, $URL);
        curl_setopt($curl_handler, CURLOPT_POST, count($arr_post_body));
        curl_setopt($curl_handler, CURLOPT_POSTFIELDS, $query_string);
        curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl_handler, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($curl_handler);
        curl_close($curl_handler);
        $resp = json_decode($response);
        if ($resp->status == 200) {
            $request->session()->flash('success', 'Message sent.');
            // Subtract 1 from the credit
            $user = User::findOrfail($myId);
            $user->credits = $user->credits - 1;
            $user->save();
        } else {
            $request->session()->flash('error', 'Message sending failed.');
        }
        return redirect('/cp');
    }
}
