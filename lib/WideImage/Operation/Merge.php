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

namespace WIdeImage\Operation;

use WideImage\Coordinate;
use WideImage\Exception\GDFunctionResultException;
use WideImage\OperationInterface;

/**
 * Merge operation class
 * 
 * @package Internal/Operations
 */
class Merge implements OperationInterface
{
    /**
     * Returns a merged image
     *
     * @param \WideImage\Image $image
     * @param \WideImage\Image $overlay
     * @param int              $left
     * @param int              $top
     * @param int              $pct
     *
     * @throws \WideImage\Exception\GDFunctionResultException
     * @return \WideImage\Image
     */
	public function execute($image, $overlay = null, $left = 0, $top = 0, $pct = 0)
	{
		$x = Coordinate::fix($left, $image->getWidth(), $overlay->getWidth());
		$y = Coordinate::fix($top, $image->getHeight(), $overlay->getHeight());
		
		$result = $image->asTrueColor();
		$result->alphaBlending(true);
		$result->saveAlpha(true);
		
		if ($pct <= 0) {
			return $result;
		}
		
		if ($pct < 100) {
			if (!imagecopymerge(
				$result->getHandle(), 
				$overlay->getHandle(), 
				$x, $y, 0, 0, 
				$overlay->getWidth(), 
				$overlay->getHeight(), 
				$pct)) {
					throw new GDFunctionResultException("imagecopymerge() returned false");
			}
		} else {
			if (!imagecopy(
				$result->getHandle(), 
				$overlay->getHandle(), 
				$x, $y, 0, 0, 
				$overlay->getWidth(), 
				$overlay->getHeight())) {
					throw new GDFunctionResultException("imagecopy() returned false");
			}
		}
		
		return $result;
	}
}
