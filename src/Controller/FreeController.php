<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;
use Symfony\Component\Routing\Annotation\Route;

class FreeController extends AbstractController
{
    #[Route('/free', name: 'app_free')]
    public function index(TexterInterface $texter): Response
    {
        $sms = new SmsMessage(
            '+330782668932',
            'A message from Symfony <3',
            '0695251179'
        );
        $sentMessage = $texter->send($sms);

        return $this->render('free/index.html.twig', [
            'controller_name' => 'FreeController',
        ]);
    }
}
