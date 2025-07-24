<?php

namespace App\Http\Controllers;

use App\Mail\DynamicEmail;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use PaypalPayoutsSDK\Core\PayPalHttpClient;
use PaypalPayoutsSDK\Core\SandboxEnvironment;
use PaypalPayoutsSDK\Core\ProductionEnvironment;
use PaypalPayoutsSDK\Payouts\PayoutsPostRequest;
use Razorpay\Api\Api;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {

        return view("settings.users.index");
        
    }


    public function edit($id)
    {
        return view('settings.users.edit')->with('id', $id);
    }

    public function adminUsers()
    {
        $users = User::join('role', 'role.id', '=', 'users.role_id')
            ->select('users.*', 'role.role_name as roleName')->where('users.id', '!=', 1)->get();
        return view('admin_users.index', compact(['users']));
    }

    public function createAdminUsers()
    {
        $roles = Role::all();
        return view('admin_users.create', compact(['roles']));
    }
    public function storeAdminUsers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            $errorMessage = $validator->errors()->first(); // Get the first error message
            return redirect()->back()->with(['message' => $errorMessage])->withInput();
        }

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role_id' => $request->input('role'),
        ]);

        return redirect('admin-users');
    }
    public function editAdminUsers($id)
    {
        $user = User::join('role', 'role.id', '=', 'users.role_id')->select('users.*', 'role.role_name as roleName')->find($id);
        $roles = Role::all();
        return view('admin_users.edit', compact(['user', 'roles']));
    }
    public function updateAdminUsers(Request $request, $id)
    {
        $name = $request->input('name');
        $password = $request->input('password');
        $old_password = $request->input('old_password');
        $email = $request->input('email');
        $role = ($id == 1) ? 1 : $request->input('role');
        if ($password == '') {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'email' => 'required|email'
            ]);
        } else {
            $user = User::find($id);
            if (password_verify($old_password, $user->password)) {
                $validator = Validator::make($request->all(), [
                    'name' => 'required|max:255',
                    'password' => 'required|min:8',
                    'confirm_password' => 'required|same:password',
                    'email' => 'required|email'
                ]);
            } else {
                return Redirect()->back()->with(['message' => "Please enter correct old password"]);
            }
        }

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return Redirect()->back()->with(['message' => $error]);
        }

        $user = User::find($id);

        if ($user) {

            $user->name = $name;
            $user->email = $email;
            if ($password != '') {
                $user->password = Hash::make($password);
            }
            $user->role_id = $role;
            $user->save();
        }

        return redirect('admin-users');
    }
    public function deleteAdminUsers($id)
    {
        $id = json_decode($id);

        if (is_array($id)) {

            for ($i = 0; $i < count($id); $i++) {
                $users = User::find($id[$i]);
                $users->delete();
            }
        } else {
            $user = User::find($id);
            $user->delete();
        }

        return redirect()->back();
    }


    public function profile()
    {
        $user = Auth::user();
        return view('settings.users.profile', compact(['user']));
    }

    public function update(Request $request, $id)
    {
        $name = $request->input('name');
        $password = $request->input('password');
        $old_password = $request->input('old_password');
        $email = $request->input('email');
        if ($password == '') {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'email' => 'required|email'
            ]);
        } else {
            $user = Auth::user();
            if (password_verify($old_password, $user->password)) {
                $validator = Validator::make($request->all(), [
                    'name' => 'required|max:255',
                    'password' => 'required|min:8',
                    'confirm_password' => 'required|same:password',
                    'email' => 'required|email'
                ]);
            } else {
                return Redirect()->back()->with(['message' => "Please enter correct old password"]);
            }
        }

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return Redirect()->back()->with(['message' => $error]);
        }

        $user = User::find($id);
        if ($user) {
            $user->name = $name;
            $user->email = $email;
            if ($password != '') {
                $user->password = Hash::make($password);
            }
            $user->save();
        }

        return redirect()->back();
    }

    public function create()
    {
        return view('settings.users.create');
    }

    public function view($id)
    {
        return view('settings.users.view')->with('id', $id);
    }

    public function payToUser(Request $request)
    {
        $response = array();
        $encrypt_data =  $request->data;

        if(!empty($encrypt_data)){

            $data = json_decode(base64_decode($encrypt_data),true);
            
            if($data['method'] == "paypal"){
            
                $response = $this->payWithPaypal($data);  
            
            }else if($data['method'] == "stripe"){
            
                $response = $this->payWithStripe($data);  
            
            }else if($data['method'] == "razorpay"){
            
                $response = $this->payWithRazorpay($data);  

            }else if($data['method'] == "flutterwave"){
            
                $response = $this->payWithFlutterwave($data);  
            }
            
        }else{
            $response['success'] = false;
            $response['message'] = 'Payout method setup is not done';
        }
        
        return response()->json($response);
    }

    public function payWithPaypal($data){

        $payout_response = array();

        if(!empty($data['user']['withdrawMethod']['paypal']['email'])){

            $paypal_email = $data['user']['withdrawMethod']['paypal']['email'];

            $isLive = $data['settings']['paypal']['isLive'];
            $clientId = $data['settings']['paypal']['paypalAppId'];
            $clientSecret = $data['settings']['paypal']['paypalSecret'];
            if($isLive){
                $environment = new ProductionEnvironment($clientId, $clientSecret);
            }else{
                $environment = new SandboxEnvironment($clientId, $clientSecret);
            }
            
            $client = new PayPalHttpClient($environment);
            $request = new PayoutsPostRequest();
            $body = [
                "sender_batch_header" => [
                    "sender_batch_id" => "Payouts_".$data["payoutId"],
                    "email_subject" => "You have a payout!",
                    "email_message" => "You have received a payout! Thanks for using our service!",
                ],
                "items" => [
                    [
                        "recipient_type" => "EMAIL",
                        "receiver" => $paypal_email,
                        "note" => "Your $".$data["amount"]." payout",
                        "sender_item_id" => $data["payoutId"],
                        "amount" => [
                            "currency" => "USD",
                            "value" => $data["amount"],
                        ],
                    ],
                ]
            ];
            
            $request->body = $body;

            try {

                $response = $client->execute($request);

                if(isset($response->statusCode) && $response->statusCode == "201"){
                    $payout_response['success'] = true;
                    $payout_response['message'] = 'We successfully processed your payout request';
                    $payout_response['result'] = $response->result;
                    $payout_response['status'] = "In Process";
                }else{
                    $payout_response['success'] = false;
                    $payout_response['message'] = 'Something went wrong to process your payout request';
                }

            }catch(\Throwable $e){
                $payout_response['success'] = false;
                $payout_response['message'] = $e->getMessage();
            }
            
        }else{
            $payout_response['success'] = false;
            $payout_response['message'] = 'User paypal email address is required';
        }
        
        return $payout_response;
    }

    public function payWithStripe($data){

        $payout_response = array();

        if(!empty($data['user']['withdrawMethod']['stripe']['accountId'])){
    
            $accountId = $data['user']['withdrawMethod']['stripe']['accountId'];
            $amount = bcmul($data["amount"], 100);
            
            $stripeSecret = $data['settings']['stripe']['stripeSecret'];
            $stripe = new \Stripe\StripeClient($stripeSecret);

            try {
                
                $response = $stripe->transfers->create([
                    'amount' => $amount,
                    'currency' => 'usd',
                    'destination' => $accountId,
                    'transfer_group' => $data["payoutId"],
                ]);

                $response = json_decode($response,true);

                if(isset($response['id']) && isset($response['balance_transaction'])){
                    $payout_response['success'] = true;
                    $payout_response['message'] = 'We successfully processed your payout request';
                    $payout_response['result'] = $response;
                    $payout_response['status'] = "Success";
                }else{
                    $payout_response['success'] = false;
                    $payout_response['message'] = "No such destination: '".$accountId."'";
                }

            }catch(\Throwable $e){
                $payout_response['success'] = false;
                $payout_response['message'] = $e->getMessage();
            }

        }else{
            $payout_response['success'] = false;
            $payout_response['message'] = 'Stripe accountId is required';
        }
        
        return $payout_response;
    }

    public function payWithRazorpay($data){
        
        $payout_response = array();

        if(!empty($data['user']['withdrawMethod']['razorpay']['accountId'])){
    
            $accountId = $data['user']['withdrawMethod']['razorpay']['accountId'];
            $amount = bcmul($data["amount"], 100);
            
            $api_key = $data['settings']['razorpay']['razorpayKey'];
            $api_secret = $data['settings']['razorpay']['razorpaySecret'];
            $api = new Api($api_key, $api_secret);
            
            try {
               
                $response = $api->transfer->create(array('account' => $accountId, 'amount' => $amount, 'currency' => 'INR'));
                $response = json_decode($response,true);
                
                if(isset($response['status']) && isset($response['id'])){
                    $payout_response['success'] = true;
                    $payout_response['message'] = 'We successfully processed your payout request';
                    $payout_response['result'] = $response;
                    $payout_response['status'] = "In Process";
                }else{
                    $payout_response['success'] = false;
                    $payout_response['message'] = $response['error']['description'];    
                }

            }catch(\Throwable $e){
                $payout_response['success'] = false;
                $payout_response['message'] = $e->getMessage();
            }

        }else{
            $payout_response['success'] = false;
            $payout_response['message'] = 'Razorpay accountId is required';
        }
        
        return $payout_response;
    }

    public function payWithFlutterwave($data){
        
        $payout_response = array();

        if(!empty($data['user']['withdrawMethod']['flutterwave'])){
    
            $bankCode = $data['user']['withdrawMethod']['flutterwave']['bankCode'];
            $accountNumber = $data['user']['withdrawMethod']['flutterwave']['accountNumber'];
            $amount = bcmul($data["amount"],10);
            $secretKey = $data['settings']['flutterwave']['secretKey'];
            
            $fields = [
                "account_bank" => $bankCode,
                "account_number" => $accountNumber,
                "amount" => $amount,
                "narration" => "Payment Request: ".$data["payoutId"]."",
                "currency" => "NGN",
                "reference" => $data["payoutId"],
            ];

            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,"https://api.flutterwave.com/v3/transfers");
            curl_setopt($ch,CURLOPT_POST, true);
            curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($fields));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization: Bearer ".$secretKey,
                "Cache-Control: no-cache",
                "Content-Type: application/json",
            ));
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
            $result = curl_exec($ch);
            $response = json_decode($result,true);

            if($response['status'] == "success"){
                $payout_response['success'] = true;
                $payout_response['message'] = 'We successfully processed your payout request';
                $payout_response['result'] = $response;
                $payout_response['status'] = "In Process";
            }else{
                $payout_response['success'] = false;
                $payout_response['message'] = $response['message'];    
            }
        
        }else{
            $payout_response['success'] = false;
            $payout_response['message'] = 'Flutterwave account detail is required';
        }
        
        return $payout_response;
    }

    public function checkPayoutStatus(Request $request){
        
        $response = array();
        $encrypt_data =  $request->data;

        if(!empty($encrypt_data)){

            $data = json_decode(base64_decode($encrypt_data),true);
            
            if($data['method'] == "paypal"){
            
                $response = $this->checkStatusPaypal($data);  
            
            }else if($data['method'] == "razorpay"){
            
                $response = $this->checkStatusRazorpay($data);  

            }else if($data['method'] == "flutterwave"){
            
                $response = $this->checkStatusFlutterwave($data);  
            }
            
        }else{
            $response['success'] = false;
            $response['message'] = 'Something went wrong to check status';
        }
        
        return response()->json($response);        
    }

    public function checkStatusPaypal($data){

        $payout_response = array();

        if(isset($data['payoutDetail']['payoutResponse']) && !empty($data['payoutDetail']['payoutResponse'])){

            $payout_batch_id = $data['payoutDetail']['payoutResponse']['batch_header']['payout_batch_id'];

            if(!empty($payout_batch_id)){

                $isLive = $data['settings']['paypal']['isLive'];
                $clientId = $data['settings']['paypal']['paypalAppId'];
                $clientSecret = $data['settings']['paypal']['paypalSecret'];
                if($isLive){
                    $api_url = "https://api-m.paypal.com";
                }else{
                    $api_url = "https://api-m.sandbox.paypal.com";
                }

                //Get access token
                $ch = curl_init();
                curl_setopt($ch,CURLOPT_URL,$api_url."/v1/oauth2/token");
                curl_setopt($ch,CURLOPT_POST, true);
                curl_setopt($ch,CURLOPT_POSTFIELDS,"grant_type=client_credentials");
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "Authorization: Basic ".base64_encode($clientId.":".$clientSecret),
                    "Content-Type: application/x-www-form-urlencoded"
                ));
                curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
                $result = curl_exec($ch);
                $response = json_decode($result,true);

                //Get status
                if($response['access_token']){

                    $ch = curl_init();
                    curl_setopt($ch,CURLOPT_URL,$api_url."/v1/payments/payouts/".$payout_batch_id);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        "Authorization: Bearer ".$response['access_token'],
                        "Cache-Control: no-cache",
                    ));
                    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
                    $result2 = curl_exec($ch);
                    $response2 = json_decode($result2,true);
                    
                    if(isset($response2['items']) && isset($response2['items'][0]['transaction_status'])){
                        if($response2['items'][0]['transaction_status'] == "SUCCESS"){
                            $payout_response['success'] = true;
                            $payout_response['message'] = "We successfully processed your transaction";
                            $payout_response['result'] = $response2;
                            $payout_response['status'] = "Success";
                        }else{
                            $payout_response['success'] = false;
                            $payout_response['message'] = $response2['items'][0]['errors']['name']." : ".$response2['items'][0]['errors']['message'];
                            $payout_response['result'] = $response2;
                            $payout_response['status'] = "Failed";
                        }
                    }else{
                        $payout_response['success'] = false;
                        $payout_response['message'] = 'Invalid payout transaction';
                    }
                }else{
                    $payout_response['success'] = false;
                    $payout_response['message'] = 'Invalid client credentials';
                }

            }else{
                $payout_response['success'] = false;
                $payout_response['message'] = 'Invalid payout_batch_id';    
            }

        }else{
            $payout_response['success'] = false;
            $payout_response['message'] = 'Invalid payout response';
        }
        
        return $payout_response;
    }

    public function checkStatusRazorpay($data){

        $payout_response = array();

        if(isset($data['payoutDetail']['payoutResponse']) && !empty($data['payoutDetail']['payoutResponse'])){
    
            $transfer_id = $data['payoutDetail']['payoutResponse']['id'];

            if(!empty($transfer_id)){

                $api_key = $data['settings']['razorpay']['razorpayKey'];
                $api_secret = $data['settings']['razorpay']['razorpaySecret'];
                $api = new Api($api_key, $api_secret);
                
                try {
                
                    $response = $api->transfer->fetch($transfer_id);
                    $response = json_decode($response,true);

                    if(isset($response['settlement_status']) && $response['settlement_status'] == "settled"){
                        $payout_response['success'] = true;
                        $payout_response['message'] = 'We successfully processed your transaction';
                        $payout_response['result'] = $response;
                        $payout_response['status'] = "Success";
                    }else{
                        $payout_response['success'] = false;
                        $payout_response['message'] = $response['error']['description'];    
                        $payout_response['status'] = "Failed";
                    }

                }catch(\Throwable $e){
                    $payout_response['success'] = false;
                    $payout_response['message'] = $e->getMessage();
                }

            }else{
                $payout_response['success'] = false;
                $payout_response['message'] = 'Invalid transfer id';    
            }
            
        }else{
            $payout_response['success'] = false;
            $payout_response['message'] = 'Invalid payout response';
        }

        return $payout_response;
    }   

    public function checkStatusFlutterwave($data){

        $payout_response = array();
        
        if(isset($data['payoutDetail']['payoutResponse']) && !empty($data['payoutDetail']['payoutResponse'])){
    
            $transfer_id = $data['payoutDetail']['payoutResponse']['data']['id'];
            
            if(!empty($transfer_id)){

                $secretKey = $data['settings']['flutterwave']['secretKey'];

                $ch = curl_init();
                curl_setopt($ch,CURLOPT_URL,"https://api.flutterwave.com/v3/transfers/".$transfer_id);
                curl_setopt($ch,CURLOPT_CUSTOMREQUEST, "GET"); 
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "Authorization: Bearer ".$secretKey,
                    "Cache-Control: no-cache",
                    "Content-Type: application/json",
                ));
                curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
                $result = curl_exec($ch);
                $response = json_decode($result,true);
                
                if($response['status'] == "success"){
                    $payout_response['success'] = true;
                    $payout_response['message'] = 'We successfully processed your transaction';
                    $payout_response['result'] = $response;
                    $payout_response['status'] = "Success";
                }else{
                    $payout_response['success'] = false;
                    $payout_response['message'] = $response['message'];    
                }

            }else{
                $payout_response['success'] = false;
                $payout_response['message'] = 'Invalid transfer id';    
            }
            
        }else{
            $payout_response['success'] = false;
            $payout_response['message'] = 'Invalid payout response';
        }
        
        return $payout_response;
    }
}
