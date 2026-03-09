<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Discordserverbox\Controllers\Admin;

class Index extends \Ilch\Controller\Admin
{
    public function indexAction()
    {
        $this->redirect(['controller' => 'settings', 'action' => 'index']);
    }
}
