<?php

namespace PacksAnSpielBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class GameType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', null, array('label' => 'Spielname'))
            ->add('description', null, array('label' => 'Kurzbeschreibung'))
            ->add('game_description', null, array(
                    'attr' => array('class' => 'tinymce', 'data-theme' => 'bbcode'),
                    'label' => 'Spielanleitung')
            )->add('duration', null, array('label' => 'Geschätzte Spieldauer'))
            ->add('gameSubject', null, array('label' => 'Themenbereich'))
            ->add('parentGame', null, array('label' => 'Spiel ist abgeleitet von Spiel'))
            ->add('level', null, array('label' => 'Level'))
            ->add('grade', ChoiceType::class, array('label' => 'Stufe', 'choices' => array(
                'Wölflinge' => 'woe',
                'Jungpfadfinder' => 'juffi',
                'Pfadfinder' => 'pfadi', 'Rover' => 'rover')))
            ->add('location', null, array('label' => 'Ort'))
            ->add('group_text_game_start', null, array('label' => 'Hinweistext für die Gruppe beim Spielstart'))
            ->add('group_text_game_question', null, array('label' => 'Frage zum Spielergebnis'))
            ->add('group_text_game_correct_answer', null, array('label' => 'Korrekte Antwort'))
            ->add('group_text_game_wrong_answer_1', null, array('label' => 'Falsche Antwort 1'))
            ->add('group_text_game_wrong_answer_2', null, array('label' => 'Falsche Antwort 2'))
            ->add('group_text_game_wrong_answer_3', null, array('label' => 'Falsche Antwort 3'))
            ->add('group_text_game_end', null, array('label' => 'Hinweistext für die Gruppe beim Spielende'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PacksAnSpielBundle\Entity\Game'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'packsanspielbundle_game';
    }


}
