<?php

namespace PacksAnSpielBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use PacksAnSpielBundle\Entity\Message;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Spiritix\HtmlToPdf\Converter;
use Spiritix\HtmlToPdf\Input\StringInput;
use Spiritix\HtmlToPdf\Output\EmbedOutput;
use PacksAnSpielBundle\Entity\Game;

/**
 * Game controller.
 *
 * @Route("admin/game")
 */
class GameController extends Controller
{
    /**
     * Lists all game entities.
     *
     * @Route("/", name="game_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $games = $em->getRepository('PacksAnSpielBundle:Game')->findAll();

        return $this->render('PacksAnSpielBundle::admin/game/index.html.twig', array(
            'games' => $games,
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

    /**
     * Creates a new game entity.
     *
     * @Route("/new", name="game_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $game = new Game();
        $form = $this->createForm('PacksAnSpielBundle\Form\GameType', $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($game);
            $em->flush($game);

            return $this->redirectToRoute('game_show', array('id' => $game->getId()));
        }

        return $this->render('PacksAnSpielBundle::admin/game/new.html.twig', array(
            'game' => $game,
            'form' => $form->createView(),
        ));
    }

    /**
     * Sends a message to a Game.
     *
     * @Route("/send", name="game_message")
     */
    public function sendMessageAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $currentTime = new \DateTime('now');

        $message = new Message();
        $message->setSendTime($currentTime->getTimestamp());
        $message->setTeam(null);

        $form = $this->createFormBuilder()
            ->add('gameId', IntegerType::class, array('label' => false, 'required' => true))
            ->add('messageText', TextareaType::class)
            ->add('save', SubmitType::class, array('label' => 'Nachricht senden'))
            ->getForm();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            $data = $form->getData();
            $game = $em->getRepository('PacksAnSpielBundle:Game')->find($data['gameId']);
            $message->setGame($game);
            $message->setMessageText($data['messageText']);

            $em->persist($message);
            $em->flush($message);
        }

        return $this->render('PacksAnSpielBundle::admin/game/message.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a game entity.
     *
     * @Route("/{id}", name="game_show")
     * @Method("GET")
     */
    public function showAction(Game $game)
    {
        $deleteForm = $this->createDeleteForm($game);

        return $this->render('PacksAnSpielBundle::admin/game/show.html.twig', array(
            'game' => $game,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing game entity.
     *
     * @Route("/{id}/edit", name="game_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Game $game)
    {
        $deleteForm = $this->createDeleteForm($game);
        $editForm = $this->createForm('PacksAnSpielBundle\Form\GameType', $game);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('game_edit', array('id' => $game->getId()));
        }

        return $this->render('PacksAnSpielBundle::admin/game/edit.html.twig', array(
            'game' => $game,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing game entity.
     *
     * @Route("/{id}/export", name="game_export")
     * @Method({"GET", "POST"})
     */
    public function exportAction(Request $request, Game $game)
    {
        $html = $this->render('PacksAnSpielBundle::admin/game/export.html.twig', array(
            'game' => $game));

        $input = new StringInput();
        $input->setHtml($html->getContent());


        $converter = new Converter($input, new EmbedOutput());

        //$converter->setOption('n');
        //$converter->setOption('d', '300');

        $converter->setOptions([
            'no-background',
            'margin-bottom' => '100',
            'margin-top' => '100',
        ]);

        $output = $converter->convert();
        $output->embed("game.pdf");
        exit;

    }

    /**
     * Deletes a game entity.
     *
     * @Route("/{id}", name="game_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Game $game)
    {
        $form = $this->createDeleteForm($game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($game);
            $em->flush($game);
        }

        return $this->redirectToRoute('game_index');
    }

    /**
     * Creates a form to delete a game entity.
     *
     * @param Game $game The game entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Game $game)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('game_delete', array('id' => $game->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
