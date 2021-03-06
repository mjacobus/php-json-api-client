<?php

namespace Brofist\ApiClient;

interface JsonInterface
{
    /**
     * @param string $path
     * @param array  $data
     * @param array  $options additional options to send with the request, if any
     *
     * @throws Exception
     *
     * @return array
     */
    public function get($path, array $data = [], array $options = []);

    /**
     * @param string $path
     * @param array  $data
     * @param array  $options additional options to send with the request, if any
     *
     * @throws Exception
     *
     * @return array
     */
    public function post($path, array $data = [], array $options = []);

    /**
     * @param string $path
     * @param array  $data
     * @param array  $options additional options to send with the request, if any
     *
     * @throws Exception
     *
     * @return array
     */
    public function put($path, array $data = [], array $options = []);

    /**
     * @param string $path
     * @param array  $data
     * @param array  $options additional options to send with the request, if any
     *
     * @throws Exception
     *
     * @return array
     */
    public function delete($path, array $data = [], array $options = []);

    /**
     * @param string $path
     * @param array  $data
     * @param array  $options additional options to send with the request, if any
     *
     * @throws Exception
     *
     * @return array
     */
    public function patch($path, array $data = [], array $options = []);
}
