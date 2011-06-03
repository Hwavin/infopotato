<div class="pager">
<a href="#" class="pre"><span class="bullet"><span></span></span>Prev</a>
<a href="#" class="next"><span class="bullet"><span></span></span>Next</a>

<ul class="pagedots">

<?php foreach ($page as $uri => $name): ?>
<?php if ($uri === $current_name): ?>
<li class="activedot">
<?php else: ?>
<li class="dot">
<?php endif; ?>
<a href="<?php echo APP_URI_BASE.'documentation/index/'.$uri; ?>">
<span class="bullet">&bull;</span>
<span class="tipbullet"><span></span></span>
<span class="tiplabel"><?php echo $name; ?></span>
</a>
</li>
<?php endforeach; ?>

</ul>

</div>