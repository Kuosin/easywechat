<?php

declare(strict_types=1);

namespace EasyWeChat\Work\Message;

use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\Exceptions\RuntimeException;
use EasyWeChat\Kernel\Messages\Message;
use EasyWeChat\Kernel\Messages\Text;

class Messenger
{
    /**
     * @var \EasyWeChat\Kernel\Messages\Message;
     */
    protected $message;

    /**
     * @var array
     */
    protected array $to = ['touser' => '@all'];

    /**
     * @var int
     */
    protected $agentId;

    /**
     * @var bool
     */
    protected $secretive = false;

    /**
     * @var \EasyWeChat\Work\Message\Client
     */
    protected $client;

    /**
     * @param \EasyWeChat\Work\Message\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Set message to send.
     *
     * @param string|Message $message
     *
     * @return \EasyWeChat\Work\Message\Messenger
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     */
    public function message($message)
    {
        if (is_string($message) || is_numeric($message)) {
            $message = new Text((string) $message);
        }

        if (!($message instanceof Message)) {
            throw new InvalidArgumentException('Invalid message.');
        }

        $this->message = $message;

        return $this;
    }

    /**
     * @param int $agentId
     *
     * @return \EasyWeChat\Work\Message\Messenger
     */
    public function ofAgent(int $agentId)
    {
        $this->agentId = $agentId;

        return $this;
    }

    /**
     * @param array|string $userIds
     *
     * @return \EasyWeChat\Work\Message\Messenger
     */
    public function toUser($userIds)
    {
        return $this->setRecipients($userIds, 'touser');
    }

    /**
     * @param array|string $partyIds
     *
     * @return \EasyWeChat\Work\Message\Messenger
     */
    public function toParty($partyIds)
    {
        return $this->setRecipients($partyIds, 'toparty');
    }

    /**
     * @param array|string $tagIds
     *
     * @return \EasyWeChat\Work\Message\Messenger
     */
    public function toTag($tagIds)
    {
        return $this->setRecipients($tagIds, 'totag');
    }

    /**
     * Keep secret.
     *
     * @return \EasyWeChat\Work\Message\Messenger
     */
    public function secretive()
    {
        $this->secretive = true;

        return $this;
    }

    /**
     * @param array|string $ids
     * @param string       $key
     *
     * @return \EasyWeChat\Work\Message\Messenger
     */
    protected function setRecipients($ids, string $key): self
    {
        if (is_array($ids)) {
            $ids = implode('|', $ids);
        }

        $this->to = [$key => $ids];

        return $this;
    }

    /**
     * @param \EasyWeChat\Kernel\Messages\Message|string|null $message
     *
     * @return mixed
     *
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     */
    public function send($message = null)
    {
        if ($message) {
            $this->message($message);
        }

        if (empty($this->message)) {
            throw new RuntimeException('No message to send.');
        }

        if (is_null($this->agentId)) {
            throw new RuntimeException('No agentid specified.');
        }

        $message = $this->message->transformForJsonRequest(array_merge([
            'agentid' => $this->agentId,
            'safe' => intval($this->secretive),
        ], $this->to));

        $this->secretive = false;

        return $this->client->send($message);
    }

    /**
     * Return property.
     *
     * @param string $property
     *
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

        throw new InvalidArgumentException(sprintf('No property named "%s"', $property));
    }
}
