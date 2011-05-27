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
 
<h3><?php echo __('Introduction'); ?></h3> 
<ul> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/sever_requirements/"><?php echo __('Server Requirements'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/installation/"><?php echo __('Installation Instructions'); ?></a></li>
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/environments/"><?php echo __('The Environments'); ?></a></li>
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/structure/"><?php echo __('The Directory Structure'); ?></a></li>
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/alternative_php/">Alternative PHP Syntax</a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/style_guide/"><?php echo __('Conventions &amp; Style Guide'); ?></a></li>
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/testing/"><?php echo __('Testing'); ?></a></li>
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/glossary/"><?php echo __('Glossary'); ?></a></li>
</ul> 
 
</td> 
<td valign="top" width="25%"> 
 
<h3><?php echo __('Core Topics'); ?></h3> 
<ul> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/workflow/"><?php echo __('Request Processing Workflow'); ?></a></li>
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/global/"><?php echo __('Global Constants and Functions'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/uri/">URI</a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/dispatcher/"><?php echo __('Dispatcher'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/manager/"><?php echo __('Manager'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/template/"><?php echo __('Template'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/data/"><?php echo __('Data Access Object'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/sql_adapters/"><?php echo __('SQL Database Adapters'); ?></a></li>
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/i18n/"><?php echo __('Internationalization'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/library/"><?php echo __('Library'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/function/"><?php echo __('Function'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/runtime/"><?php echo __('System Core Runtime Cache'); ?></a></li>  
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/dump/">Dump Variable</a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/utf8/"><?php echo __('UTF-8 Support'); ?></a></li>
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/caching/"><?php echo __('Caching'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/security/"><?php echo __('Security'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/ajax/"><?php echo __('Ajax Interaction'); ?></a></li> 
</ul> 

</td> 
<td valign="top" width="25%"> 
 
<h3><?php echo __('Library Reference'); ?></h3> 
<ul>  
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/output_cache/"><?php echo __('Output Cache'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/calendar/"><?php echo __('Calendar'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/cookie/">Cookie</a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/cart/">Shopping Cart</a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/dirinfo/">Dir Info</a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/feed_writer/">Feed Writer</a></li>
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/email/"><?php echo __('Email'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/encypt/"><?php echo __('Encryption'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/upload/"><?php echo __('File Upload'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/form_validation/"><?php echo __('Form Validation'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/ftp/">FTP</a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/pagination/"><?php echo __('Pagination'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/password_hashing/"><?php echo __('Password Hashing'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/session/">Session</a></li>  
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/user_agent/"><?php echo __('User Agent'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/mobile_detect/"><?php echo __('Mobile Device Detection'); ?></a></li> 
</ul> 
</td> 
<td valign="top" width="25%"> 

<h3><?php echo __('Function Reference'); ?></h3> 
<ul> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/captcha/">CAPTCHA</a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/download/"><?php echo __('Download'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/redirect/"><?php echo __('Redirection'); ?></a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/minify_html/">Minify HTML</a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>documentation/index/htmlawed/">HtmLawed</a></li>
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