<div class="container"> 

<div class="row">

<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading"><?php echo __('Testing'); ?></h1>	
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home"><?php echo __('Home'); ?></a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/"><?php echo __('Documentation'); ?></a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/intro/">Introduction</a> &gt; <?php echo __('Testing'); ?>
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/intro/testing/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<p>
Whenever you write a new line of code, you also potentially add new bugs. Automated tests should have you covered and this tutorial shows you how to write unit and functional tests for your InfoPotato application.
</p>

<p>
The idea is to create automatable tests for each bit of functionality that you are developing (at the same time you are developing it). This not only helps everyone later test that the software works, but helps the development itself, because it forces you to work in a modular way with very clearly defined structures and goals.
</p>
<!-- PRINT: stop -->

<?php echo isset($pager) ? $pager : ''; ?>

</div> 

</div>
