<?php
/**
 * Class to create QR-code
 * based on http://www.swetake.com/qrcode/qr1_en.html
 * 
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2013 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */

class QRcode_Library {

    /**
     * set path to data files
     * 
     * @var string
     */
    private $path;
    
    /**
     * set path to QRcode frame images
     * 
     * @var string
     */
    private $image_path;
    
    /**
     * Upper limit for version
     * 
     * @var integer
     */
    private $version_upper_limit = 40; 

    /**
     * Error correction level    L or M or Q or H   (default M)
     * 
     * @var string
     */
    private $error_correction_level = 'M';

    /**
     * Version (size)    1-40 or Auto select if you do not set.
     * 
     * @var integer
     */
    private $qrcode_version;
    
    /**
     * Output image type    jpeg or png
     * 
     * @var string
     */
    private $output_image_type = 'jpeg';
    
    /**
     * How many pixels per module (one little black square)
     * 
     * @var integer
     */
    private $pixels_per_module = 4;

    /**
     * Structured append n (2-16)
     * One QR code can be divided into up to 16 symbols
     * 
     * @var integer
     */
    private $structured_append_n = 3;
    
    /**
     * Structured append m (1-16)
     * 
     * @var integer
     */
    private $structured_append_m = 4;
    
    /**
     * parity
     * 
     * @var string
     */
    private $structured_append_parity;
    
    /**
     * Original data (URL encoded data) for calculating parity
     * 
     * @var string
     */
    private $structured_append_original_data;
    

    /**
     * Constructor
     *
     * The constructor can be passed an array of config values
     */
    public function __construct(array $config = NULL) {
        if (count($config) > 0) {
            foreach ($config as $key => $val) {
                // Using isset() requires $this->$key not to be NULL in property definition
                // property_exists() allows empty property
                if (property_exists($this, $key)) {
                    $method = 'initialize_'.$key;
                    
                    if (method_exists($this, $method)) {
                        $this->$method($val);
                    }
                } else {
                    exit("'".$key."' is not an acceptable config argument!");
                }
            }
        }
    }

    /**
     * Validate and set $path
     *
     * @param  $val string
     * @return void
     */
    private function initialize_path($val) {
        if ( ! is_string($val)) {
            $this->invalid_argument_value('path');
        }
        $this->path = $val;
    }
    
    /**
     * Validate and set $image_path
     *
     * @param  $val string
     * @return void
     */
    private function initialize_image_path($val) {
        if ( ! is_string($val)) {
            $this->invalid_argument_value('image_path');
        }
        $this->image_path = $val;
    }
    
    /**
     * Validate and set $version_upper_limit
     *
     * @param  $val int
     * @return void
     */
    private function initialize_version_upper_limit($val) {
        if ( ! is_int($val)) {
            $this->invalid_argument_value('version_upper_limit');
        }
        $this->version_upper_limit = $val;
    }
    
    /**
     * Validate and set $error_correction_level
     *
     * @param  $val string
     * @return void
     */
    private function initialize_error_correction_level($val) {
        if ( ! is_string($val) || ! in_array($val, array('L', 'M', 'Q', 'H'))) {
            $this->invalid_argument_value('error_correction_level');
        }
        $this->error_correction_level = $val;
    }

    /**
     * Validate and set $qrcode_version
     *
     * @param  $val int
     * @return void
     */
    private function initialize_qrcode_version($val) {
        if ( ! is_int($val)) {
            $this->invalid_argument_value('qrcode_version');
        }
        $this->qrcode_version = $val;
    }
    
    /**
     * Validate and set $output_image_type
     *
     * @param  $val string
     * @return void
     */
    private function initialize_output_image_type($val) {
        if ( ! is_string($val) || ! in_array($val, array('jpeg', 'png'))) {
            $this->invalid_argument_value('output_image_type');
        }
        $this->output_image_type = $val;
    }

    /**
     * Validate and set $pixels_per_module
     *
     * @param  $val int
     * @return void
     */
    private function initialize_pixels_per_module($val) {
        if ( ! is_int($val)) {
            $this->invalid_argument_value('pixels_per_module');
        }
        $this->pixels_per_module = $val;
    }
    
    /**
     * Validate and set $structured_append_n
     *
     * @param  $val int
     * @return void
     */
    private function initialize_structured_append_n($val) {
        if ( ! is_int($val) || ($val > 16) || ($val < 2)) {
            $this->invalid_argument_value('structured_append_n');
        }
        $this->structured_append_n = $val;
    }
    
    /**
     * Validate and set $structured_append_m
     *
     * @param  $val string
     * @return void
     */
    private function initialize_structured_append_m($val) {
        if ( ! is_int($val) || ($val > 16) || ($val < 1)) {
            $this->invalid_argument_value('structured_append_m');
        }
        $this->structured_append_m = $val;
    }
    
    /**
     * Output the error message for invalid argument value
     *
     * @return void
     */
    private function invalid_argument_value($arg) {
        exit('In your config array, the provided argument value of '."'".$arg."'".' is invalid.');
    }
    
    /**
     * Generate a QR code image
     * 
     * @var string 
     * @return void
     */
    public function main($str) {
        $data_length = strlen(rawurldecode($str));
        if ($data_length <= 0) {
            trigger_error("QRcode : Data do not exist.", E_USER_ERROR);
            exit;
        }
        
        $data_counter = 0;

        $data_value[0] = 3;
        $data_bits[0] = 4;

        $data_value[1] = $this->structured_append_m - 1;
        $data_bits[1] = 4;

        $data_value[2] = $this->structured_append_n - 1;
        $data_bits[2] = 4;


        $originaldata_length = strlen($this->structured_append_original_data);
        if ($originaldata_length > 1) {
            $this->structured_append_parity = 0;
            $i = 0;
            while ($i < $originaldata_length) {
                // ^ is bit Xor (exclusive or)
                $this->structured_append_parity = ($this->structured_append_parity ^ ord(substr($this->structured_append_original_data, $i, 1)));
                $i++;
            }
        }

        $data_value[3] = $this->structured_append_parity;
        $data_bits[3] = 8;

        $data_counter = 4;


        $data_bits[$data_counter] = 4;

        // Determine encoding mode
        // Supports 8bit, Alphanumeric, and Numeric
        if (preg_match('/[^0-9]/', $str) !== 0) {
            if (preg_match('/[^0-9A-Z \$\*\%\+\.\/\:\-]/', $str) !== 0) {
                // 8bit byte encoding mode
             
                $codeword_num_plus = array(
                    0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                    8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8,
                    8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8
                );

                $data_value[$data_counter] = 4;
                $data_counter++;
                $data_value[$data_counter] = $data_length;
                $data_bits[$data_counter] = 8;  // Number of bits per length field for version 1-9
                $codeword_num_counter_value = $data_counter;

                $data_counter++;
                $i = 0;
                while ($i < $data_length) {
                    $data_value[$data_counter] = ord(substr($str, $i, 1));
                    $data_bits[$data_counter] = 8;
                    $data_counter++;
                    $i++;
                }
            } else {
                // Alphanumeric encoding mode (11 bits per 2 characters)

                $codeword_num_plus = array(
                    0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                    2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2,
                    4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4
                );

                $data_value[$data_counter] = 2;
                $data_counter++;
                $data_value[$data_counter] = $data_length;
                $data_bits[$data_counter] = 9;  // Number of bits per length field for version 1-9
                $codeword_num_counter_value = $data_counter;

                // Alphanumeric encoding mode stores a message more compactly than the byte mode can, 
                // but cannot store lower-case letters and has only a limited selection of punctuation marks, 
                // which are sufficient for most web addresses.
                $alphanumeric_character_hash = array(
                    '0' => 0, '1' => 1, '2' => 2, '3' => 3, '4' => 4, 
                    '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9,
                    'A' => 10, 'B' => 11, 'C' => 12, 'D' => 13, 'E' => 14,
                    'F' => 15, 'G' => 16, 'H' => 17, 'I' => 18, 'J' => 19,
                    'K' => 20, 'L' => 21, 'M' => 22, 'N' => 23, 'O' => 24,
                    'P' => 25, 'Q' => 26, 'R' => 27, 'S' => 28, 'T' => 29,
                    'U' => 30, 'V' => 31, 'W' => 32, 'X' => 33, 'Y' => 34,
                    'Z' => 35, ' ' => 36, '$' => 37, '%' => 38, '*' => 39,
                    '+' => 40, '-' => 41, '.' => 42, '/' => 43, ':' => 44
                );

                $i = 0;
                $data_counter++;
                while ($i < $data_length) {
                    if (($i % 2) === 0){
                        $data_value[$data_counter] = $alphanumeric_character_hash[substr($str, $i, 1)];
                        $data_bits[$data_counter] = 6;
                    } else {
                        $data_value[$data_counter] = $data_value[$data_counter] * 45 + $alphanumeric_character_hash[substr($str, $i, 1)];
                        $data_bits[$data_counter] = 11;
                        $data_counter++;
                    }
                    $i++;
                }
            }
        } else {
            // Numeric encoding mode (10 bits per 3 digits)

            $codeword_num_plus = array(
                0, 0, 0, 0, 0,0, 0, 0, 0, 0,
                2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2,
                4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4
            );

            $data_value[$data_counter] = 1;
            $data_counter++;
            $data_value[$data_counter] = $data_length;
            $data_bits[$data_counter] = 10;  // Number of bits per length field for version 1-9
            $codeword_num_counter_value = $data_counter;

            $i = 0;
            $data_counter++;
            while ($i < $data_length) {
                if (($i % 3) === 0) {
                    $data_value[$data_counter] = substr($str, $i, 1);
                    $data_bits[$data_counter] = 4;
                } else {
                     $data_value[$data_counter] = $data_value[$data_counter] * 10 + substr($str, $i, 1);
                 if (($i % 3) === 1) {
                     $data_bits[$data_counter] = 7;
                 } else {
                     $data_bits[$data_counter] = 10;
                     $data_counter++;
                 }
                }
                $i++;
            }
        }
        
        if (@$data_bits[$data_counter] > 0) {
            $data_counter++;
        }
        $i = 0;
        $total_data_bits = 0;
        while($i < $data_counter){
            $total_data_bits += $data_bits[$i];
            $i++;
        }

        // Error correction levels
        // Level L (Low)   7% of codewords can be restored.
        // Level M (Medium)    15% of codewords can be restored.
        // Level Q (Quartile)  25% of codewords can be restored.
        // Level H (High)  30% of codewords can be restored.
        $ecc_character_hash = array(
            'L' => 1,
            'M' => 0,
            'Q' => 3,
            'H' => 2,
        );

        $ec = $ecc_character_hash[$this->error_correction_level]; 

        $max_data_bits_array = array(
            0, 128, 224, 352, 512, 688, 864, 992, 1232, 1456, 1728,
            2032, 2320, 2672, 2920, 3320, 3624, 4056, 4504, 5016, 5352,
            5712, 6256, 6880, 7312, 8000, 8496, 9024, 9544, 10136, 10984,
            11640, 12328, 13048, 13800, 14496, 15312, 15936, 16816, 17728, 18672,

            152, 272, 440, 640, 864, 1088, 1248, 1552, 1856, 2192,
            2592, 2960, 3424, 3688, 4184, 4712, 5176, 5768, 6360, 6888,
            7456, 8048, 8752, 9392, 10208, 10960,11744, 12248, 13048, 13880,
            14744, 15640, 16568, 17528, 18448, 19472, 20528, 21616, 22496, 23648,

            72, 128,208, 288, 368, 480, 528, 688, 800, 976,
            1120, 1264, 1440, 1576, 1784, 2024, 2264, 2504, 2728, 3080,
            3248, 3536, 3712, 4112, 4304, 4768, 5024, 5288, 5608, 5960,
            6344, 6760, 7208, 7688, 7888, 8432, 8768, 9136, 9776, 10208,

            104, 176, 272, 384, 496, 608, 704, 880, 1056, 1232,
            1440, 1648, 1952, 2088, 2360, 2600, 2936, 3176, 3560, 3880,
            4096, 4544, 4912, 5312, 5744, 6032, 6464, 6968, 7288, 7880,
            8264, 8920, 9368, 9848, 10288, 10832, 11408, 12016, 12656, 13328
        );
        
        // Auto version select if not specified
        if ( ! $this->qrcode_version) {      
            $i = 1 + 40 * $ec;
            $j = $i + 39;
            $this->qrcode_version = 1; 
            while ($i <= $j) {
                if (($max_data_bits_array[$i]) >= $total_data_bits + $codeword_num_plus[$this->qrcode_version]) {
                    $max_data_bits = $max_data_bits_array[$i];
                    break;
                }
                $i++;
                $this->qrcode_version++;
            }
        } else {
            $max_data_bits = $max_data_bits_array[$this->qrcode_version + 40 * $ec];
        }
        
        if ($this->qrcode_version > $this->version_upper_limit) {
            trigger_error("QRcode : too large version.", E_USER_ERROR);
        }

        $total_data_bits += $codeword_num_plus[$this->qrcode_version];
        $data_bits[$codeword_num_counter_value] += $codeword_num_plus[$this->qrcode_version];

        $max_codewords_array = array(
            0, 26, 44, 70, 100, 134, 172, 196, 242,
            292, 346, 404, 466, 532, 581, 655, 733, 815, 901, 991, 1085, 1156,
            1258, 1364, 1474, 1588, 1706, 1828, 1921, 2051, 2185, 2323, 2465,
            2611, 2761, 2876, 3034, 3196, 3362, 3532, 3706
        );

        $max_codewords = $max_codewords_array[$this->qrcode_version];
        $max_modules_1side = 17 + ($this->qrcode_version << 2);

        $matrix_remain_bit = array(
            0, 0, 7, 7, 7, 7, 7, 0, 0, 0, 0, 0, 0, 0, 3, 3, 3, 3, 3, 3, 3,
            4, 4, 4, 4, 4, 4, 4, 3, 3, 3, 3, 3, 3, 3, 0, 0, 0, 0, 0, 0
        );

        // Read version ECC data file

        $byte_num = $matrix_remain_bit[$this->qrcode_version] + ($max_codewords << 3);
        $filename = $this->path.'qrv'.$this->qrcode_version.'_'.$ec.'.dat';
        $fp1 = fopen ($filename, 'rb');
        $matx = fread($fp1, $byte_num);
        $maty = fread($fp1, $byte_num);
        $masks = fread($fp1, $byte_num);
        $fi_x = fread($fp1, 15);
        $fi_y = fread($fp1, 15);
        $rs_ecc_codewords = ord(fread($fp1, 1));
        $rso = fread($fp1, 128);
        fclose($fp1);

        // Unpacks from a binary string into an array
        $matrix_x_array = unpack('C*', $matx);
        $matrix_y_array = unpack('C*', $maty);
        $mask_array = unpack('C*', $masks);

        $rs_block_order = unpack('C*', $rso);

        $format_information_x2 = unpack('C*', $fi_x);
        $format_information_y2 = unpack('C*', $fi_y);

        $format_information_x1 = array(0, 1, 2, 3, 4, 5, 7, 8, 8, 8, 8, 8, 8, 8, 8);
        $format_information_y1 = array(8, 8, 8, 8, 8, 8, 8, 8, 7, 5, 4, 3, 2, 1, 0);

        $max_data_codewords = ($max_data_bits >> 3);

        $filename = $this->path.'rsc'.$rs_ecc_codewords.'.dat';
        $fp0 = fopen ($filename, 'rb');
        $i = 0;
        while ($i < 256) {
            $rs_cal_table_array[$i] = fread ($fp0, $rs_ecc_codewords);
            $i++;
        }
        fclose ($fp0);

        // Set terminator

        if ($total_data_bits <= $max_data_bits - 4) {
            $data_value[$data_counter] = 0;
            $data_bits[$data_counter] = 4;
        } else {
            if ($total_data_bits < $max_data_bits) {
                $data_value[$data_counter] = 0;
                $data_bits[$data_counter] = $max_data_bits - $total_data_bits;
            } else {
                if ($total_data_bits > $max_data_bits) {
                    trigger_error("QRcode : Overflow error", E_USER_ERROR);
                    exit;
                }
            }
        }

        // Divide data by 8bit

        $i = 0;
        $codewords_counter = 0;
        $codewords[0] = 0;
        $remaining_bits = 8;

        while ($i <= $data_counter) {
            $buffer = @$data_value[$i];
            $buffer_bits = @$data_bits[$i];

            $flag = 1;
            while ($flag) {
                if ($remaining_bits > $buffer_bits) {  
                    $codewords[$codewords_counter] = ((@$codewords[$codewords_counter] << $buffer_bits) | $buffer);
                    $remaining_bits -= $buffer_bits;
                    $flag = 0;
                } else {
                    $buffer_bits -= $remaining_bits;
                    $codewords[$codewords_counter] = (($codewords[$codewords_counter] << $remaining_bits) | ($buffer >> $buffer_bits));

                    if ($buffer_bits === 0) {
                        $flag = 0;
                    } else {
                        $buffer= ($buffer & ((1 << $buffer_bits) - 1));
                        $flag = 1;   
                    }

                    $codewords_counter++;
                    if ($codewords_counter < $max_data_codewords - 1) {
                        $codewords[$codewords_counter] = 0;
                    }
                    $remaining_bits = 8;
                }
            }
            $i++;
        }
        if ($remaining_bits !== 8) {
            $codewords[$codewords_counter] = $codewords[$codewords_counter] << $remaining_bits;
        } else {
            $codewords_counter--;
        }

        // Set padding character

        if ($codewords_counter < $max_data_codewords - 1) {
            $flag = 1;
            while ($codewords_counter < $max_data_codewords - 1) {
                $codewords_counter++;
                if ($flag === 1) {
                    $codewords[$codewords_counter] = 236;
                } else {
                    $codewords[$codewords_counter] = 17;
                }
                $flag = $flag * (-1);
            }
        }

        // RS-ECC prepare

        $i = 0;
        $j = 0;
        $rs_block_number = 0;
        $rs_temp[0] = '';

        while ($i < $max_data_codewords) {

            $rs_temp[$rs_block_number] .= chr($codewords[$i]);
            $j++;

            if ($j >= $rs_block_order[$rs_block_number + 1] - $rs_ecc_codewords) {
                $j = 0;
                $rs_block_number++;
                $rs_temp[$rs_block_number] = '';
            }
            $i++;
        }


        // RS-ECC main

        $rs_block_number = 0;
        $rs_block_order_num = count($rs_block_order);

        while ($rs_block_number < $rs_block_order_num) {
            $rs_codewords = $rs_block_order[$rs_block_number + 1];
            $rs_data_codewords = $rs_codewords - $rs_ecc_codewords;

            $rstemp = $rs_temp[$rs_block_number].str_repeat(chr(0), $rs_ecc_codewords);
            $padding_data = str_repeat(chr(0), $rs_data_codewords);

            $j = $rs_data_codewords;
            while($j > 0) {
                $first = ord(substr($rstemp, 0, 1));

                if ($first) {
                    $left_chr = substr($rstemp, 1);
                    $cal = $rs_cal_table_array[$first].$padding_data;
                    $rstemp = $left_chr ^ $cal;
                } else {
                    $rstemp = substr($rstemp,1);
                }

                $j--;
            }

            $codewords = array_merge($codewords, unpack('C*', $rstemp));

            $rs_block_number++;
        }

        // Flash matrix

        $i = 0;
        while ($i < $max_modules_1side) {
            $j = 0;
            while ($j < $max_modules_1side) {
                $matrix_content[$j][$i] = 0;
                $j++;
            }
            $i++;
        }

        // Attach data

        $i = 0;
        while ($i < $max_codewords) {
            $codeword_i = $codewords[$i];
            $j = 8;
            while ($j >= 1) {
                $codeword_bits_number = ($i << 3) +  $j;
                $matrix_content[ $matrix_x_array[$codeword_bits_number] ][ $matrix_y_array[$codeword_bits_number] ] = ((255*($codeword_i & 1)) ^ $mask_array[$codeword_bits_number] ); 
                $codeword_i = $codeword_i >> 1;
                $j--;
            }
            $i++;
        }

        $matrix_remain = $matrix_remain_bit[$this->qrcode_version];
        while ($matrix_remain) {
            $remain_bit_temp = $matrix_remain + ( $max_codewords << 3);
            $matrix_content[ $matrix_x_array[$remain_bit_temp] ][ $matrix_y_array[$remain_bit_temp] ] = (0 ^ $mask_array[$remain_bit_temp]);
            $matrix_remain--;
        }

        // Mask select

        $min_demerit_score = 0;
            $hor_master = '';
            $ver_master = '';
            $k = 0;
            while ($k < $max_modules_1side) {
                $l = 0;
                while ($l < $max_modules_1side) {
                    $hor_master = $hor_master.chr($matrix_content[$l][$k]);
                    $ver_master = $ver_master.chr($matrix_content[$k][$l]);
                    $l++;
                }
                $k++;
            }
        $i = 0;
        $all_matrix = $max_modules_1side * $max_modules_1side; 
        while ($i < 8) {
            $demerit_n1 = 0;
            $ptn_temp = array();
            $bit= 1 << $i; // Shift the bits of 1 $i steps to the left (each step means "multiply by two")
            $bit_r = (~$bit)&255;
            $bit_mask = str_repeat(chr($bit), $all_matrix);
            $hor = $hor_master & $bit_mask;
            $ver = $ver_master & $bit_mask;

            $ver_shift1 = $ver.str_repeat(chr(170), $max_modules_1side);
            $ver_shift2 = str_repeat(chr(170), $max_modules_1side).$ver;
            $ver_shift1_0 = $ver.str_repeat(chr(0), $max_modules_1side);
            $ver_shift2_0 = str_repeat(chr(0), $max_modules_1side).$ver;
            $ver_or = chunk_split(~($ver_shift1 | $ver_shift2), $max_modules_1side, chr(170));
            $ver_and = chunk_split(~($ver_shift1_0 & $ver_shift2_0), $max_modules_1side, chr(170));

            $hor = chunk_split(~$hor, $max_modules_1side, chr(170));
            $ver = chunk_split(~$ver, $max_modules_1side, chr(170));
            $hor = $hor.chr(170).$ver;

            $n1_search = '/'.str_repeat(chr(255), 5).'+|'.str_repeat(chr($bit_r), 5).'+/';
            $n3_search = chr($bit_r).chr(255).chr($bit_r).chr($bit_r).chr($bit_r).chr(255).chr($bit_r);

            $demerit_n3 = substr_count($hor, $n3_search) * 40;
            $demerit_n4 = floor(abs(( (100 * (substr_count($ver, chr($bit_r))/($byte_num)) )-50)/5)) * 10;


            $n2_search1 = '/'.chr($bit_r).chr($bit_r).'+/';
            $n2_search2 = '/'.chr(255).chr(255).'+/';
            $demerit_n2 = 0;
            preg_match_all($n2_search1,$ver_and,$ptn_temp);
            foreach ($ptn_temp[0] as $str_temp) {
                $demerit_n2 += (strlen($str_temp) - 1);
            }
            $ptn_temp = array();
            preg_match_all($n2_search2, $ver_or, $ptn_temp);
            foreach ($ptn_temp[0] as $str_temp) {
                $demerit_n2 += (strlen($str_temp) - 1);
            }
            $demerit_n2 *= 3;

            $ptn_temp = array();

            preg_match_all($n1_search,$hor, $ptn_temp);
            foreach ($ptn_temp[0] as $str_temp) {
                $demerit_n1 += (strlen($str_temp) - 2);
            }

            $demerit_score = $demerit_n1 + $demerit_n2+$demerit_n3 + $demerit_n4;

            if ($demerit_score <= $min_demerit_score || $i === 0) {
                $mask_number = $i;
                $min_demerit_score = $demerit_score;
            }

            $i++;
        }

        $mask_content = 1 << $mask_number;

        // Format information

        $format_information_value = (($ec << 3) | $mask_number);
        $format_information_array = array(
            '101010000010010', '101000100100101', '101111001111100', '101101101001011',
            '100010111111001', '100000011001110', '100111110010111', '100101010100000',
            '111011111000100', '111001011110011', '111110110101010', '111100010011101',
            '110011000101111', '110001100011000', '110110001000001', '110100101110110',
            '001011010001001', '001001110111110', '001110011100111', '001100111010000',
            '000011101100010', '000001001010101', '000110100001100', '000100000111011',
            '011010101011111', '011000001101000', '011111100110001', '011101000000110',
            '010010010110100', '010000110000011', '010111011011010', '010101111101101'
        );
        $i = 0;
        while ($i < 15) {
            $content = substr($format_information_array[$format_information_value], $i, 1);

            $matrix_content[$format_information_x1[$i]][$format_information_y1[$i]] = $content * 255;
            $matrix_content[$format_information_x2[$i+1]][$format_information_y2[$i+1]] = $content * 255;
            $i++;
        }

        $mib = $max_modules_1side + 8;
        // Determine the size of per module (one little black square)
        // png uses 4 pixels, jpeg uses 8 pixels
        $module_size = ($this->output_image_type === 'jpeg') ? $this->pixels_per_module * 2 : $this->pixels_per_module;
        $image_size = $mib * $module_size;
        if ($image_size > 1480) {
          trigger_error("QRcode : Too large image size", E_USER_ERROR);
        }
        $output_image = imagecreate($image_size, $image_size);

        $this->image_path = $this->image_path.'qrv'.$this->qrcode_version.'.png';

        $base_image = imagecreatefrompng($this->image_path);

        $col[1] = imagecolorallocate($base_image, 0, 0, 0);
        $col[0] = imagecolorallocate($base_image, 255, 255, 255);

        $i = 4;
        $mxe = 4 + $max_modules_1side;
        $ii = 0;
        while ($i < $mxe) {
            $j = 4;
            $jj = 0;
            while ($j < $mxe){
                if ($matrix_content[$ii][$jj] & $mask_content) {
                    imagesetpixel($base_image, $i, $j, $col[1]); 
                }
                $j++;
                $jj++;
            }
            $i++;
            $ii++;
        }
        
        // Output image
        header("Content-type: image/".$this->output_image_type);
        imagecopyresized($output_image, $base_image, 0, 0, 0, 0, $image_size, $image_size, $mib, $mib);
        if ($this->output_image_type === 'jpeg') {
            imagejpeg($output_image);
        } else {
            imagepng($output_image);
        }
    }
    
}

/* End of file: ./system/libraries/markdown/qrcode_library.php */
