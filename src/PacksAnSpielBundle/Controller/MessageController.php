<?php

namespace PacksAnSpielBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use PacksAnSpielBundle\Entity\Message;

/**
 * ActionLog controller.
 *
 * @Route("admin/message")
 */
class MessageController extends Controller
{
    /**
     * Lists last 100 game action logs.
     *
     * @Route("/", name="message_index")
     */
    public function indexAction(Request $request)
    {

    }
}