<?php
namespace AppBundle\EventListener;
use ArtesanIO\ArtesanusBundle\Event\ArtesanusMenuEvent;
class HomeMenuAdminListener
{
    /**
     * @param \AppBundle\Event\ConfigureMenuEvent $event
     */
    public function onArtesanusNavBar(ArtesanusMenuEvent $event)
    {
        $menu = $event->getMenu();
        $menu->addChild('Home', array('route' => 'artesanus_home'));
    }
}
?>
