<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
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
}
