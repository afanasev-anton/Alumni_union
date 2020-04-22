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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('username')
            ->add('email')
            ->add('firstName'/*, TextType::class, [
                'attr' => [
                    'class' => 'form-control w-25',
                    'placeholder' => 'First name'
                ]
            ]*/)
            ->add('lastName')
            ->add('hasJob')
            ->add('skills')
            ->add('github')
            ->add('tel')
            ->add('birth', DateType::class, [
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('course')
            ->add('nation')
            ->add('descr')
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'download_uri' => true,
                'image_uri' => true
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
