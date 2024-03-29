<!-- VERSION -1 -->
<?php session_start();
 require 'php/define.php';
 if(isset($_SESSION[constant("SESSION_USER_USERNAME")]))
 {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <link rel="icon" href="">
    <title>::Samarthya::Online Learning Portal for Technical Staff under MGNREGA</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/popup.js"></script>
    <script src="js/roman.js"></script>
    <link href="css/popup.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        #certificateButton
        {
            display:none;
        }
        .btn { margin-right :3%; }
    </style>
     <script>
         var g_coursesList=new Array();
         
         function CourseTesting(test)
         {
             popupOpen();
             document.getElementById("popcontent").innerHTML='This Test can be done only once after Assessment';
         }
         
         
         function moduleContent(courseName, courseId)
         {
             var result="";
                                $.ajax({type: "GET", 
                                                   async: false,
                                                   url: 'php/sessions.php',
                                                   data: { 
                                                       action : 'SetCourseSession',
                                                       courseName : courseName,
                                                       courseId : courseId
                                                   },
                                                   success: function(resp)
                                                   {
                                                         result=resp;
                                                   }
                                                  });
            // document.getElementById("userlanding-content").style.display='none';
            window.location.href='details.php?content=No';
         }
        function getCoursesListAfterLogin()
        {
             var result="";
                 $.ajax({type: "GET", 
                                    async: false,
                                    url: 'php/dac.courses.php',
                                    data: { 
                                        action : 'courseListOnly'
                                    },
                                    success: function(resp)
                                    {
                                          result=resp;
                                    }
                                   });
                                   
                                   //console.log("result :"+result);
                                   var res=JSON.parse(result);
                             var content='';
                             for(var index=0;index<res.length;index++)
                             {
              
                                 g_coursesList[index]=res[index].courseName;
                                 content+='<div id="course-content" class="col-xs-12 col-xs-6 col-md-3">';  
                                 content+='<div class="course-box">';     
                                
                                 content+='<div class="course-header"><h5 class="course-title">Course '+getRomanNumber(res[index].idCourses)+'</h5></div>';
                                 content+='<div align="center" class="course-img"><img src="'+res[index].courseImage+'" />';
                                 content+='</div>';
                                 content+='<div class="course-footer"><a href="#">'+res[index].courseName+'<img src="images/course-arrow.png" class="pull-right" /></a></div>';
                                 content+=' </div>';
                                 content+='<div class="course-menu">';
                                 content+='<ul>';
                                 content+='<li><span class="course-subTag" onclick="javascript:preTestforCourse(\''+res[index].courseName+'\',\''+res[index].idCourses+'\',\'preTest\' )">';
                                 content+='Take a Pretest</span></li>';
                                 content+='<li><span class="course-subTag" onclick="javascript:preTestforCourse(\''+res[index].courseName+'\',\''+res[index].idCourses+'\',\'Details\' )">Details</a></li>';
                                 content+='<li><span class="course-subTag" onclick="javascript:moduleContent(\''+res[index].courseName+'\',\''+res[index].idCourses+'\')">Go to Module</a></li>';
                                 content+='<li><span class="course-subTag" onclick="javascript:preTestforCourse(\''+res[index].courseName+'\',\''+res[index].idCourses+'\',\'Assessment\' )">Go for Assessment</a></li>';
                                // content+='<li><span class="course-subTag" onclick="javascript:CourseTesting(\'postTest\' )">Go for Post-Test</a></li>';
                                 content+='</ul>';
                                 content+='</div>';
                                 content+='</div>';
                                 // pre-test.php
                             }
                             
           document.getElementById("view-courses-list").innerHTML=content;
           
           
            var progress=false;
             // Check to invoke Certification Button
             var lastCourseName=g_coursesList[g_coursesList.length-1];
             console.log("lastCourseName : "+lastCourseName);
                   var courseId='0';
                   var qres="";
                  $.ajax({type: "GET", 
                                    async: false,
                                    url: 'php/dac.questions.php',
                                    data: { 
                                        action : 'TestDetails',
                                        courseName : lastCourseName,
                                        testType :'Assessment'
                                    },
                                    success: function(resp)
                                    {
                                          qres=resp;
                                    }
                                   });
                       
                         qres=JSON.parse(qres);
                         for(var ind=0;ind<qres.length;ind++)
                         {
                             courseId=qres[ind].idTestDetails;
                         }
             // Hard-Code
                courseId=4;
                
           var response=checkForTest(courseId, 'preTest');
                                        
                    var res=JSON.parse(response);

                    for(var ind=0;ind<res.length;ind++)
                    {
                        console.log(res[ind].testTaken);
                        if(res[ind].testTaken==='1')
                        {
                           progress=true;
                        }
                    }
          
          
          if(progress===true)
          {
            //  document.getElementById("certificateButton").style.display='block';
          }
          
          
          
          
        }
        function checkForTest(courseId, link)
        {
             var response;
             $.ajax({type: "GET", 
                                    async: false,
                                    url: 'php/dac.courses.php',
                                    data: { 
                                        action : 'CheckForTest',
                                        testType : link,
                                        courseId:courseId
                                    },
                                    success: function(resp)
                                    {
                                          response=resp;
                                    }
                                   });
           return response; 
        }
        function courseValidation(courseName, courseId, link)
        // Check for Test Taken in sequence Or Not 
        {
            var progress=false;

               console.log("g_coursesList Array : "+g_coursesList);  // list of courses in sequence
            
             
             if(link==='Assessment')
             {
                 // check the current course preTest is completed or Not
                    var response=checkForTest(courseId, 'preTest');
                          
                          console.log("Test Response : "+response);
                    var res=JSON.parse(response);

                    for(var ind=0;ind<res.length;ind++)
                    {
                        console.log(res[ind].testTaken);
                        if(res[ind].testTaken==='1')
                        {
                           progress=true;
                        }
                    }
                    
                    if(progress===false)
                    {
                        popupOpen();
                        document.getElementById("popcontent").innerHTML='<h3>You have not completed the Pre-Test of '+courseName+'. Please do it first.</h3>';
                       
                    }
                    
             }
             else if(link==='preTest')  // check for previous course Assessment completed or not
             {
                 // Get previous courseName
                    var prevcourseName='';

                    for(var ind=0;ind<g_coursesList.length;ind++)
                    {
                        console.log("array CourseName : "+g_coursesList[ind]);
                        if(g_coursesList[ind]===courseName)
                        {
                            prevcourseName=g_coursesList[ind-1];
                        }
                    }
                    console.log("current CourseName : "+courseName);
                    console.log("prev CourseName : "+prevcourseName);

                    if(prevcourseName===undefined)
                    {
                         progress=true;
                    }
                    else {
                  // Get CourseId for previous CourseName and 'Post Test'
                 // dac.questions.php ::: action=TestDetails
                 var courseId=0;
                   var qres="";
                  $.ajax({type: "GET", 
                                    async: false,
                                    url: 'php/dac.questions.php',
                                    data: { 
                                        action : 'TestDetails',
                                        courseName : prevcourseName,
                                        testType :'Assessment'
                                    },
                                    success: function(resp)
                                    {
                                          qres=resp;
                                    }
                                   });
                       console.log(qres);
                         qres=JSON.parse(qres);
                         for(var ind=0;ind<qres.length;ind++)
                         {
                             courseId=qres[ind].idTestDetails;
                         }
                     
                    
                    // Hard-code
                    if(prevcourseName=='Natural Resources Management')
                    {
                        courseId=1;
                    }
                    else if(prevcourseName=='Community/Individual Assets')
                    {
                        courseId=2;
                    }
                    else if(prevcourseName=='Common Infrastructure')
                    {
                        courseId=3;
                    }
                    else if(prevcourseName=='Rural Infrastructure')
                    {
                        courseId=4;
                    }
                         
                   // check the previous course postTest is completed or Not
                    var response=checkForTest(courseId, 'Assessment');
                               
                    var res=JSON.parse(response);

                   console.log("Response : "+res);
                    for(var ind=0;ind<res.length;ind++)
                    {
                        console.log(res[ind].testTaken);
                        if(res[ind].testTaken==='1')
                        {
                           progress=true;
                        }
                    }
                     
                     
                    }
                    
                         
                    if(progress===false)
                    {
                        popupOpen();
                        document.getElementById("popcontent").innerHTML='<h3>You have not completed the Assessment of '+prevcourseName+'. Please do it first.</h3>';
                       
                    }   
             }
    
            return progress;
        }
        
        function preTestforCourse(courseName, courseId, link)
        {
            var result="";
                                $.ajax({type: "GET", 
                                                   async: false,
                                                   url: 'php/sessions.php',
                                                   data: { 
                                                       action : 'SetCourseSession',
                                                       courseName : courseName,
                                                       courseId : courseId
                                                   },
                                                   success: function(resp)
                                                   {
                                                         result=resp;
                                                   }
                                                  });
                 /* Added on June 8 */                                     
           if(link==='preTest')
           {
              window.location.href='pre-test.php'; 
           }
           else {
            var progress=courseValidation(courseName, courseId, link);
            
            console.log("Progress : "+progress);
            
           
            if(link==='Details')
            {
                 console.log("link : "+link);
                 var access=false;
                 var exesponse=checkForTest(courseId, 'preTest');
                 console.log("exesponse : "+exesponse);
                 exesponse=JSON.parse(exesponse);
                 if(exesponse.length>0)
                 {
                      for(var ind=0;ind<exesponse.length;ind++)
                      {
                        console.log(exesponse[ind].testTaken);
                        if(exesponse[ind].testTaken==='1')
                        {
                           access=true;
                        }
                     }
                     
                     if(access)
                     {
                         window.location.href='details.php';
                     }
                 }
                 else
                 {
                       popupOpen();
                       document.getElementById("popcontent").innerHTML='<h3>You need to completed the preTest to access the Details.</h3>';
                      
                 }
            }
            
             
        
                        
            if(progress===true)
            {
                
                    var response=checkForTest(courseId, link);
                    console.log("Test : "+response);
                    var res=JSON.parse(response);
                    var flag=false;
                    for(var ind=0;ind<res.length;ind++)
                    {
                        console.log(res[ind].testTaken);
                        if(res[ind].testTaken==='1')
                        {
                            flag=true;

                        }
                    }

                    if(flag)
                    {
                        if(link==='preTest') {
                          popupOpen();
                          document.getElementById("popcontent").innerHTML='<h3>You have already completed the Pre-Test</h3>';
                        }
                        else if(link==='Details')
                        {
                            window.location.href='details.php';
                        }
                        else if(link==='Module')
                        {
                             window.location.href='#';
                        }
                         else if(link==='Assessment')
                        {
                            popupOpen();
                            document.getElementById("popcontent").innerHTML='<h3>You have already completed the Assessment</h3>';

                        }
                } else {
                       

                         if(link==='preTest') {
                              window.location.href='pre-test.php'; // Page Redirect 
                         }
                          else if(link==='Assessment')
                            {
                                
                                // Check for All PDF's are downloaded or not
                                    var course='<?php if(isset($_SESSION[constant("SESSION_COURSENAME")])) echo $_SESSION[constant("SESSION_COURSENAME")]; ?>';
                                    var userId='<?php if(isset($_SESSION[constant("SESSION_USER_REGID")])) echo $_SESSION[constant("SESSION_USER_REGID")]; ?>';
                                    var courseId='<?php if(isset($_SESSION[constant("SESSION_COURSEID")])) echo $_SESSION[constant("SESSION_COURSEID")]; ?>';
                                    // Check for Displaying Go for Assessment Option
                                    // Inputs : courseId, userId
                                    console.log("Course : "+course);
                                    console.log("UserId : "+userId); 
                                    console.log("courseId : "+courseId);
                                    
                                    var testresp="";
                                         $.ajax({type: "GET", 
                                                            async: false,
                                                            url: 'php/dac.courses.php',
                                                            data: { 
                                                                userId :userId,
                                                                course : courseId,
                                                                action : 'checkForAssessmentTest'
                                                            },
                                                            success: function(resp)
                                                            {
                                                                  testresp=resp;
                                                            }
                                                           });

                                                           console.log("testResp :"+testresp);

                                    if(testresp==='Done')
                                    {
                                        window.location.href='assessment.php';
                                    }
                                    else
                                    {
                                         popupOpen();
                                         document.getElementById("popcontent").innerHTML='<h3>You have not downloaded and read all PDFs. <br/> Please do it first to access Assessment Test</h3>';
                                    }
                            }
                       
                        
                        }
            
            }
        }
        }
        function checkpreTestCompletedOrNot()
        {
            
            
        }
       
    </script>
    <style>
        .course-subTag { cursor:pointer; }
        #course-content { margin-bottom:1%; }
        
    </style>
  </head>
<body onload="getCoursesListAfterLogin()">
    
    
  <div id="PopupAudioBackground"></div> 
        <div id="PopupAudioFrontEnd">
            <a href="#" onclick="javascript:popupClose();">
                         <img id="PopupAudioCloseButton" src="images/stuff/button.jpg"/> 
             </a>
            <div id="popcontent" align="center" class="col-xs-12"></div>
        </div>
  
  
<div class="container page-wrapper">
<!--   ----------------------  Start  Header Content -----------------------    -->
<div class="container">
   <div class="col-xs-12 col-xs-6 col-md-8"><a href="#"><img class="img-responsive" src="images/samarthya-logo.jpg" alt="samarthya" /></a></div>
   <div class="col-xs-12 col-xs-6 col-md-4"><img class="img-responsive center-block right-align" src="images/emblem-img.jpg" alt="Indian Emblem" /></div>
</div>
<!--   ---------------------- End  Header Content -----------------------    -->

<!--   ---------------------- Start  Navigation -----------------------    -->

<nav class="navbar navbar-default">
   <div class="container">   
      <div class="navbar-header">
         <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
         </button>
      </div>
 <!-- NAVIGATION BAR -->
            <!-- Start Navigation -->
            <?php $page='Home';
            include 'templates/Navigation.php';?>
            <!-- End Navigation -->
   </div>
</nav>

<!--   ---------------------- End  Navigation -----------------------    -->


<!--   ---------------------- Start Home Page About Content -----------------------    -->
<br/>
<div class="container">
   <div class="col-xs-12">
      <h3 class="featurette-heading">COURSES</h3>
      <hr class="featurette-divider">
         <p id="userlanding-content">"Mahatma Gandhi National Rural Employment Guarantee Act aims at enhancing the livelihood security of people 
             in rural areas by guaranteeing hundred days of wage employment in a financial year to a rural household 
             whose adult members volunteer to do unskilled manual work" © 2015 NIRD Inc. All rights reserved. 
             "Mahatma Gandhi National Rural Employment Guarantee Act aims at enhancing the livelihood security of people
             in rural areas by guaranteeing hundred days of wage employment in a financial year to a rural household 
             whose adult members volunteer to do unskilled manual work"
         
            "Mahatma Gandhi National Rural Employment Guarantee Act aims at enhancing the livelihood security of people 
            in rural areas by guaranteeing hundred days of wage employment in a financial year to a rural household whose
            adult members volunteer to do unskilled manual work" © 2015 NIRD Inc. All rights reserved. "Mahatma Gandhi
            National Rural Employment Guarantee Act aims at enhancing the livelihood security of people in rural areas 
            by guaranteeing hundred days of wage employment in a financial year to a rural household whose adult members 
            volunteer to do unskilled manual work"
        </p>
<br/>
</div>
</div>
<div id="view-courses-list" class="container">
    <!--div class="col-xs-12 col-xs-6 col-md-3">
    <div class="course-box">
    <div class="course-header"><h5 class="course-title">Course I</h5></div>
    <div align="center" class="course-img"><img src="images/course-i-img.jpg" />
    </div>
    <div class="course-footer"><a href="#">Natural resources management<img src="images/course-arrow.png" class="pull-right" /></a></div>
    <div class="course-menu">
    <ul>
    <li><a href="pre-test.php">Take a Pretest</a></li>
    <li><a href="details.php">Details</a></li>
    <li><a href="#">Go to Module</a></li>
    <li><a href="assessment.php">Go for Assessment</a></li>
    </ul>
    </div>
    </div>   
    </div>
    
    <div class="col-xs-12 col-xs-6 col-md-3">
    <div class="course-box">
    <div class="course-header"><h5 class="course-title">Course II</h5></div>
    <div align="center" class="course-img"><img src="images/course-ii-img.jpg" />
    </div>
    <div class="course-footer"><a href="#">Community/Individual assets<img src="images/course-arrow.png" class="pull-right" /></a></div>
    <div class="course-menu">
    <ul>
    <li><a href="pre-test.php">Take a Pretest</a></li>
    <li><a href="details.php">Details</a></li>
    <li><a href="#">Go to Module</a></li>
    <li><a href="assessment.php">Go for Assessment</a></li>
    </ul>
    </div>
    </div>   
    </div>
    
    <div class="col-xs-12 col-xs-6 col-md-3">
    <div class="course-box">
    <div class="course-header"><h5 class="course-title">Course III</h5></div>
    <div align="center" class="course-img"><img src="images/course-iii-img.jpg" />
    </div>
    <div class="course-footer"><a href="#">Common infrastructure<img src="images/course-arrow.png" class="pull-right" /></a></div>
    <div class="course-menu">
    <ul>
    <li><a href="pre-test.php">Take a Pretest</a></li>
    <li><a href="details.php">Details</a></li>
    <li><a href="#">Go to Module</a></li>
    <li><a href="assessment.php">Go for Assessment</a></li>
    </ul>
    </div>
    </div>   
    </div>
    
    <div class="col-xs-12 col-xs-6 col-md-3">
    <div class="course-box">
    <div class="course-header"><h5 class="course-title">Course IV</h5></div>
    <div align="center" class="course-img"><img src="images/course-iv-img.jpg" />
    </div>
    <div class="course-footer"><a href="#">Rural infrastructure<img src="images/course-arrow.png" class="pull-right" /></a></div>
    <div class="course-menu">
    <ul>
    <li><a href="pre-test.php">Take a Pretest</a></li>
    <li><a href="details.php">Details</a></li>
    <li><a href="#">Go to Module</a></li>
    <li><a href="assessment.php">Go for Assessment</a></li>
    </ul>
    </div>
    </div>   
    </div-->

    </div>
      <br/>
      <!--a href="finalTest.php">
        <input type="submit"  id="certificateButton" class="btn btn-default pull-right" value=" Go for Final Test "/> 
      </a-->
      <br/>
      <br/>
<!--   ---------------------- Start Details Page video Content -----------------------    -->


    
    <!--   ---------------------- End Details Page video Content -----------------------    -->
    
    <!--   ---------------------- Start Footer Page Content -----------------------    -->
    
    <div class="container">
       <hr class="featurette-divider footerdivider">
       <div class="col-xs-12 col-md-7">
       <ul class="nav navbar-nav footer-menu">
          <li class="active"><a href="#">Home</a></li>
          <li>|</li>
          <li class="active"><a href="#">Courses</a></li>
          <li>|</li>
          <li class="active"><a href="contact.php">Contact Us</a></li>   
       </ul>
       </div>
       <div class="col-xs-12 col-md-5">
          <ul class="nav navbar-nav social-menu">
          <li class="followus">Follow Us</li>
          <li><a href="#"><img src="images/facebook-icon.png" /></a></li>
          <li><a href="#"><img src="images/twitter-icon.png" /></a></li>
          <li><a href="#"><img src="images/pinterest-icon.png" /></a></li>
      <li><a href="#"><img src="images/flickr-icon.png" /></a></li>
      <li><a href="#"><img src="images/youtube-icon.png" /></a></li>
      </ul>
   </div>
</div>
<footer><div class="container">&copy; 2015 Copyright | ONLINE COURSES.</div></footer>

</div>

<!--   ---------------------- End Footer Page Content -----------------------    -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-1.11.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
<?php } else {     header("location:index.php"); } ?>