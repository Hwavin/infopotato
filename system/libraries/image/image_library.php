<?php
/**
 * Image Manipulation Library
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @link		http://codeigniter.com/user_guide/libraries/image_lib.html
 * @copyright Copyright &copy; 2009-2011 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
class Image_Library {
    /**
	 * Sets the image library to be used (Can be:  imagemagick, netpbm, gd, gd2)
	 * 
	 * Availability: R, C, X, W
	 * 
	 * @var string 
	 */
	public $image_library_to_use = 'gd2';
	
	/**
	 * Sets the server path to your ImageMagick or NetPBM library. 
	 * If you use either of those libraries you must supply the path.
	 * 
	 * Availability: R, C, X
	 * 
	 * @var string 
	 */
	public $library_path = '';
	
	/**
	 * Determines whether the new image file should be written to disk or generated dynamically. 
	 * Whether to send to browser or write to disk
	 * Note: If you choose the dynamic setting, only one image can be shown at a time, 
	 * and it can't be positioned on the page. 
	 * It simply outputs the raw image dynamically to your browser, along with image headers.
	 * 
	 * Availability: R, C, X, W
	 * 
	 * @var string 
	 */
	public $dynamic_output = FALSE;	
	
	/**
	 * Sets the source image name/path. 
	 * The path must be a relative or absolute server path, not a URL.
	 * 
	 * Availability: R, C, X, W
	 * 
	 * @var string 
	 */
	public $source_image = '';
	
	/**
	 * Sets the destination image name/path. You'll use this preference when creating an image copy. 
	 * The path must be a relative or absolute server path, not a URL.
	 * 
	 * Availability: R, C, X, W
	 * 
	 * @var string 
	 */
	public $new_image = '';
	
	/**
	 * Sets the width you would like the image set to.
	 * 
	 * Availability: R, C
	 * 
	 * @var string 
	 */
	public $width = '';
	
	/**
	 * Sets the height you would like the image set to.
	 * 
	 * Availability: R, C
	 * 
	 * @var string 
	 */
	public $height = '';
	
	/**
	 * Sets the quality of the image. The higher the quality the larger the file size.
	 * 
	 * Availability: R, C, X, W
	 * 
	 * @var string 
	 */
	public $quality = '90';
	
	/**
	 * Tells the image processing function to create a thumb.
	 * 
	 * Availability: R
	 * 
	 * @var boolean
	 */
	public $create_thumb = FALSE;
	
	/**
	 * Specifies the thumbnail indicator. It will be inserted just before the file extension, 
	 * so mypic.jpg would become mypic_thumb.jpg 
	 * 
	 * Availability: R
	 * 
	 * @var string 
	 */
	public $thumb_marker = '_thumb';
	
	/**
	 * Specifies whether to maintain the original aspect ratio when resizing or use hard values.
	 * 
	 * Availability: R, C
	 * 
	 * @var boolean
	 */
	public $maintain_ratio = TRUE;	
	
	/**
	 * Specifies what to use as the master axis when resizing or creating thumbs. 
	 * For example, let's say you want to resize an image to 100 X 75 pixels. 
	 * If the source image size does not allow perfect resizing to those dimensions, 
	 * this setting determines which axis should be used as the hard value. 
	 * "auto" sets the axis automatically based on whether the image is taller then wider, or vice versa.
	 *
	 * auto, height, or width.  Determines what to use as the master dimension
	 * 
	 * Availability: R
	 * 
	 * @var string 
	 */
	public $master_dim = 'auto';
	
	/**
	 * Specifies the angle of rotation when rotating images. 
	 * Note that PHP rotates counter-clockwise, 
	 * so a 90 degree rotation to the right must be specified as 270.
	 * 
	 * Availability: X
	 * 
	 * @var string 
	 */
	public $rotation_angle = '';
	
	/**
	 * Sets the X coordinate in pixels for image cropping. 
	 * For example, a setting of 30 will crop an image 30 pixels from the left.
	 * 
	 * Availability: C
	 * 
	 * @var string 
	 */
	public $x_axis = '';
	
	/**
	 * Sets the Y coordinate in pixels for image cropping. 
	 * For example, a setting of 30 will crop an image 30 pixels from the top.
	 * 
	 * Availability: C
	 * 
	 * @var string 
	 */
	public $y_axis = '';

	// Watermark Vars
	
	/**
	 * The text you would like shown as the watermark. 
	 * Typically this will be a copyright notice.
	 * 
	 * @var string 
	 */
	public $wm_text = '';
	
	/**
	 * Sets the type of watermarking that should be used.
	 * Options:  text/overlay
	 * 
	 * @var string 
	 */
	public $wm_type = 'text';
	
	/**
	 * If your watermark image is a PNG or GIF image, 
	 * you may specify a color on the image to be "transparent". 
	 * This setting (along with the next) will allow you to specify that color. 
	 * This works by specifying the "X" and "Y" coordinate pixel (measured from the upper left) 
	 * within the image that corresponds to a pixel representative of the color you want to be transparent.
	 * 
	 * @var integer
	 */
	public $wm_x_transp	= 4;
	
	/**
	 * Along with the previous setting, this allows you to specify the coordinate 
	 * to a pixel representative of the color you want to be transparent.
	 * 
	 * @var integer
	 */
	public $wm_y_transp = 4;
	
	/**
	 * The server path to the image you wish to use as your watermark. 
	 * Required only if you are using the overlay method.
	 * 
	 * @var string 
	 */
	public $wm_overlay_path	= '';
	
	/**
	 * The server path to the True Type Font you would like to use. 
	 * If you do not use this option, the native GD font will be used.
	 * 
	 * @var string 
	 */
	public $wm_font_path = '';
	
	/**
	 * The size of the text. Note: If you are not using the True Type option above, 
	 * the number is set using a range of 1 - 5. 
	 * Otherwise, you can use any valid pixel size for the font you're using.
	 * 
	 * @var integer
	 */
	public $wm_font_size = 17;
	
	/**
	 * Sets the vertical alignment for the watermark image.
	 * Vertical alignment:   T M B
	 * 
	 * @var string 
	 */
	public $wm_vrt_alignment = 'B';
	
	/**
	 * Sets the horizontal alignment for the watermark image.
	 * Horizontal alignment: L R C
	 * 
	 * @var string 
	 */
	public $wm_hor_alignment = 'C';	
	
	/**
	 * The amount of padding around text, set in pixels, 
	 * that will be applied to the watermark to set it away from the edge of your images.
	 * 
	 * @var integer
	 */
	public $wm_padding = 0;
	
	/**
	 * You may specify a horizontal offset (in pixels) to apply to the watermark position. 
	 * The offset normally moves the watermark to the right, except if you have your alignment 
	 * set to "right" then your offset value will move the watermark toward the left of the image.
	 * 
	 * @var integer
	 */
	public $wm_hor_offset = 0;
	
	/**
	 * You may specify a vertical offset (in pixels) to apply to the watermark position. 
	 * The offset normally moves the watermark down, except if you have your alignment 
	 * set to "bottom" then your offset value will move the watermark toward the top of the image.
	 * 
	 * @var integer
	 */
	public $wm_vrt_offset = 0;
	
	/**
	 * The font color, specified in hex. 
	 * Note, you must use the full 6 character hex value (ie, 993300), 
	 * rather than the three character abbreviated version (ie fff).
	 * 
	 * @var string 
	 */
	public $wm_font_color = '#ffffff';
	
	/**
	 * The color of the drop shadow, specified in hex. 
	 * If you leave this blank a drop shadow will not be used. 
	 * Note, you must use the full 6 character hex value (ie, 993300), 
	 * rather than the three character abbreviated version (ie fff).
	 * 
	 * @var string 
	 */
	public $wm_shadow_color	= '';
	
	/**
	 * The distance (in pixels) from the font that the drop shadow should appear.
	 * 
	 * @var integer
	 */
	public $wm_shadow_distance = 2;
	
	/**
	 * Image opacity. You may specify the opacity (i.e. transparency) of your watermark image. 
	 * This allows the watermark to be faint and not completely obscure the details 
	 * from the original image behind it. A 50% opacity is typical.
	 * Image opacity: 1 - 100  Only works with image
	 * 
	 * @var integer
	 */
	public $wm_opacity = 50;

	// Private Vars
	private $source_folder		= '';
	private $dest_folder		= '';
	private $mime_type			= '';
	private $orig_width			= '';
	private $orig_height		= '';
	private $image_type			= '';
	private $size_str			= '';
	private $full_src_path		= '';
	private $full_dst_path		= '';
	private $create_fnc			= 'imagecreatetruecolor';
	private $copy_fnc			= 'imagecopyresampled';
	private $error_msg			= array();
	private $wm_use_drop_shadow	= FALSE;
	private $wm_use_truetype	= FALSE;

	/**
	 * Constructor
	 *
	 * @param	string
	 * @return	void
	 */
	public function __construct($config = array()) {
		if (count($config) > 0) {
			// Convert array elements into class variables
			foreach ($config as $key => $val) {
				$this->$key = $val;
			}
		    // Check image preferences
			$this->_init();
		}
	}

	
	/**
	 * Check image preferences
	 *
	 * @access	private
	 * @param	array
	 * @return	bool
	 */
	private function _init() {
		// Is there a source image? If not, there's no reason to continue
		if ($this->source_image == '') {
			$this->_set_error('source_image_required');
			return FALSE;	
		}

		// Is getimagesize() Available?
		// We use it to determine the image properties (width/height).
		// Note: We need to figure out how to determine image
		// properties using ImageMagick and NetPBM
		if ( ! function_exists('getimagesize')) {
			$this->_set_error('gd_required_for_props');
			return FALSE;
		}

		$this->image_library_to_use = strtolower($this->image_library_to_use);

		// Set the full server path
		// The source image may or may not contain a path.
		// Either way, we'll try use realpath to generate the
		// full server path in order to more reliably read it.
		if (function_exists('realpath') && @realpath($this->source_image) !== FALSE) {
			$full_source_path = str_replace("\\", "/", realpath($this->source_image));
		} else {
			$full_source_path = $this->source_image;
		}

		$x = explode('/', $full_source_path);
		$this->source_image = end($x);
		$this->source_folder = str_replace($this->source_image, '', $full_source_path);

		// Set the Image Properties
		if ( ! $this->_get_image_properties($this->source_folder.$this->source_image)) {
			return FALSE;	
		}

		// Assign the "new" image name/path
		// If the user has set a "new_image" name it means
		// we are making a copy of the source image. If not
		// it means we are altering the original. 
		// We'll set the destination filename and path accordingly.
		if ($this->new_image == '') {
			$this->dest_image = $this->source_image;
			$this->dest_folder = $this->source_folder;
		} else {
			if (strpos($this->new_image, '/') === FALSE) {
				$this->dest_folder = $this->source_folder;
				$this->dest_image = $this->new_image;
			} else {
				if (function_exists('realpath') && @realpath($this->new_image) !== FALSE) {
					$full_dest_path = str_replace("\\", "/", realpath($this->new_image));
				} else {
					$full_dest_path = $this->new_image;
				}

				// Is there a file name?
				if ( ! preg_match("#\.(jpg|jpeg|gif|png)$#i", $full_dest_path)) {
					$this->dest_folder = $full_dest_path.'/';
					$this->dest_image = $this->source_image;
				} else {
					$x = explode('/', $full_dest_path);
					$this->dest_image = end($x);
					$this->dest_folder = str_replace($this->dest_image, '', $full_dest_path);
				}
			}
		}

		// Compile the finalized filenames/paths
		// We'll create two master strings containing the
		// full server path to the source image and the
		// full server path to the destination image.
		// We'll also split the destination image name
		// so we can insert the thumbnail marker if needed.
		if ($this->create_thumb === FALSE || $this->thumb_marker == '') {
			$this->thumb_marker = '';
		}

		$xp	= $this->_explode_name($this->dest_image);

		$filename = $xp['name'];
		$file_ext = $xp['ext'];

		$this->full_src_path = $this->source_folder.$this->source_image;
		$this->full_dst_path = $this->dest_folder.$filename.$this->thumb_marker.$file_ext;

		// Should we maintain image proportions?
		// When creating thumbs or copies, the target width/height
		// might not be in correct proportion with the source
		// image's width/height.  We'll recalculate it here.
		if ($this->maintain_ratio === TRUE && ($this->width != '' && $this->height != '')) {
			$this->_image_reproportion();
		}

		// Was a width and height specified?
		// If the destination width/height was
		// not submitted we will use the values
		// from the actual file
		if ($this->width == '') {
			$this->width = $this->orig_width;
        }
		
		if ($this->height == '') {
			$this->height = $this->orig_height;
        }
		
		// Set the quality
		$this->quality = trim(str_replace("%", "", $this->quality));

		if ($this->quality == '' || $this->quality == 0 || ! is_numeric($this->quality)) {
			$this->quality = 90;
        }
		
		// Set the x/y coordinates
		$this->x_axis = ($this->x_axis == '' || ! is_numeric($this->x_axis)) ? 0 : $this->x_axis;
		$this->y_axis = ($this->y_axis == '' || ! is_numeric($this->y_axis)) ? 0 : $this->y_axis;

		// Watermark-related Stuff...
		if ($this->wm_font_color != '') {
			if (strlen($this->wm_font_color) == 6) {
				$this->wm_font_color = '#'.$this->wm_font_color;
			}
		}

		if ($this->wm_shadow_color != '') {
			if (strlen($this->wm_shadow_color) == 6) {
				$this->wm_shadow_color = '#'.$this->wm_shadow_color;
			}
		}

		if ($this->wm_overlay_path != '') {
			$this->wm_overlay_path = str_replace("\\", "/", realpath($this->wm_overlay_path));
		}

		if ($this->wm_shadow_color != '') {
			$this->wm_use_drop_shadow = TRUE;
		}

		if ($this->wm_font_path != '') {
			$this->wm_use_truetype = TRUE;
		}

		return TRUE;
	}


	/**
	 * Resets values in case this class is used in a loop
	 *
	 * @access	public
	 * @return	void
	 */
	public function clear() {
		$props = array('source_folder', 'dest_folder', 'source_image', 'full_src_path', 'full_dst_path', 'new_image', 'image_type', 'size_str', 'quality', 'orig_width', 'orig_height', 'rotation_angle', 'x_axis', 'y_axis', 'create_fnc', 'copy_fnc', 'wm_overlay_path', 'wm_use_truetype', 'dynamic_output', 'wm_font_size', 'wm_text', 'wm_vrt_alignment', 'wm_hor_alignment', 'wm_padding', 'wm_hor_offset', 'wm_vrt_offset', 'wm_font_color', 'wm_use_drop_shadow', 'wm_shadow_color', 'wm_shadow_distance', 'wm_opacity');

		foreach ($props as $val) {
			$this->$val = '';
		}

		// special consideration for master_dim
		$this->master_dim = 'auto';
	}
	
	
	/**
	 * Image Resize: R
	 *
	 * This is a wrapper function that chooses the proper
	 * resize function based on the protocol specified
	 *
	 * @access	public
	 * @return	bool
	 */
	public function resize() {
		$protocol = '_image_process_'.$this->image_library_to_use;

		if (preg_match('/gd2$/i', $protocol)) {
			$protocol = '_image_process_gd';
		}

		return $this->$protocol('resize');
	}

	
	/**
	 * Image Crop: C
	 *
	 * This is a wrapper function that chooses the proper
	 * cropping function based on the protocol specified
	 *
	 * @access	public
	 * @return	bool
	 */
	public function crop() {
		$protocol = '_image_process_'.$this->image_library_to_use;

		if (preg_match('/gd2$/i', $protocol)) {
			$protocol = '_image_process_gd';
		}

		return $this->$protocol('crop');
	}


	/**
	 * Image Rotate: X
	 *
	 * This is a wrapper function that chooses the proper
	 * rotation function based on the protocol specified
	 *
	 * @access	public
	 * @return	bool
	 */
	public function rotate() {
		// Allowed rotation values
		$degs = array(90, 180, 270, 'vrt', 'hor');

		if ($this->rotation_angle == '' || ! in_array($this->rotation_angle, $degs)) {
			$this->_set_error('rotation_angle_required');
			return FALSE;	
		}

		// Reassign the width and height
		if ($this->rotation_angle == 90 || $this->rotation_angle == 270) {
			$this->width	= $this->orig_height;
			$this->height	= $this->orig_width;
		} else {
			$this->width	= $this->orig_width;
			$this->height	= $this->orig_height;
		}


		// Choose resizing function
		if ($this->image_library_to_use == 'imagemagick' || $this->image_library_to_use == 'netpbm') {
			$protocol = '_image_process_'.$this->image_library_to_use;

			return $this->$protocol('rotate');
		}

		if ($this->rotation_angle == 'hor' || $this->rotation_angle == 'vrt') {
			return $this->_image_mirror_gd();
		} else {
			return $this->_image_rotate_gd();
		}
	}


	/**
	 * Image Process Using GD/GD2
	 *
	 * This function will resize or crop
	 *
	 * @param	string
	 * @return	bool
	 */
	private function _image_process_gd($action = 'resize') {
		$v2_override = FALSE;

		// If the target width/height match the source, AND if the new file name is not equal to the old file name
		// we'll simply make a copy of the original with the new name... assuming dynamic rendering is off.
		if ($this->dynamic_output === FALSE) {
			if ($this->orig_width == $this->width && $this->orig_height == $this->height) {
				if ($this->source_image != $this->new_image) {
					if (@copy($this->full_src_path, $this->full_dst_path)) {
						@chmod($this->full_dst_path, 0666);
					}
				}

				return TRUE;
			}
		}

		// Let's set up our values based on the action
		if ($action == 'crop') {
			//  Reassign the source width/height if cropping
			$this->orig_width  = $this->width;
			$this->orig_height = $this->height;

			// GD 2.0 has a cropping bug so we'll test for it
			if ($this->_gd_version() !== FALSE) {
				$gd_version = str_replace('0', '', $this->_gd_version());
				$v2_override = ($gd_version == 2) ? TRUE : FALSE;
			}
		} else {
			// If resizing the x/y axis must be zero
			$this->x_axis = 0;
			$this->y_axis = 0;
		}

		//  Create the image handle
		if ( ! ($src_img = $this->_image_create_gd())) {
			return FALSE;
		}

		//  Create The Image
		//
		//  old conditional which users report cause problems with shared GD libs who report themselves as "2.0 or greater"
		//  it appears that this is no longer the issue that it was in 2004, so we've removed it, retaining it in the comment
		//  below should that ever prove inaccurate.
		//
		//  if ($this->image_library_to_use == 'gd2' AND function_exists('imagecreatetruecolor') AND $v2_override == FALSE)
		if ($this->image_library_to_use == 'gd2' && function_exists('imagecreatetruecolor')) {
			$create	= 'imagecreatetruecolor';
			$copy	= 'imagecopyresampled';
		} else {
			$create	= 'imagecreate';
			$copy	= 'imagecopyresized';
		}

		$dst_img = $create($this->width, $this->height);

		// png we can actually preserve transparency
		if ($this->image_type == 3) {
			imagealphablending($dst_img, FALSE);
			imagesavealpha($dst_img, TRUE);
		}

		$copy($dst_img, $src_img, 0, 0, $this->x_axis, $this->y_axis, $this->width, $this->height, $this->orig_width, $this->orig_height);

		//  Show the image
		if ($this->dynamic_output == TRUE) {
			$this->_image_display_gd($dst_img);
		} else {
			// Or save it
			if ( ! $this->_image_save_gd($dst_img)) {
				return FALSE;
			}
		}

		//  Kill the file handles
		imagedestroy($dst_img);
		imagedestroy($src_img);

		// Set the file to 777
		@chmod($this->full_dst_path, 0666);

		return TRUE;
	}


	/**
	 * Image Process Using ImageMagick
	 *
	 * This function will resize, crop or rotate
	 *
	 * @param	string
	 * @return	bool
	 */
	private function _image_process_imagemagick($action = 'resize') {
		//  Do we have a vaild library path?
		if ($this->library_path == '') {
			$this->_set_error('libpath_invalid');
			return FALSE;
		}

		if ( ! preg_match("/convert$/i", $this->library_path)) {
			$this->library_path = rtrim($this->library_path, '/').'/';

			$this->library_path .= 'convert';
		}

		// Execute the command
		$cmd = $this->library_path." -quality ".$this->quality;

		if ($action == 'crop') {
			$cmd .= " -crop ".$this->width."x".$this->height."+".$this->x_axis."+".$this->y_axis." \"$this->full_src_path\" \"$this->full_dst_path\" 2>&1";
		} elseif ($action == 'rotate') {
			switch ($this->rotation_angle) {
				case 'hor'	: 
				    $angle = '-flop';
					break;
				
				case 'vrt' : 
				    $angle = '-flip';
					break;
				
				default : 
				    $angle = '-rotate '.$this->rotation_angle;
					break;
			}

			$cmd .= " ".$angle." \"$this->full_src_path\" \"$this->full_dst_path\" 2>&1";
		} else {
			// Resize
			$cmd .= " -resize ".$this->width."x".$this->height." \"$this->full_src_path\" \"$this->full_dst_path\" 2>&1";
		}

		$retval = 1;

		@exec($cmd, $output, $retval);

		//	Did it work?
		if ($retval > 0) {
			$this->_set_error('image_process_failed');
			return FALSE;
		}

		// Set the file to 777
		@chmod($this->full_dst_path, 0666);

		return TRUE;
	}


	/**
	 * Image Process Using NetPBM
	 *
	 * This function will resize, crop or rotate
	 *
	 * @param	string
	 * @return	bool
	 */
	private function _image_process_netpbm($action = 'resize') {
		if ($this->library_path == '') {
			$this->_set_error('imglib_libpath_invalid');
			return FALSE;
		}

		//  Build the resizing command
		switch ($this->image_type) {
			case 1 :
				$cmd_in		= 'giftopnm';
				$cmd_out	= 'ppmtogif';
				break;
			
			case 2 :
				$cmd_in		= 'jpegtopnm';
				$cmd_out	= 'ppmtojpeg';
				break;
			
			case 3 :
				$cmd_in		= 'pngtopnm';
				$cmd_out	= 'ppmtopng';
				break;
		}

		if ($action == 'crop') {
			$cmd_inner = 'pnmcut -left '.$this->x_axis.' -top '.$this->y_axis.' -width '.$this->width.' -height '.$this->height;
		} elseif ($action == 'rotate') {
			switch ($this->rotation_angle) {
				case 90 :	
				    $angle = 'r270';
					break;
				
				case 180 :	
				    $angle = 'r180';
					break;
				
				case 270 :	
				    $angle = 'r90';
					break;
				
				case 'vrt' :	
				    $angle = 'tb';
					break;
				
				case 'hor' :	
				    $angle = 'lr';
					break;
			}

			$cmd_inner = 'pnmflip -'.$angle.' ';
		} else {
			// Resize
			$cmd_inner = 'pnmscale -xysize '.$this->width.' '.$this->height;
		}

		$cmd = $this->library_path.$cmd_in.' '.$this->full_src_path.' | '.$cmd_inner.' | '.$cmd_out.' > '.$this->dest_folder.'netpbm.tmp';

		$retval = 1;

		@exec($cmd, $output, $retval);

		//  Did it work?
		if ($retval > 0) {
			$this->_set_error('image_process_failed');
			return FALSE;
		}

		// With NetPBM we have to create a temporary image.
		// If you try manipulating the original it fails so
		// we have to rename the temp file.
		copy ($this->dest_folder.'netpbm.tmp', $this->full_dst_path);
		unlink ($this->dest_folder.'netpbm.tmp');
		@chmod($this->full_dst_path, 0666);

		return TRUE;
	}


	/**
	 * Image Rotate Using GD
	 *
	 * @return	bool
	 */
	private function _image_rotate_gd() {
		//  Create the image handle
		if ( ! ($src_img = $this->_image_create_gd())) {
			return FALSE;
		}

		// Set the background color
		// This won't work with transparent PNG files so we are
		// going to have to figure out how to determine the color
		// of the alpha channel in a future release.

		$white	= imagecolorallocate($src_img, 255, 255, 255);

		//  Rotate it!
		$dst_img = imagerotate($src_img, $this->rotation_angle, $white);

		//  Save the Image
		if ($this->dynamic_output == TRUE) {
			$this->_image_display_gd($dst_img);
		} else {
			// Or save it
			if ( ! $this->_image_save_gd($dst_img)) {
				return FALSE;
			}
		}

		//  Kill the file handles
		imagedestroy($dst_img);
		imagedestroy($src_img);

		// Set the file to 777

		@chmod($this->full_dst_path, 0666);

		return TRUE;
	}


	/**
	 * Create Mirror Image using GD
	 *
	 * This function will flip horizontal or vertical
	 *
	 * @return	bool
	 */
	private function _image_mirror_gd() {
		if ( ! $src_img = $this->_image_create_gd()) {
			return FALSE;
		}

		$width  = $this->orig_width;
		$height = $this->orig_height;

		if ($this->rotation_angle == 'hor') {
			for ($i = 0; $i < $height; $i++) {
				$left  = 0;
				$right = $width-1;

				while ($left < $right) {
					$cl = imagecolorat($src_img, $left, $i);
					$cr = imagecolorat($src_img, $right, $i);

					imagesetpixel($src_img, $left, $i, $cr);
					imagesetpixel($src_img, $right, $i, $cl);

					$left++;
					$right--;
				}
			}
		} else {
			for ($i = 0; $i < $width; $i++) {
				$top = 0;
				$bot = $height-1;

				while ($top < $bot) {
					$ct = imagecolorat($src_img, $i, $top);
					$cb = imagecolorat($src_img, $i, $bot);

					imagesetpixel($src_img, $i, $top, $cb);
					imagesetpixel($src_img, $i, $bot, $ct);

					$top++;
					$bot--;
				}
			}
		}

		//  Show the image
		if ($this->dynamic_output == TRUE) {
			$this->_image_display_gd($src_img);
		} else {
			// Or save it
			if ( ! $this->_image_save_gd($src_img)) {
				return FALSE;
			}
		}

		//  Kill the file handles
		imagedestroy($src_img);

		// Set the file to 777
		@chmod($this->full_dst_path, 0666);

		return TRUE;
	}


	/**
	 * Image Watermark: W
	 *
	 * This is a wrapper function that chooses the type
	 * of watermarking based on the specified preference.
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	public function watermark() {
		if ($this->wm_type == 'overlay') {
			return $this->_overlay_watermark();
		} else {
			return $this->_text_watermark();
		}
	}


	/**
	 * Watermark - Graphic Version
	 *
	 * @return	bool
	 */
	private function _overlay_watermark() {
		if ( ! function_exists('imagecolortransparent')) {
			$this->_set_error('gd_required');
			return FALSE;
		}

		//  Fetch source image properties
		$this->_get_image_properties();

		//  Fetch watermark image properties
		$props			= $this->_get_image_properties($this->wm_overlay_path, TRUE);
		$wm_img_type	= $props['image_type'];
		$wm_width		= $props['width'];
		$wm_height		= $props['height'];

		//  Create two image resources
		$wm_img  = $this->_image_create_gd($this->wm_overlay_path, $wm_img_type);
		$src_img = $this->_image_create_gd($this->full_src_path);

		// Reverse the offset if necessary
		// When the image is positioned at the bottom
		// we don't want the vertical offset to push it
		// further down.  We want the reverse, so we'll
		// invert the offset.  Same with the horizontal
		// offset when the image is at the right

		$this->wm_vrt_alignment = strtoupper(substr($this->wm_vrt_alignment, 0, 1));
		$this->wm_hor_alignment = strtoupper(substr($this->wm_hor_alignment, 0, 1));

		if ($this->wm_vrt_alignment == 'B') {
			$this->wm_vrt_offset = $this->wm_vrt_offset * -1;
        }
		
		if ($this->wm_hor_alignment == 'R') {
			$this->wm_hor_offset = $this->wm_hor_offset * -1;
        }
		
		//  Set the base x and y axis values
		$x_axis = $this->wm_hor_offset + $this->wm_padding;
		$y_axis = $this->wm_vrt_offset + $this->wm_padding;

		//  Set the vertical position
		switch ($this->wm_vrt_alignment) {
			case 'T':
				break;
			
			case 'M':	
			    $y_axis += ($this->orig_height / 2) - ($wm_height / 2);
				break;
			
			case 'B':	
			    $y_axis += $this->orig_height - $wm_height;
				break;
		}

		//  Set the horizontal position
		switch ($this->wm_hor_alignment) {
			case 'L':
				break;
			
			case 'C':	
			    $x_axis += ($this->orig_width / 2) - ($wm_width / 2);
				break;
			
			case 'R':	
			    $x_axis += $this->orig_width - $wm_width;
				break;
		}

		//  Build the finalized image
		if ($wm_img_type == 3 && function_exists('imagealphablending')) {
			@imagealphablending($src_img, TRUE);
		}

		// Set RGB values for text and shadow
		$rgba = imagecolorat($wm_img, $this->wm_x_transp, $this->wm_y_transp);
		$alpha = ($rgba & 0x7F000000) >> 24;

		// make a best guess as to whether we're dealing with an image with alpha transparency or no/binary transparency
		if ($alpha > 0) {
			// copy the image directly, the image's alpha transparency being the sole determinant of blending
			imagecopy($src_img, $wm_img, $x_axis, $y_axis, 0, 0, $wm_width, $wm_height);
		} else {
			// set our RGB value from above to be transparent and merge the images with the specified opacity
			imagecolortransparent($wm_img, imagecolorat($wm_img, $this->wm_x_transp, $this->wm_y_transp));
			imagecopymerge($src_img, $wm_img, $x_axis, $y_axis, 0, 0, $wm_width, $wm_height, $this->wm_opacity);
		}

		//  Output the image
		if ($this->dynamic_output == TRUE) {
			$this->_image_display_gd($src_img);
		} else {
			if ( ! $this->_image_save_gd($src_img)) {
				return FALSE;
			}
		}

		imagedestroy($src_img);
		imagedestroy($wm_img);

		return TRUE;
	}


	/**
	 * Watermark - Text Version
	 *
	 * @return	bool
	 */
	private function _text_watermark() {
		if ( ! ($src_img = $this->_image_create_gd())) {
			return FALSE;
		}

		if ($this->wm_use_truetype == TRUE && ! file_exists($this->wm_font_path)) {
			$this->_set_error('missing_font');
			return FALSE;
		}

		//  Fetch source image properties
		$this->_get_image_properties();

		// Set RGB values for text and shadow
		$this->wm_font_color	= str_replace('#', '', $this->wm_font_color);
		$this->wm_shadow_color	= str_replace('#', '', $this->wm_shadow_color);

		$R1 = hexdec(substr($this->wm_font_color, 0, 2));
		$G1 = hexdec(substr($this->wm_font_color, 2, 2));
		$B1 = hexdec(substr($this->wm_font_color, 4, 2));

		$R2 = hexdec(substr($this->wm_shadow_color, 0, 2));
		$G2 = hexdec(substr($this->wm_shadow_color, 2, 2));
		$B2 = hexdec(substr($this->wm_shadow_color, 4, 2));

		$txt_color	= imagecolorclosest($src_img, $R1, $G1, $B1);
		$drp_color	= imagecolorclosest($src_img, $R2, $G2, $B2);

		// Reverse the vertical offset
		// When the image is positioned at the bottom
		// we don't want the vertical offset to push it
		// further down.  We want the reverse, so we'll
		// invert the offset.  Note: The horizontal
		// offset flips itself automatically

		if ($this->wm_vrt_alignment == 'B') {
			$this->wm_vrt_offset = $this->wm_vrt_offset * -1;
        }
		
		if ($this->wm_hor_alignment == 'R') {
			$this->wm_hor_offset = $this->wm_hor_offset * -1;
        }
		// Set font width and height
		// These are calculated differently depending on
		// whether we are using the true type font or not
		if ($this->wm_use_truetype == TRUE) {
			if ($this->wm_font_size == '') {
				$this->wm_font_size = '17';
            }
			$fontwidth  = $this->wm_font_size-($this->wm_font_size/4);
			$fontheight = $this->wm_font_size;
			$this->wm_vrt_offset += $this->wm_font_size;
		} else {
			$fontwidth  = imagefontwidth($this->wm_font_size);
			$fontheight = imagefontheight($this->wm_font_size);
		}

		// Set base X and Y axis values
		$x_axis = $this->wm_hor_offset + $this->wm_padding;
		$y_axis = $this->wm_vrt_offset + $this->wm_padding;

		// Set verticle alignment
		if ($this->wm_use_drop_shadow == FALSE) {
			$this->wm_shadow_distance = 0;
        }
		$this->wm_vrt_alignment = strtoupper(substr($this->wm_vrt_alignment, 0, 1));
		$this->wm_hor_alignment = strtoupper(substr($this->wm_hor_alignment, 0, 1));

		switch ($this->wm_vrt_alignment) {
			case  "T" :
				break;
			
			case "M":	
			    $y_axis += ($this->orig_height/2)+($fontheight/2);
				break;
			
			case "B":	
			    $y_axis += ($this->orig_height - $fontheight - $this->wm_shadow_distance - ($fontheight/2));
				break;
		}

		$x_shad = $x_axis + $this->wm_shadow_distance;
		$y_shad = $y_axis + $this->wm_shadow_distance;

		// Set horizontal alignment
		switch ($this->wm_hor_alignment) {
			case "L":
				break;
			
			case "R":
				if ($this->wm_use_drop_shadow) {
					$x_shad += ($this->orig_width - $fontwidth*strlen($this->wm_text));
				}
				$x_axis += ($this->orig_width - $fontwidth*strlen($this->wm_text));
				break;
			
			case "C":
				if ($this->wm_use_drop_shadow) {
					$x_shad += floor(($this->orig_width - $fontwidth*strlen($this->wm_text))/2);
				}
				$x_axis += floor(($this->orig_width  -$fontwidth*strlen($this->wm_text))/2);
				break;
		}

		//  Add the text to the source image
		if ($this->wm_use_truetype) {
			if ($this->wm_use_drop_shadow) {
				imagettftext($src_img, $this->wm_font_size, 0, $x_shad, $y_shad, $drp_color, $this->wm_font_path, $this->wm_text);
			}
			imagettftext($src_img, $this->wm_font_size, 0, $x_axis, $y_axis, $txt_color, $this->wm_font_path, $this->wm_text);
		} else {
			if ($this->wm_use_drop_shadow) {
				imagestring($src_img, $this->wm_font_size, $x_shad, $y_shad, $this->wm_text, $drp_color);
			}
			imagestring($src_img, $this->wm_font_size, $x_axis, $y_axis, $this->wm_text, $txt_color);
		}

		//  Output the final image
		if ($this->dynamic_output == TRUE) {
			$this->_image_display_gd($src_img);
		} else {
			$this->_image_save_gd($src_img);
		}

		imagedestroy($src_img);

		return TRUE;
	}


	/**
	 * Create Image - GD
	 *
	 * This simply creates an image resource handle
	 * based on the type of image being processed
	 *
	 * @param	string
	 * @return	resource
	 */
	private function _image_create_gd($path = '', $image_type = '') {
		if ($path == '') {
			$path = $this->full_src_path;
        }
		
		if ($image_type == '') {
			$image_type = $this->image_type;
        }

		switch ($image_type) {
			case 1 :
				if ( ! function_exists('imagecreatefromgif')) {
					$this->_set_error(array('unsupported_imagecreate', 'gif_not_supported'));
					return FALSE;
				}

				return imagecreatefromgif($path);
				break;
			
			case 2 :
				if ( ! function_exists('imagecreatefromjpeg')) {
					$this->_set_error(array('unsupported_imagecreate', 'jpg_not_supported'));
					return FALSE;
				}

				return imagecreatefromjpeg($path);
				break;
			
			case 3 :
				if ( ! function_exists('imagecreatefrompng')) {
					$this->_set_error(array('unsupported_imagecreate', 'png_not_supported'));
					return FALSE;
				}

				return imagecreatefrompng($path);
				break;

		}

		$this->_set_error(array('unsupported_imagecreate'));
		return FALSE;
	}


	/**
	 * Write image file to disk - GD
	 *
	 * Takes an image resource as input and writes the file
	 * to the specified destination
	 *
	 * @param	resource
	 * @return	bool
	 */
	private function _image_save_gd($resource) {
		switch ($this->image_type) {
			case 1 :
				if ( ! function_exists('imagegif')) {
					$this->_set_error(array('unsupported_imagecreate', 'gif_not_supported'));
					return FALSE;
			    }

			    if ( ! @imagegif($resource, $this->full_dst_path)) {
				    $this->_set_error('save_failed');
				    return FALSE;
			    }
				break;
			
			case 2 :
				if ( ! function_exists('imagejpeg')) {
					$this->_set_error(array('unsupported_imagecreate', 'jpg_not_supported'));
					return FALSE;
				}

				if ( ! @imagejpeg($resource, $this->full_dst_path, $this->quality)) {
					$this->_set_error('save_failed');
					return FALSE;
				}
				break;
			
			case 3 :
				if ( ! function_exists('imagepng')) {
					$this->_set_error(array('unsupported_imagecreate', 'png_not_supported'));
					return FALSE;
				}

				if ( ! @imagepng($resource, $this->full_dst_path)) {
					$this->_set_error('save_failed');
					return FALSE;
				}
				break;
			
			default	:
				$this->_set_error(array('unsupported_imagecreate'));
				return FALSE;
				break;
		}

		return TRUE;
	}


	/**
	 * Dynamically outputs an image
	 *
	 * @param	resource
	 * @return	void
	 */
	private function _image_display_gd($resource) {
		header("Content-Disposition: filename={$this->source_image};");
		header("Content-Type: {$this->mime_type}");
		header('Content-Transfer-Encoding: binary');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()).' GMT');

		switch ($this->image_type) {
			case 1 :	
			    imagegif($resource);
				break;
			
			case 2 :	
			    imagejpeg($resource, '', $this->quality);
				break;
			
			case 3 :	
			    imagepng($resource);
				break;
			
			default :	
			    echo 'Unable to display the image';
				break;
		}
	}


	/**
	 * Re-proportion Image Width/Height
	 *
	 * When creating thumbs, the desired width/height
	 * can end up warping the image due to an incorrect
	 * ratio between the full-sized image and the thumb.
	 *
	 * This function lets us re-proportion the width/height
	 * if users choose to maintain the aspect ratio when resizing.
	 *
	 * @return	void
	 */
	private function _image_reproportion() {
		if ( ! is_numeric($this->width) || ! is_numeric($this->height) || $this->width == 0 || $this->height == 0) {
			return;
        }
		
		if ( ! is_numeric($this->orig_width) || ! is_numeric($this->orig_height) || $this->orig_width == 0 || $this->orig_height == 0) {
			return;
        }
		
		$new_width	= ceil($this->orig_width*$this->height/$this->orig_height);
		$new_height	= ceil($this->width*$this->orig_height/$this->orig_width);

		$ratio = (($this->orig_height/$this->orig_width) - ($this->height/$this->width));

		if ($this->master_dim != 'width' && $this->master_dim != 'height') {
			$this->master_dim = ($ratio < 0) ? 'width' : 'height';
		}

		if (($this->width != $new_width) && ($this->height != $new_height)) {
			if ($this->master_dim == 'height') {
				$this->width = $new_width;
			} else {
				$this->height = $new_height;
			}
		}
	}


	/**
	 * Get image properties
	 *
	 * A helper function that gets info about the file
	 *
	 * @param	string
	 * @return	mixed
	 */
	private function _get_image_properties($path = '', $return = FALSE) {
		// For now we require GD but we should
		// find a way to determine this using IM or NetPBM

		if ($path == '') {
			$path = $this->full_src_path;
        }
		
		if ( ! file_exists($path)) {
			$this->_set_error('invalid_path');
			return FALSE;
		}

		$vals = @getimagesize($path);

		$types = array(1 => 'gif', 2 => 'jpeg', 3 => 'png');

		$mime = (isset($types[$vals['2']])) ? 'image/'.$types[$vals['2']] : 'image/jpg';

		if ($return == TRUE) {
			$v['width']			= $vals['0'];
			$v['height']		= $vals['1'];
			$v['image_type']	= $vals['2'];
			$v['size_str']		= $vals['3'];
			$v['mime_type']		= $mime;

			return $v;
		}

		$this->orig_width	= $vals['0'];
		$this->orig_height	= $vals['1'];
		$this->image_type	= $vals['2'];
		$this->size_str		= $vals['3'];
		$this->mime_type	= $mime;

		return TRUE;
	}


	/**
	 * Size calculator
	 *
	 * This function takes a known width x height and
	 * recalculates it to a new size.  Only one
	 * new variable needs to be known
	 *
	 *	$props = array(
	 *					'width'			=> $width,
	 *					'height'		=> $height,
	 *					'new_width'		=> 40,
	 *					'new_height'	=> ''
	 *				  );
	 *
	 * @param	array
	 * @return	array
	 */
	private function size_calculator($vals) {
		if ( ! is_array($vals)) {
			return;
		}

		$allowed = array('new_width', 'new_height', 'width', 'height');

		foreach ($allowed as $item) {
			if ( ! isset($vals[$item]) || $vals[$item] == '') {
				$vals[$item] = 0;
			}
		}

		if ($vals['width'] == 0 || $vals['height'] == 0) {
			return $vals;
		}

		if ($vals['new_width'] == 0) {
			$vals['new_width'] = ceil($vals['width']*$vals['new_height']/$vals['height']);
		} elseif ($vals['new_height'] == 0) {
			$vals['new_height'] = ceil($vals['new_width']*$vals['height']/$vals['width']);
		}

		return $vals;
	}


	/**
	 * Explode source_image
	 *
	 * This is a helper function that extracts the extension
	 * from the source_image.  This function lets us deal with
	 * source_images with multiple periods, like:  my.cool.jpg
	 * It returns an associative array with two elements:
	 * $array['ext']  = '.jpg';
	 * $array['name'] = 'my.cool';
	 *
	 * @param	array
	 * @return	array
	 */
	private function _explode_name($source_image) {
		$ext = strrchr($source_image, '.');
		$name = ($ext === FALSE) ? $source_image : substr($source_image, 0, -strlen($ext));

		return array('ext' => $ext, 'name' => $name);
	}


	/**
	 * Is GD Installed?
	 *
	 * @return	bool
	 */
	private function gd_loaded() {
		if ( ! extension_loaded('gd')) {
			if ( ! dl('gd.so')) {
				return FALSE;
			}
		}

		return TRUE;
	}

	
	/**
	 * Get GD version
	 *
	 * @return	mixed
	 */
	private function _gd_version() {
		if (function_exists('gd_info')) {
			$gd_version = @gd_info();
			$gd_version = preg_replace("/\D/", "", $gd_version['GD Version']);

			return $gd_version;
		}

		return FALSE;
	}


	/**
	 * Set error message
	 *
	 * @param	string
	 * @return	void
	 */
	private function _set_error($msg) {
		$error_messages = array(
		    'source_image_required' => "You must specify a source image in your preferences.",
			'gd_required' => "The GD image library is required for this feature.",
			'gd_required_for_props' => "Your server must support the GD image library in order to determine the image properties.",
			'unsupported_imagecreate' => "Your server does not support the GD function required to process this type of image.",
			'gif_not_supported' => "GIF images are often not supported due to licensing restrictions.  You may have to use JPG or PNG images instead.",
			'jpg_not_supported' => "JPG images are not supported.",
			'png_not_supported' => "PNG images are not supported.",
			'jpg_or_png_required' => "The image resize protocol specified in your preferences only works with JPEG or PNG image types.",
			'copy_error' => "An error was encountered while attempting to replace the file.  Please make sure your file directory is writable.",
			'rotate_unsupported' => "Image rotation does not appear to be supported by your server.",
			'libpath_invalid' => "The path to your image library is not correct.  Please set the correct path in your image preferences.",
			'image_process_failed' => "Image processing failed.  Please verify that your server supports the chosen protocol and that the path to your image library is correct.",
			'rotation_angle_required' => "An angle of rotation is required to rotate the image.",
			'writing_failed_gif' => "GIF image.",
			'invalid_path' => "The path to the image is not correct.",
			'copy_failed' => "The image copy routine failed.",
			'missing_font' => "Unable to find a font to use.",
			'save_failed' => "Unable to save the image.  Please make sure the image and file directory are writable."
		);
		
		if (is_array($msg)) {
			foreach ($msg as $val) {
				$msg = ($error_messages[$val] == FALSE) ? $val : $error_messages[$val];
				$this->error_msg[] = $msg;
			}
		} else {
			$msg = ($error_messages[$msg] == FALSE) ? $msg : $error_messages[$msg];
			$this->error_msg[] = $msg;
		}
	}


	/**
	 * Show error messages
	 *
	 * @param	string
	 * @return	string
	 */
	public function display_errors($open = '<p>', $close = '</p>') {
		$str = '';
		foreach ($this->error_msg as $val) {
			$str .= $open.$val.$close;
		}

		return $str;
	}

}

/* End of file: ./system/libraries/image/image_library.php */