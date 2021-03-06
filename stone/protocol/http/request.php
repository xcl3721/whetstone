<?php

namespace WhetStone\Stone\Protocol\Http;

/**
 * Http协议请求对象封装
 * Class Http
 * @package WhetStone\Stone\Protocol\Http\Request
 */
class Request
{

    /**
     * @var \Swoole\Http\Request
     */
    private $request = null;

    private $request_uri = null;

    private $request_uri_param = array();

    public function __construct(\Swoole\Http\Request $request)
    {
        $this->request = $request;
        $this->request_uri = $this->getServer("request_uri");
    }

    /**
     * 获取Header信息
     * @param string $name 不指定获取所有header，指定获取指定key的header
     * @return string
     */
    public function getHeader($name = '')
    {
        if ($name == '') {
            return $this->request->header;
        }

        if (!isset($this->request->header[$name])) {
            return '';
        }

        return $this->request->header[$name];
    }

    /**
     * 获取Server信息
     * @param string $name 不指定获取所有server，指定获取指定key的server
     * @return string
     */
    public function getServer($name = '')
    {
        if ($name == '') {
            return $this->request->server;
        }

        if (!isset($this->request->server[$name])) {
            return '';
        }

        return $this->request->server[$name];
    }

    /**
     * 设置url中带的参数信息
     * @param array $param
     */
    public function setRequestUrlParam(array $param){
        $this->request_uri_param = $param;
    }

    /**
     * 获取网址内所带参数
     * @return array
     */
    public function getRequestUrlParam(){
        return $this->request_uri_param;
    }

    /**
     * 获取请求域名后路径如URi
     * @return string
     */
    public function getUri(){
        return $this->getServer("request_uri");
    }

    /**
     * 请求method获取，如post get
     * @return string
     */
    public function getMethod(){
        return $this->getServer("request_method");
    }

    /**
     * 获取Get内容
     * @param string $name 不指定获取所有get，指定获取指定key的get
     * @return string
     */
    public function getGet($name = '')
    {
        if ($name == '') {
            return $this->request->get;
        }

        if (!isset($this->request->get[$name])) {
            return '';
        }

        return $this->request->get[$name];
    }

    /**
     * 获取Post内容
     * @param string $name 不指定获取所有post，指定获取指定key的post
     * @return string
     */
    public function getPost($name = '')
    {
        if ($name == '') {
            return $this->request->post;
        }

        if (!isset($this->request->post[$name])) {
            return '';
        }

        return $this->request->post[$name];
    }

    /**
     * 获取cookie
     * @param string $name 不指定获取所有cookie，指定获取指定key的cookie
     * @return string
     */
    public function getCookie($name = '')
    {

        if ($name == '') {
            return $this->request->cookie;
        }

        if (!isset($this->request->cookie[$name])) {
            return '';
        }

        return $this->request->cookie[$name];

    }

    /**
     * 获取所有上传文件
     * @return mixed
     */
    public function getUploadFiles()
    {
        return $this->request->files;
    }

    /**
     * 获取原始body，不做任何解析
     * @return mixed
     */
    public function getRawBody()
    {
        return $this->request->rawContent();
    }

    /**
     * 获取原始请求报文 header+body
     * @return mixed
     */
    public function getRawRequest()
    {
        return $this->request->getData();
    }

    /**
     * 获取当前请求fd
     * @return int
     */
    public function getFd()
    {
        return $this->request->fd;
    }
}