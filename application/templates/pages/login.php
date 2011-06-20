<!-- begin onecolumn --> 
<div id="onecolumn">

<!-- begin login -->
<div id="login">
<form id="login_form" name="login_form" method="post" action="<?php echo APP_URI_BASE; ?>auth/login/"> 
<h2>Login form</h2> 
<p> 
The red asterisk <span class="req">*</span> indicates a required field.
</p>

<?php if (isset($auth) && $auth === FALSE) : ?>
<div class="pinkbox"><?php echo 'Incorrect Username/Password Combination'; ?></div>
<?php endif; ?>

<div class="form_item"> 
<label for="contact_subject" id="contact_subject_label" class="desc">Username <span class="req">*</span> </label>  
<input type="text" name="username" id="username" size="30" maxlength="50" value="" class="text-input" tabindex="1" />  
</div>

<div class="form_item"> 
<label for="contact_subject" id="contact_subject_label" class="desc">Password <span class="req">*</span> </label>  
<input type="password" name="password" id="password" size="30" maxlength="50" value="" class="text-input" tabindex="2" />  
</div>

<input type="submit" name="submit_btn" class="uibutton large confirm" id="submit_btn" value="Login" tabindex="3" /> 

</form> 

</div>
<!-- end login -->

</div>
<!-- end onecolumn --> 