<?php
/**
 * Created by PhpStorm.
 * User: emili
 * Date: 02/02/2019
 * Time: 21:39
 */

namespace Inovuerj\Renderer;

interface PHPRendererInterface
{
    public function setData($data);
    public function run();
}
