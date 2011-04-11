<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo APP_ENTRY_URI; ?>home">Home</a> &gt; Contact
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn --> 
<div id="onecolumn" class="inner"> 

<h1 class="first_heading">Contact Us</h1> 

<div class="box_right greybox">
<blockquote>
<span>A great part to the information I have was acquired by looking up something and finding something else on the way.</span>
<div>&mdash; Adams Franklin</div>
</blockquote>
</div>

<p> 
We hope you have enjoyed the site. If you have a question or comment that you would like to send to us, please do so by filling in the contact form on the left side of the page.
</p>  

<!-- begin contact --> 
<div id="contact"> 
<?php if (isset($errors)) : ?>
<div class="errorbox">
<?php echo $errors; ?>
</div>
<?php endif; ?>


<?php if (isset($sent)) : ?>
<?php if ($sent == TRUE) : ?>
<div class="successbox">Your message has been sent</div>
<?php else : ?>
<div class="errorbox">Failed to send your email</div>
<?php endif; ?>
<?php else : ?>
<p>
Required field is marked with <span class="req">*</span>. 
</p>

<form id="contact_form" method="post" action="<?php echo APP_ENTRY_URI; ?>contact/" accept-charset="utf-8">  
<div>

<input type="hidden" name="form_token" value="<?php if (isset($form_token)) echo $form_token; ?>" />

<div class="anti_spam">
<label for="contact_title" id="contact_title_label" class="desc">Title <span class="req">*</span></label>  
<input type="text" name="contact_title" id="contact_title" size="60" value="" class="contact_input" />   
</div>
	
<div class="form_item">
<label for="contact_subject" id="contact_subject_label" class="desc">Subject <span class="req">*</span></label>  
<input type="text" name="contact_subject" id="contact_subject" size="60" value="<?php if (isset($contact_subject)) echo $contact_subject; ?>" class="contact_input" />   
</div>

<div class="form_item"> 
<label for="contact_message" id="contact_message_label" class="desc">Message <span class="req">*</span></label>  
<textarea name="contact_message" id="contact_message" class="contact_textarea" rows="8" cols="80"><?php if (isset($contact_message)) echo $contact_message; ?></textarea> 
</div>

<div class="form_item">
<label for="contact_name" id="contact_name_label" class="desc">Name <span class="req">*</span></label>  
<input type="text" name="contact_name" id="contact_name" size="60" value="<?php if (isset($contact_name)) echo $contact_name; ?>" class="contact_input" />   
</div>

<div class="form_item">
<label for="contact_email" id="contact_email_label" class="desc">Email <span class="req">*</span></label>  
<input type="text" name="contact_email" id="contact_email" size="60" value="<?php if (isset($contact_email)) echo $contact_email; ?>" class="contact_input" />   
</div>

<div class="form_item">
<input type="submit" name="submit_btn" class="submit" id="submit_btn" value="Send" />  
</div>

</div>
</form>  
<?php endif; ?>
</div> 
<!-- end contact --> 
</div> 
<!-- end onecolumn --> 
 


 