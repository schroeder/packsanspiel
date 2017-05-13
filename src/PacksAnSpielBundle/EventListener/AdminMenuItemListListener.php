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
            $menuItem00 = new MenuItemModel('Log-Buch', 'Log-Buch', 'todo', array(/* options */), 'iconclasses fa fa-film'),
            $menuItem01 = new MenuItemModel('Punktestand', 'Punktestand', 'todo', array(/* options */), 'iconclasses fa fa-send'),
            $menuItem02 = new MenuItemModel('Gruppen', 'Gruppen', 'todo', array(/* options */), 'iconclasses fa fa-group'),
            $menuItem03 = new MenuItemModel('Spiele', 'Spiele', 'todo', array(/* options */), 'iconclasses fa  fa-futbol-o'),
            $menuItem04 = new MenuItemModel('AnzeigeStandLevel', 'Anzeige Stand Level', 'todo', array(/* options */), 'iconclasses fa fa-flag'),
            $menuItem05 = new MenuItemModel('PasscodeGenerieren', 'Passcode generieren', 'todo', array(/* options */), 'iconclasses fa fa-qrcode')
        );
        $menuItem02->addChild(new MenuItemModel('MitgliederListe', 'Liste', 'todo', array(/* options */), 'iconclasses fa fa-list'));
        $menuItem02->addChild(new MenuItemModel('MitgliederVerwalten', 'Mitglieder verwalten', 'todo', array(/* options */), 'iconclasses fa fa-edit'));
        $menuItem02->addChild(new MenuItemModel('MitgliederNachrichtSchicken', 'Nachricht schicken', 'todo', array(/* options */), 'iconclasses fa fa-commenting'));
        $menuItem03->addChild(new MenuItemModel('SpieleListe', 'Liste', 'game_index', array(/* options */), 'iconclasses fa fa-list'));
        $menuItem03->addChild(new MenuItemModel('SpieleNachrichtSchicken', 'Nachricht schicken', 'todo', array(/* options */), 'iconclasses fa fa-commenting'));
        $menuItem05->addChild(new MenuItemModel('PasscodeGenerierenSpiel', 'Spiel', 'todo', array(/* options */), 'iconclasses fa fa-futbol-o'));
        $menuItem05->addChild(new MenuItemModel('PasscodeGenerierenGruppe', 'Gruppe', 'admin_team_passcode', array(/* options */), 'iconclasses fa fa-users'));
        $menuItem05->addChild(new MenuItemModel('PasscodeGenerierenTeilnehmer', 'Teilnehmer', 'admin_member_passcode', array(/* options */), 'iconclasses fa fa-user'));
        $menuItem05->addChild(new MenuItemModel('PasscodeGenerierenAdmin', 'Administrator', 'todo', array(/* options */), 'iconclasses fa fa-linux'));

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
