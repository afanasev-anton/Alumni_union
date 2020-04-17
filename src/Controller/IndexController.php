<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", defaults={"page": "1", "_format"="html"}, methods="GET", name="index")
     */
    public function index(Request $request, int $page, string $_format, PostRepository $posts, TagRepository $tags): Response
    {
        $tag = null;
        if ($request->query->has('tag')) {
            $tag = $tags->findOneBy(['name' => $request->query->get('tag')]);
        }
        $latestPosts = $posts->findLatest($page, $tag);

        // Every template name also has two extensions that specify the format and
        // engine for that template.
        // See https://symfony.com/doc/current/templates.html#template-naming
        return $this->render('index/index.'.$_format.'.twig', [
            'latestPosts' => $latestPosts,
        ]);
    }

    /**
     * @Route("/alumni", name="alumni")
     */
    public function alumni()
    {
    	$users = $this->getDoctrine()->getRepository('App:User')->findAll();
    	//findBy(['type'=>'alumni']); 
        // add correct type from entity

        return $this->render('pages/alumnies.html.twig', array('users' => $users));
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
        $events = $this->getDoctrine()->getRepository('App:Tag')->findBy(['name'=>'Event']); 
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
