<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 03.07.15
 * Time: 16:55
 */

namespace BugBundle\Services;


use Symfony\Component\HttpFoundation\Request;

class RequestDecorator
{
    public function emptyToNull(Request $request)
    {
        $params = $request->request->all();
        $this->trace($params);
        foreach ($params as $key => $param) {
            $request->request->set($key, $param);
        }

    }

    public function  trace(&$arr)
    {
        foreach ($arr as &$el) {
            if (is_array($el)) $this->trace($el);
            else if ($el === '') $el = null;

        }
    }

}