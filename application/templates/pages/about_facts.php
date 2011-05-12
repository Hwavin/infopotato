<!-- begin onecolumn -->
<div id="onecolumn"> 

<div id="toc"> 
<ul> 
<li><a href="<?php echo APP_URI_BASE; ?>about/" title="Motivation">Motivation</a></li>
<li class="current_tab"><a href="<?php echo APP_URI_BASE; ?>about/index/facts/" title="Facts">Facts</a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>about/index/license/" title="License">License</a></li> 
<li><a href="<?php echo APP_URI_BASE; ?>about/index/founder/" title="The Founder">The Founder</a></li> 
</ul> 
</div>

<!-- begin breadcrumb -->
<div id="breadcrumb">
<h1 class="first_heading">Facts about InfoPotato</h1>	
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; About
</div>
<!-- end breadcrumb -->


<p> 
Whether you are a developer building a fairly simple website, or a team of distributed developers building a complex web application, using InfoPotato is like augmenting your development team with additional experienced, professional, and efficient resources. 
</p> 

<h2>What does InfoPotato offer?</h2>
<ul class="greenbox">
<li>
<strong>Loose RESTful Approach</strong> 
InfoPotato adopts this Request-Reponse handling model. Using its clearly seperated components, you can open up new levels of robustness, code reuse, and organization.
</li>

<li>
<strong>Truly Loose Coupling</strong> 
All the libraries do not communicate at all with one another. That also means you can use the libraries in other non-framework projects without any modification.
</li>

<li>
<strong>Security Enhancement</strong> 
InfoPotato is equipped with many security measures to help prevent your Web applications from attacks such as <a href="http://en.wikipedia.org/wiki/SQL_injection" class="external_link">SQL injection</a>, <a href="http://en.wikipedia.org/wiki/Cross-site_scripting" class="external_link">cross-site scripting (XSS)</a>, <a href="http://en.wikipedia.org/wiki/CSRF" class="external_link">cross-site request forgery (CSRF)</a>.
</li>

<li>
<strong>Multiple Database (SQL &amp; NoSQL) Adapters</strong> 
No matter RDBMS or NoSQL you decide to use, InfoPotato provides you the easy database adapter to speed your development.
</li> 

<li>
<strong>Truly Lightweight &amp; Fast</strong> 
InfoPotato's core system requires only a few very small components. Additional libraries/scripts are loaded dynamically upon request. It uses the lazy loading and avoids slow PHP functions &amp; methods.
</li>

<li>
<strong>Rich Standalone Libraries</strong> 
InfoPotato comes with many standalone and reusable PHP libraries. With no pre-requisite, except for PHP, you can start using them right away!
</li>

<li>
<strong>Small learning curve &amp; Detailed documentation</strong> 
Every single method or property is clearly documented. A book and comprehensive tutorials are also available for you to systematically learn InfoPotato.
</li>
</ul>

<h2>What doesn't InfoPotato offer?</h2>
<ul class="pinkbox">
<li>
<strong>No URI Routing</strong> 
InfoPotato implements a simple Regular Expression based routes system for mapping URLs to controllers/actions. With URI routes, you get clean URL for your website which is good for SEO purpose.
</li> 

<li>
<strong>No Object Relational Mapping (ORM)</strong> 
Since RDBMS is not necessary, InfoPotato doesn't implement ORM. When working with SQL CRUD, raw SQL queries is an efficient method of data access using the simple database adapters. 
</li>

<li>
<strong>No ACL/RBAC system</strong> 
InfoPotato encourages developers to use thirty-party or their own Access Control mechanism for complex multi-user applications.
</li>

<li>
<strong>No Tamplating system</strong> 
Why brother to learn a new templating language? Just use PHP's alternative syntax in the View. Template engines simply can not match the performance of native PHP.
</li>

<li>
<strong>No I18N/L10N Support</strong> 
I18N/L10N Support will break the Loose Coupling that InfoPotato designed for. So InfoPotato encourages the developer to design their own solution.
</li>

<li>
<strong>No Integrated Javascript Library</strong> 
Yes, InfoPotato doesn't integrate with any popular Javascript libraries. Because not every project requires Javascript. But you can always use Javascript with InfoPotato very easily.
</li>

<li>
<strong>No Automatic code generation</strong> 
The auto generated frontend/backend design which are usually hard to modify and maintain later on. InfoPotato doesn't want to cause developers more trouble and make them lazy to think.
</li>

<li>
<strong>No use of command line</strong> 
InfoPotato doesn't require  the use of command line. Why bother?
</li>
</ul>

<div class="clear"></div>

</div> 
<!-- end onecolumn -->