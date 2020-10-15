<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Twilio\Exceptions\RestException;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;

use App\User;
use App\PlayGame;

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
            'phone' => ['required'],
            'device_type' => ['required'], //ios or android
            'password' => ['required']
        ]);

        if ($validator->fails()) {
            $this->response_error($validator->errors()->first(), 406);                              // 406: Not Acceptable
        }

        $phone_number = $this->get_real_phone_number($request->phone);

        // check whether phone number already is exists.
        $user = User::select('id')->where( ['phone' => $phone_number, 'remove'=>0])->first();
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
                $this->response_success('The request was processed successfully.', '' , '');
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
            'phone' => ['required'],
        ]);

        if ($validator->fails()) {
            $this->response_error($validator->errors()->first(), 406);                              // 406: Not Acceptable
        }

        $phone_number = $this->get_real_phone_number($request->phone);

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
            'phone' => 'required',
            'digits' => 'required'
        ]);

        if ($validator->fails()) {
            $this->response_error($validator->errors()->first(), 406);                              // 406: Not Acceptable
        }

        $phone_number = $this->get_real_phone_number($request->phone);
        $verification_code = $request->digits;

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
        if (substr($phone_number, 0, 3) != '+79') {
            $twilio = new Client($this->twilio_sid, $this->twilio_token);
        }
        try {
            if (substr($phone_number, 0, 3) != '+79') {
                $verification = $twilio->verify->v2->services($this->twilio_verify_sid)
                    ->verificationChecks
                    ->create($verify_code, array('to' => $phone_number));

                if ($verification->valid)
                    return "success";
                else
                    return "failure";
            }
            else
                return "success";

        } catch(RestException $e) {
            return $e->getMessage();
        }

    }

    public function get_real_phone_number($phone_number)
    {
        if (substr($phone_number, 0, 1) == '0') $phone_number = substr($phone_number, 1);
        if (substr($phone_number, 0, 1) != '+') $phone_number = '+' . $phone_number;
        if (substr($phone_number, 0, 2) == '+0') $phone_number = '+'. substr($phone_number, 2);
        return $phone_number;
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            $this->response_error($validator->errors()->first(), 406);                              // 406: Not Acceptable
        }

        $phone_number = $this->get_real_phone_number($request->phone);
        $password = $request->password;

        $user = User::select('id', 'phone', 'name', 'avatar', 'coins', 'cash', 'vouchers', 'password', 'code_requested_at', 'is_verified_at', 'is_blocked_at')
            ->where( ['phone' => $phone_number, 'remove'=>0])->first();
        if( !empty($user) ) {
            if (Hash::check($password, $user->password)) {
                if($user->is_blocked_at != '' && $user->is_blocked_at != null)
                    $this->response_error("Your account has been blocked by admin.",400);   // 400: Bad Request
                else if ($user->is_verified_at == '' || $user->is_verified_at == null)
                    $this->response_error("Your phone is not verified yet.", 401);          // 401: Unauthorized
                else {
                    $login_data = DB::table("user_logins")->where('user_id', $user->id)->first();
                    if (!empty($login_data) && $login_data->is_online == 1) {
                        $this->response_error("You already logged in.", 406);          // 401: Unauthorized
                    }
                    else {
                        if (empty($login_data))
                            DB::insert("INSERT user_logins SET user_id='" . $user->id . "', login_at=NOW(), is_online=1");
                        else
                            DB::update("UPDATE user_logins SET login_at=NOW(), is_online=1 WHERE user_id='" . $user->id . "'");

                        DB::update("UPDATE user_logins SET is_online=0 WHERE is_online=1 AND timestampdiff(SECOND, login_at, NOW()) > 15");

                        $user->access_token = $user->createToken('WhotClash')->accessToken;
                        $this->response_success('success', $user, 'user');
                    }

                    
                }
            }
            else
                $this->response_error('Incorrect phone number or password.', 406);         // 406: Not Acceptable

        }
        else
        $this->response_error('Incorrect phone number or password.', 406);                // 406: Not Acceptable


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

    public function add_friend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id1' => 'required',
            'user_id2' => 'required'
        ]);

        if ($validator->fails()) {
            $this->response_error($validator->errors()->first(), 406);                              // 406: Not Acceptable
        }


        // check if the users is already add
        $user = DB::select("SELECT * FROM friends WHERE (user_id1=:user_id1 AND user_id2=:user_id2) OR (user_id1=:user_id3 AND user_id2=:user_id4)",
            ['user_id1'=>$request->user_id1, 'user_id2'=>$request->user_id2, 'user_id3'=>$request->user_id2, 'user_id4'=>$request->user_id1]);

        if (empty($user)) {
            DB::table('friends')->insert(['user_id1'=> $request->user_id1, 'user_id2'=> $request->user_id2]);
        }
        $this->response_success('The user just added as friend successfully.', '' , '');
    }

    public function get_friend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);

        if ($validator->fails()) {
            $this->response_error($validator->errors()->first(), 406);                              // 406: Not Acceptable
        }

        $user = DB::select("
                SELECT * FROM (
                    SELECT U.* FROM friends f INNER JOIN (SELECT * FROM users WHERE isverified=1 AND remove=0 AND (is_blocked_at IS NULL OR is_blocked_at='')) U ON f.user_id2=U.id WHERE f.user_id1=:user_id1
                    UNION
                    SELECT U.* FROM friends f INNER JOIN (SELECT * FROM users WHERE isverified=1 AND remove=0 AND (is_blocked_at IS NULL OR is_blocked_at='')) U ON f.user_id1=U.id WHERE f.user_id2=:user_id2
                ) T ", ['user_id1'=>$request->user_id, 'user_id2'=>$request->user_id]);

        $this->response_success('', $user, 'friend');
    }

    public function add_invite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_code' => 'required',
            'user_id' => 'required',
            'friend_list' => 'required',
            'players' => 'required',
            'betting_amount' => 'required'
        ]);

        if ($validator->fails()) {
            $this->response_error($validator->errors()->first(), 406);                              // 406: Not Acceptable
        }

        DB::table('invites')->where('user_id', $request->user_id)->delete();
        $user = DB::select("SELECT * FROM invites WHERE room_code=:room_code", ['room_code'=>$request->room_code]);

        if (empty($user)) {
            $friend_list = substr($request->friend_list,  0, -1);
            $friend_array = explode(',', $friend_list);
            foreach($friend_array as $friend_id) {
                $already_exist = DB::table('invites')->where('friend_id', $friend_id)->where('accept_flag', '')->first();
                if (empty($already_exist))
                    DB::table('invites')->insert(['room_code'=> $request->room_code, 'user_id'=> $request->user_id, 'friend_id'=>$friend_id, 'players'=>$request->players, 'betting_amount'=>$request->betting_amount]);
            }
            $this->response_success('Your request is processed successfully.', '' , '');
        }
        else {
            $this->response_success('Someone is using the room number.', '', '');
        }
    }

    public function get_invite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'friend_id' => 'required'
        ]);

        if ($validator->fails()) {
            $this->response_error($validator->errors()->first(), 406);                              // 406: Not Acceptable
        }

        $user = DB::table("invites")->join('users', 'invites.user_id', '=', 'users.id')
            ->select('invites.id', 'users.name as room_creator', 'invites.room_code', 'invites.players', 'invites.betting_amount')
            ->where('users.remove', 0)
            ->where('users.isverified', 1)
            ->where(function ($query) {
                $query->whereNull('users.is_blocked_at')
                    ->orWhere('users.is_blocked_at', '=', '');
            })
            ->where('invites.friend_id', $request->friend_id)
            ->where('invites.accept_flag', '')->first();
        if (!empty($user))
            DB::table('invites')->where('id', $user->id)->update(['accept_flag'=>'showed']);
        $this->response_success('no invite data', $user, 'friend');
    }

    public function accept_invite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_code' => 'required',
            'friend_id' => 'required'
        ]);

        if ($validator->fails()) {
            $this->response_error($validator->errors()->first(), 406);                              // 406: Not Acceptable
        }

        DB::table('invites')->where(['room_code'=>$request->room_code, 'friend_id'=> $request->friend_id])->update(['accept_flag'=>'accepted']);
        $this->response_success('Accepted successfully.', '', '');
    }

    public function remove_invite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_code' => 'required',
            'friend_id' => 'required'
        ]);

        if ($validator->fails()) {
            $this->response_error($validator->errors()->first(), 406);                              // 406: Not Acceptable
        }

        DB::table('invites')->where(['room_code'=>$request->room_code, 'friend_id'=> $request->friend_id])->delete();
        $this->response_success('Removed invitation successfully.', '', '');
    }

    public function remove_invite_room(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_code' => 'required'
        ]);

        if ($validator->fails()) {
            $this->response_error($validator->errors()->first(), 406);                              // 406: Not Acceptable
        }

        DB::table('invites')->where(['room_code'=>$request->room_code])->delete();
        $this->response_success('Removed inviationt room successfully.', '', '');
    }

    public function get_user_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);

        if ($validator->fails()) {
            $this->response_error($validator->errors()->first(), 406);                              // 406: Not Acceptable
        }
        $user = DB::select("SELECT * FROM users WHERE isverified=1 AND remove=0 AND (is_blocked_at IS NULL OR is_blocked_at='') AND id != :user_id ORDER BY name", ['user_id'=>$request->user_id]);
        $this->response_success('no invite data', $user, 'user');
    }

    public function check_block_status(Request $request){
        $user = auth()->user();

        if($user->block == '1'){
            $response = ["success" => true,'is_block' => 1];
        }else{
            $response = ["success" => true,'is_block' => 0];
        }
        return response()->json($response);
    }

    public function update_coins(Request $request){
        //Made Edits
        $user_id = Auth::id();
        $validator = Validator::make($request->all(),[
            "cash"	=>	"required"
        ]);

        if($validator->fails()){
            return response()->json(["message" => $validator->errors()->first()],406);
        }
        $flag = 0;
        $gameId 	= $request->input('game_id');
        $gameStatus = $request->input('game_status');
        $cash 		= $request->input('cash');

        if ($gameId != '' || $gameId != null) {
            $gameData = PlayGame::find($gameId);
            $stageUsers = [];

            if ($gameData != null) {
                if ($gameData->user_first) $stageUsers[] = $gameData->user_first->id;
                if ($gameData->user_second) $stageUsers[] = $gameData->user_second->id;
                if ($gameData->user_third) $stageUsers[] = $gameData->user_third->id;
                if ($gameData->user_fourth) $stageUsers[] = $gameData->user_fourth->id;

                if (in_array($user_id, $stageUsers)) {
                    switch ($gameStatus) {
                        case 'deal':
                            if (abs($cash) == $gameData->bet_amount) {
                                $flag = 1;
                            }
                            break;
                        case 'draw':
                            if ($cash == $gameData->bet_amount) {
                                $flag = 1;
                            }
                            break;
                        case 'win':
                            if ($cash < $gameData->bet_amount * count($stageUsers)) {
                                $flag = 1;
                            }
                            break;

                        default:
                            $flag = 0;
                            break;
                    }
                }
            }
        }


        if ($flag == 0) {
            //that user insert log table
            return;
        }

        $user = User::find($user_id);
        if($request->cash < 0)
        {
            $newreq = 0 - $request->cash;
            if($user->coins >= $newreq)
            {
                $user->coins += $request->cash;
            }
            else
            {
                $remaining_debit = $request->cash + $user->coins;
                $user->coins = 0;
                $user->cash += $remaining_debit;
            }
        }
        else
        {
            $user->cash += $request->cash;
        }

        if($user->save()){
            return response()->json(["message" => "Coins updated successfully"]);
        }else {
            return response()->json(["message" => "Unable to proceed your request, please try later"],400);
        }
    }

    public function get_coins(){
        $coins = User::find(Auth::id())->coins;
        $cash = User::find(Auth::id())->cash;

        $login_data = DB::table("user_logins")->where('user_id', Auth::id())->first();
        if (empty($login_data))
            DB::insert("INSERT user_logins SET user_id='" . $user->id . "', login_at=NOW(), is_online=1");
        else
            DB::update("UPDATE user_logins SET login_at=NOW(), is_online=1 WHERE user_id='" . $user->id . "'");

        DB::update("UPDATE user_logins SET is_online=0 WHERE is_online=1 AND timestampdiff(SECOND, login_at, NOW()) > 15");
        return response()->json(["message" => "Coins get successfully","coin" => $coins, "cash" => $cash]);
    }

    public function get_login_user()
    {
        $login_users = DB::table("user_logins")->select('user_id')->where('is_online', 1)->get();
        $this->response_success('no login users', $login_users, 'user');
    }

    public function logout_true()
    {
        DB::table("user_logins")->where('user_id', Auth::id())->update(['is_online'=> 0]);
        DB::update("UPDATE user_logins SET is_online=0 WHERE is_online=1 AND timestampdiff(SECOND, login_at, NOW()) > 15");

        $this->response_success('Your request is processed successfully.', '' , '');
    }
}
