# octobercms-soulstrology-theme
This is an [OctoberCMS](https://docs.octobercms.com/2.x/setup/installation.html#installing-composer) theme for an Astrology Web App called Soulstrology.

MAIN FEATURES

1. Content Based Membership Platform
2. E-Commerce
3. Astrology Chart Calcultors
4. Tapfilliate 


## CONTENT BASED MEMBERSHIP PLATFORM
The Membership Platform was built using [Stripe API](https://stripe.com/docs/api) and [RainLab User-Plugin](https://github.com/rainlab/user-plugin). Stripe is used  to manage the subscriptions, you can find more details on how it works [here](https://stripe.com/docs/billing/subscriptions/overview). RainLab User-Plugin is used for front end user management.

### **INITIAL STRIPE SET-UP**

If you haven't already, you'll need to create an account with [Stripe](https://stripe.com/) and create *live* Secret and Public API keys. 

Locate the `stripe.js` and `profilestripe.js` files located in `themes/soulstology/assets/vendor`. You just need to add your Public Key in this step. Update the files with your own Public Keys.

```
// Create a Stripe client.
var stripe = Stripe('[ YOUR STRIPE PUBLIC KEY ]');
```

These 3 script files work with `<script src="https://js.stripe.com/v3/"></script>` found in the site templates. 


### MEMBERSHIP REGISTRATION
I created a plugin found in `plugins/abrabinah/registration` to process this Membership Registration. It uses [RainLab User-Plugin](https://github.com/rainlab/user-plugin), [Stripe API](https://stripe.com/docs/api), and [MailChimp](https://mailchimp.com/). Here's what it does:

1. It first checks for all the required arguments: First Name, Last Name, Email, Password, StripeToken (CC), and Membership Plan. It will throw an error if there's any duplicate data from the database, missing data from the form, or missing StripeToken.

2. It creates a new *Active Subscription* and *Client Profile* in your Stripe Account by passing the following arguments: Name, Email, Membership Plan, and StripeToken (Credit Card). 

3. It creates an Account in your own database then adds the following data: First Name, Last Name, Email, Membership Plan, and (newly created) StripeID.

3. Adds the new Member's email to your MailChimp account.

4. Once it completes, it flashes a success note and sends the new member a welcome email. 


The Membership Registration Form is in a modal found in `themes/soulstrology/partials/membership-registration.htm`. It's activated by *SIGN UP NOW!* in the Navbar.

Locate the `Plugin.php` to update the Stripe and MailChimp API keys to your own configuration.



### MEMBER REALM
The `member-realm.htm` is the main page where Member's can access their content. This page and `member-post.htm` is restricted to *active* Stripe Subscriptions. It runs the code below on-load to validate the subscription using the StripeID connected to the User's account. If the Subscription doesn't check out, the User will be redirected to the **MY PROFILE** page (see the next section). To activate this feature update the page with your Stripe Secret Key.   

```
function onStart()
{ 
    $user = \Auth::getUser();
    
    $stripe_id = $user-> stripe_id;
    
    \Stripe\Stripe::setApiVersion("2019-05-16");

    \Stripe\Stripe::setApiKey("[ YOUR STRIPE SECRET KEY ]");
    
    $data = \Stripe\Customer::retrieve( $stripe_id );
    
    $id = $data->subscriptions->data;
     
    if ( $id == FALSE ) {
        // This user is not a paying customer...
        return redirect('my-profile');   
    }
}
```


### MY PROFILE
Using the code below in the `my-profile.htm`, here's a few features for the User when they are on this page.

```
function onStart()
{
    $user = \Auth::getUser();
    
    $stripe_id = $user-> stripe_id;
    
    \Stripe\Stripe::setApiVersion("2019-05-16");

    \Stripe\Stripe::setApiKey("[ YOUR STRIPE SECRET KEY ]");

    $this['data'] = \Stripe\Customer::retrieve( $stripe_id );
}
```

1. Shows what Membership Plan their on.

2. Shows the last 4 digits of the Credit Card used for their Membership.

3. They can update their Credit Card.
```
function onStart()
{
        \Stripe\Stripe::setApiVersion("2019-05-16");
        
        $user = \Auth::getUser();
        
        $token = $_POST['stripeToken'];
            if ( ! $token) {
            throw new \RuntimeException('Stripe token is missing!');
        }
        
        $user->updateCard($token);
        
        return redirect('my-profile');
}
```

4. They can cancel their Membership. 
```
function onStart()
{       
        \Stripe\Stripe::setApiVersion("2019-05-16");
        
        \Stripe\Stripe::setApiKey("[ YOUR STRIPE SECRET KEY ]");
        
        $user = \Auth::getUser();
        
        $stripe_id = $user-> stripe_id;
        
        $subscription = $_POST['subscription_id'];
        
        $sub = \Stripe\Subscription::retrieve( $subscription );            
        $sub->update(
            $subscription,
            [
            
                'cancel_at_period_end' => 'true',
                    
            ]
            );
        
        \Stripe\Customer::update(
          $stripe_id,
            [
                'description' => 'cancelled',
            ]
        );
        
        $data = [
          'email'     => $user-> email,
          'status'    => 'unsubscribed'
        ];

      $apiKey = '[ YOUR MAILCHIMP API KEY]';
      $listId = '[ YOUR MEMBERSHIP LIST ID ]';
      $memberId = md5(strtolower($data['email']));
      $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
      $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;
      $json = json_encode([
      'email_address' => $data['email'],
      'status'        => $data['status'], // "subscribed","unsubscribed","cleaned","pending"

      ]);
      
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
      curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_TIMEOUT, 10);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                                                                 
      $result = curl_exec($ch);
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);
        
      return redirect('my-profile');
}
```

5. They can resume their Membership
```
function onStart()
{
       
        \Stripe\Stripe::setApiVersion("2019-05-16");
    
        \Stripe\Stripe::setApiKey("[ YOUR STRIPE SECRET KEY ]");
    
        $user = \Auth::getUser();
        
        $stripe_id = $user-> stripe_id;
        
        $new_plan = $_POST['new_plan'];
         
        $user->newSubscription('main', $new_plan )->create();
        
        \Stripe\Customer::update(
        $stripe_id,
            [
                'description' => $new_plan,
            ]
        );
        
        $data = [
          'email'     => $user-> email,
          'status'    => 'subscribed'
        ];

      $apiKey = '[ YOUR MAILCHIMP API KEY ]';
      $listId = '[ YOUR MEMBERSHIP LIST ID ]';
      $memberId = md5(strtolower($data['email']));
      $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
      $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;
      $json = json_encode([
      'email_address' => $data['email'],
      'status'        => $data['status'], // "subscribed","unsubscribed","cleaned","pending"

      ]);
      
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
      curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_TIMEOUT, 10);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                                                                 
      $result = curl_exec($ch);
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);
        
      return redirect('my-profile');
 
}
```






## E-COMMERCE
SnipCart was used to manage the physical and digital products. Activate this feature by creating an account with SnipCart and generate your API Keys with them. Add your API Keys in the designated SnipCart sections found at the botttom of the pages in `themes/soulstrology/layout/member-realm-template` and `themes/soulstrology/layout/main-website-template`

```
<!-- SnipCart -->
<link rel="stylesheet" href="https://cdn.snipcart.com/themes/v3.0.21/default/snipcart.css" />
<script async src="https://cdn.snipcart.com/themes/v3.0.21/default/snipcart.js"></script>
<div id="snipcart" data-api-key="[ YOUR SNIPCART API KEY ]" hidden></div>

```

E-Commerce Main Shop Page  `shop.htm`

Physical Product Template 

Virtual Product Template 



## ASTROLOGY CHART CALCULATORS



