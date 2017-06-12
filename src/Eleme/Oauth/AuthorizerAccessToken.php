<?php

namespace Laraver\Waimai\Eleme\Oauth;

use Laraver\Waimai\Eleme\Core\AccessToken as CoreAccessToken;
use Laraver\Waimai\Eleme\Core\Api as CoreApi;
use Symfony\Component\HttpFoundation\Request;

class AuthorizerAccessToken extends CoreAccessToken
{
    private $scope;

    /**
     * @var Request
     */
    protected $request;

    protected $refreshToken;

    private $redirectUri;

    /**
     * Constructor.
     *
     * @param $appId
     * @param $secret
     * @param bool $debug
     * @param bool $request
     */
    public function __construct($appId, $secret, $request, $debug)
    {
        parent::__construct($appId, $secret, $debug);

        $this->scope = 'all';
        $this->request = $request;
    }

    public function getTokenFromServer($authCode = null)
    {
        $response = (new CoreApi($this))->getHttp()->post($this->getUrl().'/token', [
            'grant_type'   => 'authorization_code',
            'client_id'    => $this->getAppId(),
            'code'         => $authCode ?: $this->request->get('code'),
            'redirect_uri' => $this->request->getUri(),
        ], true);

        return $response;
    }

    /**
     * @return mixed
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param mixed $redirectUri
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
    }

    /**
     * @return mixed
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }
}
