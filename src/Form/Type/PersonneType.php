<?php

/*
 * Ceci sera ajouté dans tous vos fichiers PHP en entête.
 *
 * (c) Zozor <zozor@openclassrooms.com>
 *
 * A adapter et ré-utiliser selon vos besoins!
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonneType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Personne',
            'personnes' => null,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $listeParents = [];
        foreach ($options['personnes'] as $personne) {
            $listeParents[$personne->__toString()] = $personne->getId();
        }

        $builder
            ->add('prenom', TextType::class, [
                'required' => true,
                'label_format' => 'Saisir le prénom : ', ])
            ->add('prenom2', TextType::class, [
                'required' => false,
                'label_format' => 'Saisir le deuxièmle prénom : ', ])
            ->add('prenom3', TextType::class, [
                'required' => false,
                'label_format' => 'Saisir le troisième prénom : ', ])
            ->add('prenom4', TextType::class, [
                'required' => false,
                'label_format' => 'Saisir le quatrième prénom : ', ])
            ->add('nom', TextType::class, [
                'required' => true,
                'label_format' => 'Saisir le nom : ', ])
            ->add('surnom', TextType::class, [
                'required' => false,
                'label_format' => 'Saisir le surnom : ',
                'help' => 'Le surnom est le petit nom par lequel le village vous appéles.', ])
            ->add('sexe', ChoiceType::class, [
                'required' => true,
                'label_format' => 'Selectionnez votre sexe : ',
                'choices' => [
                    'Homme' => 'M',
                    'Femme' => 'F',
                ], ])
            ->add('typeAscendence', ChoiceType::class, [
                'required' => true,
                'label_format' => 'Selectionnez votre type de descendance : ',
                'choices' => [
                    'Pére' => 'M',
                    'Mére' => 'F',
                ],
                'help' => 'Le type de descendence est de choisir 
                si vous avez gardé le nom du pére ou de la mére.', ])
            ->add('parent1', ChoiceType::class, [
                'required' => false,
                'label_format' => 'Selectionnez votre pére : ',
                'choices' => $listeParents, ])
            ->add('parent2', ChoiceType::class, [
                'required' => false,
                'label_format' => 'Selectionnez votre mére : ',
                'choices' => $listeParents,
                ])
            ->add('Sauvegarde', SubmitType::class);
    }
}
