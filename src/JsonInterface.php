<?php

namespace Brofist\ApiClient;

interface JsonInterface
{
    /**
     * @param string $path
     * @param array $data
     *
     * @throws Exception
     *
     * @return array
     */
    public function get($path, array $data = []);

    /**
     * @param string $path
     * @param array $data
     *
     * @throws Exception
     *
     * @return array
     */
    public function post($endpoint, array $data = []);

    /**
     * @param string $path
     * @param array $data
     *
     * @throws Exception
     *
     * @return array
     */
    public function put($path, array $data = []);

    /**
     * @param string $path
     * @param array $data
     *
     * @throws Exception
     *
     * @return array
     */
    public function delete($path, array $data = []);

    /**
     * @param string $path
     * @param array $data
     *
     * @throws Exception
     *
     * @return array
     */
    public function patch($path, array $data = []);
}
