<?php
/**
 * Created by PhpStorm.
 * User: emili
 * Date: 02/02/2019
 * Time: 21:39
 */

namespace Inovuerj\Renderer;

use Inovuerj\CORS\CorsMiddleware;

interface PHPRendererInterface
{

  public function setCORS(CorsMiddleware $objCors);

  public function setData($data);

  public function run();
  
}
