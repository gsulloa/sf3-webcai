<?php
namespace Cai\WebBundle\ServicesClass;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * Created by PhpStorm.
 * User: gulloa
 * Date: 18-07-16
 * Time: 11:50
 */
class ServiceMail
{
    private $mailer;
    private $renderer;

    /**
     * ServiceMail constructor.
     * @param $mailer
     */
    public function __construct($mailer, EngineInterface $templating)
    {
        $this->mailer = $mailer;
        $this->renderer = $templating;
    }

    public function send($params){
        $message = \Swift_Message::newInstance()->setSubject($params['subject'])
            ->setFrom('no-reply@caiuc.cl')
            ->setTo($params['to'])
            ->setBody($this->renderer->render('CaiFrontendBundle:mailing:'.$params['type'].'.html.twig',
                $params["renderParams"]),
                "text/html");
        $this->mailer->send($message);
    }
}