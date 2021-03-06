<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2018/7/7
 * Time: 下午8:14
 */

namespace App\Kong\Client\Kong\Routes\Request;

use App\Kong\Basic\Request;

class UploadRequest extends Request
{
    public function getUri()
    {
        return '/routes/' . $this->data['id'];
    }

    public function getMethod()
    {
        return "PATCH";
    }

    public function getName()
    {
        return 'routes.upload';
    }
}