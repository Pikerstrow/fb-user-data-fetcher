<?php declare(strict_types=1);
/**
 * @author Oleksandr Mishchuk <pikerstrow@gmail.com>
 * @copyright Copyright (c) 2019 Oleksandr Mishchuk
 */

namespace Pikerstrow\FBUserDataFetcher;

use Pikerstrow\FBUserDataFetcher\HTTPClient\FBHttpClient;
use Pikerstrow\FBUserDataFetcher\Exceptions\FBFetcherException;


/**
 * Class FBUserDataFetcher
 * @package App\Helpers
 */
class FBUserDataFetcher
{
    protected $user_data_for_fetching = array();
    protected $fb_base_url;
    protected $fb_token;
    protected $http_client;
    protected $url_for_request;


    /**
     * FBUserDataFetcher constructor.
     */
    public function __construct()
    {
        $this->http_client = new FBHttpClient();
    }


    /**
     * @param string $url
     * @return $this
     */
    public function url(string $url): self
    {
        $this->fb_base_url = $url;
        $this->url_for_request = $url . '?fields=';
        return $this;
    }

    /**
     * @param string $token
     * @return $this
     */
    public function token(string $token): self
    {
        $this->fb_token = $token;
        return $this;
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function fields(array $fields = ['id', 'name']): self
    {
        $this->user_data_for_fetching = $fields;
        return $this;
    }


    /**
     * @throws FBFetcherException
     */
    public function prepare(): void
    {
        if ($this->checkPossibility()) {
            foreach ($this->user_data_for_fetching as $key => $value) {
                if ($key === 'picture' and is_array($value)) {
                    if ($key === array_key_last($this->user_data_for_fetching)) {
                        $this->url_for_request .= 'picture.width(' . $value['width'] . ').height(' . $value['height'] . ')';
                    } else {
                        $this->url_for_request .= 'picture.width(' . $value['width'] . ').height(' . $value['height'] . '),';
                    }
                } else if ($key === array_key_last($this->user_data_for_fetching)) {
                    $this->url_for_request .= $value;
                } else {
                    $this->url_for_request .= $value . ',';
                }
            }
            $this->url_for_request .= '&access_token=' . $this->fb_token;
        } else {
            throw new FBFetcherException('url and token must be set before request');
        }
    }


    /**
     * @return bool
     */
    protected function checkPossibility(): bool
    {
        return ($this->fb_token and $this->fb_base_url);
    }


    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws FBFetcherException
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->http_client, $name)) {
            if ($name === 'fetch') {
                $this->prepare();
                return $this->http_client->$name($this->url_for_request);
            } else {
                return $this->http_client->$name($arguments);
            }
        }
        return null;
    }

}