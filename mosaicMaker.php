<?php

$width = 1200;
$height = 600;
$imageHeight = 30;
$maximages = 2000;
$sourceDir = '/Users/bill/projects/mosaic/images/';
$destFile  ='/Users/bill/projects/mosaic/test.jpg';


function makeMosaic($directory,$totalHeight,$totalWidth,$height,$maximages,&$bg){

	$allowed = ['png','jpg','gif','GIF','PNG','JPG','jpeg','JPG'];

	// open this directory 
	$myDirectory = opendir($directory);

	// get each entry, have to do this first so we can sort by time afterwards. shame.
	while($entryName = readdir($myDirectory)) {
		//get file type
		$pathinfo = pathinfo($entryName);

        if (array_search($pathinfo['extension'],$allowed)){

            if (strrpos($entryName,'..') > -1){
                //that shouldnt be there
                continue;
            }

            $dirArray[$directory.'/'.$entryName]=filemtime($directory.'/'.$entryName);
        }
	}

    closedir($myDirectory);

    //sort by modified date
    arsort($dirArray);

    if (count($dirArray)>$maximages){
        $dirArray = array_slice($dirArray,0,$maximages);
    }

    ///clever height figuring out stuff
    //how many total pixels
    $totalPixels = $totalHeight * $totalWidth;

    //divide that by the number of images
    $pixelsPerImage = $totalPixels/count($dirArray);

    //square root gives us the width/height per image
    $height = ceil(sqrt($pixelsPerImage));

    //how many rows
    $rows = $totalHeight / $height;

    //divide the avail images by rows
    $col = count($dirArray) / $rows;


    //start spinning
    $x = 0;
    $y = 0;

    $totalstart = microtime(true);
    $imagesizetime = 0;
    $imagecopyresampledtime = 0;
    foreach ($dirArray as $file=>$time){

        $thisstart = microtime(true);

        // close directory
        $sizes = getimagesize ( $file );

        $thisend = microtime(true);
        $imagesizetime += ($thisend - $thisstart);

        //  print("\r\n\r\n");
     //   print($file);
      //  print_r($sizes);

        $thisImage = null;

        switch ($sizes['mime']){
            case 'image/jpeg':
            case 'image/JPG':
            case 'image/jpg':
            case 'image/JPEG':
                $thisImage = imagecreatefromjpeg($file);
                break;
            case 'image/png':
            case 'image/PNG':
                $thisImage = imagecreatefrompng($file);
                break;
            case 'image/gif':
            case 'image/GIF':
                $thisImage = imagecreatefromgif($file);
                break;
        }

        if (!$thisImage){
            print('couldnt figure this one out:');
            print_r($file);
            continue;
        }

        //figure aspect ratio
        $ratio = $sizes[0] / $sizes[1];

        //always a height of $height
        $newwidth= ceil($height * $ratio);


        //print(sprintf('imgae was %s changing to %s', $sizes[1],$newwidth));

       // print (sprintf('old width/height:%s/%s new width/height:%s/%s ratio:%s position:%s',$sizes[0],$sizes[1],$newwidth,$sqrt,$ratio,$x));
      //  print("\r\n");
       // print ($x);
        if ($x >= $totalWidth){

            $y = $y + $height;
            $x = 0;
        }



      //  print('.');
        //print(sprintf("writing %s at x:%s  y:%s\r\n",$file,$x,$y));
        // stamp!
        $thisstart = microtime(true);

        imagecopyresampled ( $bg , $thisImage ,$x ,$y ,0 ,0 ,$newwidth,$height  ,$sizes[0] , $sizes[1] );

        imagedestroy($thisImage);
        $thisend = microtime(true);
        $imagecopyresampledtime += ($thisend - $thisstart);

        $x = floor($x + $newwidth);

    }

    $totalend = microtime(true);

    $total = $totalend - $totalstart;
    echo "total in $total seconds\n";
    echo "imagecopyresampledtime in $imagecopyresampledtime seconds\n";
    echo "imagesizetime in $imagesizetime seconds\n";

}



//make the target image
$im = imagecreatetruecolor ( $width ,  $height );
$red = imagecolorallocate($im, 0x30,0x64,0xb1);

// Make the background transparent

// Draw a red rectangle
imagefilledrectangle($im, 0, 0, $width, $height, $red);


//make the mosaic
makeMosaic($sourceDir,$height,$width,$imageHeight,$maximages,$im);

// Output and free memory
imagejpeg($im,$destFile);

imagedestroy($im);
