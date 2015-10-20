<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{

    public function adminAction(Request $request)
    {
        $homeManager = $this->get('barbara.home_manager');

        $home = $homeManager->findHome();

        if(null === $home){
            $home = $homeManager->create();
        }

        $homeForm = $this->createForm('barbara_home_type', $home)->handleRequest($request);

        $videosOriginals = $homeManager->videosOriginals($home);

        if($homeForm->isValid()){

            $homeManager->save($home);

            $this->get('artesanus.flashers')->add('info','Datos del Home, guardados');
            return $this->redirect($this->generateUrl('artesanus_home'));
        }

        $homeVideosForm = $this->createForm('barbara_home_videos_type', $home)->handleRequest($request);

        if($homeVideosForm->isValid()){

            foreach($home->getVideos() as $video){
                $video->setHome($home);
            }

            $homeManager->updateVideos($home, $videosOriginals);
            $this->get('artesanus.flashers')->add('info','Videos guardados');
            return $this->redirect($this->generateUrl('artesanus_home'));
        }

        return $this->render('MoocsyBundle:Home:home-admin.html.twig', array(
            'home_form'         => $homeForm->createView(),
            'home_videos_form'  => $homeVideosForm->createView()
        ));
    }

    public function frontAction()
    {
        $homeManager = $this->get('barbara.home_manager');
        $home = $homeManager->findHome();

        return $this->render('MoocsyBundle:Home:home-front.html.twig', array(
            'home' => $home
        ));
    }

}
