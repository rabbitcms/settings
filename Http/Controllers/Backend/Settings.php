<?php

namespace RabbitCMS\Settings\Http\Controllers\Backend;


use Illuminate\View\View;
use RabbitCMS\Backend\Annotation\Permissions;
use RabbitCMS\Settings\Manager as SettingsManager;

/**
 * Class Settings
 * @Permissions("system.settings.read")
 */
class Settings extends Controller
{
    /**
     * @return View
     */
    public function getIndex(SettingsManager $manager)
    {
        $settings = $manager->getGroups();

        //$settings = \(new Manager(Mod))->

        dd($settings);

        return $this->view('index');
    }
}
