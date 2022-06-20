<?php

namespace Acms\Plugins\GA4;

use ACMS_App;
use Acms\Services\Common\CorrectorFactory;
use Acms\Services\Common\InjectTemplate;

class ServiceProvider extends ACMS_App
{
    /**
     * @var string
     */
    public $version = '1.0.0';

    /**
     * @var string
     */
    public $name = 'GA4';

    /**
     * @var string
     */
    public $author = 'Mr. Gradation';

    /**
     * @var bool
     */
    public $module = false;

    /**
     * @var bool|string
     */
    public $menu = 'ga4_index';

    /**
     * @var string
     */
    public $desc = 'Google Analytics 4での機能を提供します。';

    /**
     * サービスの初期処理
     */
    public function init()
    {
        $corrector = CorrectorFactory::singleton();
        $corrector->attach('GA4Corrector', new Corrector);

        // アプリ管理画面を作成
        $inject = InjectTemplate::singleton();
        if (ADMIN === 'app_ga4_index') {
            $inject->add('admin-main', PLUGIN_DIR . 'GA4/template/index.html');
            $inject->add('admin-topicpath', PLUGIN_DIR . 'GA4/template/topicpath.html');
        }

        $inject->add('admin-module-select', PLUGIN_DIR . 'GA4/template/select.html');
        $inject->add('admin-module-config-GA4Ranking', PLUGIN_DIR . 'GA4/template/edit.html');

    }

    /**
     * インストールする前の環境チェック処理
     *
     * @return bool
     */
    public function checkRequirements()
    {
        return true;
    }

    /**
     * インストールするときの処理
     * データベーステーブルの初期化など
     *
     * @return void
     */
    public function install()
    {

    }

    /**
     * アンインストールするときの処理
     * データベーステーブルの始末など
     *
     * @return void
     */
    public function uninstall()
    {
      
    }

    /**
     * アップデートするときの処理
     *
     * @return bool
     */
    public function update()
    {
        return true;
    }

    /**
     * 有効化するときの処理
     *
     * @return bool
     */
    public function activate()
    {
        return true;
    }

    /**
     * 無効化するときの処理
     *
     * @return bool
     */
    public function deactivate()
    {
        return true;
    }
}
