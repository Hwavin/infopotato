<?php
/**
 * Force File Download
 * 
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2014 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
 
namespace InfoPotato\libraries\download;

class Download_Library {
    /**
     * Force File Download
     *
     * @param string $file the path of the file to be downloaded
     * @param string $content_type Content type of the target file
     * @return none
     * @link http://w-shadow.com/blog/2007/08/12/how-to-force-file-download-with-php/
     */
    public function download($file, $content_type) { 
        // Tells whether a file exists and is readable
        if ( ! file_exists($file)) {
            exit("The file $file does not exists!");
        } else {
            if ( ! is_readable($file)) {
                exit("The file $file is not readable!");
            }
        }

        // The fastest method to get file extension?
        // http://cowburn.info/2008/01/13/get-file-extension-comparison/
        $file_extension = pathinfo($file, PATHINFO_EXTENSION);
        
        // File name shown in the save window
        $save_name = substr(strrchr($file, DIRECTORY_SEPARATOR), 1);

        $size = filesize($file);
        
        // Turn off output buffering to decrease cpu usage
        @ob_end_clean(); 
        
        header('Content-Type: '.$content_type);
        header('Content-Disposition: attachment; filename="'.$save_name.'"');
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
        
        // The three lines below basically make the download non-cacheable 
        header('Cache-control: private');
        header('Pragma: private');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        
        // Multipart-download and download resuming support
        if (isset($_SERVER['HTTP_RANGE'])) {
            list($a, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
            list($range) = explode(',', $range, 2);
            list($range, $range_end) = explode('-', $range);
            $range = intval($range);
            if ( ! $range_end) {
                $range_end = $size - 1;
            } else {
                $range_end = intval($range_end);
            }
            
            $new_length = $range_end - $range + 1;
            header("HTTP/1.1 206 Partial Content");
            header("Content-Length: $new_length");
            header("Content-Range: bytes $range-$range_end/$size");
        } else {
            $new_length = $size;
            header("Content-Length: ".$size);
        }
        
        // Output the file itself 
        // You may want to change this
        $chunksize = 1*(1024*1024); 
        $bytes_send = 0;
        if ($file = fopen($file, 'r')) {
            if (isset($_SERVER['HTTP_RANGE'])) {
                fseek($file, $range);
            }
            while ( ! feof($file) && ( ! connection_aborted()) && ($bytes_send < $new_length)) {
                $buffer = fread($file, $chunksize);
                echo $buffer; 
                flush();
                $bytes_send += strlen($buffer);
            }
            fclose($file);
        } else {
            exit('Error - can not open file.');
        }
        exit();
    }
}

// End of file: ./system/libraries/download/download_library.php
