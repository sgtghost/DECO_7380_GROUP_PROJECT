
    <title>Organiser Management</title>
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

    <main id="organiserManage">
        <div class="management">
            <div class="events">
                <h2>Event Management</h2>
                <div class="eventsContainer">
                    <div class="eventList">
                        <div class="category private">
                            <p>Private</p>
                            <?php 
                                if (isset($privateEvents)) {
                                    echo "<div class='arrow'></div>";
                                }
                            ?>
                            <div class="lists private">
                                <?php 
                                    if (isset($privateEvents)) {
                                        foreach ($privateEvents as $key => $event) {
                                            if ($event->applying > "0") {
                                                echo "<h3 eventid='$event->eventID'>" . json_decode($event->descriptionText)->name . "<span class='amount'>$event->applying</span></h3>";
                                            } else {
                                                echo "<h3 eventid='$event->eventID'>" . json_decode($event->descriptionText)->name . "</h3>";
                                            }   
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="category ongoing">
                            <p>OnGoing</p>
                            <?php 
                                if (isset($ongoingEvents)) {
                                    echo "<div class='arrow'></div>";
                                }
                            ?>
                            <div class="lists ongoing">
                                <?php 
                                    if (isset($ongoingEvents)) {
                                        foreach ($ongoingEvents as $key => $event) {
                                            if ($event->applying != "0") {
                                                echo "<h3 eventid='$event->eventID'>" . json_decode($event->descriptionText)->name . "<span class='amount'>$event->applying</span></h3>";
                                            } else {
                                                echo "<h3 eventid='$event->eventID'>" . json_decode($event->descriptionText)->name . "</h3>";
                                            }
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="category ended">
                            <p>Ended</p>
                            <?php 
                                if (isset($endedEvents)) {
                                    echo "<div class='arrow'></div>";
                                }
                            ?>
                            <div class="lists ended">
                                <?php 
                                    if (isset($endedEvents)) {
                                        foreach ($endedEvents as $key => $event) {
                                            if ($event->applying != "0") {
                                                echo "<h3 eventid='$event->eventID'>" . json_decode($event->descriptionText)->name . "<span class='amount'>$event->applying</span></h3>";
                                            } else {
                                                echo "<h3 eventid='$event->eventID'>" . json_decode($event->descriptionText)->name . "</h3>";
                                            }
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="eventDetail">
                        <div class="eventInfo"><img class="event_intro" src="https://productlander.uqcloud.net/static/assets/images/event_intro.png" alt=""></div>

                        <div class="filter" style="display:none;">
                            <h4>NEW APPLICATION</h4>
                            <div class="times">
                                <h5>AVAILABLE TIME</h5>
                            </div>
                            <div class="areas">
                                <h5>INTERESTED AREA</h5>
                            </div>

                            <div class="new"></div>
                        </div>

                        <div class="approved"></div>
                    </div>
                </div>

            </div>

            <div class="personal">
                <img src="<?php echo base_url() . '/assets/images/' . $this->session->userdata('avatar');?>">
                <h3><?php echo $this->session->userdata('username');?></h3>
                <div class="edit">EDIT INFORMATION</div>
            </div>
        </div>
        
        <div class="addEvent">
            <div class="tip">Add an Event</div>
            <svg class='icon' aria-hidden='true'><use xlink:href='#icon-jiahao1'></use></svg>
        </div>

        <div class="chooseTime hide">
            <div class="timeChosenWindow">
                <img class="cancel" src="<?php echo base_url(); ?>/assets/images/cancel.svg" alt="">
                <h3>Available Time</h3>
                <p class='intro'>Tell us when you are free</p>
                <p class='timeOption'>Monday</p>
                <p class='timeOption'>Tuesday</p>
                <p class='timeOption'>Wednesday</p>
                <p class='timeOption'>Thursday</p>
                <p class='timeOption'>Friday</p>
                <p class='timeOption'>Saturday</p>
                <p class='timeOption'>Sunday</p>
                <div class="confirmButton">CONFIRM</div>
            </div>
        </div>
        <div class="chooseArea hide">
            <div class="areaChosenWindow">
                <img class="cancel" src="<?php echo base_url(); ?>/assets/images/cancel.svg" alt="">
                <h3>Choose Areas</h3>
                <div class="areaOptions">
                    <p class="areaOption">Animals</p>
                    <p class="areaOption">Art&Culture</p>
                    <p class="areaOption">Children&Youth</p>
                    <p class="areaOption">Computer&Technology</p>
                    <p class="areaOption">Education</p>
                    <p class="areaOption">Environment</p>
                    <p class="areaOption">Health&Medicine</p>
                    <p class="areaOption">Seniors</p>
                    <p class="areaOption">Sports&Recreation</p>
                    <p class="areaOption">Community</p>
                    <p class="areaOption">Museum</p>
                </div>
                <div class="confirmButton">CONFIRM</div>
            </div>
        </div>

        <div class="confirm publish hide">
            <div class="confirmWindow">
                <img class="cancel" src="<?php echo base_url(); ?>/assets/images/cancel.svg" alt="">
                <h3>Are you sure to <strong>Publish</strong>?</h3>
                <p>This action cannot be <strong>undone</strong></p>
                <div class="buttons">
                    <div class="yes">YES</div>
                    <div class="no">CANCEL</div>
                </div>
            </div>
        </div>

        <div class="confirm reject hide">
            <div class="confirmWindow">
                <img class="cancel" src="<?php echo base_url(); ?>/assets/images/cancel.svg" alt="">
                <h3>Are you sure to <strong>Reject</strong> this application?</h3>
                <p>This action cannot be <strong>undone</strong></p>
                <div class="buttons">
                    <div class="yes">YES</div>
                    <div class="no">CANCEL</div>
                </div>
            </div>
        </div>

        <div class="confirm accept hide">
            <div class="confirmWindow">
                <img class="cancel" src="<?php echo base_url(); ?>/assets/images/cancel.svg" alt="">
                <h3>Are you sure to <strong>Accept</strong> this application?</h3>
                <p>This action cannot be <strong>undone</strong></p>
                <div class="buttons">
                    <div class="yes">YES</div>
                    <div class="no">CANCEL</div>
                </div>
            </div>
        </div>
       
        <div class="confirm delete hide">
            <div class="confirmWindow">
                <img class="cancel" src="<?php echo base_url(); ?>/assets/images/cancel.svg" alt="">
                <h3>Are you sure to <strong>Delete</strong> this event?</h3>
                <p>This action cannot be <strong>undone</strong></p>
                <p>Deleted items cannot be <strong>retreived</strong></p>
                <div class="buttons">
                    <div class="yes">YES</div>
                    <div class="no">CANCEL</div>
                </div>
            </div>
        </div>
    </main>

    <script src="//at.alicdn.com/t/font_2506080_2mev0uhqlhi.js"></script>
    <script>
        let eventID = 0;
        let uid = 0;
        let remain = 0;
        let processingApplications = [];
        let filterTimes = [];
        let filterAreas = [];

        //Show list
        $(".category").on("click", "p", function() {
            $(this).next().next().toggleClass("show");
            $(this).toggleClass("focus");
        });

        //Choose window interactions
        $("#organiserManage .chooseTime .timeOption, #organiserManage .areaChosenWindow .areaOption").on("click", function() {
            $(this).toggleClass("chosen");
        });

        //Hide choose window
        $("#organiserManage .chooseTime .cancel").on("click", () => {
            $("#organiserManage .chooseTime").addClass("hide");
        });
        $("#organiserManage .chooseArea .cancel").on("click", () => {
            $("#organiserManage .chooseArea").addClass("hide");
        });

        //Open the chosen options window
        $("#organiserManage .times").on("click", "h5", () => {
            if ($("#organiserManage .times .keyword").length > 0) {
                $("#organiserManage .times .keyword").each(function(index) {
                    let value = $(this).html();
                    $("#organiserManage .timeChosenWindow .timeOption").each(function(index1) {
                        if ($(this).html() == value) {
                            $(this).addClass("chosen");
                        }
                    });
                });
            }

            $("#organiserManage .chooseTime").removeClass("hide");
        });

        $("#organiserManage .areas").on("click", "h5", () => {
            if ($("#organiserManage .areas .keyword").length > 0) {
                $("#organiserManage .areas .keyword").each(function(index) {
                    let value = $(this).html();
                    $("#organiserManage .areaChosenWindow .areaOption").each(function(index1) {
                        if ($(this).html() == value) {
                            $(this).addClass("chosen");
                        }
                    });
                });
            }

            $("#organiserManage .chooseArea").removeClass("hide");
        });

        function filterProcessing() {
            $("#organiserManage .events .eventDetail .filter .new .resume").removeClass("filtered");

            //Filter new applications
            for (const application of processingApplications) {
                for (let time of filterTimes) {
                    if (JSON.parse(application.descriptionText).time.indexOf(time) == -1) {
                        //Don't have this time
                        $("#organiserManage .events .eventDetail .filter .new").find(".resume[uid='" + application.uid + "']").addClass("filtered");
                    }
                }
                for (let area of filterAreas) {
                    if (application.tags.indexOf(area) == -1) {
                        //Don't have this area  
                        $("#organiserManage .events .eventDetail .filter .new").find(".resume[uid='" + application.uid + "']").addClass("filtered");
                    }    
                }  
            } 
        }

        //Update the chosen options
        $("#organiserManage .chooseTime .confirmButton").on("click", function() {
            let html = "<h5>AVAILABLE TIME</h5>";
            filterTimes = [];

            $("#organiserManage .timeChosenWindow .timeOption").each(function(index) {
                if ($(this).hasClass('chosen')) {
                    html = html + "<span class='keyword no'>" + $(this).html() + "</span>";
                    filterTimes.push($(this).text());
                }
            });
            filterProcessing();
            $("#organiserManage .times").html(html);
            $("#organiserManage .chooseTime").addClass("hide");
        }); 

        $("#organiserManage .chooseArea .confirmButton").on("click", function() {
            let html = "<h5>INTERESTED AREA</h5>";
            filterAreas = [];

            $("#organiserManage .areaChosenWindow .areaOption").each(function(index) {
                if ($(this).hasClass('chosen')) {
                    html = html + "<span class='keyword no'>" + $(this).html() + "</span>";
                    filterAreas.push($(this).text());
                }
            });
            filterProcessing();
            $("#organiserManage .areas").html(html);
            $("#organiserManage .chooseArea").addClass("hide");
        });

        //Refresh detail page when choose one event
        $(".category .lists").on("click","h3", function() {
            $(".category .lists").find(".focus").removeClass("focus");
            $(this).addClass("focus");
            eventID = $(this).attr("eventid");
            processingApplications = []
            $.ajax({
                url: window.location.origin + "/static/organiserManage/getEvent",
                method: "POST",
                data: {eventID},
                type: "jsonp",
                success: function(response) {
                    //Get Event detail
                    let event = JSON.parse(response);
                    let toPublic= "";

                    if (event.status == 'private') {
                        toPublic = "<div class='toPublic'>To Public: &nbsp;<div class='back'><div class='button'></div><div class='status'>Status: Private</div></div></div>";
                    } else if (event.status == 'ongoing') {
                        toPublic = "<div class='toPublic'><div class='back ongoing'><div class='status'>Status: Ongoing</div></div></div>";
                    } else {
                        toPublic = "<div class='toPublic'><div class='back ended'><div class='status'>Status: Ended</div></div></div>";
                    }

                    let eventInfo = "<img src='" + window.location.origin + "/static/assets/images/" + JSON.parse(event.descriptionImageLink).avatar + "'>\
                                    <div class='text'>\
                                        <h4 eventid='" + eventID + "'>" + JSON.parse(event.descriptionText).name + "</h4><br>\
                                        <p>Project Time: " + event.date + "</p>\
                                        <p>Address: " + event.address + "</p>\
                                    </div>" + toPublic + "\
                                    <div class='tianxie icons'>\
                                        <svg class='icon' aria-hidden='true'>\
                                            <use xlink:href='#icon-tianxie'></use>\
                                        </svg>\
                                    </div>\
                                    <div class='lajixiang icons'>\
                                        <svg class='icon' aria-hidden='true'>\
                                            <use xlink:href='#icon-lajixiang'></use>\
                                        </svg>\
                                    </div>";

                    if (event.status == 'ended') {
                        eventInfo = "<img src='" + window.location.origin + "/static/assets/images/" + JSON.parse(event.descriptionImageLink).avatar + "'>\
                                    <div class='text'>\
                                        <h4 eventid='" + eventID + "'>" + JSON.parse(event.descriptionText).name + "</h4><br>\
                                        <p>Project Time: " + event.date + "</p>\
                                        <p>Address: " + event.address + "</p>\
                                    </div>" + toPublic;
                    }

                    $("#organiserManage .events .eventDetail .eventInfo").html(eventInfo);    

                    $.ajax({
                        url: window.location.origin + "/static/organiserManage/getApplication",
                        method: "POST",
                        data: {eventID},
                        type: "jsonp",
                        success: function(response) {
                            if (response) {
                                let arr = JSON.parse(response);
                                let newApplication = "";
                                let approvedApplication = "<h4>APPROVED</h4>";
                                let appliedMount = 0;

                                for (const application of arr) {
                                    if (application.status == "Processing" && application.deletion == "0") {
                                        processingApplications.push(application);
                                        newApplication = newApplication + "<div class='resume'" +
                                                        "uid='" + application.uid + "'>Name: " + JSON.parse(application.descriptionText).name +
                                                        "<div class='view' username='" + application.username + "'>VIEW RESUME</div>" + 
                                                        "<div class='gou icons'><svg class='icon' aria-hidden='true'><use xlink:href='#icon-gou'></use></svg></div>" +
                                                        "<div class='cha icons'><svg class='icon' aria-hidden='true'><use xlink:href='#icon-cha'></use></svg></div>" +
                                                    "</div>";
                                    } else if (application.status == "Passed") {
                                        //Show all approved resumes including deleted by the applicant
                                        appliedMount++;
                                        approvedApplication = approvedApplication + "<div class='resume'" +
                                                        "uid='" + application.uid + "'>Name: " + JSON.parse(application.descriptionText).name +
                                                        "<div class='view' username='" + application.username + "'>VIEW RESUME</div>" + 
                                                    "</div>";
                                    }
                                }
                                remain = parseInt(event.slot) - appliedMount
                                $("#organiserManage .events .eventDetail .eventInfo .text").append("<p>Remaining Quantity : " + remain + "</p>");
                                $("#organiserManage .events .eventDetail .filter .new").html(newApplication);
                                $("#organiserManage .events .eventDetail .filter").css("display", "block");
                                $("#organiserManage .events .eventDetail .approved").html(approvedApplication);
                            } else {
                                $("#organiserManage .events .eventDetail .filter .new").html("");
                                $("#organiserManage .events .eventDetail .filter").css("display", "none");
                                $("#organiserManage .events .eventDetail .approved").html("");
                            }            
                        },
                        error: function(reason) {
                            console.log(reason);
                        }
                    });    
                },
                error: function(reason) {
                    console.log(reason);
                }
            });


        });

        //Interactions on detail page
        $("#organiserManage .events .eventDetail").on("click", ".resume .view", function() {
            window.open(window.location.origin + "/static/resume?username=" + $(this).attr("username"));
        });

        $("#organiserManage .events .eventDetail .eventInfo").on("click", ".text h4", function() {
            window.open(window.location.origin + "/static/eventDetail?eventID=" + $(this).attr("eventid"));
        });

        $("#organiserManage .events .eventDetail").on("click", ".tianxie", function() {
            window.location.href = window.location.origin + "/static/eventDetail/edit?eventID=" + eventID;
        });

        $("#organiserManage .addEvent").on("click", function() {
            window.location.href = window.location.origin + "/static/eventDetail/edit";
        });

        //To public
        $("#organiserManage .events .eventDetail").on("click", ".toPublic .back .button", function() {
            $("#organiserManage .confirm.publish").removeClass("hide");
        });

        $("#organiserManage .confirm .cancel").on("click", function() {
            $(this).parent().parent().addClass("hide");
        });

        $("#organiserManage .confirm .no").on("click", function() {
            $(this).parent().parent().parent().addClass("hide");
        });

        $("#organiserManage .confirm .yes").on("click", function() {
            $(this).html("<span class='spiral'></span>");
        });

        $("#organiserManage .confirm.publish .yes").on("click", function() {
            $("#organiserManage .confirm.publish").addClass("hide");
            $("#organiserManage .events .eventDetail .toPublic .back").addClass("slide");
            $("#organiserManage .category .lists.private").removeClass("show");
            $("#organiserManage .category .lists.private").prev().prev().removeClass("focus");
            $.ajax({
                url: window.location.origin + "/static/organiserManage/toPublic",
                method: "POST",
                data: {eventID},
                type: "jsonp",
                success: function(response) {
                    $("h3[eventid='" + eventID + "']").trigger("click");
                    $("#organiserManage .eventList .lists.ongoing").prepend($("h3[eventid='" + eventID + "']"));
                    $("#organiserManage .category .lists.ongoing").prev().prev().addClass("focus");
                    $("#organiserManage .category .lists.ongoing").addClass("show");
                },
                error: function(reason) {
                    console.log(reason);
                }
            });
        });

        /*Accept and reject applicaitons*/
        $("#organiserManage .events .eventDetail").on("click", ".resume .gou", function() {
            if (remain == 1) {
                $("#organiserManage .confirm.accept .confirmWindow p").html("Attention: This is your <strong style='color:red;'>last</strong> slot. \
                                                                If you accept this one, other applications would be <strong style='color:red;'>rejected</strong> and\
                                                                the event would be treated as <strong style='color:red;'>'Ended'</strong> automatically.</strong>");
            } else {
                $("#organiserManage .confirm.accept .confirmWindow p").html("This action cannot be <strong>undone");
            }
            $("#organiserManage .confirm.accept").removeClass("hide");
            uid = $(this).parent().attr("uid");
        });

        $("#organiserManage .events .eventDetail").on("click", ".resume .cha", function() {
            $("#organiserManage .confirm.reject").removeClass("hide");
            uid = $(this).parent().attr("uid");
        });

        //Reject
        $("#organiserManage .confirm.reject .yes").on("click", function() {
            $.ajax({
                url: window.location.origin + "/static/apply/rejectOne",
                method: "POST",
                data: {eventID, uid},
                type: "jsonp",
                success: function(response) {
                    $("#organiserManage .confirm.reject").addClass("hide");
                    $("#organiserManage .confirm.reject .yes").html("YES");
                    $("#organiserManage .events .eventDetail").find(".resume[uid='" + uid + "']").addClass("slideOut");
                    let amount = parseInt($("h3[eventid='" + eventID + "'] .amount").text()) - 1;

                    if (amount < 1) {
                        $("h3[eventid='" + eventID + "'] .amount").attr("style", "display:none;");
                    } else {
                        $("h3[eventid='" + eventID + "'] .amount").text(amount);
                    }
                },
                error: function(reason) {
                    console.log(reason);
                }
            });

        });

        //Accept
        $("#organiserManage .confirm.accept .yes").on("click", function() {
            remain--;
            let html = "";
            if (remain > 0) {
                //Has space for incoming
                html = window.location.origin + "/static/apply/acceptOne";
            } else {
                html = window.location.origin + "/static/apply/acceptOneFull";
            }

            $.ajax({
                url: html,
                method: "POST",
                data: {eventID, uid},
                type: "jsonp",
                success: function(response) {
                    $("#organiserManage .confirm.accept").addClass("hide");
                    $("#organiserManage .confirm.accept .yes").html("YES");
                    $("#organiserManage .events .eventDetail").find(".resume[uid='" + uid + "']").addClass("slideOut");
                    $("h3[eventid='" + eventID + "']").trigger("click");
                    let amount = parseInt($("h3[eventid='" + eventID + "'] .amount").text()) - 1;

                    if (amount < 1 || remain <= 0) {
                        $("h3[eventid='" + eventID + "'] .amount").attr("style", "display:none;");
                    } else {
                        $("h3[eventid='" + eventID + "'] .amount").text(amount);
                    }
                },
                error: function(reason) {
                    console.log(reason);
                }
            });
        });

        //Delete the event
        $("#organiserManage .events .eventDetail").on("click", ".lajixiang", function() {
            $("#organiserManage .confirm.delete").removeClass("hide");
        });

        $("#organiserManage .confirm.delete .yes").on("click", function() {
            $.ajax({
                url: window.location.origin + "/static/apply/rejectAll",
                method: "POST",
                data: {eventID},
                type: "jsonp",
                success: function(response) {
                    $("#organiserManage .confirm.delete").addClass("hide");
                    $("#organiserManage .confirm.delete .yes").html("YES");
                    $("h3[eventid='" + eventID + "']").parent().removeClass("show");
                    $("h3[eventid='" + eventID + "']").parent().prev().prev().removeClass("focus");
                    $("#organiserManage .events .eventDetail .filter .new .resume").addClass("slideOut");
                    $("h3[eventid='" + eventID + "'] .amount").attr("style", "display:none;");
                    $("h3[eventid='" + eventID + "']").trigger("click");
                    $("#organiserManage .eventList .lists.ended").prepend($("h3[eventid='" + eventID + "']"));
                    $("#organiserManage .category .lists.ended").prev().prev().addClass("focus");
                    $("#organiserManage .category .lists.ended").addClass("show");
                },
                error: function(reason) {
                    console.log(reason);
                }
            });
        });
    </script>
</body>
</html>