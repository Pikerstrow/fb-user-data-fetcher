<?php declare(strict_types=1);
/**
 * @author Oleksandr Mishchuk <pikerstrow@gmail.com>
 * @copyright Copyright (c) 2019 Oleksandr Mishchuk
 */

namespace Pikerstrow\FBUserDataFetcher\HTTPClient;

use Pikerstrow\FBUserDataFetcher\Exceptions\FBFetcherException;


class FBHttpClient
{
    protected $resource;

    /**
     * FBHttpClient constructor.
     */
    public function __construct()
    {
        $this->resource = curl_init();
        curl_setopt($this->resource, CURLOPT_RETURNTRANSFER, 1);
    }

    /**
     * @param string $url
     * @return bool|string|null
     * @throws FBFetcherException
     */
    public function fetch(string $url)
    {
        try {
            curl_setopt($this->resource, CURLOPT_URL, $url);
            $user = curl_exec($this->resource);
            return $user;
        } catch (Throwable $e) {
            throw new FBFetcherException('Fetching data failed. Error ' . $e->getMessage());
        }
    }


    /**
     *
     */
    public function close(): void
    {
        curl_close($this->resource);
    }
}