<form enctype="multipart/form-data" method="post" action="<?php echo APP_URI_BASE; ?>home/">  

<div class="anti_spam">
<label for="contact_title" id="contact_title_label" class="desc">Title <span class="req">*</span></label>  
<input type="text" name="contact_title" id="contact_title" value="" class="contact_input" />   
</div>

<div class="form_item grid-4-12">
<label for="contact_name" id="contact_name_label" class="desc">Name <span class="req">*</span> </label>  
<input type="text" name="contact_name" id="contact_name" value="" class="contact_input" />   
</div>

<div class="form_item grid-4-12">
<label for="contact_email" id="contact_email_label" class="desc">Email <span class="req">*</span> </label>  
<input type="text" name="contact_email" id="contact_email" value="" class="contact_input" />   
</div>

<div class="form_item grid-4-12">
<label for="contact_subject" id="contact_subject_label" class="desc">Subject <span class="req">*</span> </label>  
<input type="text" name="contact_subject" id="contact_subject" value="" class="contact_input" />   
</div>

<div class="form_item"> 
<label for="contact_message" id="contact_message_label" class="desc">Message <span class="req">*</span> </label>  
<textarea name="contact_message" id="contact_message" class="contact_textarea" rows="8"></textarea> 
</div>


<input name="userfile" type="file" />

<div class="form_item">
<input type="submit" name="submit_btn" class="uibutton large confirm" id="submit_btn" value="Send" />  
</div>

</div>
</form>