<?php
namespace Concrete\Core\Routing;

use Concrete\Core\Http\Request;

class RedirectResponse extends \Symfony\Component\HttpFoundation\RedirectResponse
{
    protected $request;

    public function __construct($url, $status = 302, $headers = array())
    {
        $url = (string)$url; // sometimes we get an object.
        parent::__construct($url, $status, $headers);
    }

    /**
     * @deprecated This method doesn't actually do anything.
     * @param \Concrete\Core\Http\Request $r
     */
    public function setRequest(Request $r)
    {
    }

    public function setTargetUrl($url)
    {
        if ($url == '') {
            $url = '/';
        }

        return parent::setTargetUrl($url); // TODO: Change the autogenerated stub
    }
}
