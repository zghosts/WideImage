<?php
/**
 * Created by PhpStorm.
 * User: jos
 * Date: 4/25/14
 * Time: 10:48 PM
 */

namespace WideImage;


interface OperationInterface {
    /**
     * @param \WideImage\Image $image
     *
     * @return mixed
     */
    public function execute($image);
}
