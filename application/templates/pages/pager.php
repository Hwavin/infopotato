<div class="pager">

<?php if (isset($prev)): ?>
<a href="<?php echo $base_uri.$prev['uri']; ?>" class="prev" title="<?php echo $prev['name']; ?>"><span class="bullet"><span></span></span>Prev</a>
<?php endif; ?>

<?php if (isset($next)): ?>
<a href="<?php echo $base_uri.$next['uri']; ?>" class="next" title="<?php echo $next['name']; ?>"><span class="bullet"><span></span></span>Next</a>
<?php endif; ?>


<ul class="pagedots">
<?php foreach ($pages as $page): ?>
<?php if ($page['uri'] === $current_name): ?>
<li class="activedot">
<?php else: ?>
<li class="dot">
<?php endif; ?>
<a href="<?php echo $base_uri.$page['uri']; ?>">
<span class="bullet">&bull;</span>
<span class="tipbullet"><span></span></span>
<span class="tiplabel"><?php echo $page['name']; ?></span>
</a>
</li>
<?php endforeach; ?>
</ul>

</div>