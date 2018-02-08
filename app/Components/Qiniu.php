<?php

namespace App\Components;

use Qiniu\Auth;
use Qiniu\Storage\BucketManager;

class Qiniu
{
    /** @var Auth */
    private $auth;

    /** @var BucketManager */
    private $bucketMgr;

    /** @var string */
    private $privateBucket;

    /** @var string */
    private $publicBucket;

    /**
     * Qiniu constructor.
     */
    public function __construct()
    {
        $this->auth = new Auth(config('global.qiniu.access_key'), config('global.qiniu.secret_key'));
        $this->bucketMgr = new BucketManager($this->auth);
        $this->privateBucket = config('global.qiniu.private_bucket');
        $this->publicBucket = config('global.qiniu.public_bucket');
    }

    /**
     * 获取token
     * @param $cbkUrl
     * @return string
     */
    public function token($cbkUrl)
    {
        $policy = array(
            'callbackUrl' => $cbkUrl,
            'callbackBody' => json_encode(array(
                'key' => '$(key)',
                'etag' => '$(etag)',
                'ext' => '$(ext)',
                'bucket' => '$(bucket)',
                'mimeType' => '$(mimeType)',// 资源类型，例如JPG图片的资源类型为image/jpg
                'name' => '$(fname)',// 上传的原始文件名
                'size' => '$(fsize)',// 资源尺寸，单位为字节
                'width' => '$(imageInfo.width)',
                'height' => '$(imageInfo.height)',
            ))
        );

        return $this->auth->uploadToken($this->privateBucket, null, 3600, $policy);
    }

    /**
     * 验证回调
     * @param $contentType
     * @param $authorization
     * @param $url
     * @param $callbackBody
     * @return bool
     */
    public function verify($contentType, $authorization, $url, $callbackBody)
    {
        if ($this->auth->verifyCallback($contentType, $authorization, $url, $callbackBody)) {
            // 验证body中4个必要的参数
            $callbackBodyHash = json_decode($callbackBody, true);
            $requireParams = array('key', 'etag', 'ext', 'bucket');
            foreach ($requireParams as $requireParam) {
                if (empty($callbackBodyHash[$requireParam])) {
                    return false;
                }
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * 移到公开空间，并返回url
     * @param array $callbackBodyHash
     * @return string
     */
    public function moveToPublic(array $callbackBodyHash)
    {
        // 组装etag+后缀
        $key = $callbackBodyHash['etag'] . $callbackBodyHash['ext'];

        // 判断在公开bucket中是否存在
        list($file) = $this->bucketMgr->stat($this->publicBucket, $key);

        if ($file === null) {
            // 移到公开环境
            $bucket = $callbackBodyHash['bucket'];
            $this->bucketMgr->move($bucket, $callbackBodyHash['key'], $this->publicBucket, $key);
        }

        return $this->getUrl($key);
    }

    /**
     * 返回资源地址
     * @param $key
     * @return string
     */
    public function getUrl($key)
    {
        return 'http://' . config('global.qiniu.public_domain') . '/' . $key;
    }

}