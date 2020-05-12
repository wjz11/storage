<?php
/*
 * PHP QR Code encoder
 *
 * Image output of code using GD2
 *
 * PHP QR Code is distributed under LGPL 3
 * Copyright (C) 2010 Dominik Dzienia <deltalab at poczta dot fm>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */
 
    define('QR_IMAGE', true);

    class QRimage {
    
        //----------------------------------------------------------------------
        public static function png($frame, $filename = false, $pixelPerPoint = 4, $outerFrame = 4, $saveandprint = false, $r, $g, $b,$centerfile,$bgfile,$w,$x,$y) 
        {
            $image = self::image($frame, $pixelPerPoint, $outerFrame, $r, $g, $b,$centerfile,$bgfile,$w,$x,$y);
            
            if ($filename === false) {
                Header("Content-type: image/png");
                ImagePng($image);
            } else {
                if($saveandprint===TRUE){
                    ImagePng($image, $filename);
                    header("Content-type: image/png");
                    ImagePng($image);
                }else{
                    ImagePng($image, $filename);
                }
            }
            
            ImageDestroy($image);
        }
    
        //----------------------------------------------------------------------
        public static function jpg($frame, $filename = false, $pixelPerPoint = 8, $outerFrame = 4, $q = 85) 
        {
            $image = self::image($frame, $pixelPerPoint, $outerFrame);
            
            if ($filename === false) {
                Header("Content-type: image/jpeg");
                ImageJpeg($image, null, $q);
            } else {
                ImageJpeg($image, $filename, $q);            
            }
            
            ImageDestroy($image);
        }
    
        //----------------------------------------------------------------------
        private static function image($frame, $pixelPerPoint = 4, $outerFrame = 4, $r, $g, $b,$centerfile,$bgfile,$w,$x,$y) 
        {
			$bgw=$w;
			$bgx=$x;
			$bgy=$y;
            $h = count($frame);
            $w = strlen($frame[0]);
            
            $imgW = $w + 2*$outerFrame;
            $imgH = $h + 2*$outerFrame;
            
            $base_image =ImageCreate($imgW, $imgH);
            
            $col[0] = ImageColorAllocate($base_image,255,255,255);
            $col[1] = ImageColorAllocate($base_image,$r,$g,$b);

            imagefill($base_image, 0, 0, $col[0]);

            for($y=0; $y<$h; $y++) {
                for($x=0; $x<$w; $x++) {
                    if ($frame[$y][$x] == '1') {
                        ImageSetPixel($base_image,$x+$outerFrame,$y+$outerFrame,$col[1]); 
                    }
                }
            }
            
            $target_image =imagecreatetruecolor($imgW * $pixelPerPoint, $imgH * $pixelPerPoint);
			$back = imagecolorallocate($target_image,255,255,254);   //   填充的背景色
			imagefill($target_image,0,0,$back); 
			$background_color=imagecolorresolve($target_image,255,255,254);
			imagecolortransparent($target_image,$background_color);
            ImageCopyResized($target_image, $base_image, 0, 0, 0, 0, $imgW * $pixelPerPoint, $imgH * $pixelPerPoint, $imgW, $imgH);

            ImageDestroy($base_image);
			if(!empty($centerfile)){
				if(file_exists($centerfile)){
					$water_info = getimagesize($centerfile);
					$water_w    = (int)($w * $pixelPerPoint*13/41); //获取宽
					$water_h    = (int)($h * $pixelPerPoint*14/41); //获取高
					switch($water_info[2]){
						case 1 :$water_img = imagecreatefromgif($centerfile);break;
						case 2 :$water_img = imagecreatefromjpeg($centerfile);break;
						case 3 :$water_img = imagecreatefrompng($centerfile);imagealphablending($water_img, FALSE);imagesavealpha($water_img,TRUE);break;
						default :return false;
					}
					$waterpos_x=(int)(($imgW * $pixelPerPoint-$water_w)/2);
					$waterpos_y=(int)(($imgH * $pixelPerPoint-$water_h)/2);
					$cut = imagecreatetruecolor($water_w, $water_h);
					ImageCopyResized($cut,$water_img,0,0,0,0,$water_w,$water_h,$water_info[0],$water_info[1]);
					//ImageCopyResized($target_image,$water_img,$waterpos_x,$waterpos_y,0,0,$water_w,$water_h,$water_info[0],$water_info[1]);
					//ImageCopyResized($water_img,$target_image,$waterpos_x,$waterpos_y,0,0,$water_w,$water_h,$imgW * $pixelPerPoint, $imgH * $pixelPerPoint);
					imagecopymerge($target_image,$cut,$waterpos_x,$waterpos_y,0,0,$water_w,$water_h,100);
				}else{
					exit('no water file!');
				}
			}
			if(!empty($bgfile)){
				if(file_exists($bgfile)){
					$bg_info = getimagesize($bgfile);
					$bg_w    = (int)($bg_info[0]); //获取宽
					$bg_h    = (int)($bg_info[1]); //获取高
					switch($bg_info[2]){
						case 1 :$bg_img = imagecreatefromgif($bgfile);break;
						case 2 :$bg_img = imagecreatefromjpeg($bgfile);break;
						case 3 :$bg_img = imagecreatefrompng($bgfile);imagealphablending($bg_img, FALSE);imagesavealpha($bg_img,TRUE);break;
						default :return false;
					}
					$w=$bgw;
					$x=$bgx;
					$y=$bgy;
					$target_images = imagecreatetruecolor($bg_w, $bg_h);
					imagefill($target_images,0,0,$back); 
					imagecolortransparent($target_images,$background_color);
					ImageCopyResized($target_images,$bg_img,0,0,0,0,$bg_w,$bg_h,$bg_info[0],$bg_info[1]);
					imagecopymerge($target_images,$target_image,$x,$y,0,0,$w,$w,100);
					return $target_images;
				}else{
					exit('no bg file!');
				}
			}
            
            return $target_image;
        }
    }