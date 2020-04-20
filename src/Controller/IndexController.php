<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use App\Events\CommentCreatedEvent;
use App\Form\CommentFormType;
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
// for the inputs
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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

        return $this->render('pages/showTag.html.twig', array('posts' => $posts, 'tag' => $tag));
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

        /** @var \App\Entity\User $user */
        $userCurrent = $this->getUser();

        return $this->render('pages/profile.html.twig', ['user' => $user, 'current' => $userCurrent]);
    }
    
    /**
     * @Route("/alumni/profile={id}/edit", name="profile_edit")
     */
    public function profileEdit($id, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getDoctrine()->getRepository('App:User')->find($id);

        // returns your User object, or null if the user is not authenticated
        // use inline documentation to tell your editor your exact User class
        /** @var \App\Entity\User $user */
        $userCurrent = $this->getUser();
        $idCurrent = $userCurrent->getId();
        //  id username roles password email first_name last_name has_job profile_pic skills github
        
        //Edit form
        $form = $this->createFormBuilder($userCurrent)
        ->add('firstName', TextType::class, array('label'=> 'First Name', 'attr' => array('class'=> 'form-control')))
        ->add('lastName', TextType::class, array('label'=> 'Last Name', 'attr' => array('class'=> 'form-control')))
        ->add('save', SubmitType::class, array('label'=> 'Update Profile', 'attr' => array('class'=> 'btn btn-primary w-25 mt-2')))
        ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //fetching data
            $FirstName = $form['firstName']->getData();
            $LastName = $form['lastName']->getData();

            //set attributes for User object
            $userCurrent->setFirstName($FirstName);
            $userCurrent->setLastName($LastName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userCurrent);
            $entityManager->flush();
            $this->addFlash(
                'notice',
                'User updated'
            );
            return $this->redirectToRoute('profile',['id'=>$idCurrent]);
        }

        return $this->render('pages/profile_edit.html.twig', [
            'user' => $user, 
            'current' => $userCurrent,
            'form' => $form->createView()
        ]);
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

    /**
     * @Route("/comment/{postSlug}/new", methods="POST", name="comment_new")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @ParamConverter("post", options={"mapping": {"postSlug": "slug"}})
     *
     * NOTE: The ParamConverter mapping is required because the route parameter
     * (postSlug) doesn't match any of the Doctrine entity properties (slug).
     * See https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html#doctrine-converter
     */
    public function commentNew(Request $request, Post $post, EventDispatcherInterface $eventDispatcher): Response
    {
        $comment = new Comment();
        $comment->setAuthor($this->getUser());
        $post->addComment($comment);

        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            // When an event is dispatched, Symfony notifies it to all the listeners
            // and subscribers registered to it. Listeners can modify the information
            // passed in the event and they can even modify the execution flow, so
            // there's no guarantee that the rest of this controller will be executed.
            // See https://symfony.com/doc/current/components/event_dispatcher.html
            $eventDispatcher->dispatch(new CommentCreatedEvent($comment));

            return $this->redirectToRoute('showPost', ['slug' => $post->getSlug()]);
        }

        return $this->render('inc/comment_form_error.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    public function commentForm(Post $post): Response
    {
        $form = $this->createForm(CommentFormType::class);

        return $this->render('inc/comment_form.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }
}