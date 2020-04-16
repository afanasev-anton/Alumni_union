<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $posts = $this->getDoctrine()->getRepository('App:Post')->findAll();

        return $this->render('index/index.html.twig', array('posts' => $posts));
    }

    /**
     * @Route("/alumni", name="alumni")
     */
    public function alumni()
    {
    	$users = $this->getDoctrine()->getRepository('App:User')->findAll();
        //findByRoles('ROLE_USER');
        // add correct type from entity

        return $this->render('pages/alumnies.html.twig', array('users' => $users));
    }

    /**
     * @Route("/alumni/profile={id}", name="profile")
     */
    public function profile($id)
    {
        $user = $this->getDoctrine()->getRepository('App:User')->find($id); 

        return $this->render('pages/profile.html.twig', array('user' => $user));
    }

    /**
     * @Route("/careers", name="career")
     */
    public function career()
    {
        $careers = $this->getDoctrine()->getRepository('App:Post')->findAll();
        //findBy(['type'=>'offers']); 
        // add correct type from entity

        return $this->render('pages/careers.html.twig', array('careers' => $careers));
    }

    /**
     * @Route("/events", name="event")
     */
    public function event()
    {
        $events = $this->getDoctrine()->getRepository('App:Post')->findAll();
        //findBy(['type'=>'events']); 
        // add correct type from entity

        return $this->render('pages/events.html.twig', array('events' => $events));
    }

    /**
     * @Route("/stories", name="story")
     */
    public function story()
    {
        $stories = $this->getDoctrine()->getRepository('App:Post')->findAll();
        //findBy(['type'=>'stories']); 
        // add correct type from entity

        return $this->render('pages/stories.html.twig', array('stories' => $stories));
    }
}
