<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 

<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">Image Manipulation</h1>	
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/library/">Library</a> &gt; Image Manipulation
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/library/image/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<p>
InfoPotato's Image Manipulation library lets you perform the following actions:
</p> 

<ul>
<li>Image Resizing (Thumbnail Creation)</li>
<li>Image Cropping</li>
<li>Image Rotating</li>
<li>Image Watermarking</li>
</ul>

<p>
All three major image libraries are supported: GD/GD2, NetPBM, and ImageMagick
</p>
 
<div class="notebox">
Watermarking is only available using the GD/GD2 library. In addition, even though other libraries are supported, GD is required in order for the script to calculate the image properties. The image processing, however, will be performed with the library you specify.
</div>
 
<h2>Initializing the Class</h2> 
 
<p>Like most other classes in InfoPotato, the Image Manipulation class is initialized in your controller using the <dfn>$this->load_library</dfn> function:</p> 
 
<div class="syntax">
<pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;image/image_library&#39;</span><span class="p">,</span> <span class="s1">&#39;img&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span> 
</pre>
</div> 

<p>Once loaded, the Image object will be available using: <dfn>$this->img</dfn></p> 

<h2>Preferences</h2> 
 
<p>The  preferences described below allow you to tailor the image processing to suit your needs.</p> 
 
<p>Note that not all preferences are available for every
function.  For example, the x/y axis preferences are only available for image cropping. Likewise, the width and height
preferences have no effect on cropping.  The "availability" column indicates which functions support a given preference.</p> 
 
<p>Availability Legend:</p> 
 
<ul> 
<li><var>R</var> - Image Resizing</li> 
<li><var>C</var> - Image Cropping</li> 
<li><var>X</var> - Image Rotation</li> 
<li><var>W</var> - Image Watermarking</li> 
</ul> 
 

<table class="grid"> 
<tr> 
<th>Preference</th> 
<th>Default&nbsp;Value</th> 
<th>Options</th> 
<th>Description</th> 
<th>Availability</th> 
</tr> 
 
<tr> 
<td class="td"><strong>image_library</strong></td> 
<td class="td">GD2</td> 
<td class="td">GD, GD2, ImageMagick, NetPBM</td> 
<td class="td">Sets the image library to be used.</td> 
<td class="td">R, C, X, W</td> 
</tr> 
 
<tr> 
<td class="td"><strong>library_path</strong></td> 
<td class="td">None</td> 
<td class="td">None</td> 
<td class="td">Sets the server path to your ImageMagick or NetPBM library.  If you use either of those libraries you must supply the path.</td> 
<td class="td">R, C, X</td> 
</tr> 
 
<tr> 
<td class="td"><strong>source_image</strong></td> 
<td class="td">None</td> 
<td class="td">None</td> 
<td class="td">Sets the source image name/path.  The path must be a relative or absolute server path, not a URL.</td> 
<td class="td">R, C, S, W</td> 
</tr> 
 
<tr> 
<td class="td"><strong>dynamic_output</strong></td> 
<td class="td">FALSE</td> 
<td class="td">TRUE/FALSE (boolean)</td> 
<td class="td">Determines whether the new image file should be written to disk or generated dynamically.  Note: If you choose the dynamic setting, only one image can be shown at a time, and it can't be positioned on the page. It simply outputs the raw image dynamically to your browser, along with image headers.</td> 
<td class="td">R, C, X, W</td> 
</tr> 
 
 
<tr> 
<td class="td"><strong>quality</strong></td> 
<td class="td">90%</td> 
<td class="td">1 - 100%</td> 
<td class="td">Sets the quality of the image. The higher the quality the larger the file size.</td> 
<td class="td">R, C, X, W</td> 
</tr> 
 
 
<tr> 
<td class="td"><strong>new_image</strong></td> 
<td class="td">None</td> 
<td class="td">None</td> 
<td class="td">Sets the destination image name/path.  You'll use this preference when creating an image copy. The path must be a relative or absolute server path, not a URL.</td> 
<td class="td">R, C, X, W</td> 
</tr> 
 
<tr> 
<td class="td"><strong>width</strong></td> 
<td class="td">None</td> 
<td class="td">None</td> 
<td class="td">Sets the width you would like the image set to.</td> 
<td class="td">R, C </td> 
</tr> 
 
<tr> 
<td class="td"><strong>height</strong></td> 
<td class="td">None</td> 
<td class="td">None</td> 
<td class="td">Sets the height you would like the image set to.</td> 
<td class="td">R, C </td> 
</tr> 
 
<tr> 
<td class="td"><strong>create_thumb</strong></td> 
<td class="td">FALSE</td> 
<td class="td">TRUE/FALSE (boolean)</td> 
<td class="td">Tells the image processing function to create a thumb.</td> 
<td class="td">R</td> 
</tr> 
 
<tr> 
<td class="td"><strong>thumb_marker</strong></td> 
<td class="td">_thumb</td> 
<td class="td">None</td> 
<td class="td">Specifies the thumbnail indicator.  It will be inserted just before the file extension, so mypic.jpg would become mypic_thumb.jpg</td> 
<td class="td">R</td> 
</tr> 
 
<tr> 
<td class="td"><strong>maintain_ratio</strong></td> 
<td class="td">TRUE</td> 
<td class="td">TRUE/FALSE (boolean)</td> 
<td class="td">Specifies whether to maintain the original aspect ratio when resizing or use hard values.</td> 
<td class="td">R, C</td> 
</tr> 
 
 
<tr> 
<td class="td"><strong>master_dim</strong></td> 
<td class="td">auto</td> 
<td class="td">auto, width, height</td> 
<td class="td">Specifies what to use as the master axis when resizing or creating thumbs. For example, let's say you want to resize an image to 100 X 75 pixels. If the source image size does not allow perfect resizing to those dimensions, this setting determines which axis should be used as the hard value. "auto" sets the axis automatically based on whether the image is taller then wider, or vice versa.</td> 
<td class="td">R</td> 
</tr> 
 
 
 
 
<tr> 
<td class="td"><strong>rotation_angle</strong></td> 
<td class="td">None</td> 
<td class="td">90, 180, 270, vrt, hor</td> 
<td class="td">Specifies the angle of rotation when rotating images.  Note that PHP rotates counter-clockwise, so a 90 degree rotation to the right must be specified as 270.</td> 
<td class="td">X</td> 
</tr> 
 
<tr> 
<td class="td"><strong>x_axis</strong></td> 
<td class="td">None</td> 
<td class="td">None</td> 
<td class="td">Sets the X coordinate in pixels for image cropping. For example, a setting of 30 will crop an image 30 pixels from the left.</td> 
<td class="td">C</td> 
</tr> 
<tr> 
<td class="td"><strong>y_axis</strong></td> 
<td class="td">None</td> 
<td class="td">None</td> 
<td class="td">Sets the Y coordinate in pixels for image cropping. For example, a setting of 30 will crop an image 30 pixels from the top.</td> 
<td class="td">C</td> 
</tr> 
 
</table> 
 

<h2>Image Resizing (Thumbnail Creation)</h2>

<p>
The image resizing function lets you resize the original image, create a copy (with or without resizing), or create a thumbnail image.
</p>

<p>
For practical purposes there is no difference between creating a copy and creating a thumbnail except a thumb will have the thumbnail marker as part of the name (ie, mypic_thumb.jpg).
</p>

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">final</span> <span class="k">class</span> <span class="nc">Img_Manager</span> <span class="k">extends</span> <span class="nx">Manager</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">get_resize</span><span class="p">()</span> <span class="p">{</span> 
 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;source_image&#39;</span><span class="p">]</span>	<span class="o">=</span> <span class="nx">APP_DIR</span><span class="o">.</span><span class="s1">&#39;t.jpg&#39;</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;create_thumb&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="k">TRUE</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;maintain_ratio&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="k">TRUE</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;width&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="m">120</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;height&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="m">90</span><span class="p">;</span> 
		
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;image/image_library&#39;</span><span class="p">,</span> <span class="s1">&#39;img&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span> 
 
         <span class="k">if</span> <span class="p">(</span> <span class="o">!</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">img</span><span class="o">-&gt;</span><span class="na">resize</span><span class="p">())</span> <span class="p">{</span> 
            <span class="k">echo</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">img</span><span class="o">-&gt;</span><span class="na">display_errors</span><span class="p">();</span> 
        <span class="p">}</span> 
    <span class="p">}</span> 
 
<span class="p">}</span> 
 
<span class="c1">// End of file: ./application/managers/img_manager.php</span> 
</pre></div> 


<h2>Image Cropping</h2>

<p>
The cropping function works nearly identically to the resizing function except it requires that you set preferences for the X and Y axis (in pixels) specifying where to crop, like this:
</p>

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">final</span> <span class="k">class</span> <span class="nc">Img_Manager</span> <span class="k">extends</span> <span class="nx">Manager</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">get_crop</span><span class="p">()</span> <span class="p">{</span> 
 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;image_library_to_use&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="s1">&#39;gd2&#39;</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;source_image&#39;</span><span class="p">]</span>	<span class="o">=</span> <span class="nx">APP_DIR</span><span class="o">.</span><span class="s1">&#39;t.jpg&#39;</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;x_axis&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="m">500</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;y_axis&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="m">200</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;width&#39;</span><span class="p">]</span>  <span class="o">=</span> <span class="m">205</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;height&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="m">200</span><span class="p">;</span> 
		
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;image/image_library&#39;</span><span class="p">,</span> <span class="s1">&#39;img&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span> 
 
         <span class="k">if</span> <span class="p">(</span> <span class="o">!</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">img</span><span class="o">-&gt;</span><span class="na">crop</span><span class="p">())</span> <span class="p">{</span> 
            <span class="k">echo</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">img</span><span class="o">-&gt;</span><span class="na">display_errors</span><span class="p">();</span> 
        <span class="p">}</span> 
    <span class="p">}</span> 
 
<span class="p">}</span> 
 
<span class="c1">// End of file: ./application/managers/img_manager.php</span> 
</pre></div> 

<h2>Image Rotating</h2>

<p>
The image rotation function requires that the angle of rotation be set via its preference:
</p>

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">final</span> <span class="k">class</span> <span class="nc">Img_Manager</span> <span class="k">extends</span> <span class="nx">Manager</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">get_rotate</span><span class="p">()</span> <span class="p">{</span> 
 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;source_image&#39;</span><span class="p">]</span>	<span class="o">=</span> <span class="nx">APP_DIR</span><span class="o">.</span><span class="s1">&#39;t.jpg&#39;</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;rotation_angle&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="s1">&#39;hor&#39;</span><span class="p">;</span> 
		
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;image/image_library&#39;</span><span class="p">,</span> <span class="s1">&#39;img&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span> 
 
         <span class="k">if</span> <span class="p">(</span> <span class="o">!</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">img</span><span class="o">-&gt;</span><span class="na">rotate</span><span class="p">())</span> <span class="p">{</span> 
            <span class="k">echo</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">img</span><span class="o">-&gt;</span><span class="na">display_errors</span><span class="p">();</span> 
        <span class="p">}</span> 
    <span class="p">}</span> 
 
<span class="p">}</span> 
 
<span class="c1">// End of file: ./application/managers/img_manager.php</span> 
</pre></div> 

<h2>Image Watermarking</h2>

<p>
There are two types of watermarking that you can use:
</p>

<ul>
<li>
<strong>Text</strong>: The watermark message will be generating using text, either with a True Type font that you specify, or using the native text output that the GD library supports. If you use the True Type version your GD installation must be compiled with True Type support (most are, but not all).
</li>
<li>
<strong>Overlay</strong>: The watermark message will be generated by overlaying an image (usually a transparent PNG or GIF) containing your watermark over the source image.
</li>
</ul>
 

<h2>Watermarking Preferences</h2> 
 
<p>This table shown the preferences that are available for both types of watermarking (text or overlay)</p> 
 
<table class="grid">  
<tr> 
<th>Preference</th> 
<th>Default&nbsp;Value</th> 
<th>Options</th> 
<th>Description</th> 
</tr> 
 
<tr> 
<td class="td"><strong>wm_type</strong></td> 
<td class="td">text</td> 
<td class="td">text, overlay</td> 
<td class="td">Sets the type of watermarking that should be used.</td> 
</tr> 
 
<tr> 
<td class="td"><strong>source_image</strong></td> 
<td class="td">None</td> 
<td class="td">None</td> 
<td class="td">Sets the source image name/path.  The path must be a relative or absolute server path, not a URL.</td> 
</tr> 
 
<tr> 
<td class="td"><strong>dynamic_output</strong></td> 
<td class="td">FALSE</td> 
<td class="td">TRUE/FALSE (boolean)</td> 
<td class="td">Determines whether the new image file should be written to disk or generated dynamically.  Note: If you choose the dynamic setting, only one image can be shown at a time, and it can't be positioned on the page. It simply outputs the raw image dynamically to your browser, along with image headers.</td> 
</tr> 
 
<tr> 
<td class="td"><strong>quality</strong></td> 
<td class="td">90%</td> 
<td class="td">1 - 100%</td> 
<td class="td">Sets the quality of the image. The higher the quality the larger the file size.</td> 
</tr> 
 
<tr> 
<td class="td"><strong>wm_padding</strong></td> 
<td class="td">None</td> 
<td class="td">A number</td> 
<td class="td">The amount of padding, set in pixels, that will be applied to the watermark to set it away from the edge of your images.</td> 
</tr> 
 
<tr> 
<td class="td"><strong>wm_vrt_alignment</strong></td> 
<td class="td">bottom</td> 
<td class="td">top, middle, bottom</td> 
<td class="td">Sets the vertical alignment for the watermark image.</td> 
</tr> 
 
<tr> 
<td class="td"><strong>wm_hor_alignment</strong></td> 
<td class="td">center</td> 
<td class="td">left, center, right</td> 
<td class="td">Sets the horizontal alignment for the watermark image.</td> 
</tr> 
 
<tr> 
<td class="td"><strong>wm_hor_offset</strong></td> 
<td class="td">None</td> 
<td class="td">None</td> 
<td class="td">You may specify a horizontal offset (in pixels) to apply to the watermark position. The offset normally moves the watermark to the right, except if you have your alignment set to "right" then your offset value will move the watermark toward the left of the image.</td> 
</tr> 
 
<tr> 
<td class="td"><strong>wm_vrt_offset</strong></td> 
<td class="td">None</td> 
<td class="td">None</td> 
<td class="td">You may specify a vertical offset (in pixels) to apply to the watermark position. The offset normally moves the watermark down, except if you have your alignment set to "bottom" then your offset value will move the watermark toward the top of the image.</td> 
</tr> 
 
</table> 
 
 
 
<h3>Text Preferences</h3> 
<p>This table shown the preferences that are available for the text type of watermarking.</p> 
 
 
<table class="grid"> 
<tr> 
<th>Preference</th> 
<th>Default&nbsp;Value</th> 
<th>Options</th> 
<th>Description</th> 
</tr> 
 
<tr> 
<td class="td"><strong>wm_text</strong></td> 
<td class="td">None</td> 
<td class="td">None</td> 
<td class="td">The text you would like shown as the watermark.  Typically this will be a copyright notice.</td> 
</tr> 
 
<tr> 
<td class="td"><strong>wm_font_path</strong></td> 
<td class="td">None</td> 
<td class="td">None</td> 
<td class="td">The server path to the True Type Font you would like to use.  If you do not use this option, the native GD font will be used.</td> 
</tr> 
 
<tr> 
<td class="td"><strong>wm_font_size</strong></td> 
<td class="td">16</td> 
<td class="td">None</td> 
<td class="td">The size of the text.  Note: If you are not using the True Type option above, the number is set using a range of 1 - 5.  Otherwise, you can use any valid pixel size for the font you're using.</td> 
</tr> 
 
<tr> 
<td class="td"><strong>wm_font_color</strong></td> 
<td class="td">ffffff</td> 
<td class="td">None</td> 
<td class="td">The font color, specified in hex.  Note, you must use the full 6 character hex value (ie, 993300), rather than the three character abbreviated version (ie fff).</td> 
</tr> 
 
 
<tr> 
<td class="td"><strong>wm_shadow_color</strong></td> 
<td class="td">None</td> 
<td class="td">None</td> 
<td class="td">The color of the drop shadow, specified in hex. If you leave this blank a drop shadow will not be used. Note, you must use the full 6 character hex value (ie, 993300), rather than the three character abbreviated version (ie fff).</td> 
</tr> 
 
<tr> 
<td class="td"><strong>wm_shadow_distance</strong></td> 
<td class="td">3</td> 
<td class="td">None</td> 
<td class="td">The distance (in pixels) from the font that the drop shadow should appear.</td> 
</tr> 
 
</table> 
 
 
 
 
<h3>Overlay Preferences</h3> 
<p>This table shown the preferences that are available for the overlay type of watermarking.</p> 
 
 
<table class="grid"> 
<tr> 
<th>Preference</th> 
<th>Default&nbsp;Value</th> 
<th>Options</th> 
<th>Description</th> 
</tr> 
 
<tr> 
<td class="td"><strong>wm_overlay_path</strong></td> 
<td class="td">None</td> 
<td class="td">None</td> 
<td class="td">The server path to the image you wish to use as your watermark. Required only if you are using the overlay method.</td> 
</tr> 
 
<tr> 
<td class="td"><strong>wm_opacity</strong></td> 
<td class="td">50</td> 
<td class="td">1 - 100</td> 
<td class="td">Image opacity. You may specify the opacity (i.e. transparency) of your watermark image. This allows the watermark to be faint and not completely obscure the details from the original image behind it. A 50% opacity is typical.</td> 
</tr> 
 
<tr> 
<td class="td"><strong>wm_x_transp</strong></td> 
<td class="td">4</td> 
<td class="td">A number</td> 
<td class="td">If your watermark image is a PNG or GIF image, you may specify a color on the image to be "transparent". This setting (along with the next) will allow you to specify that color. This works by specifying the "X" and "Y" coordinate pixel (measured from the upper left) within the image that corresponds to a pixel representative of the color you want to be transparent.</td> 
</tr> 
 
<tr> 
<td class="td"><strong>wm_y_transp</strong></td> 
<td class="td">4</td> 
<td class="td">A number</td> 
<td class="td">Along with the previous setting, this allows you to specify the coordinate to a pixel representative of the color you want to be transparent.</td> 
</tr> 
</table> 
 

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">final</span> <span class="k">class</span> <span class="nc">Img_Manager</span> <span class="k">extends</span> <span class="nx">Manager</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">get_watermark</span><span class="p">()</span> <span class="p">{</span> 
 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;source_image&#39;</span><span class="p">]</span>	<span class="o">=</span> <span class="nx">APP_DIR</span><span class="o">.</span><span class="s1">&#39;t.jpg&#39;</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;wm_text&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="s1">&#39;Copyright 2006 - John Doe&#39;</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;wm_type&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="s1">&#39;text&#39;</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;wm_font_path&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="nx">APP_DIR</span><span class="o">.</span><span class="s1">&#39;times.ttf&#39;</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;wm_font_size&#39;</span><span class="p">]</span>	<span class="o">=</span> <span class="s1">&#39;16&#39;</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;wm_font_color&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="s1">&#39;000000&#39;</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;wm_vrt_alignment&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="s1">&#39;middle&#39;</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;wm_hor_alignment&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="s1">&#39;center&#39;</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;wm_padding&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="s1">&#39;20&#39;</span><span class="p">;</span> 
		
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;image/image_library&#39;</span><span class="p">,</span> <span class="s1">&#39;img&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span> 
 
         <span class="k">if</span> <span class="p">(</span> <span class="o">!</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">img</span><span class="o">-&gt;</span><span class="na">watermark</span><span class="p">())</span> <span class="p">{</span> 
            <span class="k">echo</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">img</span><span class="o">-&gt;</span><span class="na">display_errors</span><span class="p">();</span> 
        <span class="p">}</span> 
    <span class="p">}</span> 
 
<span class="p">}</span> 
 
<span class="c1">// End of file: ./application/managers/img_manager.php</span> 
</pre></div>
 
<!-- PRINT: stop -->

<?php echo isset($pager) ? $pager : ''; ?>

</div> 
<!-- end onecolumn -->
