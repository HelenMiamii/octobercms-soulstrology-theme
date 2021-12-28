<?php namespace OFFLINE\Cashier;

use App;
use Config;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\CashierServiceProvider;
use OFFLINE\Cashier\Components\InvoicesList;
use OFFLINE\Cashier\Components\NeedsSubscription;
use OFFLINE\Cashier\Components\StripeElementsForm;
use OFFLINE\Cashier\Models\Settings;
use System\Classes\PluginBase;
use Rainlab\User\Controllers\Users as UsersController;

class Plugin extends PluginBase
{
    public $require = ['RainLab.User'];

    public function boot()
    {
        App::register(CashierServiceProvider::class);
        Cashier::useCurrency(
            Settings::get('currency_currency', 'USD'),
            Settings::get('currency_symbol', '$')
        );

        UsersController::extendFormFields(function($form, $model, $context){

              $form->addTabFields([

                'offline_cashier_stripe_id' => [
                  'label' => 'Stripe ID',
                  'tab' => 'Billing',
                ],

              ]);

        });


    }


    public function register()
    {
        App::singleton('user.auth', function () {
            return \OFFLINE\Cashier\Classes\AuthManager::instance();
        });
    }

    public function registerComponents()
    {
        return [
            StripeElementsForm::class => 'stripeElementsForm',
            NeedsSubscription::class  => 'needsSubscription',
            InvoicesList::class       => 'invoicesList',
        ];
    }

    public function registerPermissions()
    {
        return [
            'offline.cashier.manage_settings' => [
                'tab'   => 'offline.cashier::lang.plugin.name',
                'label' => 'offline.cashier::lang.plugin.manage_settings_permission',
            ],
        ];
    }

    public function registerSettings()
    {
        return [
            'config' => [
                'label'       => 'offline.cashier::lang.plugin.name',
                'description' => 'offline.cashier::lang.plugin.manage_settings',
                'category'    => 'system::lang.system.categories.cms',
                'icon'        => 'icon-money',
                'class'       => 'Offline\Cashier\Models\Settings',
                'order'       => 500,
                'keywords'    => 'cashier',
                'permissions' => ['offline.cashier.manage_settings'],
            ],
        ];
    }
}
