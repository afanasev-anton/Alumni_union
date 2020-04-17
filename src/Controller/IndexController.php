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

        return $this->render('index/index.html.twig', array('posts' => $posts, 'school' => 'CodeFactory'));
    }

    /**
     * @Route("/alumni", name="alumni")
     */
    public function alumni()
    {
    	$this->denyAccessUnlessGranted('ROLE_USER');

        $users = $this->getDoctrine()->getRepository('App:User')
        ->findByRoles('ROLE_ALUMNI');

        return $this->render('pages/alumnies.html.twig', array('users' => $users, 'school' => 'CodeFactory'));
    }

    /**
     * @Route("/alumni/profile={id}", name="profile")
     */
    public function profile($id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getDoctrine()->getRepository('App:User')->find($id);

        // returns your User object, or null if the user is not authenticated
        // use inline documentation to tell your editor your exact User class
        /** @var \App\Entity\User $user */
        $userCurrent = $this->getUser();
        $currentName = $userCurrent->getUsername();

        return $this->render('pages/profile.html.twig', array('user' => $user, 'school' => 'CodeFactory', 'current' => $currentName));
    }

    /**
     * @Route("/careers", name="career")
     */
    public function career()
    {
        $careers = $this->getDoctrine()->getRepository('App:Post')->findAll();
        //findBy(['type'=>'offers']); 
        // add correct type from entity

        return $this->render('pages/careers.html.twig', array('careers' => $careers, 'school' => 'CodeFactory'));
    }

    /**
     * @Route("/events", name="event")
     */
    public function event()
    {
        $events = $this->getDoctrine()->getRepository('App:Post')->findAll();
        //findBy(['type'=>'events']); 
        // add correct type from entity

        return $this->render('pages/events.html.twig', array('events' => $events, 'school' => 'CodeFactory'));
    }

    /**
     * @Route("/stories", name="story")
     */
    public function story()
    {
        $stories = $this->getDoctrine()->getRepository('App:Post')->findAll();
        //findBy(['type'=>'stories']); 
        // add correct type from entity

        return $this->render('pages/stories.html.twig', array('stories' => $stories, 'school' => 'CodeFactory'));
    }
}
