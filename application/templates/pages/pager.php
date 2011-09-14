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

<div class="clear"></div>

<div id="disqus_thread"></div>
<script type="text/javascript">
    /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
    var disqus_shortname = 'infopotato'; // required: replace example with your forum shortname
	var disqus_identifier = '<?php echo $current_name; ?>';
	var disqus_url = '<?php echo $base_uri.$current_name; ?>';

    /* * * DON'T EDIT BELOW THIS LINE * * */
    (function() {
        var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
        dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
    })();
</script>
<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
<a href="http://disqus.com" class="dsq-brlink">blog comments powered by <span class="logo-disqus">Disqus</span></a>