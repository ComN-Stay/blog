<?php 

namespace App\Service;

use Twig\Environment;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Exception;
use App\Repository\TransactionalRepository;


class MailService
{
    
    protected $mailer;
    protected $mailFrom;
    protected $twig;
    protected $transactionalRepository;

    public function __construct(
        MailerInterface $mailer, 
        Environment $twig,
        TransactionalRepository $transactionalRepository,
        $mailFrom
        )
    {
        $this->mailer = $mailer;
        $this->mailFrom = $mailFrom;
        $this->twig = $twig;
        $this->transactionalRepository = $transactionalRepository;
    }

    /*
    * $datas Array
    * to : recipient email : required
    * tpl : email transactionnal template
    * vars : Array of objects and vars for message variables (['mavar1' => 'var1 content', ..., 'user' => User object, ...])
    * usage eg. : $datas = [
                    'to' => 'recipient@domain.tld',
                    'tpl' => 'template_name',
                    'vars' => [
                        'user' => $userRepository->find(1), // object
                        'url' => 'https://www.mydomain.tld', // var
                        ...
                    ]
                ];
    */
    public function sendMail($datas)
    {
        $template = $this->transactionalRepository->findOneBy(['template' => $datas['tpl']]);
        if($template === NULL) {
            return false;
        }

        $htmlMsg = $this->twig->render('emails/' . $datas['tpl'] . '.html.twig', $datas['vars']);

        $email = new Email();
        $email->from($this->mailFrom)
            ->to($datas['to'])
            ->replyTo($this->mailFrom)
            ->priority(Email::PRIORITY_HIGH)
            ->subject($template->getSubject())
            ->html($htmlMsg);
        
        try {
            $this->mailer->send($email);
        } catch (Exception $e) {
            dd('Exception reçue : ',  $e->getMessage());
        }
        
        
    }
    
}