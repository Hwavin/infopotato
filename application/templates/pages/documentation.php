<!-- begin onecolumn --> 
<div id="onecolumn" class="inner">

<!-- begin breadcrumb -->
<div id="breadcrumb">
<h1 class="first_heading"><?php echo __('Documentation'); ?></h1> 
<a href="<?php echo APP_URI_BASE; ?>home"><?php echo __('Home'); ?></a> &gt; <?php echo __('Documentation'); ?>
</div>
<!-- end breadcrumb -->

<div class="box_right greybox">

<img src="<?php echo STATIC_URI_BASE; ?>images/shared/new_ribbon.png" width="37" height="36" class="new_ribbon" alt="New"> 

<h3><?php echo __('Offline Documentation'); ?></h3>
<p>
Grab an <a class="pdf_doc" href="<?php echo APP_URI_BASE; ?>file/resume-pdf"><?php echo __('Offline Documentation'); ?></a> containing all topics listed below.
</p>
</div>

<p>
Thank you for being interested in InfoPotato. Any software application requires some effort to learn. We’ve done our best to minimize the learning curve while making the process as enjoyable as possible. This documentation assumes that you have a general understanding of PHP and a basic understanding of object-oriented programming (OOP). Different functionality within the framework makes use of different technologies – such as SQL, JavaScript, CSS, and XML – and this documentation does not attempt to explain those technologies, only how they are used in context. This documentation is a work in progress. There are planned areas I haven't written yet. Without much babble, start your journey and see how you can use InfoPotato.
</p>

<table width="100%"> 
<tr> 
<td valign="top" width="25%"> 
 
<h3><a href="<?php echo APP_URI_BASE; ?>documentation/intro/"><?php echo __('Introduction'); ?></a></h3> 
<ul> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/intro/server_requirements/"><?php echo __('Server Requirements'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/intro/installation/"><?php echo __('Installation Instructions'); ?></a></li>
<li><a href="<?php echo APP_URI_BASE; ?>documentation/intro/environments/"><?php echo __('The Environments'); ?></a></li>
<li><a href="<?php echo APP_URI_BASE; ?>documentation/intro/structure/"><?php echo __('The Directory Structure'); ?></a></li>
<li><a href="<?php echo APP_URI_BASE; ?>documentation/intro/alternative_php/">Alternative PHP Syntax</a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/intro/style_guide/"><?php echo __('Conventions &amp; Style Guide'); ?></a></li>
<li><a href="<?php echo APP_URI_BASE; ?>documentation/intro/testing/"><?php echo __('Testing'); ?></a></li>
</ul> 
 
</td> 
<td valign="top" width="25%"> 
 
<h3><a href="<?php echo APP_URI_BASE; ?>documentation/core/"><?php echo __('Core Topics'); ?></a></h3> 
<ul> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/core/workflow/"><?php echo __('Request Processing Workflow'); ?></a></li>
<li><a href="<?php echo APP_URI_BASE; ?>documentation/core/global/"><?php echo __('Global Constants and Functions'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/core/uri/">URI</a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/core/dispatcher/"><?php echo __('Dispatcher'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/core/manager/"><?php echo __('Manager'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/core/template/"><?php echo __('Template'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/core/data/"><?php echo __('Data Access Object'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/core/sql_adapters/"><?php echo __('SQL Database Adapters'); ?></a></li>
<li><a href="<?php echo APP_URI_BASE; ?>documentation/core/i18n/"><?php echo __('Internationalization'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/core/library/"><?php echo __('Library'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/core/function/"><?php echo __('Function'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/core/runtime/"><?php echo __('System Core Runtime Cache'); ?></a></li>  
<li><a href="<?php echo APP_URI_BASE; ?>documentation/core/dump/">Dump Variable</a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/core/utf8/"><?php echo __('UTF-8 Support'); ?></a></li>
<li><a href="<?php echo APP_URI_BASE; ?>documentation/core/caching/"><?php echo __('Caching'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/core/cookie/">Cookie</a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/core/session/">Session</a></li>  
<li><a href="<?php echo APP_URI_BASE; ?>documentation/core/security/"><?php echo __('Security'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/core/ajax/"><?php echo __('Ajax Interaction'); ?></a></li> 
</ul> 

</td> 
<td valign="top" width="25%"> 
 
<h3><a href="<?php echo APP_URI_BASE; ?>documentation/library/"><?php echo __('Library Reference'); ?></a></h3> 
<ul>  
<li><a href="<?php echo APP_URI_BASE; ?>documentation/library/output_cache/"><?php echo __('Output Cache'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/library/calendar/"><?php echo __('Calendar'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/library/dirinfo/">Dir Info</a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/library/feed_writer/">Feed Writer</a></li>
<li><a href="<?php echo APP_URI_BASE; ?>documentation/library/email/"><?php echo __('Email'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/library/encypt/"><?php echo __('Encryption'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/library/upload/"><?php echo __('File Upload'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/library/form_validation/"><?php echo __('Form Validation'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/library/ftp/">FTP</a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/library/pagination/"><?php echo __('Pagination'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/library/password_hashing/"><?php echo __('Password Hashing'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/library/user_agent/"><?php echo __('User Agent'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/library/mobile_detect/"><?php echo __('Mobile Device Detection'); ?></a></li> 
</ul> 
</td> 
<td valign="top" width="25%"> 

<h3><a href="<?php echo APP_URI_BASE; ?>documentation/function/"><?php echo __('Function Reference'); ?></a></h3> 
<ul> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/function/captcha/">CAPTCHA</a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/function/download/"><?php echo __('Download'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/function/redirect/"><?php echo __('Redirection'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/function/minify_html/">Minify HTML</a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/function/htmlawed/">HtmLawed</a></li>
</ul> 
 
</td> 
</tr> 
</table> 

<h2>Acknowledgements</h2>

<p>
A huge special thanks goes to my girlfriend. She helped me proofread all the documentation.
</p>

</div>
<!-- end onecolumn --> 