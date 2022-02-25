<?php

namespace App\Notification;

use Mailjet\Client;
use Mailjet\Resources;

class MailContact {
    private $apiKey = "9fb903790da99b1e19d8a483dca7f3e3";
    private $apiKeySecret = "0e21cadf5d85c2240448176b8f345d8d";

    public function send($email, $name, $subject, $content) {
        $mj = new Client($this->apiKey, $this->apiKeySecret, true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $email,
                        'Name' => $name
                    ],
                    'To' => [
                        [
                            'Email' => "boubadevri@gmail.com",
                            'Name' => "SocioDev"
                        ]
                    ],
                    'TemplateID' => 3498962,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content,
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}