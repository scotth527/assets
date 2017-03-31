<!doctype html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, maximum-scale=1.0, minimum-scale=1.0, initial-scale=1.0" />
        <title>RegExer</title>
        
        <link rel="stylesheet" type="text/css" href="./regexer.css">
        <script type="text/javascript" src="./regexer.js"></script>
        <!-- experemental with alpha version of CSSC -->
        <script type="text/javascript" src="https://csscjs.com/cssc.min.js"></script> 
        <script type="text/javascript">
            var regX;
            window.onload = function()
            {
                var defaultE = false;
                var defaultC = false;
                <?php if(isset($_GET['e'])) { ?>
                    defaultE = '<?php echo base64_decode($_GET["e"]); ?>';
                <?php } ?>
                <?php if(isset($_GET['c'])) { ?>
                    defaultC = '<?php echo preg_replace( "/\n/m", '\n',strip_tags(base64_decode($_GET["c"]))); ?>';
                <?php } ?>
                regX = new RegExer("regexer",defaultE,defaultC);
            };
        </script>
        <style type="text/css">
            body{
                margin: 0;
                padding: 0;
            }
            #regexer
            {
                top: 10px;
                bottom: 40px;
                position: absolute;
                width: 99%;
            }
            #head
            {
                text-align: left;
                font-family: monospace;
            }
            #copyright
            {
                bottom: 5px;
                position: absolute;
                
                font-family: monospace;
                font-size: 11px;
            }
        </style>
            
    </head>
    <body>
        <div id="regexer"></div>
        <div id="copyright">Breathe Code regex tester @2017</div> 
    </body>
</html>
