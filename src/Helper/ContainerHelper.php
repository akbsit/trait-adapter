<?php namespace Falbar\TraitAdapter\Helper;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Container\Container;

/**
 * Class ContainerHelper
 * @package Falbar\TraitAdapter\Helper
 */
class ContainerHelper
{
    /**
     * @param string|null $sAbstract
     * @param array       $arParamList
     *
     * @return Container|mixed|object
     *
     * @throws BindingResolutionException
     */
    public static function make($sAbstract = null, array $arParamList = [])
    {
        if (is_null($sAbstract)) {
            return Container::getInstance();
        }

        return Container::getInstance()->make($sAbstract, $arParamList);
    }
}
