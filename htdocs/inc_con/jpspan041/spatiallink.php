<?php
// Including this sets up the JPSPAN constant
require_once 'JPSpan.php';

// Load the PostOffice server
require_once JPSPAN . 'Server/PostOffice.php';

// Some class you've written...
$name = "harsh";
class HelloWorld {
    function sayHello($name) {
        return 'Hello '.$name;
    }
}

// Create the PostOffice server
$S = & new JPSpan_Server_PostOffice();

// Register your class with it...
$S->addHandler(new HelloWorld());

// This allows the JavaScript to be seen by
// just adding ?client to the end of the
// server's URL

if (isset($_SERVER['QUERY_STRING']) &&
        strcasecmp($_SERVER['QUERY_STRING'], 'client')==0) {

    // Compress the output Javascript (e.g. strip whitespace)
    define('JPSPAN_INCLUDE_COMPRESS',TRUE);

    // Display the Javascript client
    $S->displayClient();

} else {

    // This is where the real serving happens...
    // Include error handler
    // PHP errors, warnings and notices serialized to JS
    require_once JPSPAN . 'ErrorHandler.php';

    // Start serving requests...
    $S->serve();

}
?>




The Client (Javascript)
<html>
    <head>
    <!-- Load the generated client side code... -->
    <script type='text/javascript' src='http://localhost/server.php?client'></script>
    <script type='text/javascript'>

    <!--
    // Some function perhaps called onclick of a button

    function doSayHello(name) {

        // Create the client object, passing the async
        // handler. Note lower case!
        var h = new helloworld(HelloWorldHandler);

        // Call the remote method (method lower case!)
        h.sayhello(name);
    }

    // A handler is required to accept the response
    // of asychronous calls...
    var HelloWorldHandler = {

        // Function must have same name as remote method
        sayhello: function(result) {
            alert(result);
        }
    }

    -->

    </script>

    <!-- etc. -->

