<?php
	/**
##DOC-SIGNATURE##

    This file is part of WideImage.
		
    WideImage is free software; you can redistribute it and/or modify
    it under the terms of the GNU Lesser General Public License as published by
    the Free Software Foundation; either version 2.1 of the License, or
    (at your option) any later version.
		
    WideImage is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Lesser General Public License for more details.
		
    You should have received a copy of the GNU Lesser General Public License
    along with WideImage; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

    * @package Internal/Mappers
  **/

namespace WideImage\Mapper;

use WideImage\MapperInterface;

/**
 * Mapper class for JPEG files
 * 
 * @package Internal/Mappers
 */
class JPEG implements MapperInterface
{
	public function load($uri)
	{
		return @imagecreatefromjpeg($uri);
	}

    /**
     * @param resource $handle
     * @param string   $uri
     * @param int      $quality
     *
     * @return bool
     */
    public function save($handle, $uri = null, $quality = 100)
	{
		return imagejpeg($handle, $uri, $quality);
	}
}
