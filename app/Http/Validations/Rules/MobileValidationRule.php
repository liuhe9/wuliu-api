<?php

namespace App\Http\Validations\Rules;

use Illuminate\Contracts\Validation\Rule;

class MobileValidationRule implements Rule
{
    public function passes($attribute, $value)
    {
        if (strlen($value) !== 11) {
            return false;
        }
        if (!is_numeric($value)) {
            return false;
        }
        // 中国移动
        $cmcc = '((13[4-9])|(147)|(15[0-2,7-9])|(17[8])|(18[2-4,7-8]))[0-9]{8}|(170[5])[0-9]{7}';

        //中国联通
        $uniform = '((13[0-2])|(145)|(15[5-6])|(17[156])|(18[5,6])|(16[6]))[0-9]{8}|(170[4,7-9])[0-9]{7}';

        //电信
        $telecom = '((133)|(149)|(153)|(17[3,7])|(18[0,1,9])|(19[1,9]))[0-9]{8}|(170[0-2])[0-9]{7}';
        $patterns = '/\A%s\z/D';//限定开头和结尾
        return preg_match(sprintf($patterns, $cmcc), $value) || preg_match(sprintf($patterns, $uniform), $value) || preg_match(sprintf($patterns, $telecom), $value);
    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '手机号格式不正确';
    }
}