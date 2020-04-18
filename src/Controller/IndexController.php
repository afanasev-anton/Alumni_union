<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;

use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $posts = $this->getDoctrine()->getRepository('App:Post')->findBy([], ['createdAt' => 'DESC']);
        $tag = 'All';

        return $this->render('index/index.html.twig', array('posts' => $posts, 'school' => 'CodeFactory', 'tag' => $tag));
    }

    /**
     * @Route("/tag/?tag={tag}", methods="GET", name="showTag")
     */
    public function showTag($tag): Response
    {
        $posts = $this->getDoctrine()->getRepository('App:Post')->findByTag($tag); 

        return $this->render('pages/showTag.html.twig', array('posts' => $posts));
    }

    /**
     * @Route("/post/{slug}", methods="GET", name="showPost")
     */
    public function showPost(Post $post): Response
    {
        return $this->render('pages/showPost.html.twig', array('post' => $post));
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
        $posts = $this->getDoctrine()->getRepository('App:Post')->findByTag('Job Offer');
        $tag = 'Career'; 

        return $this->render('pages/showTag.html.twig', array('posts' => $posts, 'tag' => $tag));
    }

    /**
     * @Route("/events", name="event")
     */
    public function event()
    {
        $posts = $this->getDoctrine()->getRepository('App:Post')->findByTag('Event'); 
        $tag = 'Event'; 

        return $this->render('pages/showTag.html.twig', array('posts' => $posts, 'tag' => $tag));
    }

    /**
     * @Route("/stories", name="story")
     */
    public function story()
    {
        $posts = $this->getDoctrine()->getRepository('App:Post')->findByTag('Story'); 
        $tag = 'Story';

        return $this->render('pages/showTag.html.twig', array('posts' => $posts, 'tag' => $tag));
    }
}