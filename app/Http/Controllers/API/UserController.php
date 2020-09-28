<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Twilio\Exceptions\RestException;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Validator;
use Auth;

class UserController extends BaseController
{
    private $twilio_token;
    private $twilio_sid;
    private $twilio_verify_sid;

    public function __construct()
    {
        $this->twilio_token = getenv("TWILIO_AUTH_TOKEN");
        $this->twilio_sid = getenv("TWILIO_SID");
        $this->twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
    }

    public function request_phone_verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'surname' => ['required'],
            'firstname' => ['required'],
            'birthday' => ['required'],
            'gender' => ['required'],
            'phone' => ['required', 'max:15'],
            'device_type' => ['required'], //ios or android
            'password' => ['required']
        ]);

        if ($validator->fails()) {
            $this->response_error($validator->errors()->first(), 406);                              // 406: Not Acceptable
        }

        $phone_number = $request->phone;
        if (substr($phone_number, 0, 1) != '+') $phone_number = '+' . $phone_number;

        // check whether phone number already is exists.
        $user = User::select('id')->where( ['phone' => $phone_number])->first();
        if ( ! empty($user)) {
            $this->response_error('Already exist the phone number, please enter another phone number.', 406); // 406: Not Acceptable
        }

        $password = $request->password;
        $encrypt_password = Hash::make($password);

        // Request verificaiton code. If success, user can receive to phone message that includes verification code
        $verify_request_result = $this->request_verify_code($phone_number);
        if ($verify_request_result == 'success') {

            $token = mt_rand(100000, 999999);
            $user = User::create([
                'name' => $request->surname . ' ' . $request->firstname,
                'birthday' => $request->birthday,
                'gender' => $request->gender,
                'phone' => $phone_number,
                'password' => $encrypt_password,
                'device_type' => $request->device_type,
                'device_token' => !empty($request->input('device_token')) ? $request->input('device_token') : '',
                'token' => $token,
                'code_requested_at' => date('Y-m-d H:i:s')
            ]);

            if ($user)
                $this->response_success('The request was processed successfully.', $token , 'token');
            else
                $this->response_error('Unable to signup into the application, please try again.', 406); // 406: Not Acceptable
        }
        else {
            $this->response_error('Request failure. Please check your network.', 599);       // 599: Network connect timeout error
        }
    }

    public function retry_phone_verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'max:15'],
        ]);

        if ($validator->fails()) {
            $this->response_error($validator->errors()->first(), 406);                              // 406: Not Acceptable
        }

        $phone_number = $request->phone;
        if (substr($phone_number, 0, 1) != '+') $phone_number = '+' . $phone_number;

        // Request verificaiton code. If success, user can receive to phone message that includes verification code
        $verify_request_result = $this->request_verify_code($phone_number);
        if ($verify_request_result == 'success') {
            $user = tap(User::where('phone', $phone_number)->update(['code_requested_at' => date('Y-m-d H:i:s')]));
            if ($user)
                $this->response_success('The request was processed successfully.', '', '');
            else
                $this->response_success('Unable to access to the database.', '', '');
        }
        else
            $this->response_error('Request failure. Please check your network', 599);       // 599: Network connect timeout error
    }

    public function phone_verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|max:15',
            'digits' => 'required'
        ]);

        if ($validator->fails()) {
            $this->response_error($validator->errors()->first(), 406);                              // 406: Not Acceptable
        }

        $phone_number = $request->phone;
        $verification_code = $request->digits;
        if (substr($phone_number, 0, 1) != '+') $phone_number = '+' . $phone_number;

        // Request whether verification code is valid
        $check_request_result = $this->check_verify_code($phone_number, $verification_code);

        if ($check_request_result == 'success') {
            $user = User::select('id', 'phone', 'name', 'avatar', 'coins', 'cash', 'vouchers', 'password', 'code_requested_at', 'is_verified_at', 'is_blocked_at')->where( ['phone' => $phone_number])->first();

            if ( empty($user) )
                $this->response_success('Can not get user information, please try signup again', '', '');

            $from = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user->code_requested_at);
            $to = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

            $diff_in_minutes = $to->diffInMinutes($from);
            if ($diff_in_minutes > 10)
                $this->response_error('Valid period is expired!', 406); // 406: Not Acceptable

            $user_update = tap(User::where('phone', $phone_number)->update( ['is_verified_at' => date('Y-m-d H:i:s'), 'isverified' => 1] ));
            $user->access_token = $user->createToken('WhotClash')->accessToken;
            if ($user_update)
                $this->response_success('Your phone was verified successfully.', $user, 'user');
            else
                $this->response_error('Unable to signup into the application, please try again.', 406); // 406: Not Acceptable
        }
        elseif ($check_request_result != 'success')
            $this->response_error('Invalid verification code entered!', 406);               // 406: Not Acceptable
        else
            $this->response_error('Request failure. Please check your network', 599);       // 599: Network connect timeout error
    }

    public function request_verify_code($phone_number)
    {
        $twilio = new Client($this->twilio_sid, $this->twilio_token);
        try {
            $twilio->verify->v2->services($this->twilio_verify_sid)
                ->verifications
                ->create($phone_number, "sms");
        } catch(RestException $e) {
            return $e->getMessage();
        }

        return "success";
    }

    public function check_verify_code($phone_number, $verify_code)
    {
        //$twilio = new Client($this->twilio_sid, $this->twilio_token);
        try {
            /*$verification = $twilio->verify->v2->services($this->twilio_verify_sid)
                ->verificationChecks
                ->create($verify_code, array('to' => $phone_number));

            if ($verification->valid)
                return "success";
            else
                return "failure";*/
            return "success";
        } catch(RestException $e) {
            return $e->getMessage();
        }

    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|max:15',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            $this->response_error($validator->errors()->first(), 406);                              // 406: Not Acceptable
        }

        $phone_number = $request->phone;
        $password = $request->password;
        if (substr($phone_number, 0, 1) != '+') $phone_number = '+' . $phone_number;

        $user = User::select('id', 'phone', 'name', 'avatar', 'coins', 'cash', 'vouchers', 'password', 'code_requested_at', 'is_verified_at', 'is_blocked_at')->where( ['phone' => $phone_number])->first();
        if( !empty($user) ) {
            if (Hash::check($password, $user->password)) {
                if($user->is_blocked_at != '' && $user->is_blocked_at != null)
                    $this->response_error("Your account has been blocked by admin.",400);   // 400: Bad Request
                else if ($user->is_verified_at == '' || $user->is_verified_at == null)
                    $this->response_error("Your phone is not verified yet.", 401);          // 401: Unauthorized
                else {
                    $user->access_token = $user->createToken('WhotClash')->accessToken;
                    $this->response_success('success', $user, 'user');
                }
            }
            else
                $this->response_error('Incorrect phone number or password1.', 406);         // 406: Not Acceptable

        }
        else
        $this->response_error('Incorrect email address or password2.', 406);                // 406: Not Acceptable

    }

    //Get User Profile with Bank Details
    public function get_profile() {
        $user = Auth::user();

        if(!empty($user)) {
            if($user->block == '1') {
                $this->response_error("Your account has been blocked by admin.",400);
            }

            if($user->device_token == null){
                $this->response_error('Un Authenticated.', 406);
            }
//            $bank_details = BankDetail::where(['user_id' => $user->id])->first();
//            if (!empty($bank_details)) {
//                $user->bank_details = $bank_details;
//            }
            $this->response_success('', $user, 'user');
        }
        else {
            $this->response_error('Un Authenticated.', 406);
        }
    }
}
