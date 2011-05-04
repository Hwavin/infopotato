<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; About
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn --> 
<div id="onecolumn" class="inner"> 

<div id="toc"> 
<ul> 
<li class="current_tab"><a href="<?php echo APP_URI_BASE; ?>about/" title="Motivation">Motivation</a></li>
<li><a href="<?php echo APP_URI_BASE; ?>about/index/facts/" title="Facts">Facts</a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>about/index/license/" title="License">License</a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>about/index/founder/" title="The Founder">The Founder</a></li> 
</ul> 
</div>

<h1 class="first_heading">Motivation</h1>	

<div class="box_right greybox">
<blockquote>
<span>If I have seen further it is by standing on the shoulders of giants.</span>
<div>&mdash; Isaac Newton</div>
</blockquote>
</div>

<p>
My first experience with PHP web application development framework can be tracked back to the year 2007 when I was in China and FleaPHP was the first framework I learned. I created several apps with it and became excited about Framework after that. Since its complex code base and poor documentation I never trully understood it. But I did learned some cool design ideas from it. Now its new name is QeePHP. This PHP framework inspired me to learn more of how to build a dynamic yweb application instead of just a simple website. During my learning, I read many books about PHP OOP, design patterns, database abstraction, and many other related PHP programming techniques.
</p>

<p>
With more and more design and development I found myself the need of a good structure to manage and maintain my web applications. I naturally gravitated towards what are supposed to be the greatest PHP frameworks available. However, their enormous footprints, slow loading times, complicated components coupling, poor documentation and huge learning curve quickly made me think twice about using them. <strong>Why not just write something myself, that does only what it needs to&mdash;and leave the heaps of needless code in the trash?</strong> A few months of careful thinking and learning from other well-known frameworks had led me to create my own Framework&mdash;I named it InfoPotato. The actual start date of this project was Auguest 1, 2009. It was first meant for private usage.
</p>

<h2>Design Evolution</h2>

<p>
For a long time, I thought InfoPotato should follow the MVC architectural pattern because many other web frameworks claim themselves MVC frameworks and at during that period I didn't have a clear understanding of what is MVC. But one day after lunch, I came accross a book called "Building PHP Applications with Symfony, CakePHP, and Zend Framework". In this book, the author memtioned that many so-called PHP MVC frameworks are not real MVC, and the truth is many of them are following the MVP architectural pattern. After that, I did more reading and I found my InfoPotato was more like MVP indeed. But with more research, I realized that I don't need a MVC framework for the PHP web development. Because that Model-View-Controller object relationships as they are conventionally known don't apply to the web in a meaningful way. What I really need is to look at a very simple request-response handling framework. So from that monment, I rewrote InfoPotato and got rid off the concept of either MVC or MVP. InfoPotato now, is only a simple web development framework built around HTTP and the principles of REST that handles request and response.
</p>

<h2>Philosophy</h2>

<p>
Like most web frameworks, InfoPotato is designed to ease the development of web applications by adding structure to the code and promoting a logical separation of application logic and presentation for the developer to write better, more readable, and more maintainable code. Unlike many other well-known PHP frameworks, InfoPotato has very low learning curve because it's very lightweight, simple and fast, and truly loosely coupled with high component singularity. InfoPotato wasn&#039;t designed to give you all the tools you will ever need, instead, it gives you a solid base of well-tested and most common libraries, and gives you the ability to easily add your own if you need to.
</p> 

<p>
Besides, when it comes to frameworks, developers really need to think about <strong>performance</strong> for not only scalability reasons but for green reasons. If programs were more efficient it would cut the number of data centres and would reduce energy needs as a result. In our newly emerging age of energy awareness this does become an important aspect.
</p>

<h2>Credits</h2> 

<p>
InfoPotato is influenced by many other PHP/Ruby/Java/Python frameworks/toolkits and incorporates many ideas and work from them. Below is a short list of those PHP frameworks from which InfoPotato drew inspiration. Some core code and libraries/helper functions of InfoPotato are built upon the following projects.
</p>

<table> 
<tr> 
<td width="25%"> 
<ul> 
<li><a href="http://akaikiwi.sourceforge.net/" class="external_link">Akaikiwi</a></li>
<li><a href="http://alloyframework.org/" class="external_link">Alloy</a></li>
<li><a href="http://cakephp.org/" class="external_link">CakePHP</a></li> 
<li><a href="http://codeigniter.com/" class="external_link">CodeIgniter</a></li>
<li><a href="http://www.dingoframework.com/" class="external_link">Dingo</a></li> 
<li><a href="http://doophp.com/" class="external_link">DooPHP</a></li> 
<li><a href="http://justinvincent.com/ezsql" class="external_link">ezSQL</a></li>
<li><a href="http://qeephp.com/" class="external_link">FleaPHP/QeePHP</a></li> 
<li><a href="http://flourishlib.com/" class="external_link">Flourish</a></li>
</ul> 
</td> 

<td width="25%"> 
<ul> 
<li><a href="http://fuelphp.com/" class="external_link">Fuel</a></li> 
<li><a href="http://gluephp.com/" class="external_link">GluePHP</a></li>
<li><a href="http://kohanaphp.com" class="external_link">Kohana</a></li>
<li><a href="http://konstrukt.dk/" class="external_link">Konstrukt</a></li>
<li><a href="http://www.lightvc.org/" class="external_link">LightVC</a></li>
<li><a href="http://www.lionframework.org/" class="external_link">Lion PHP Framework</a></li>
<li><a href="http://lithify.me/" class="external_link">Lithium</a></li>
<li><a href="http://framework.maintainable.com/mvc/1_intro.php" class="external_link">Maintainable</a></li>
<li><a href="http://nanomvc.com/" class="external_link">NanoMVC</a></li>
</ul> 
</td> 

<td width="25%"> 
<ul> 
<li><a href="http://nette.org/en/" class="external_link">Nette Framework</a></li> 
<li><a href="http://obullo.com/" class="external_link">Obullo</a></li>
<li><a href="http://ownyx.com/" class="external_link">Ownyx</a></li> 
<li><a href="http://www.peecfw.org/" class="external_link">PeecFW</a></li> 
<li><a href="http://www.pluf.org/" class="external_link">Pluf</a></li> 
<li><a href="http://www.prontoproject.com/" class="external_link">Pronto</a></li>
<li><a href="http://www.recessframework.org/" class="external_link">Recess</a></li>
<li><a href="http://www.slimframework.com/" class="external_link">Slim</a></li>
<li><a href="http://spawnframework.com/" class="external_link">Spawn Framework</a></li>
</ul> 
</td>

<td width="25%"> 
<ul> 
<li><a href="http://speedphp.com/" class="external_link">SpeedPHP</a></li>
<li><a href="http://www.spoon-library.com/" class="external_link">Spoon Library</a></li>
<li><a href="http://symfony.com/" class="external_link">Symfony</a></li>
<li><a href="http://www.thinkphp.cn/" class="external_link">ThinkPHP</a></li>
<li><a href="http://thinphp.com/" class="external_link">ThinPHP</a></li>
<li><a href="http://www.tinymvc.com/" class="external_link">TinyMVC</a></li> 
<li><a href="http://www.tokernel.com/" class="external_link">toKernel</a></li> 
<li><a href="http://peej.github.com/tonic/" class="external_link">Tonic</a></li> 
<li><a href="http://www.yiiframework.com/" class="external_link">Yii Framework</a></li> 
</ul> 
</td> 
</tr> 
</table> 
	
</div> 
<!-- end onecolumn --> 
