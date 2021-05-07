<?php

declare(strict_types=1);

namespace EasyWeChat\MiniProgram\TemplateMessage;

use EasyWeChat\OfficialAccount\TemplateMessage\Client as BaseClient;

class Client extends BaseClient
{
    public const API_SEND = 'cgi-bin/message/wxopen/template/send';

    protected array $message = [
        'touser' => '',
        'template_id' => '',
        'page' => '',
        'form_id' => '',
        'data' => [],
        'emphasis_keyword' => '',
    ];

    protected array $required = ['touser', 'template_id', 'form_id'];

    /**
     * @param int $offset
     * @param int $count
     *
     * @return \Psr\Http\Message\ResponseInterface|\EasyWeChat\Kernel\Support\Collection|array|object|string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function list(int $offset, int $count)
    {
        return $this->httpPostJson('cgi-bin/wxopen/template/library/list', compact('offset', 'count'));
    }

    /**
     * @param string $id
     *
     * @return \Psr\Http\Message\ResponseInterface|\EasyWeChat\Kernel\Support\Collection|array|object|string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get(string $id)
    {
        return $this->httpPostJson('cgi-bin/wxopen/template/library/get', compact('id'));
    }

    /**
     * @param string $id
     * @param array  $keyword
     *
     * @return \Psr\Http\Message\ResponseInterface|\EasyWeChat\Kernel\Support\Collection|array|object|string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function add(string $id, array $keyword)
    {
        return $this->httpPostJson('cgi-bin/wxopen/template/add', [
            'id' => $id,
            'keyword_id_list' => $keyword,
        ]);
    }

    /**
     * @param string $templateId
     *
     * @return \Psr\Http\Message\ResponseInterface|\EasyWeChat\Kernel\Support\Collection|array|object|string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete(string $templateId)
    {
        return $this->httpPostJson('cgi-bin/wxopen/template/del', [
            'template_id' => $templateId,
        ]);
    }

    /**
     * @param int $offset
     * @param int $count
     *
     * @return \Psr\Http\Message\ResponseInterface|\EasyWeChat\Kernel\Support\Collection|array|object|string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTemplates(int $offset, int $count)
    {
        return $this->httpPostJson('cgi-bin/wxopen/template/list', compact('offset', 'count'));
    }
}
