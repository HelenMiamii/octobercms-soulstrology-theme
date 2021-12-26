# octobercms-soulstrology-theme
This is an OctoberCMS theme for an Astrology Web App called Soulstrology.

MAIN FEATURES
Content Based Membership Platform
E-Commerce
Astrology Chart Calcultors
Tapfilliate 


## CONTENT BASED MEMBERSHIP PLATFORM
Using StripeAPI and RainLab's Account Plugin, we can create a pretty secure Content Based Membership Platform and alter the site experiences between Members/ Non-Members.

STRIPE SET-UP

REGISTRATION

LOGIN

MEMBER REALM (where members access their content)




## E-COMMERCE
I used SnipCart to manage our physical and digital products. Activate this feature by creating an account with SnipCart and generate your API Keys with them. Add your API Keys in the designated SnipCart sections found at the botttom of the pages in `themes/soulstrology/layout/member-realm-template` and `themes/soulstrology/layout/main-website-template`

```
<!-- SnipCart -->
<link rel="stylesheet" href="https://cdn.snipcart.com/themes/v3.0.21/default/snipcart.css" />
<script async src="https://cdn.snipcart.com/themes/v3.0.21/default/snipcart.js"></script>
<div id="snipcart" data-api-key="[ YOUR SNIPCART API KEY ]" hidden></div>

```



## ASTROLOGY CHART CALCULATORS



