<?php

namespace AppBundle\Service;

use AppBundle\Entity\ApiUser;
use AppBundle\Entity\Delivery;
use AppBundle\Entity\StripePayment;
use Symfony\Bridge\Twig\TwigEngine;
use Sylius\Component\Order\Model\OrderInterface;
use Symfony\Component\Translation\TranslatorInterface;

class EmailManager
{
    private $mailer;
    private $templating;
    private $translator;
    private $settingsManager;
    private $transactionalAddress;

    public function __construct(
        \Swift_Mailer $mailer,
        TwigEngine $templating,
        TranslatorInterface $translator,
        SettingsManager $settingsManager,
        $transactionalAddress)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->translator = $translator;
        $this->settingsManager = $settingsManager;
        $this->transactionalAddress = $transactionalAddress;
    }

    private function getFrom()
    {
        return [
            $this->transactionalAddress => $this->settingsManager->get('brand_name')
        ];
    }

    public function createHtmlMessage($subject = null, $body = null)
    {
        $message = \Swift_Message::newInstance($subject);

        if ($body) {
            $message->setBody($body, 'text/html');
        }

        $message->setFrom($this->getFrom());

        return $message;
    }

    public function send(\Swift_Message $message)
    {
        foreach ($message->getTo() as $address => $name) {
            if (preg_match('/@demo.coopcycle.org$/', $address)) {
                return;
            }
        }

        $this->mailer->send($message);
    }

    public function sendTo(\Swift_Message $message, $to)
    {
        $message->setTo($to);

        $this->send($message);
    }

    public function createOrderCreatedMessage(OrderInterface $order)
    {
        if ($order->isFoodtech()) {
            $subject = $this->translator->trans('order.confirmationMail.subject', [
                '%orderId%' => $order->getId()
            ], 'emails');
            $body = $this->templating->render('AppBundle::Emails/orderConfirmation.html.twig', [
                'order' => $order,
                'orderId' => $order->getId()
            ]);
        } else {
            $subject = $this->translator->trans('delivery.to_be_confirmed.subject', [], 'emails');
            $body = $this->templating->render('@App/Emails/Delivery/toBeConfirmed.html.twig');
        }

        return $this->createHtmlMessage($subject, $body);
    }

    public function createOrderCreatedMessageForAdmin(OrderInterface $order)
    {
        $subject = $this->translator->trans('delivery.has_to_be_confirmed.subject', [], 'emails');
        $body = $this->templating->render('@App/Emails/Delivery/hasToBeConfirmed.html.twig', [
            'order' => $order,
        ]);

        return $this->createHtmlMessage($subject, $body);
    }

    public function createOrderCancelledMessage(OrderInterface $order)
    {
        $subject = $this->translator->trans('order.cancellationMail.subject', [
            '%orderId%' => $order->getId()
        ], 'emails');

        $body = $this->templating->render('AppBundle::Emails/orderCancelled.html.twig', [
            'order' => $order,
            'orderId' => $order->getId()
        ]);

        return $this->createHtmlMessage($subject, $body);
    }

    public function createOrderAcceptedMessage(OrderInterface $order)
    {
        if ($order->isFoodtech()) {
            $subject = $this->translator->trans('order.acceptedMail.subject', [
                '%orderId%' => $order->getId()
            ], 'emails');
            $body = $this->templating->render('AppBundle::Emails/orderAccepted.html.twig', [
                'order' => $order,
                'orderId' => $order->getId()
            ]);
        } else {
            $subject = $this->translator->trans('delivery.confirmed.subject', [], 'emails');
            $body = $this->templating->render('@App/Emails/Delivery/confirmed.html.twig', [
                'order' => $order,
            ]);
        }

        return $this->createHtmlMessage($subject, $body);
    }
}