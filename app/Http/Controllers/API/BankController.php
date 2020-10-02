<?php

namespace App\Http\Controllers\API;

use App\User;
use Auth;
use Validator;
use App\BankDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use App\Transaction;

use App\PaymentGateWay\PayStack\PayStackController as PayStack;

class BankController extends BaseController
{
	function __construct(){
		PayStack::setKey(env("TEST_PAYSTACK_SECRET_KEY"));
	}

    //Update Bank Details
    public function updateBankDetails(Request $request) {

    	$validator = Validator::make($request->all(), [ 
            'account_name' => 'required', 
            'bank_name' => 'required',
            'account_number' => 'required',
        	]);
		if ($validator->fails()) { 
			$this->responseError($validator->errors()->first(), 406);
        }
    	$user = Auth::user();
		if(!empty($user)) {
				if($user->block == '1') {
					$this->responseError("Your account has been blocked by admin.",400);
				}
				$bank_details = BankDetail::where('user_id',$user->id)->first();
				$account_name = $request->input('account_name');
				$account_number = $request->input('account_number');
				$bank_name = $request->input('bank_name');
				if (!empty($bank_details)) {
					BankDetail::where('id',$bank_details->id)
          ->update([
                'account_name' => $account_name,
                'account_number' => $account_number,
                'bank_name' => $bank_name
              ]);
				}
				else {
					$bank_data = array('bank_name' => $bank_name,
						'account_name' => $account_name,
						'account_number' => $account_number,
						'user_id' => $user->id
					);
					BankDetail::create($bank_data);
				}

				$bank = ['account_name'=>$account_name, 'account_number'=>$account_number, 'bank_name'=>$bank_name];
				$this->responseSuccess('', $bank, 'bank_details');
		}	
		else {
			$this->responseError('Un Authenticated.', 406);
		}
    }

    public function chargeCustomer(Request $request){
    	$user = Auth::user();

    	$validator = Validator::make($request->all(),[
    		"amount" => "required|min:1",
    		"pin"	 => "required|min:3",
    		"number" => "required",
    		"expiry_month" 	=> "required",
    		"expiry_year" 	=> "required",
    		"cvv" 			=> "required",
    		"coins"			=>	"required"
    	]);

      if ($validator->fails()) { 
      $this->responseError($validator->errors()->first(), 406);
      }
        /*
            amount
            coins
            user_id
            package
            status
      */

      $transaction = new Transaction();
      $transaction->user_id = $user->id;
      $transaction->amount = $request->amount;
      $transaction->coins = $request->coins;

      if($request->coins <= 200){

        $package = "Package 1";
      }elseif ($request->coins >= 500 && $request->coins < 1000) {
        $package = "Package 2";
      }elseif ($request->coins >= 1000 && $request->coins < 5000) {
        $package = "Package 3";
      }elseif ($request->coins >= 5000 && $request->coins < 10000) {
        $package = "Package 4";
      }elseif ($request->coins >= 10000 && $request->coins < 50000) {
        $package = "Package 5";
      }elseif ($request->coins >= 50000) {
        $package = "Package 6";
      }else{
        $package = "";
      }
      $transaction->package = $package;
      $transaction->save();

    	
        $all_request = $request->all();
        $request_data["email"] 	= $user->email;
        $request_data["amount"] = $request->amount * 100;
        $request_data["pin"] 	= $request->pin;

        $request_data["card"]["number"] = $request->number;
        $request_data["card"]["cvv"] = $request->cvv;
        $request_data["card"]["expiry_month"] = $request->expiry_month;
        $request_data["card"]["expiry_year"] = $request->expiry_year;

    	$is_payment = PayStack::charge($request_data);
    	$is_payment = json_decode($is_payment);
    	
   		if($is_payment->status == "true"){

   			$user = User::find(Auth::id());
   			$user_coins = $user->coins;
   			$new_coins = $request->coins;
   			$user->coins = $new_coins + $user_coins;
   			$user->update();

        $transaction_find = Transaction::whereId($transaction->id)->first();
        $transaction_find->status = "Approved";
        $transaction_find->update();
   			return response()->json(['success'=>true,"message" => "Package purchased successfully"]);
   		}else { 
   			return response()->json(['failed'=>true,"message" => "Unable to proceed request, please try later","payment_message" => $is_payment->data->message],400);
   		}
    }
}
