<?xml version="1.0" encoding="ISO-8859-1" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"> 
	<xsl:template match="/">
		<html>
		<head>
		</head> 
		<body> 
		<xsl:for-each select="entry">
			<b><xsl:value-of select="job" /></b>
			<u><xsl:value-of select="date" /></u>
		    <br/> 
		</xsl:for-each> 
		</body> 
		</html> 
	</xsl:template> 
</xsl:stylesheet> 