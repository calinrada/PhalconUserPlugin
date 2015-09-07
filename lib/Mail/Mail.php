<?php

namespace Phalcon\UserPlugin\Mail;

use Phalcon\Mvc\User\Component;
use Phalcon\Mvc\View;

/**
 * Phalcon\UserPlugin\Mail\Mail.
 *
 * Sends e-mails based on pre-defined templates
 */
class Mail extends Component
{
    protected $_transport;

    protected $_directSmtp = true;

    protected $attachments = array();

    protected $images = array();

    /**
     * Adds a new file to attach.
     *
     * @param unknown $file
     */
    public function addAttachment($name, $content, $type = 'text/plain')
    {
        $this->attachments[] = array(
            'name' => $name,
            'content' => $content,
            'type' => $type,
        );
    }

    /**
     * Applies a template to be used in the e-mail.
     *
     * @param string $name
     * @param array  $params
     */
    public function getTemplate($message, $name, $params)
    {
        $parameters = array_merge(array(
            'publicUrl' => $this->config->application->publicUrl,
        ), $params);

        foreach ($this->images as $name => $image_path) {
            $parameters[$name] = $message->embed(\Swift_Image::fromPath($image_path));
        }

        return $this->view->getRender('emailTemplates', $name, $parameters, function ($view) {
            $view->setRenderLevel(View::LEVEL_LAYOUT);
        });
    }

    /**
     * Inserts images without using getTemplate.
     *
     * @param string $message
     * @param string $content
     *
     * @return mixed
     */
    public function insertImages($message, $content)
    {
        foreach ($this->images as $name => $image_path) {
            $image_embed = $message->embed(\Swift_Image::fromPath($image_path));
            $content = str_replace(rawurlencode('{{ '.$name.' }}'), $image_embed, $content);
        }

        return $content;
    }

    /**
     * Sends e-mails based on predefined templates. If the $body param
     * has value, the template will be ignored.
     *
     * @param array  $to
     * @param string $subject
     * @param string $name    Template name
     * @param array  $params
     * @param array  $body
     */
    public function send($to, $subject, $name = null, $params = null, $body = null)
    {
        // Create the message
        $message = \Swift_Message::newInstance();

        //Settings
        $mailSettings = $this->config->mail;

        //Images
        if (isset($params['images'])) {
            $this->images = $params['images'];
        }

        if (null === $body) {
            $template = $this->getTemplate($message, $name, $params);
        } else {
            $template = $this->insertImages($message, $body);
        }

         // Setting message params
        $message->setSubject($subject)
            ->setTo($to)
            ->setFrom(array(
                $mailSettings->fromEmail => $mailSettings->fromName,
            ))
            ->setBody($template, 'text/html');

        // Check attachments to add
        foreach ($this->attachments as $file) {
            $message->attach(\Swift_Attachment::newInstance()
                ->setBody($file['content'])
                ->setFilename($file['name'])
                ->setContentType($file['type'])
            );
        }

        if (!$this->_transport) {
            $this->_transport = \Swift_SmtpTransport::newInstance(
                $mailSettings->smtp->server,
                $mailSettings->smtp->port,
                $mailSettings->smtp->security
            )
            ->setUsername($mailSettings->smtp->username)
            ->setPassword($mailSettings->smtp->password);
        }

        // Create the Mailer using your created Transport
        $mailer = \Swift_Mailer::newInstance($this->_transport);
        $result = $mailer->send($message);

        $this->attachments = array();

        return $result;
    }
}
