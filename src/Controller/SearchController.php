<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\PostRepository;
use Symfony\Component\Form\Extension\Core\Type\{TextType, SubmitType};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends AbstractController
{
    /**
     * @Route("/alumni/search", name="alumni_search")
     */

    public function searchBar()
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('handleSearch'))
            ->add('search', TextType::class, [
                'attr' => [
                    'class' => 'form-control', 
                    'placeholder' => 'search for skills'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-2'
                ]
            ])
            ->getForm();
            
        return $this->render('search/searchBar.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/handleSearch", name="handleSearch")
     * @param Request $request
     */
    public function handleSearch(Request $request, UserRepository $userRepository)
    {
        $search = $request->request->get('form')['search'];
        if ($search) {
        $users = $userRepository->findBySkill($search);
        }

        return $this->render('pages/alumnies.html.twig', array('users' => $users));

    }

    /**
     * @Route("/posts/search", name="post_search")
     */

    public function searchBarPosts()
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('handlePostSearch'))
            ->add('searchPost', TextType::class, [
                'attr' => [
                    'class' => 'form-control', 
                    'placeholder' => 'search for posts'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-2'
                ]
            ])
            ->getForm();
            
        return $this->render('search/searchBar.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/handlePostSearch", name="handlePostSearch")
     * @param Request $request
     */
    public function handleSearchPosts(Request $request, PostRepository $postRepository)
    {
        $searchPost = $request->request->get('form')['searchPost'];

        $tag = 'Searched';
        
        if ($searchPost) {
            $posts = $postRepository->findByTitle($searchPost);
        }

        return $this->render('pages/showCategory.html.twig', array('posts' => $posts, 'tag' => $tag));

    }
}
