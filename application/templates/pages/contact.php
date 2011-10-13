<div class="row">

<!-- begin breadcrumb -->
<div id="breadcrumb">
<h1 class="first_heading">Contact</h1> 
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; Contact
</div>
<!-- end breadcrumb -->



<!-- begin contact form --> 
<form method="post" action="<?php echo APP_URI_BASE; ?>contact/">  

<div id="contact_form">
<!--
<?php if (isset($errors)) : ?>
<div class="errorbox">
<?php echo $errors; ?>
</div>
<?php endif; ?>
-->

<?php if (isset($sent)) : ?>
<?php if ($sent == TRUE) : ?>
<div class="greenbox">Your message has been sent</div>
<?php else : ?>
<div class="pinkbox">Failed to send your email</div>
<?php endif; ?>
<?php else : ?>

<p>
Required field is marked with <span class="req">*</span>. 
</p>

<input type="hidden" name="form_token" value="<?php if (isset($form_token)) echo $form_token; ?>" />

<div class="anti_spam">
<label for="contact_title" id="contact_title_label" class="desc">Title <span class="req">*</span></label>  
<input type="text" name="contact_title" id="contact_title" value="" class="contact_input" />   
</div>

<div class="form_item grid-4-12">
<label for="contact_name" id="contact_name_label" class="desc">Name <span class="req">*</span> <?php if (isset($contact_name_error)) echo $contact_name_error; ?></label>  
<input type="text" name="contact_name" id="contact_name" value="<?php if (isset($contact_name)) echo $contact_name; ?>" class="contact_input" />   
</div>

<div class="form_item grid-4-12">
<label for="contact_email" id="contact_email_label" class="desc">Email <span class="req">*</span> <?php if (isset($contact_email_error)) echo $contact_email_error; ?></label>  
<input type="text" name="contact_email" id="contact_email" value="<?php if (isset($contact_email)) echo $contact_email; ?>" class="contact_input" />   
</div>

<div class="form_item grid-4-12">
<label for="contact_subject" id="contact_subject_label" class="desc">Subject <span class="req">*</span> <?php if (isset($contact_subject_error)) echo $contact_subject_error; ?></label>  
<input type="text" name="contact_subject" id="contact_subject" value="<?php if (isset($contact_subject)) echo $contact_subject; ?>" class="contact_input" />   
</div>

<div class="form_item"> 
<label for="contact_message" id="contact_message_label" class="desc">Message <span class="req">*</span> <?php if (isset($contact_message_error)) echo $contact_message_error; ?></label>  
<textarea name="contact_message" id="contact_message" class="contact_textarea" rows="8"><?php if (isset($contact_message)) echo $contact_message; ?></textarea> 
</div>

<div class="form_item">
<input type="submit" name="submit_btn" class="uibutton large confirm" id="submit_btn" value="Send" />  
</div>

</div>
</form>  
<?php endif; ?>
</div> 
<!-- end contact form --> 

</div> 

 


 