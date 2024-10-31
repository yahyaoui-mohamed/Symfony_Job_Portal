<?php

// src/Service/EmailService.php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
  private $mailer;

  public function __construct(MailerInterface $mailer)
  {
    $this->mailer = $mailer;
  }

  public function sendPasswordResetEmail(string $toEmail, string $resetLink): void
  {
    $email = (new Email())
      ->from('hamayah4@gmail.com')
      ->to($toEmail)
      ->subject('Password Reset Request')
      ->html("<p>Click <a href='$resetLink'>here</a> to reset your password.</p>");

    $this->mailer->send($email);
  }
}
