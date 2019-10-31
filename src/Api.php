<?php

namespace Askoldex\Teletant;


use Askoldex\Teletant\Entities\Chat;
use Askoldex\Teletant\Entities\ChatMember;
use Askoldex\Teletant\Entities\ChatMembers;
use Askoldex\Teletant\Entities\File;
use Askoldex\Teletant\Entities\GameHighScores;
use Askoldex\Teletant\Entities\Message;
use Askoldex\Teletant\Entities\Messages;
use Askoldex\Teletant\Entities\Poll;
use Askoldex\Teletant\Entities\StickerSet;
use Askoldex\Teletant\Entities\Updates;
use Askoldex\Teletant\Entities\User;
use Askoldex\Teletant\Entities\UserProfilePhotos;
use Askoldex\Teletant\Exception\TeletantException;
use Askoldex\Teletant\Upload\InputFile;
use Closure;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use Psr\Http\Message\ResponseInterface;

class Api
{
    private $client;

    private $settings;

    private $hook = false;

    private $hook_used = false;

    private $webhook_blacklist = [
        'getUpdates',
        'getChat',
        'getChatAdministrators',
        'getChatMember',
        'getChatMembersCount',
        'getFile',
        'getFileLink',
        'getGameHighScores',
        'getMe',
        'getUserProfilePhotos',
        'getWebhookInfo'
    ];

    public function __construct($settings)
    {
        $this->setSettings($settings);

        $this->setClient(new Client([
            'base_uri' => $this->getApiUrl(),
            'request.options' => [
                'proxy' => $this->getSettings()->getProxy(),
            ],
        ]));
    }

    /**
     * @return string
     */
    private function getApiUrl(): string
    {
        return trim($this->getSettings()->getBaseUri(), '/') . '/bot' . $this->getSettings()->getApiToken() . '/';
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param Client $client
     * @return self
     */
    public function setClient(Client $client): self
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return Settings
     */
    public function getSettings(): Settings
    {
        return $this->settings;
    }

    /**
     * @param Settings $settings
     * @return self
     */
    public function setSettings(Settings $settings): self
    {
        $this->settings = $settings;
        return $this;
    }

    public function closeRequestWithResponse($data)
    {
        if (function_exists('fastcgi_finish_request')) {
            echo $data;
            fastcgi_finish_request();
        } else {
            ob_start();
            header("Connection: close\r\n");
            header("Content-Type: application/json; charset=utf-8");
            echo $data;

            $size = ob_get_length();
            header("Content-Length: " . $size . "\r\n");
            ob_end_flush();
            flush();
        }
    }

    public function hook()
    {
        $this->hook = true;

        return $this;
    }

    private function hookReply($action, $params)
    {
        if (!in_array($action, $this->webhook_blacklist) and !$this->hook_used) {
            $params['method'] = $action;
            $this->closeRequestWithResponse(json_encode($params));
            $this->hook = false;
            $this->hook_used = true;
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $action
     * @param array $params
     * @param bool $multipart
     * @param bool $async
     * @return TeletantHookResponse|TeletantResponse|Closure
     * @throws TeletantException
     */
    public function invokeAction(string $action, array $params = [], bool $multipart = false, bool $async = false)
    {
        if (
            ($this->hook or $this->getSettings()->isHookOnFirstRequest())
            and $this->hookReply($action, $params))
            return new TeletantHookResponse;


        if ($multipart) {
            $params = ['multipart' => $params];
        } else {
            if (array_key_exists('reply_markup', $params)) {
                $params['reply_markup'] = (string)$params['reply_markup'];
            }
            $params = ['form_params' => $params];
        }

        try {
            if ($async) {
                return $this->sendRequestAsync('POST', $action, $params);
            } else {
                try {
                    $response = $this->sendRequest('POST', $action, $params);
                } catch (GuzzleException $e) {
                    throw new TeletantException($e->getMessage(), $e->getCode());
                }
            }
        } catch (RequestException $e) {
            $response = $e->getResponse();

            if (!$response instanceof ResponseInterface) {
                throw new TeletantException($e->getMessage(), $e->getCode());
            }
        }
        return new TeletantResponse($response);

    }


    /**
     * @param string $action
     * @param array $params
     * @param bool $async
     * @return TeletantHookResponse|TeletantResponse|Closure
     * @throws TeletantException
     */
    public function uploadFile(string $action, array $params = [], bool $async = false)
    {
        $multipart = [];
        foreach ($params as $name => $contents) {
            if (!is_resource($contents) && $this->isValidFileOrUrl($name, $contents)) {
                $contents = (new InputFile($contents))->open();
            }

            $multipart[] = [
                'name' => $name,
                'contents' => $contents,
            ];
        }
        return $this->invokeAction($action, $multipart, true, $async);
    }

    public function sendAsync(array $promises, $fulfilled = null, $rejected = null)
    {
        $pool = new Pool($this->getClient(), $promises, [
            'fulfilled' => (is_callable($fulfilled) ? $fulfilled : null),
            'rejected' => (is_callable($rejected) ? $rejected : null)
        ]);
        $promise = $pool->promise();
        $promise->wait();
    }

    /**
     * @param string $method
     * @param string $action
     * @param array $params
     * @return mixed|ResponseInterface
     * @throws GuzzleException
     */
    public function sendRequest(string $method, string $action, array $params)
    {
        return $this->getClient()->request($method, $action, $params);
    }

    public function sendRequestAsync(string $method, string $action, array $params)
    {
        $client = &$this->getClient();
        $promise = function () use ($client, $method, $action, $params) {
            return $client->requestAsync($method, $action, $params);
        };
        return $promise;
    }

    /**
     * @param array $params
     * @return Updates
     * @throws TeletantException
     */
    public function getUpdates(array $params = [])
    {
        return new Updates($this->invokeAction('getUpdates', $params));
    }

    /**
     * @param array $params
     * @return Message
     * @throws TeletantException
     */
    public function sendMessage(array $params)
    {
        return new Message($this->invokeAction('sendMessage', $params));
    }

    /**
     * @param array $params
     * @return TeletantHookResponse|TeletantResponse|Closure
     * @throws TeletantException
     */
    public function sendMessageAsync(array $params)
    {
        return $this->invokeAction('sendMessage', $params, false, true);
    }

    /**
     * @return User
     * @throws TeletantException
     */
    public function getMe()
    {
        return new User($this->invokeAction('getMe'));
    }

    /**
     * @param $params
     * @return Message
     * @throws TeletantException
     */
    public function forwardMessage($params)
    {
        return new Message($this->invokeAction('forwardMessage', $params));
    }

    /**
     * @param $params
     * @return Message
     * @throws TeletantException
     */
    public function sendPhoto($params)
    {
        return new Message($this->uploadFile('sendPhoto', $params));
    }

    /**
     * @param $params
     * @return Message
     * @throws TeletantException
     */
    public function sendAudio($params)
    {
        return new Message($this->uploadFile('sendAudio', $params));
    }

    /**
     * @param $params
     * @return Message
     * @throws TeletantException
     */
    public function sendDocument($params)
    {
        return new Message($this->uploadFile('sendDocument', $params));
    }

    /**
     * @param $params
     * @return Message
     * @throws TeletantException
     */
    public function sendSticker($params)
    {
        return new Message($this->uploadFile('sendSticker', $params));
    }

    /**
     * @param $params
     * @return TeletantResponse
     * @throws TeletantException
     */
    public function createNewStickerSet($params)
    {
        return $this->uploadFile('createNewStickerSet', $params);
    }

    /**
     * @param $params
     * @return TeletantResponse
     * @throws TeletantException
     */
    public function addStickerToSet($params)
    {
        return $this->uploadFile('addStickerToSet', $params);
    }

    /**
     * @param $params
     * @return StickerSet
     * @throws TeletantException
     */
    public function getStickerSet($params)
    {
        return new StickerSet($this->invokeAction('getStickerSet', $params));
    }

    /**
     * @param $params
     * @return TeletantResponse
     * @throws TeletantException
     */
    public function setStickerPositionInSet($params)
    {
        return $this->invokeAction('setStickerPositionInSet', $params);
    }

    /**
     * @param $params
     * @return TeletantResponse
     * @throws TeletantException
     */
    public function deleteStickerFromSet($params)
    {
        return $this->invokeAction('deleteStickerFromSet', $params);
    }

    /**
     * @param $params
     * @return File
     * @throws TeletantException
     */
    public function uploadStickerFile($params)
    {
        return new File($this->uploadFile('uploadStickerFile', $params));
    }

    /**
     * @param $params
     * @return Message
     * @throws TeletantException
     */
    public function sendVideo($params)
    {
        return new Message($this->uploadFile('sendVideo', $params));
    }

    /**
     * @param $params
     * @return Message
     * @throws TeletantException
     */
    public function sendAnimation($params)
    {
        return new Message($this->uploadFile('sendAnimation', $params));
    }

    /**
     * @param $params
     * @return Message
     * @throws TeletantException
     */
    public function sendVoice($params)
    {
        return new Message($this->uploadFile('sendVoice', $params));
    }

    /**
     * @param $params
     * @return Message
     * @throws TeletantException
     */
    public function sendVideoNote($params)
    {
        return new Message($this->uploadFile('sendVideoNote', $params));
    }

    /**
     * @param $params
     * @return Messages
     * @throws TeletantException
     */
    public function sendMediaGroup($params)
    {
        return new Messages($this->uploadFile('sendMediaGroup', $params));

    }

    /**
     * @param $params
     * @return Message
     * @throws TeletantException
     */
    public function sendLocation($params)
    {
        return new Message($this->invokeAction('sendLocation', $params));
    }

    /**
     * @param $params
     * @return Message
     * @throws TeletantException
     */
    public function editMessageLiveLocation($params)
    {
        return new Message($this->invokeAction('editMessageLiveLocation', $params));
    }

    /**
     * @param $params
     * @return Message
     * @throws TeletantException
     */
    public function stopMessageLiveLocation($params)
    {
        return new Message($this->invokeAction('stopMessageLiveLocation', $params));
    }

    /**
     * @param $params
     * @return Message
     * @throws TeletantException
     */
    public function sendVenue($params)
    {
        return new Message($this->invokeAction('sendVenue', $params));
    }

    /**
     * @param $params
     * @return TeletantHookResponse|TeletantResponse|Closure
     * @throws TeletantException
     */
    public function sendContact($params)
    {
        return $this->invokeAction('sendContact', $params);
    }

    /**
     * @param $params
     * @return Message
     * @throws TeletantException
     */
    public function sendPoll($params)
    {
        return new Message($this->invokeAction('sendPoll', $params));
    }

    /**
     * @param $params
     * @return TeletantHookResponse|TeletantResponse|Closure
     * @throws TeletantException
     */
    public function sendChatAction($params)
    {
        return $this->invokeAction('sendChatAction', $params);
    }

    /**
     * @param $params
     * @return UserProfilePhotos
     * @throws TeletantException
     */
    public function getUserProfilePhotos($params)
    {
        return new UserProfilePhotos($this->invokeAction('getUserProfilePhotos', $params));
    }

    /**
     * @param $params
     * @return File
     * @throws TeletantException
     */
    public function getFile($params)
    {
        return new File($this->invokeAction('getFile', $params));
    }

    /**
     * @param $params
     * @return TeletantHookResponse|TeletantResponse|Closure
     * @throws TeletantException
     */
    public function kickChatMember($params)
    {
        return $this->invokeAction('kickChatMember', $params);
    }

    /**
     * @param $params
     * @return TeletantHookResponse|TeletantResponse|Closure
     * @throws TeletantException
     */
    public function unbanChatMember($params)
    {
        return $this->invokeAction('unbanChatMember', $params);
    }

    /**
     * @param $params
     * @return TeletantHookResponse|TeletantResponse|Closure
     * @throws TeletantException
     */
    public function restrictChatMember($params)
    {
        return $this->invokeAction('restrictChatMember', $params);
    }

    /**
     * @param $params
     * @return TeletantHookResponse|TeletantResponse|Closure
     * @throws TeletantException
     */
    public function promoteChatMember($params)
    {
        return $this->invokeAction('promoteChatMember', $params);
    }

    /**
     * @param $params
     * @return TeletantHookResponse|TeletantResponse|Closure
     * @throws TeletantException
     */
    public function exportChatInviteLink($params)
    {
        return $this->invokeAction('exportChatInviteLink', $params);
    }

    /**
     * @param $params
     * @return TeletantHookResponse|TeletantResponse|Closure
     * @throws TeletantException
     */
    public function setChatPhoto($params)
    {
        return $this->uploadFile('setChatPhoto', $params);
    }

    /**
     * @param $params
     * @return TeletantHookResponse|TeletantResponse|Closure
     * @throws TeletantException
     */
    public function deleteChatPhoto($params)
    {
        return $this->invokeAction('deleteChatPhoto', $params);
    }

    /**
     * @param $params
     * @return TeletantHookResponse|TeletantResponse|Closure
     * @throws TeletantException
     */
    public function setChatTitle($params)
    {
        return $this->invokeAction('setChatTitle', $params);
    }

    /**
     * @param $params
     * @return TeletantHookResponse|TeletantResponse|Closure
     * @throws TeletantException
     */
    public function setChatDescription($params)
    {
        return $this->invokeAction('setChatDescription', $params);
    }

    /**
     * @param $params
     * @return TeletantHookResponse|TeletantResponse|Closure
     * @throws TeletantException
     */
    public function pinChatMessage($params)
    {
        return $this->invokeAction('pinChatMessage', $params);
    }

    /**
     * @param $params
     * @return TeletantHookResponse|TeletantResponse|Closure
     * @throws TeletantException
     */
    public function unpinChatMessage($params)
    {
        return $this->invokeAction('unpinChatMessage', $params);
    }

    /**
     * @param $params
     * @return TeletantHookResponse|TeletantResponse|Closure
     * @throws TeletantException
     */
    public function leaveChat($params)
    {
        return $this->invokeAction('leaveChat', $params);
    }

    /**
     * @param $params
     * @return Chat
     * @throws TeletantException
     */
    public function getChat($params)
    {
        return new Chat($this->invokeAction('getChat', $params));
    }

    /**
     * @param $params
     * @return ChatMembers
     * @throws TeletantException
     */
    public function getChatAdministrators($params)
    {
        return new ChatMembers($this->invokeAction('getChatAdministrators', $params));
    }

    /**
     * @param $params
     * @return TeletantHookResponse|TeletantResponse|Closure
     * @throws TeletantException
     */
    public function getChatMembersCount($params)
    {
        return $this->invokeAction('getChatMembersCount', $params);
    }

    /**
     * @param $params
     * @return ChatMember
     * @throws TeletantException
     */
    public function getChatMember($params)
    {
        return new ChatMember($this->invokeAction('getChatMember', $params));
    }

    /**
     * @param $params
     * @return TeletantHookResponse|TeletantResponse|Closure
     * @throws TeletantException
     */
    public function setChatStickerSet($params)
    {
        return $this->invokeAction('setChatStickerSet', $params);
    }

    /**
     * @param $params
     * @return TeletantHookResponse|TeletantResponse|Closure
     * @throws TeletantException
     */
    public function deleteChatStickerSet($params)
    {
        return $this->invokeAction('deleteChatStickerSet', $params);
    }

    /**
     * @param $params
     * @return TeletantHookResponse|TeletantResponse|Closure
     * @throws TeletantException
     */
    public function answerCallbackQuery($params)
    {
        return $this->invokeAction('answerCallbackQuery', $params);
    }

    /**
     * @param $params
     * @return Message
     * @throws TeletantException
     */
    public function editMessageText($params)
    {
        return new Message($this->invokeAction('editMessageText', $params));
    }

    /**
     * @param $params
     * @return Message
     * @throws TeletantException
     */
    public function editMessageCaption($params)
    {
        return new Message($this->invokeAction('editMessageCaption', $params));
    }

    /**
     * @param $params
     * @return Message
     * @throws TeletantException
     */
    public function editMessageMedia($params)
    {
        return new Message($this->invokeAction('editMessageMedia', $params));
    }

    /**
     * @param $params
     * @return Message
     * @throws TeletantException
     */
    public function editMessageReplyMarkup($params)
    {
        return new Message($this->invokeAction('editMessageReplyMarkup', $params));
    }

    /**
     * @param $params
     * @return Poll
     * @throws TeletantException
     */
    public function stopPoll($params)
    {
        return new Poll($this->invokeAction('stopPoll', $params));
    }

    /**
     * @param $params
     * @return TeletantHookResponse|TeletantResponse|Closure
     * @throws TeletantException
     */
    public function deleteMessage($params)
    {
        return $this->invokeAction('deleteMessage', $params);
    }

    /**
     * @param $params
     * @return TeletantHookResponse|TeletantResponse|Closure
     * @throws TeletantException
     */
    public function answerInlineQuery($params)
    {
        return $this->invokeAction('answerInlineQuery', $params);
    }

    /**
     * @param $params
     * @return Message
     * @throws TeletantException
     */
    public function sendInvoice($params)
    {
        return new Message($this->invokeAction('sendInvoice', $params));
    }

    /**
     * @param $params
     * @return TeletantHookResponse|TeletantResponse|Closure
     * @throws TeletantException
     */
    public function answerShippingQuery($params)
    {
        return $this->invokeAction('answerShippingQuery', $params);
    }

    /**
     * @param $params
     * @return TeletantHookResponse|TeletantResponse|Closure
     * @throws TeletantException
     */
    public function answerPreCheckoutQuery($params)
    {
        return $this->invokeAction('answerPreCheckoutQuery', $params);
    }

    /**
     * @param $params
     * @return TeletantHookResponse|TeletantResponse|Closure
     * @throws TeletantException
     */
    public function setPassportDataErrors($params)
    {
        return $this->invokeAction('setPassportDataErrors', $params);
    }

    /**
     * @param $params
     * @return Message
     * @throws TeletantException
     */
    public function sendGame($params)
    {
        return new Message($this->invokeAction('sendGame', $params));
    }

    /**
     * @param $params
     * @return Message
     * @throws TeletantException
     */
    public function setGameScore($params)
    {
        return new Message($this->invokeAction('setGameScore', $params));
    }

    /**
     * @param $params
     * @return GameHighScores
     * @throws TeletantException
     */
    public function getGameHighScores($params)
    {
        return new GameHighScores($this->invokeAction('getGameHighScores', $params));
    }


    protected function isValidFileOrUrl($name, $contents)
    {
        //Don't try to open a url as an actual file when using this method to setWebhook.
        if ($name == 'url') {
            return false;
        }
        //If a certificate name is passed, we must check for the file existing on the local server,
        // otherwise telegram ignores the fact it wasn't sent and no error is thrown.
        if ($name == 'certificate') {
            return true;
        }
        //Is the content a valid file name.
        if (is_readable($contents)) {
            return true;
        }
        //Is the content a valid URL
        return filter_var($contents, FILTER_VALIDATE_URL);
    }


}