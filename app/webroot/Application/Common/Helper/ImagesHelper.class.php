<?php
/**
 * Created by PhpStorm.
 * User: zhaojunlike
 * Date: 2016/12/15
 * Time: 20:06
 */

namespace Common\Helper;


class ImagesHelper
{

    /**
     * @param $input
     */
    public function StreamToImages($data)
    {

        return imagecreatefromstring($data);

    }

}