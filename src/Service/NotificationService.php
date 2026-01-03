<?php

namespace App\Service;

use App\Entity\Reservation;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NotificationService
{
    public function __construct(
        private MailerInterface $mailer
    ) {
    }

    public function envoyerConfirmationReservation(Reservation $reservation): void
    {
        $user = $reservation->getUser();

        $email = (new Email())
            ->from('noreply@cinema.local')
            ->to($user->getEmail())
            ->subject('Confirmation de réservation')
            ->text('Votre réservation est confirmée.');

        $this->mailer->send($email);
    }
}
