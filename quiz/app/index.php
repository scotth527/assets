<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="content-type">

        <title>SlickQuiz Demo</title>
        <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
        <link href="css/reset.css" media="screen" rel="stylesheet" type="text/css">
        <link href="css/slickQuiz.css" media="screen" rel="stylesheet" type="text/css">
        <link href="css/master.css?v4" media="screen" rel="stylesheet" type="text/css">
    </head>

    <body id="slickQuiz">
        <h1 class="quizName"><!-- where the quiz name goes --></h1>

        <div class="quizArea">
            <div class="quizHeader">
                <!-- where the quiz main copy goes -->

                <a class="button btn-primary startQuiz" href="#">Get Started!</a>
            </div>

            <!-- where the quiz gets built -->
        </div>

        <div class="quizResults">
            <h3 class="quizScore">You Scored: <span><!-- where the quiz score goes --></span></h3>

            <h3 class="quizLevel"><strong>Ranking:</strong> <span><!-- where the quiz ranking level goes --></span></h3>

            <div class="quizResultsCopy">
                <!-- where the quiz result copy goes -->
            </div>
        </div>

        <script src="js/jquery.js"></script>
        <script src="js/slickQuiz.js?v4"></script>
        <script type="text/javascript">
            $(function() {
                
                $.ajax({
                   url: '../quizes.php', 
                   dataType: 'json',
                   data: {slug: '<?php echo $_GET['slug']; ?>'},
                   cache: false,
                   success: function(data){
                       if(data && data.length==1)
                       {
                            $('#slickQuiz').slickQuiz({
                                json: data[0],
                                onComplete: sendActivity
                            });
                       }
                   },
                   error: function(p1,p2,p3){
                       console.log("Error: "+p3);
                   }
                });
                
                function sendActivity(passed, total){
                    console.log('You passed '+passed+' from '+total);
                }
                
            });
        </script>
    </body>
</html>
