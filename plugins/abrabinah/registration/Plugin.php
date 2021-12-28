<?php namespace Abrabinah\Registration;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {

	return [
		
		'\Abrabinah\Registration\Components\Register' => 'MembershipRegistration'		
	];

    }

    public function registerSettings()
    {
    }


}
