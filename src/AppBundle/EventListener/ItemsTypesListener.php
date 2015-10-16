<?php

namespace AppBundle\EventListener;

use ArtesanIO\MoocsyBundle\Event\ItemsTypesEvent;
use ArtesanIO\MoocsyBundle\Event\MoocsyEvents;

class ItemsTypesListener
{
    /**
     * @param \AppBundle\Event\ConfigureMenuEvent $event
     */
    public function onItemsTypes(ItemsTypesEvent $event)
    {
        $itemType = $event->getItemType();

        $itemType->createType(array('items_audio', array('itemType' => 'Audio', 'description' => 'Admin', 'login_route_success' => 'usuarios')));
        $itemType->createType(array('items_audio_down', array('itemType' => 'Audio Download', 'description' => 'Admin', 'login_route_success' => 'usuarios')));
        $itemType->createType(array('items_file', array('itemType' => 'File', 'description' => 'Admin', 'login_route_success' => 'usuarios')));
        $itemType->createType(array('items_forum', array('itemType' => 'Reto', 'description' => 'Admin', 'login_route_success' => 'usuarios')));
        $itemType->createType(array('items_video', array('itemType' => 'Video', 'description' => 'Admin', 'login_route_success' => 'usuarios')));
        $itemType->createType(array('items_quiz', array('itemType' => 'Diario', 'description' => 'Admin', 'login_route_success' => 'usuarios')));
    }

    public static function getSubscribedEvents()
    {
        return array(
            MoocsyEvents::MOOCSY_ITEMS_TYPE => 'onItemsTypes',
        );
    }
}

?>
