<div class="row">

<?php if (isset($sent)) : ?>
<?php if ($sent == TRUE) : ?>
<div class="greenbox">Your message has been sent</div>
<?php else : ?>
<div class="pinkbox">Failed to send your message</div>
<?php endif; ?>
<?php endif; ?>

<h2><a href="<?php echo APP_URI_BASE; ?>documentation/tutorial/form_validation/">Click here to go back</a></h2>

</div>
