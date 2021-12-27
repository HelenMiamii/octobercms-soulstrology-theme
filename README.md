# octobercms-soulstrology-theme
This is an OctoberCMS theme for an Astrology Web App called Soulstrology.

MAIN FEATURES

1. Content Based Membership Platform
2. E-Commerce
3. Astrology Chart Calcultors
4. Tapfilliate 


## CONTENT BASED MEMBERSHIP PLATFORM
Using StripeAPI and RainLab's User Plugin, we can create a pretty secure Content Based Membership Platform and alter the site experiences between Members/ Non-Members.

INITIAL STRIPE SET-UP

If you haven't already, you'll need to create an account with [Stripe](https://stripe.com/). After that, you'll need to create Live Secret and Public API keys. Optional: You can also create Test versions of the same keys. I left the files in the theme that will let you run the features that involve Stripe in test mode.

Locate the `stripe.js`, `stripe-form.js`, and `profilestripe.js` files located in `themes/soulstology/assets/vendor`. You just need to add your Public Key in this step. Update the files with your own Public Keys.

```
// Create a Stripe client.
var stripe = Stripe('[ YOUR STRIPE PUBLIC KEY ]');
```

These 3 script files work with `<script src="https://js.stripe.com/v3/"></script>` found in the site templates. 


MEMBER REGISTRATION
I created a plugin found in `plugins/abrabinah/registration` to process this Membership Registration. It uses Rainlab's User Plugin, StripeAPI, and MailChimp. Here's what it does:

1. It first checks for all the required arguments: First Name, Last Name, Email, Password, StripeToken (CC), and Membership Plan. It will throw an error if there's any duplicate data from the database, missing data from the from, or missing StripeToken.

2. It creates a new Active Subscription in your Stripe Account by passing the following arguments: Name, Email, Membership Plan, and StripeToken (Credit Card). 

3. It creates an Account in your own database then adds the following data: First Name, Last Name, Email, Membership Plan, and (newly created) StripeID.

3. Adds the new Member's email to your MailChimp account.

4. Once it completes, it flashes a success note and sends the new member a welcome email. 


LOGIN

MEMBER REALM (where members access their content)

MEMBER POST

PROFILE




## E-COMMERCE
I used SnipCart to manage our physical and digital products. Activate this feature by creating an account with SnipCart and generate your API Keys with them. Add your API Keys in the designated SnipCart sections found at the botttom of the pages in `themes/soulstrology/layout/member-realm-template` and `themes/soulstrology/layout/main-website-template`

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



