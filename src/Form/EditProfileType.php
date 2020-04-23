<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
// for the inputs
use Symfony\Component\Form\Extension\Core\Type\TextType;
//use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('username')
            ->add('email', TextType::class, [
                'attr' => [
                    'class' => 'form-control w-75 ml-2',
                    'placeholder' => 'E-mail'
                ]
            ])
            ->add('firstName', TextType::class, [
                'attr' => [
                    'class' => 'form-control w-75 ml-auto',
                    'placeholder' => 'First name'
                ]
            ])
            ->add('lastName', TextType::class, [
                'attr' => [
                    'class' => 'form-control w-75 mr-auto',
                    'placeholder' => 'Last name'
                ]
            ])
            ->add('hasJob', CheckboxType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'custom-control-input'
                ]
            ])
            ->add('skills', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control w-75 ml-2',
                    'placeholder' => 'your skills here...'
                ]
            ])
            ->add('github', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control w-75 ml-2',
                    'placeholder' => 'repository link'
                ]
            ])
            ->add('tel', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control w-75 ml-2',
                    'placeholder' => 'Telephone'
                ]
            ])
            ->add('birth', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
                'attr' => ['class' => 'form-control w-50 ml-2'],
            ])
            ->add('course', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control w-75 ml-2',
                    'placeholder' => 'your course/es...'
                ]
            ])
            ->add('nation', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control w-75 ml-2',
                    'placeholder' => 'Nationality'
                ]
            ])
            ->add('descr', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control mx-auto',
                    'placeholder' => 'write something about yourself...'
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'image_uri' => true,
                'attr' => [
                    'class' => 'custom-file-input'
                ]
            ])
            ->add('save', SubmitType::class, [
                'label'=> 'Update Profile',
                'attr' => [
                    'class'=> 'btn btn-primary w-100']
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
