<?php

namespace App\Components\Upload;

class Local implements Contracts
{

    /** @var array */
    private $config;

    /**
     * Local constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * 获取 upload token
     * @return string
     */
    public function getToken()
    {
        return '';
    }

    /**
     * 获取上传 action
     * @return string
     */
    public function getAction()
    {
        return $this->config['action'];
    }

    /**
     * 存储
     * @param \Illuminate\Http\Request $request
     * @return array
     * @throws \Exception
     */
    public function storage($request)
    {
        $files = $request->file();
        if (count($files) == 0) {
            throw new \Exception('Not has file');
        }

        /** @var \Illuminate\Http\UploadedFile $file */
        $file = current($files);

        $date = date('Ymd');
        $storagePath = $this->config['root'] . "/{$date}";
        $accessPath = $this->config['url'] . "/{$date}";
        $filename = substr(md5(uniqid()), 8, 16) . ".{$file->guessExtension()}";

        if (!is_dir($storagePath)) {
            mkdir($storagePath, 0777, true);
        }

        $size = getimagesize($file->path());
        $width = $height = 0;
        if ($size) {
            list($width, $height) = $size;
        }

        $body = array(
            'ext' => ".{$file->guessExtension()}",
            'width' => $width,
            'height' => $height,
            'size' => $file->getSize(),
        );

        // move 必须放在最后，否则会导致前面的文件目录失效
        $file->move($storagePath, $filename);

        return array(
            'url' => url("{$accessPath}/{$filename}"),
            'body' => $body,
        );
    }
}