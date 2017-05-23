<?php

namespace PacksAnSpielBundle\Controller;

use PacksAnSpielBundle\Entity\Team;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Group controller.
 *
 * @Route("admin/group")
 */
class GroupController extends Controller
{
    /**
     * Lists all game entities.
     *
     * @Route("/", name="group_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $groups = $em->getRepository('PacksAnSpielBundle:Team')->findAll();

        return $this->render('PacksAnSpielBundle::admin/team/index.html.twig', array(
            'groups' => $groups,
        ));
    }

    /**
     * For all undone features.
     *
     * @Route("/todo", name="todo")
     * @Method("GET")
     */
    public function todoAction()
    {
        return $this->render('PacksAnSpielBundle::admin/game/todo.html.twig', array());
    }
}