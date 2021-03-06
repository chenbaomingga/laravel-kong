<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2018/7/4
 * Time: 下午6:39
 */

namespace App\Src\Form\User;


use App\Src\Basic\Form;

class UserInfoForm extends Form
{
    public function rules()
    {
        return [
            'id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute必填。',
        ];
    }

    public function attributes()
    {
        return [
            'id' => '用户Id',
        ];
    }

    public function validation()
    {
    }
}