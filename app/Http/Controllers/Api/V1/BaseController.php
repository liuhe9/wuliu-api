<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    // 接口帮助调用
    use Helpers;

    public function __construct(Request $request)
    {
        $this->validateRequest($request);
    }

    protected function validateRequest(Request $request, $name = null)
    {
        if (! $validator = $this->getValidator($request, $name) ) {
            return;
        }

        $rules    = array_get($validator, 'rules', []);
        $messages = array_get($validator, 'messages', []);
        $this->validate($request, $rules, $messages);
    }

    protected function getValidator(Request $request, $name = null)
    {
        list($controller, $method) = explode('@', $request->route()[1]['uses']);
        $method = $name ?: $method;
        $namespace = 'App\Http\Validations';
        $class  = str_replace($request->route()[1]['namespace'], $namespace, $controller);
        $class  = str_replace('Controller', 'Validation', $class);
        if (! class_exists($class) || ! method_exists($class, $method)) {
            return false;
        }

        return call_user_func([new $class, $method], $request);
    }

    // 返回错误的请求
    protected function errorBadRequest($validator)
    {
        // github like error messages
        // if you don't like this you can use code bellow
        //
        //throw new ValidationHttpException($validator->errors());

        $result = [];
        $messages = $validator->errors()->toArray();

        if ($messages) {
            foreach ($messages as $field => $errors) {
                foreach ($errors as $error) {
                    $result[] = [
                        'field' => $field,
                        'code' => $error,
                    ];
                }
            }
        }

        throw new ValidationHttpException($result);
    }

}
