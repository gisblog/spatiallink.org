<style type="text/css" media="screen"> @import "tanfa.css";
/*	http://www.tanfa.co.uk/css/examples/menu/hs7.asp */
#menu {
width: 100%;
background: #eee;
float: left;
}

#menu ul {
list-style: none;
margin: 0;
padding: 0;
width: 12em;
float: left;
}

#menu a, #menu h2 {
font: bold 11px/16px arial, helvetica, sans-serif;
display: block;
border-width: 1px;
border-style: solid;
border-color: #ccc #888 #555 #bbb;
margin: 0;
padding: 2px 3px;
}

#menu h2 {
color: #fff;
background: #000;
text-transform: uppercase;
}

#menu a {
color: #000;
background: #efefef;
text-decoration: none;
}

#menu a:hover {
color: #a00;
background: #fff;
}

#menu li {position: relative;}

#menu ul ul {
position: absolute;
z-index: 500;
}

#menu ul ul ul {
position: absolute;
top: 0;
left: 100%;
}

div#menu ul ul,
div#menu ul li:hover ul ul,
div#menu ul ul li:hover ul ul
{display: none;}

div#menu ul li:hover ul,
div#menu ul ul li:hover ul,
div#menu ul ul ul li:hover ul
{display: block;}
</style>

<!--[if IE]>
<style type="text/css" media="screen">
body {
behavior: url(csshover.htc);
font-size: 100%;
}

#menu ul li {float: left; width: 100%;}
#menu ul li a {height: 1%;} 

#menu a, #menu h2 {
font: bold 0.7em/1.4em arial, helvetica, sans-serif;
}
</style>
<![endif]-->

<div id="menu">
<ul>
  <li><h2>Horizontal Drop &amp; Pop Menu</h2>
    <ul>
      <li><a href="http://www.seoconsultants.com/css/menus/horizontal/" title="SEO Consultants Directory Example">SEO Consultants Sample</a></li>
      <li><a href="http://www.tanfa.co.uk/css/examples/menu/hs7.asp">tanfa Demo example</a><!-- fully working sample -->
        <ul>
          <li><a href="http://www.tanfa.co.uk/css/examples/menu/tutorial-h.asp" title="Horizontal Menu Tutorial">tanfa Tutorial</a>
            <ul>
              <li><a href="http://www.tanfa.co.uk/css/examples/menu/hs1.asp" title="Horizontal Menu - Page 1">Stage 1</a></li>
              <li><a href="http://www.tanfa.co.uk/css/examples/menu/hs2.asp" title="Horizontal Menu - Page 2">Stage 2</a></li>			
              <li><a href="http://www.tanfa.co.uk/css/examples/menu/hs3.asp" title="Horizontal Menu - Page 3">Stage 3</a></li>
              <li><a href="http://www.tanfa.co.uk/css/examples/menu/hs4.asp" title="Horizontal Menu - Page 4">Stage 4</a></li>
              <li><a href="http://www.tanfa.co.uk/css/examples/menu/hs5.asp" title="Horizontal Menu - Page 5">Stage 5</a></li>			
              <li><a href="http://www.tanfa.co.uk/css/examples/menu/hs6.asp" title="Horizontal Menu - Page 6">Stage 6</a></li>
            </ul>
          </li>
        </ul>
      </li>
    </ul>
  </li>
</ul>							
</div>