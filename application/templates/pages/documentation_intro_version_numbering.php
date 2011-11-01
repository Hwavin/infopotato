<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">Version numbering</h1>	
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/intro/">Introduction</a> &gt; Version numbering
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/intro/version_numbering/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<h2>The Version Number Format</h2>

<p>
The approach we take for version numbering is very similar to the way the Linux kernel handles it. With this approach, it is easy to tell what's going on just by looking at the three-part version number. The full version number is a three-part number, using the format x.y.z, indicating a specific implementation of SquirrelMail. Our examples will be 1.0.6 and 1.1.2.
</p>

<p>
The first number is the major release number (that's the x). At first, this was 0 which meant we hadn't had any major releases yet. In August of 2010, we released 1.0.0 which was our first major stable release, and everything since then has been in the 1 family.
</p> 

<p>
A major release of InfoPotato should have a high-level set of specific goals that it is meeting. For InfoPotato 1.0, this was the reaching of a stable release. For later versions, this may include implementation of more modular libraries and API behind the code, a templating system for the user-interface, and so forth.
</p>

<p>
The second number is the minor release number (that's the y). The minor release number will tell you whether a release is a stable or development branch of InfoPotato. Even numbers (0, 2, 4...) mean that it's a stable release, and odd numbers (1, 3, 5...) mean it's a development release.
</p>

<p>
A minor release of InfoPotato should have a lower-level set of specific goals that it is meeting. These goals should, somehow or another :), be a subset of the goals for the major release of which it is a part. Basically, the minor releases allow for the major goals of a major release to be broken into a more discrete, attainable set of objectives.
</p>

<p>
Lastly, the final number is a release incrementor (that's the z). In a stable branch, new versions of InfoPotato will be release as bugs are found and squished. In a development branch, new versions of InfoPotato will be released as progress is made toward the goals set for the next major and minor release. New releases will happen as needed in a stable branch. In a development branch, however, they should happen frequently, as more progress is made toward the next minor release.
</p>

<h2>Important points</h2>
<ul>
<li>
Versions with a even minor release number are stable.
</li>
<li>
Versions with a odd minor release number are development.
</li>
<li>
New incremental releases in a stable branch should ONLY CONTAIN bug fixes. New features should always be implemented in the current development branch.
</li>
</ul>

<!-- PRINT: stop -->

<?php echo isset($pager) ? $pager : ''; ?>