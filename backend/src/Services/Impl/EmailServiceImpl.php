<?php
declare(strict_types=1);

namespace App\Services\Impl;

use App\Services\EmailService;
use PHPMailer\PHPMailer\PHPMailer;

class EmailServiceImpl implements EmailService
{
    private PHPMailer $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer();

        $this->mailer->CharSet = 'UTF-8';

        $this->mailer->isSMTP();
        $this->mailer->Host = 'smtp.office365.com';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = 'adsmarketplace@outlook.com';
        $this->mailer->Password = 'SpringAdvertisements';
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = 587;

        $this->mailer->setFrom('adsmarketplace@outlook.com', 'Ads Marketplace');
        $this->mailer->isHTML(true);
    }

    public function sendEmail(string $receiverEmail, string $subject, string $messageBody): void
    {
        $this->mailer->addAddress($receiverEmail);
        $this->mailer->Subject = $subject;
        $this->mailer->Body = $messageBody;
        $this->mailer->send();
//        $this->mailer->AltBody = 'Это тело письма для не-HTML почтовых клиентов';
    }
}