<?php
namespace PacksAnSpielBundle\EventListener;

// ...

use PacksAnSpielBundle\Model\MenuItemModel;
use Avanzu\AdminThemeBundle\Event\SidebarMenuEvent;
use Symfony\Component\HttpFoundation\Request;

class AdminMenuItemListListener
{

    public function onSetupMenu(SidebarMenuEvent $event)
    {
        $request = $event->getRequest();
        foreach ($this->getMenu($request) as $item) {
            $event->addItem($item);
        }
    }

    protected function getMenu(Request $request)
    {
        $menuItems = array(
            $menuItem01 = new MenuItemModel('Punktestand', 'Punktestand', 'admin', array(/* options */), 'iconclasses fa fa-send'),
            $menuItem02 = new MenuItemModel('Gruppen', 'Gruppen', 'admin', array(/* options */), 'iconclasses fa fa-group'),
            $menuItem03 = new MenuItemModel('Spiele', 'Spiele', 'admin', array(/* options */), 'iconclasses fa  fa-futbol-o'),
            $menuItem04 = new MenuItemModel('AnzeigeStandLevel', 'Anzeige Stand Level', 'admin', array(/* options */), 'iconclasses fa fa-flag'),
            $menuItem05 = new MenuItemModel('PasscodeGenerieren', 'Passcode generieren', 'admin', array(/* options */), 'iconclasses fa fa-qrcode')
        );
        $menuItem02->addChild(new MenuItemModel('MitgliederListe', 'Liste', 'admin', array(/* options */), 'iconclasses fa fa-list'));
        $menuItem02->addChild(new MenuItemModel('MitgliederVerwalten', 'Mitglieder verwalten', 'admin', array(/* options */), 'iconclasses fa fa-edit'));
        $menuItem02->addChild(new MenuItemModel('MitgliederNachrichtSchicken', 'Nachricht schicken', 'admin', array(/* options */), 'iconclasses fa fa-commenting'));
        $menuItem03->addChild(new MenuItemModel('SpieleListe', 'Liste', 'game_index', array(/* options */), 'iconclasses fa fa-list'));
        $menuItem03->addChild(new MenuItemModel('SpieleNachrichtSchicken', 'Nachricht schicken', 'admin', array(/* options */), 'iconclasses fa fa-commenting'));
        $menuItem05->addChild(new MenuItemModel('PasscodeGenerierenSpiel', 'Spiel', 'admin', array(/* options */), 'iconclasses fa fa-futbol-o'));
        $menuItem05->addChild(new MenuItemModel('PasscodeGenerierenGruppe', 'Gruppe', 'admin_team_passcode', array(/* options */), 'iconclasses fa fa-users'));
        $menuItem05->addChild(new MenuItemModel('PasscodeGenerierenTeilnehmer', 'Teilnehmer', 'admin_member_passcode', array(/* options */), 'iconclasses fa fa-user'));
        $menuItem05->addChild(new MenuItemModel('PasscodeGenerierenAdmin', 'Administrator', 'admin', array(/* options */), 'iconclasses fa fa-linux'));

        return $this->activateByRoute($request->get('_route'), $menuItems);
    }

    protected function activateByRoute($route, $items)
    {
        foreach ($items as $item) {
            if ($item->hasChildren()) {
                $this->activateByRoute($route, $item->getChildren());
            } else {
                if ($item->getRoute() == $route) {
                    $item->setIsActive(true);
                }
            }
        }
        return $items;
    }
}
