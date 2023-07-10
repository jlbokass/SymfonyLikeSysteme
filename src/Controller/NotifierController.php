<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Notifier;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Routing\Annotation\Route;

class NotifierController extends AbstractController
{
    #[Route('/bin', name: 'app_notifier')]
    public function index(NotifierInterface $notifier): Response
    {
        //Resources: https://www.strangebuzz.com/en/blog/send-symfony-application-logs-to-slack-with-monolog
        $notification  = new Notification('Hello from Symfony <3 at 12h50 with part of team',['chat/slack']);
        $notifier->send($notification);

        return $this->render('notifier/index.html.twig', [
            'controller_name' => 'NotifierController',
        ]);
    }
}
