<?php

/**
 * Description of image_magick
 *
 * @author vijay
 */
class ImageMagickComponent extends Component
{

    //var $helpers = array('Html');
    /**
     * path to cache folder
     *
     * @var string
     * @access public
     */
    var $cachePath = '';
    /**
     * path to ImageMagick convert tool
     *
     * @var string
     * @access public
     */

    var $convertPath = '';
    //'/opt/local/bin/convert'; //'/usr/bin/convert';
    /**
     * permitted image formats to convert
     *
     * @var array
     * @access public
     */
    var $permitted = array('image/jpg', 'image/jpeg', 'image/gif', 'image/png');

    /**
     * Automatically resizes an image and returns formatted img tag or only url (optional)
     *
     * @param string $filePath Path to the image file, relative to the webroot/ directory.
     * @param integer $width Width of returned image
     * @param integer $height Height of returned image
     * @param boolean $tag Return html tag (default: true)
     * @param boolean $cachePath Path to cache folder (default: this->cachePath)
     * @param boolean $quality JPEG Quality (default: 100)
     * @param boolean $aspect Maintain aspect ratio (default: true, 1 = maintain ratio, 2 = cut image to fit)
     * @param boolean $fill Maintain aspect ratio & fill (default: false)
     * @param array    $permitted Array of HTML attributes.
     * @access public
     */
    function resize($filePath, $width, $height, $tag = true, $cachePath = null, $quality = 100, $aspect = true, $image_name = '01')
    {
        //chk os
        $osName = strtolower(php_uname());
        if (strpos($osName, "darwin") !== false) {
            $this->convertPath = '/opt/local/bin/convert';
        } else if (strpos($osName, "win") !== false) {
            $this->convertPath = '/usr/bin/convert';
        } else if (strpos($osName, "linux") !== false) {
            $this->convertPath = '/usr/bin/convert';
        } else {
            $this->convertPath = '/usr/bin/convert';
        }

        $fullPath = ROOT . DS . APP_DIR . DS . WEBROOT_DIR . DS;

        //create directory if not created
        $this->create_dir($cachePath);

        //create cache directory if not created before
        //$this->create_dir($cachePath);

        if (!$cachePath)
            $cachePath = $this->cachePath;

        $url = $fullPath . $filePath;

        // Verify image exist
        if (!($size = getimagesize($url))) {
            return;
        }

        // Relative file (for return use)
        $relFile = $cachePath . '/' . $image_name;
        // Location on server (for resize use)
        $cacheFile = $fullPath . $cachePath . DS . $image_name;


        // Verify if file is cached
        $cached = false;
        if (file_exists($cacheFile)) {
            if (@filemtime($cacheFile) > @filemtime($url)) {
                $cached = true;
            }
        }

        // Verify resize neccessity
        $resize = false;
        if (!$cached) {
            $resize = ($size[0] > $width || $size[1] > $height) || ($size[0] < $width || $size[1] < $height);
        }

        $jpgOptions = '';
        if ($resize) {
            if ($aspect) {
                // Use Image Magick build-in keep ratio option
                if ($aspect == 1) {
                    $resizeOption = '\> -size ' . $width . 'x' . $height . ' xc:white +swap -gravity center -composite';
                } elseif ($aspect == 2) {
                    $resizeOption = '^ -gravity center -extent ' . $width . 'x' . $height;
                }
            } else {
                $resizeOption = '';
            }

            //for approx given size image thumb creation
            //$resizeOption = '!';
            exec($this->convertPath . ' ' . escapeshellarg($url) . ' -resize ' . $width . 'x' . $height . $resizeOption . ' -quality ' . $quality . $jpgOptions . ' ' . escapeshellarg($cacheFile) . '');

           // $this->log($this->convertPath . ' ' . escapeshellarg($url). ' -resize ' . $width . 'x' . $height . $resizeOption . ' -quality ' . $quality . $jpgOptions . ' ' . escapeshellarg($cachefile) . '');

        } else {
            // No resize and no cache, copy image to destination
            if (!$cached) {
                copy($url, $cacheFile);
            }
        }
        $this->change_permissions($relFile, 0777);

        return $image_name;
    }

    function create_dir($path)
    {

        $return = false;

        //create directory if not created already
        if (!is_dir($path)) {

            mkdir($path, 0777, true); // or even 01777 so you get the sticky bit set

            $this->change_permissions($path, 0777);

            $return = true;
        }

        return $return;
    }

    function change_permissions($file, $permissions)
    {

        $return = false;

        if (file_exists($file)) {

            chmod($file, $permissions); // or even 01777 so you get the sticky bit set
            $return = true;
        }

        if (is_dir($file)) {

            chmod($file, $permissions); // or even 01777 so you get the sticky bit set

            $return = true;
        }

        return $return;
    }

}