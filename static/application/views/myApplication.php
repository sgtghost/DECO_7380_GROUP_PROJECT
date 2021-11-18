
    <title>My Application</title>
</head>
<body class="manageBody">
    <header id="header">
        <nav>
            <img class="logo" src="<?php echo base_url(); ?>/assets/images/logo.png">
            <section class="menu"></section>
            <section class="login">
                <?php
                    if ($this->session->userdata('logged_in')) {
                        if ($this->session->userdata('organiser') == "1") {
                            $avatarMenu = "<div class='events'>Events</div>";
                        } else {
                            $avatarMenu = "<div class='application'>Application</div>";
                        }
                        echo "<div class='container'>
                                <img class='avatar' src='" . base_url() . "/assets/images/" . $this->session->userdata('avatar') . "'>
                                <div class='avatar_menu'><div class='resume'>Resume</div>" . $avatarMenu . "<div class='logout'>Log out</div></div>
                            </div>";
                    } else {
                        echo '<a class="login" href="">Login</a>
                            <a class="register" href="">Register</a>';
                    }
                ?>
            </section>
        </nav>
    </header>

    <main id="myApplication">
        <div class="management">
            <div class="applications">
                <div class='headers'>
                    <h3 class='yourEvents focus'>Your Events</h3>
                    <h3 class="yourApplications">Your Applications</h3>
                </div>
                
                <div class="content">
                    
                </div>
            </div>

            <div class="personal">
                <img src="<?php echo base_url() . '/assets/images/' . $this->session->userdata('avatar');?>">
                <h3><?php echo $this->session->userdata('username');?></h3>
                <div class="edit">EDIT RESUME</div>
            </div>
        </div>

        <div class="confirm delete hide">
            <div class="confirmWindow">
                <img class="cancel" src="<?php echo base_url(); ?>/assets/images/cancel.svg" alt="">
                <h3>Are you sure to <strong>Withdraw</strong> this application?</h3>
                <p>This action cannot be <strong>undone</strong></p>
                <div class="buttons">
                    <div class="yes">YES</div>
                    <div class="no">CANCEL</div>
                </div>
            </div>
        </div>
    </main>

    <script src="//at.alicdn.com/t/font_2506080_2mev0uhqlhi.js"></script>
    <script>
        let username = '<?php echo $this->session->userdata('username');?>';
        let month = (new Date().getMonth() + 1 + '').padStart(2, '0');
        let day = (new Date().getDate() + '').padStart(2, '0');
        let curDate = new Date().getFullYear() + "-" + month + "-" + day;
        let eventID = 0;
        let uid = 0;
        let processingCount = 0;
        //Your Events
        $("#myApplication .management .applications .headers h3.yourEvents").on("click", function() {
            $(this).addClass("focus");
            $("#myApplication .management .applications .headers h3.yourApplications").removeClass("focus");
            $.ajax({
                url: window.location.origin + "/static/myApplication/events",
                method: "POST",
                data: {username},
                type: "jsonp",
                success: function(response) {
                    if (response != 'false') {
                        $("#myApplication .management .applications .content").html("\
                            <div class='running'>\
                                <h4>Running Now<div class='arrowBlock'><div class='arrow'></div></div></h4>\
                                <div class='container'></div>\
                            </div>\
                            <div class='upcoming'>\
                                <h4>Upcoming<div class='arrowBlock'><div class='arrow'></div></div></h4>\
                                <div class='container'></div>\
                            </div>\
                            <div class='ended'>\
                                <h4>Ended<div class='arrowBlock'><div class='arrow'></div></div></h4>\
                                <div class='container'></div>\
                            </div>");
                        let events = JSON.parse(response);
                        let runningHtml = "";
                        let upcomingHtml = "";
                        let endedHtml = "";

                        for (const event of events) {
                            if (event.status == 'ongoing') {
                                if (event.date == curDate) {
                                    //Running now
                                    runningHtml += '<div class="detail" eventid="' + event.eventID + '">' +
                                                        '<div class="intro">' + 
                                                            '<h5 class="name">' + JSON.parse(event.descriptionText).name + '</h5><br>' + 
                                                            '<p>Project time: <span class="time">' + event.date + '</span></p>' + 
                                                            '<p>Address: ' + event.address + '</p>' +
                                                        '</div>' + 

                                                        '<div class="status">' +
                                                            '<div class="info">' +
                                                                '<h5>Status: </h5>' +
                                                                '<p>Running Now</p>' +
                                                            '</div>' + 
                                                            '<img src=\"' + window.location.origin + '/static/assets/images/' + JSON.parse(event.descriptionImageLink).avatar + '\" alt="" >' +
                                                        '</div>' +                     
                                                    '</div>';
                                } else {
                                    //Upcoming
                                    let comingDays = parseInt(Math.round(new Date(event.date).getTime() - new Date(curDate).getTime()) / 1000 / 60 / 60 / 24);
                                    upcomingHtml += '<div class="detail" eventid="' + event.eventID + '">' +
                                                        '<div class="intro">' + 
                                                            '<h5 class="name">' + JSON.parse(event.descriptionText).name + '</h5><br>' + 
                                                            '<p>Project time: <span class="time">' + event.date + '</span></p>' + 
                                                            '<p>Address: ' + event.address + '</p>' +
                                                        '</div>' + 

                                                        '<div class="status">' +
                                                            '<div class="info">' +
                                                                '<h5>Status: </h5>' +
                                                                '<p>Upcoming in <strong>' + comingDays + '</strong> Days</p>' +
                                                            '</div>' + 
                                                            '<img src=\"' + window.location.origin + '/static/assets/images/' + JSON.parse(event.descriptionImageLink).avatar + '\" alt="" >' +
                                                        '</div>' +                     
                                                    '</div>';
                                }
                            } else {
                                //Ended events
                                endedHtml += '<div class="detail" eventid="' + event.eventID + '">' +
                                                        '<div class="intro">' + 
                                                            '<h5 class="name">' + JSON.parse(event.descriptionText).name + '</h5><br>' + 
                                                            '<p>Project time: <span class="time">' + event.date + '</span></p>' + 
                                                            '<p>Address: ' + event.address + '</p>' +
                                                        '</div>' + 

                                                        '<div class="status">' +
                                                            '<div class="info">' +
                                                                '<h5>Status: </h5>' +
                                                                '<p>Finished</p>' +
                                                            '</div>' + 
                                                            '<img src=\"' + window.location.origin + '/static/assets/images/' + JSON.parse(event.descriptionImageLink).avatar + '\" alt="" >' +
                                                        '</div>' +                     
                                                    '</div>';
                            }
                        }

                        if (runningHtml) {
                            $("#myApplication .management .applications .content .running .container").html(runningHtml);
                        } else {
                            $("#myApplication .management .applications .content .running .container").html("<h5>You have no Events running now.</h5>");
                        }

                        if (upcomingHtml) {
                            $("#myApplication .management .applications .content .upcoming .container").html(upcomingHtml);
                        }else {
                            $("#myApplication .management .applications .content .upcoming .container").html("<h5>You have no upcoming Events.</h5>");
                        }

                        if (endedHtml) {
                            $("#myApplication .management .applications .content .ended .container").html(endedHtml);
                        } else {
                            $("#myApplication .management .applications .content .ended .container").html("<h5>You have no ended Events.</h5>");
                        }
                    } else {
                        $("#myApplication .management .applications .content").html("<h2>You have no passed events.</h2>");
                    }
                },
                error: function(reason) {
                    console.log(reason);
                }
            });
        });
        //Show and hide items
        $("#myApplication .management .applications .content").on("click", ".arrowBlock", function() {
            $(this).parent().next().toggleClass("show");
            $(this).toggleClass("focus");
        });

        $("#myApplication .management .applications .headers h3.yourEvents").trigger("click");

        $("#myApplication .management .applications .content").on("click", ".container .detail .intro h5", function() {
            window.open(window.location.origin + "/static/eventDetail?eventID=" + $(this).parent().parent().attr("eventid"));
        });

        //Your Applications
        $("#myApplication .management .applications .headers h3.yourApplications").on("click", function() {
            $(this).addClass("focus");
            $("#myApplication .management .applications .headers h3.yourEvents").removeClass("focus");
            $.ajax({
                url: window.location.origin + "/static/myApplication/applications",
                method: "POST",
                data: {username},
                type: "jsonp",
                success: function(response) {
                    if (response != 'false') {
                        $("#myApplication .management .applications .content").html("\
                            <div class='processing'>\
                                <h4>Processing<div class='arrowBlock'><div class='arrow'></div></div></h4>\
                                <div class='container'></div>\
                            </div>\
                            <div class='passed'>\
                                <h4>Passed<div class='arrowBlock'><div class='arrow'></div></div></h4>\
                                <div class='container'></div>\
                            </div>\
                            <div class='denied'>\
                                <h4>Denied<div class='arrowBlock'><div class='arrow'></div></div></h4>\
                                <div class='container'></div>\
                            </div>");
                        let events = JSON.parse(response);
                        let processingHtml = "";
                        let passedHtml = "";
                        let deniedHtml = "";

                        for (const event of events) {
                            if (event.applyStatus == 'Processing' && event.deletion == '0') {
                                //Processing
                                processingCount++;
                                processingHtml += '<div eventid=' + event.eventID + ' class="detail" eventid="' + event.eventID + '">' +
                                                    '<div class="intro">' + 
                                                        '<h5 class="name">' + JSON.parse(event.descriptionText).name + '</h5><br>' + 
                                                        '<p>Project time: <span class="time">' + event.date + '</span></p>' + 
                                                        '<p>Address: ' + event.address + '</p>' +
                                                    '</div>' + 

                                                    '<div class="status">' +
                                                        '<div class="info">' +
                                                            '<h5>Status: </h5>' +
                                                            '<p><span class="processingCircle"></span>Processing</p>' +
                                                        '</div>' + 
                                                        '<img src=\"' + window.location.origin + '/static/assets/images/' + JSON.parse(event.descriptionImageLink).avatar + '\" alt="" >' +
                                                        '<div eventid="' + event.eventID + '" uid="' + event.id + '" class="lajixiang icons">' +
                                                            '<svg eventid="' + event.eventID + '" class="icon" aria-hidden="true">' +
                                                                '<use xlink:href="#icon-lajixiang"></use>' +
                                                            '</svg>' +
                                                        '</div>' +
                                                    '</div>' +                     
                                                '</div>';
                            } else if (event.applyStatus == 'Passed') {
                                //Passed
                                passedHtml += '<div class="detail" eventid="' + event.eventID + '">' +
                                                    '<div class="intro">' + 
                                                        '<h5 class="name">' + JSON.parse(event.descriptionText).name + '</h5><br>' + 
                                                        '<p>Project time: <span class="time">' + event.date + '</span></p>' + 
                                                        '<p>Address: ' + event.address + '</p>' +
                                                    '</div>' + 

                                                    '<div class="status">' +
                                                        '<div class="info">' +
                                                            '<h5>Status: </h5>' +
                                                            '<p><span class="passedCircle"></span>Passed</p>' +
                                                        '</div>' + 
                                                        '<img src=\"' + window.location.origin + '/static/assets/images/' + JSON.parse(event.descriptionImageLink).avatar + '\" alt="" >' +
                                                    '</div>' +                     
                                                '</div>';
                                
                            } else if (event.applyStatus == 'Denied') {
                                //Denied
                                deniedHtml += '<div class="detail" eventid="' + event.eventID + '">' +
                                                        '<div class="intro">' + 
                                                            '<h5 class="name">' + JSON.parse(event.descriptionText).name + '</h5><br>' + 
                                                            '<p>Project time: <span class="time">' + event.date + '</span></p>' + 
                                                            '<p>Address: ' + event.address + '</p>' +
                                                        '</div>' + 

                                                        '<div class="status">' +
                                                            '<div class="info">' +
                                                                '<h5>Status: </h5>' +
                                                                '<p><span class="deniedCircle"></span>Denied</p>' +
                                                            '</div>' + 
                                                            '<img src=\"' + window.location.origin + '/static/assets/images/' + JSON.parse(event.descriptionImageLink).avatar + '\" alt="" >' +
                                                        '</div>' +                     
                                                    '</div>';
                            }
                        }

                        if (processingHtml) {
                            $("#myApplication .management .applications .content .processing .container").html(processingHtml);
                        } else {
                            $("#myApplication .management .applications .content .processing .container").html("<h5>You have no processing applications.</h5>");
                        }

                        if (passedHtml) {
                            $("#myApplication .management .applications .content .passed .container").html(passedHtml);
                        }else {
                            $("#myApplication .management .applications .content .passed .container").html("<h5>You have no passed applications.</h5>");
                        }

                        if (deniedHtml) {
                            $("#myApplication .management .applications .content .denied .container").html(deniedHtml);
                        } else {
                            $("#myApplication .management .applications .content .denied .container").html("<h5>You have no denied applications.</h5>");
                        }
                    } else {
                        $("#myApplication .management .applications .content").html("<h2>You have no applications yet.</h2>");
                    }
                },
                error: function(reason) {
                    console.log(reason);
                }
            });
        });

        //Withdraw application
        $("#myApplication .applications .content").on("click", ".processing .status .lajixiang", function() {
            $("#myApplication .confirm.delete").removeClass("hide");
            eventID = $(this).attr("eventid");
            uid = $(this).attr("uid");
        });

        $("#myApplication .confirm .no").on("click", function() {
            $(this).parent().parent().parent().addClass("hide");
        });

        $("#myApplication .confirm .cancel").on("click", function() {
            $(this).parent().parent().addClass("hide");
        });

        $("#myApplication .confirm .yes").on("click", function() {
            $("#myApplication .confirm .yes").html("<span class='spiral'></span>");
            $.ajax({
                url: window.location.origin + "/static/apply/deleteOne",
                method: "POST",
                data: {eventID, uid},
                type: "jsonp",
                success: function(response) {
                    $("#myApplication .confirm .yes").html("YES");
                    $("#myApplication .confirm.delete").addClass("hide");
                    processingCount--;
                    $("#myApplication .content").find(".detail[eventid='" + eventID + "']").addClass("slideOut");
                    setTimeout(() => {
                        $("#myApplication .content").find(".detail[eventid='" + eventID + "']").css("display", "none");
                        if (processingCount < 1) {
                            $("#myApplication .management .applications .content .processing .container").html("<h5>You have no processing applications.</h5>");
                        }
                    }, 600);
                },
                error: function(reason) {
                    console.log(reason);
                }
            });
        });

    </script>
</body>
</html>