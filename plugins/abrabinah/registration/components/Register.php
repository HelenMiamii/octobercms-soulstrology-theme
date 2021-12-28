<?php namespace Abrabinah\Registration\Components;

use Cms\Classes\ComponentBase;
use RainLab\User\Models\Settings;

class Register extends ComponentBase

{

    public function componentDetails()
    {
        return [
            'name' => 'Membership Registration',
            'description' => 'Processes to Users, Stripe, and MailChimp.'
        ];
    }
    
    function onSubmitMembershipRegistration()
    {
            $data = post();
    
            $rules = [
                'name' => 'required',
                'surname' => 'required',
                'email' => 'required',
                'password' => 'required',
                'plan' => 'required',
            ];
    
            $validation = Validator::make($data, $rules);
    
            if ($validation->fails()) {
            throw new ValidationException($validation);
            }
        
        
            \Stripe\Stripe::setApiVersion("2019-05-16");
        
            $token = $_POST['stripeToken'];
                if ( ! $token) {
                throw new \RuntimeException('Stripe token is missing!');
            }
            
            $plan = $_POST['plan'];
            
            $user = Auth::register([
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'password_confirmation' => $_POST['password'],
                
            ],
            true
            );
            
            Auth::login($user);
    
            $user = \Auth::getUser();
            $user->newSubscription('main', $plan)->create($token, [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'description' => $plan,
                    
            ]);
            
            \Stripe\Stripe::setApiKey("[ YOUR STRIPE SECRETY KEY ]");
            
            $this['stripe_id'] = $user-> stripe_id;
            $this['data'] = \Stripe\Customer::retrieve( $this['stripe_id'] );
            
            
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            $vars = [ 'name'=>$name, 'email'=>$email, 'password'=>$password ];
            
            Mail::send('rainlab.user::mail.new', $vars, function($message) {
    
            $message->to($_POST['email'], $_POST['name']);
            
            });
            
            $data = [
              'email'     => $_POST['email'],
              'status'    => 'subscribed',
              'firstname' => $_POST['name'],
              'lastname'  => ''
            ];
    
          $apiKey = '[ YOUR MAILCHIMP API KEY ]';
          $listId = '[ YOUR MAILCHIMP MEMBERSHIP LIST ID ]';
          $memberId = md5(strtolower($data['email']));
          $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
          $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;
          $json = json_encode([
          'email_address' => $data['email'],
          'status'        => $data['status'], // "subscribed","unsubscribed","cleaned","pending"
          'merge_fields'  => [
              'FNAME'     => $data['firstname'],
              'LNAME'     => $data['lastname']
          ]
      ]);
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
      curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_TIMEOUT, 10);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                                                                 
      $result = curl_exec($ch);
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);
     
      Flash::success('You are officially a Soulstrology Member!');
    }
    



}