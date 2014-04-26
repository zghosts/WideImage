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

use WideImage\OperationInterface;
use WideImage\PaletteImage;

/**
 * CopyChannelsPalette operation class
 * 
 * This operation is intended to be used on palette images
 * 
 * @package Internal/Operations
 */
class CopyChannelsPalette implements OperationInterface
{
	/**
	 * Returns an image with only specified channels copied
	 *
     * @param \WideImage\PaletteImage $image
     * @param array                   $channels
     *
     * @return \WideImage\PaletteImage
	 */
	public function execute($image, $channels = null)
	{
		$blank = array('red' => 0, 'green' => 0, 'blue' => 0);
		
		if (isset($channels['alpha'])) {
			unset($channels['alpha']);
		}
		
		$width  = $image->getWidth();
		$height = $image->getHeight();
		$copy   = PaletteImage::create($width, $height);
		
		if ($image->isTransparent()) {
			$otci = $image->getTransparentColor();
			$TRGB = $image->getColorRGB($otci);
			$tci  = $copy->allocateColor($TRGB);
		} else {
			$otci = null;
			$tci  = null;
		}
		
		for ($x = 0; $x < $width; $x++) {
			for ($y = 0; $y < $height; $y++) {
				$ci = $image->getColorAt($x, $y);
				
				if ($ci === $otci) {
					$copy->setColorAt($x, $y, $tci);
					continue;
				}
				
				$RGB = $image->getColorRGB($ci);
				
				$newRGB = $blank;
				
				foreach ($channels as $channel) {
					$newRGB[$channel] = $RGB[$channel];
				}
				
				$color = $copy->getExactColor($newRGB);
				
				if ($color == -1) {
					$color = $copy->allocateColor($newRGB);
				}
				
				$copy->setColorAt($x, $y, $color);
			}
		}
		
		if ($image->isTransparent()) {
			$copy->setTransparentColor($tci);
		}
		
		return $copy;
	}
}
