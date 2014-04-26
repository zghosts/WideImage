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

    * @package Internal/Operations
  **/

namespace WideImage\Operation;

use WideImage\Coordinate;
use WideImage\Exception\Exception;
use WideImage\Exception\GDFunctionResultException;
use WideImage\OperationInterface;
use WideImage\PaletteImage;

/**
 * Crop operation class
 * 
 * @package Internal/Operations
 */
class Crop implements OperationInterface
{
    /**
     * Returns a cropped image
     *
     * @param \WideImage\Image $image
     * @param integer          $left
     * @param integer          $top
     * @param integer          $width
     * @param integer          $height
     *
     * @throws \WideImage\Exception\GDFunctionResultException
     * @throws \WideImage\Exception\Exception
     * @return \WideImage\Image
     */
	public function execute($image, $left = 0, $top = 0, $width = 0, $height = 0)
	{
		$width  = Coordinate::fix($width, $image->getWidth(), $width);
		$height = Coordinate::fix($height, $image->getHeight(), $height);
		$left   = Coordinate::fix($left, $image->getWidth(), $width);
		$top    = Coordinate::fix($top, $image->getHeight(), $height);
		
		if ($left < 0) {
			$width = $left + $width;
			$left  = 0;
		}
		
		if ($width > $image->getWidth() - $left) {
			$width = $image->getWidth() - $left;
		}
		
		if ($top < 0) {
			$height = $top + $height;
			$top    = 0;
		}
		
		if ($height > $image->getHeight() - $top) {
			$height = $image->getHeight() - $top;
		}
		
		if ($width <= 0 || $height <= 0) {
			throw new Exception("Can't crop outside of an image.");
		}
		
		$new = $image->doCreate($width, $height);
		
		if ($image->isTransparent() || $image instanceof PaletteImage) {
			$new->copyTransparencyFrom($image);
			
			if (!imagecopyresized($new->getHandle(), $image->getHandle(), 0, 0, $left, $top, $width, $height, $width, $height)) {
				throw new GDFunctionResultException("imagecopyresized() returned false");
			}
		} else {
			$new->alphaBlending(false);
			$new->saveAlpha(true);
			
			if (!imagecopyresampled($new->getHandle(), $image->getHandle(), 0, 0, $left, $top, $width, $height, $width, $height)) {
				throw new GDFunctionResultException("imagecopyresampled() returned false");
			}
		}
		
		return $new;
	}
}
