<?php
/**
 * Created by PhpStorm.
 * User: jos
 * Date: 4/26/14
 * Time: 12:06 AM
 */

namespace WideImage;


interface MapperInterface {
    public function load($uri);

    //public function loadFromString($data);

    public function save($handle, $uri = null);
}