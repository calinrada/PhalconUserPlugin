<?php
namespace Phalcon\UserPlugin\Mail;

use Phalcon\Mvc\User\Component,
Phalcon\Mvc\View,
Swift_Message as Message,
Swift_SmtpTransport as Smtp,
Crada\UserPlugin\Models\User\User;

/**
 * Phalcon\UserPlugin\Mail\Mail
 *
 * Sends e-mails based on pre-defined templates
 */
class Mail extends Component
{
    protected $_transport;

    protected $_directSmtp = true;

    /**
     * Applies a template to be used in the e-mail
     *
     * @param string $name
     * @param array  $params
     */
    public function getTemplate($name, $params)
    {
        $parameters = array_merge(array(
            'publicUrl' => $this->config->application->publicUrl,
        ), $params);

        return $this->view->getRender('emailTemplates', $name, $parameters, function ($view) {
            $view->setRenderLevel(View::LEVEL_LAYOUT);
        });

            return $view->getContent();
    }

    /**
     * Sends e-mails based on predefined templates. If the $body param
     * has value, the template will be ignored
     *
     * @param array  $to
     * @param string $subject
     * @param string $name Template name
     * @param array  $params
     * @param array  $body
     */
    public function send($to, $subject, $name = null, $params = null, $body = null)
    {
        //Settings
        $mailSettings = $this->config->mail;

        $template = $body ? $body : $this->getTemplate($name, $params);

        // Create the message
        $message = Message::newInstance()
        ->setSubject($subject)
        ->setTo($to)
        ->setFrom(array(
            $mailSettings->fromEmail => $mailSettings->fromName
        ))
        ->setBody($template, 'text/html');

        if (!$this->_transport) {
            $this->_transport = Smtp::newInstance(
                $mailSettings->smtp->server,
                $mailSettings->smtp->port,
                $mailSettings->smtp->security
            )
            ->setUsername($mailSettings->smtp->username)
            ->setPassword($mailSettings->smtp->password);
        }

        // Create the Mailer using your created Transport
        $mailer = \Swift_Mailer::newInstance($this->_transport);

        return $mailer->send($message);

    }
}
