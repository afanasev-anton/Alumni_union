<?php

namespace App\Controller;

use App\Repository\UserRepository;
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
}
