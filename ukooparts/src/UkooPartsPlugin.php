<?php

namespace Ukoo\Ukooparts;

class UkooPartsPlugin
{
    const TRANSIENT_UKOOPARTS_PLUGIN_ACTIVATED = 'ukooparts_plugin_activated';

    public function __construct(string  $file){
        register_activation_hook($file, [$this, 'PluginActivation']);
        add_action('admin_notices', [$this, 'NoticeActivation']);
    }

    public function PluginActivation(): void{
        set_transient(self::TRANSIENT_UKOOPARTS_PLUGIN_ACTIVATED, true);
    }

    public function NoticeActivation(): void{
        if (get_transient(self::TRANSIENT_UKOOPARTS_PLUGIN_ACTIVATED)){
            self::render('notice', [
                'message' => "Ukooparts for Woocommerce is activated !"
            ]);
            delete_transient(self::TRANSIENT_UKOOPARTS_PLUGIN_ACTIVATED);
        }
    }

    public static function render(string $name, array $args = []): void{
        extract($args);
        $file = UKOOPARTS_PLUGIN_DIR. "views/$name.php";
        ob_start();
        include_once($file);

        echo  ob_get_clean();
    }
}