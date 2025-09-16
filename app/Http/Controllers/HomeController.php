<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsersPhoneNumber;
use Twilio\Rest\Client;

class HomeController extends Controller
{
    /**
     * Store a new user phone number.
     *
     * @param  Request  $request
     * @return Response
     */
    public function storePhoneNumber(Request $request)
    {
        $validatedData = $request->validate([
            'users' => 'required|array',
            'body' => 'required|string',
        ]);
        dd($validatedData);

        foreach ($validatedData['users'] as $userId) {
            $user = UsersPhoneNumber::find($userId);
            if ($user && $user->phone_number) {
                $this->sendMessage($validatedData['body'], $user->phone_number);
            }
        }

        return back()->with(['success' => "Messages on their way!"]);
    }

    /**
     * Show the forms with users phone number details.
     *
     * @return Response
     */
    public function show()
    {
        $users = UsersPhoneNumber::all(); //query db with model
        return view('welcome', compact("users")); //return view with data
    }
    /**
     * Sends sms to user using Twilio's programmable sms client
     * @param String $message Body of sms
     * @param Number $recipients string or array of phone number of recepient
     */
    public function sendMessage($message, $recipients)
    {
        $account_sid = getenv("TWILIO_SID");
        $auth_token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_number = getenv("TWILIO_NUMBER");
        $client = new Client($account_sid, $auth_token);
        // Ensure $recipients is a string (phone number)
        $recipientNumber = is_object($recipients) && method_exists($recipients, '__toString')
            ? (string)$recipients
            : (is_array($recipients) ? (string)reset($recipients) : (string)$recipients);

        $client->messages->create(
            $recipientNumber,
            ['from' => $twilio_number, 'body' => $message]
        );
    }
}
