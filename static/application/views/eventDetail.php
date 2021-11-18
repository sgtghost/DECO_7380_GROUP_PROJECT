    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/leaflet.css">
    <script src="<?php echo base_url(); ?>assets/js/leaflet.js"></script>
    <title>EventDetail</title>
</head>
<body>
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

    <main id="event">
        <section class="detail">
            <section class="intro">
                <h2><?php echo json_decode($event->descriptionText)->name; ?></h2><br>

                <section class="info">
                    <section class="title">
                        <p>WHEN</p>
                        <P>WHERE</P>
                        <P>GOOD FOR</P>
                        <P>Event Status</P>
                    </section>

                    <section class="data">
                        <p><?php echo $event->date; ?></p>
                        <p><?php echo $event->address; ?></p>
                        <p><?php echo json_decode($event->requirement)->personas;?></p>
                        <p class="status"><?php echo $event->status; ?></p>
                    </section>
                </section>

                <section class="interact">
                    <div class="keywords">
                        <?php
                            $areas = json_decode($event->requirement)->tags;
                            foreach ($areas as $key => $value) {
                                echo "<span class='keyword'>$value</span>";
                            }
                        ?>
                    </div>
                    
                    <?php
                        if (!$this->session->userdata('organiser')) {
                            if (isset($applied)) {
                                if ($applied->deletion == 0) {
                                    echo "<div class='button'>
                                            <div class='applied'>$applied->status</div>
                                        </div>";
                                } else {
                                    echo "<div class='button'>
                                            <div class='applied'>WITHDRAW</div>
                                        </div>";
                                }                              
                            } else {
                                echo "<div class='button'>
                                        <div class='apply'>Apply</div>
                                    </div>";
                            }
                        } else {
                            if ($this->session->userdata('username') == $organiser->username) {
                                if ($event->status == 'ended') {
                                    echo "<div class='ended'>Ended</div>";
                                } else {
                                    echo "<div class='edit'>Edit</div>";
                                }   
                            }
                        }
                    ?>    
                </section>
            </section>

            <img src="<?php echo base_url() . 'assets/images/' . json_decode($event->descriptionImageLink)->avatar; ?>" alt="">
        </section>

        <section class="desc">
            <section class="text">
                <h3>Description</h3>
                <div class='descContainer'><?php echo json_decode($event->descriptionText)->description; ?></div>
                <h3>Skills and Requirements</h3>
                <img src="<?php echo base_url(); ?>assets/images/<?php 
                    $skills = json_decode($event->requirement)->skills;
                    if ($skills[0] == 0) {
                        echo 'checkbox.svg';
                    } else {
                        echo 'checkbox_checked.svg';
                    }
                ?>"> <span>Teaching / Instruction</span><br>
                <img src="<?php echo base_url(); ?>assets/images/<?php 
                    $skills = json_decode($event->requirement)->skills;
                    if ($skills[1] == 0) {
                        echo 'checkbox.svg';
                    } else {
                        echo 'checkbox_checked.svg';
                    }
                ?>"> <span>English as a Secondary Language (ESL)</span><br>
                <img src="<?php echo base_url(); ?>assets/images/<?php 
                    $skills = json_decode($event->requirement)->skills;
                    if ($skills[2] == 0) {
                        echo 'checkbox.svg';
                    } else {
                        echo 'checkbox_checked.svg';
                    }
                ?>"> <span>Tutoring Experience</span><br>
                <img src="<?php echo base_url(); ?>assets/images/<?php 
                    $skills = json_decode($event->requirement)->skills;
                    if ($skills[3] == 0) {
                        echo 'checkbox.svg';
                    } else {
                        echo 'checkbox_checked.svg';
                    }
                ?>"> <span>Must be at least 18</span><br>
                <img src="<?php echo base_url(); ?>assets/images/<?php 
                    $skills = json_decode($event->requirement)->skills;
                    if ($skills[4] == 0) {
                        echo 'checkbox.svg';
                    } else {
                        echo 'checkbox_checked.svg';
                    }
                ?>"> <span>At least 1 month with the commitment</span><br>
            </section>

            <section class="organization">
                <h3>About Organization</h3>
                <div class="info">
                    <img src="<?php echo base_url() . 'assets/images/' . json_decode($organiser->descriptionImageLink)->avatar; ?>" class="avatar" alt="">
                    <h4><?php echo json_decode($organiser->descriptionText)->name; ?></h4>
                    <div class="container">
                        <p><?php echo json_decode($organiser->descriptionText)->description; ?></p><br>
                        <h5>Area: </h5>
                        <?php 
                            $areas = json_decode($organiser->tags)->tags;
                            foreach ($areas as $key => $value) {
                                echo "<span class='area'>$value</span>";
                            }
                        ?>
                        <br><br>
                        <h5>Contact: </h5>
                        <img src="<?php echo base_url(); ?>assets/images/mail.svg" alt=""> <span><?php echo $organiser->emailAddress; ?></span><br>
                        <img src="<?php echo base_url(); ?>assets/images/phone.svg" alt=""> <span><?php echo $organiser->phoneNo; ?></span><br>
                    </div>
                </div>
            </section>
        </section>

        <section class="location">
            <h2><img src="<?php echo base_url(); ?>assets/images/location.svg" alt="">Location:</h2>
            <p><?php echo $event->address; ?></p>
            <p class="distance">Distance: <span></span>km</p>
            <div id="map" class="map"></div>
            <?php
                if (!$this->session->userdata('organiser')) {
                    if (isset($applied)) { 
                        if ($applied->deletion == 0) {
                            echo "<div class='applied'>$applied->status</div>";
                        } else {
                            echo "<div class='applied'>WITHDRAW</div>";
                        }  
                    } else {
                        echo "<div class='apply'>Apply</div>";
                    }
                } else {
                    if ($this->session->userdata('username') == $organiser->username) {
                        if ($event->status == 'ended') {
                            echo "<div class='ended'>Ended</div>";
                        } else {
                            echo "<div class='edit'>Edit</div>";
                        }  
                    }
                }
            ?>
        </section>
    
        <section class="comment">
            <h2><?php if (isset($comments)) {
                    echo count($comments); 
                } else {
                    echo "0";
                }
            ?> Comment <span class="error hide">*Comments can't be null!</span></h2>

            <form id="commentContentForm" action="">
                <input type="text" name="commentContent" id="commentContent" placeholder="Write your comment here.">
                <input type="hidden" name="replyContent" id="replyContent">
            </form>
            <div class="commentTextContainer hide">
                <textarea id="commentTextArea"></textarea>
                <div class="comment">COMMENT</div>
                <div class="cancel">CANCEL</div>
            </div>

            <div class='replyTextAreaContainer hide'>
                <textarea id='replyTextArea'></textarea>
                <div class='commentButton'>COMMENT</div>
                <div class='cancelButton'>CANCEL</div>
            </div>

            <?php
                if (isset($comments)) {
                    $curUsername = $this->session->userdata('username');
                    foreach ($comments as $key => $comment) {
                        if (strpos($comment->whoUpvote, $curUsername) !== false) {
                            $liked = "liked";
                        } else {
                            $liked = "";
                        }

                        if ($organiser->username == $comment->username) {
                            $avatarTitle = "<div class='avatarTitle'>Organiser</div>";
                        } else {
                            $avatarTitle = "";
                        }

                        echo "<div class='data' commentid='$comment->commentID'>
                                <div class='user_info'>
                                    <div class='name'>
                                        <img class='avatar' src='" . base_url() . "/assets/images/" . json_decode($comment->descriptionImageLink)->avatar . "'>
                                        <span>$comment->username" . "$avatarTitle</span>
                                    </div>
                
                                    <div class='time'>$comment->commentDate</div>
                                </div>
                
                                <div class='content'>
                                    $comment->contentText
                                </div>

                                <div class='interact'>
                                    <span class='replyButton'>Reply</span>
                                </div>
                                <div class='dianzan'>
                                    <svg class='icon $liked' aria-hidden='true'>
                                        <use xlink:href='#icon-dianzan'></use>
                                    </svg>
                                    <div class='amount'>$comment->upvote</div>
                                </div>
                            </div>";

                        if (isset($replies)) {
                            echo "<div class='commentReplies hide'>";
                            foreach ($replies as $key => $reply) {
                                if ($reply->cid == $comment->commentID) {
                                    if ($organiser->username == $reply->username) {
                                        $avatarTitle = "<div class='avatarTitle'>Organiser</div>";
                                    } else {
                                        $avatarTitle = "";
                                    }
    
                                    echo "<div class='data reply' cid='$reply->cid' replyid='$reply->replyID'>
                                            <div class='user_info'>
                                                <div class='name'>
                                                    <img class='avatar' src='" . base_url() . "/assets/images/" . json_decode($reply->descriptionImageLink)->avatar . "'>
                                                    <span>$reply->username" . "$avatarTitle</span>
                                                </div>
                            
                                                <div class='time'>$reply->replyDate</div>
                                            </div>
                            
                                            <div class='content'>
                                                $reply->replyContent
                                            </div>
                                        </div>";
                                }
                            }

                            echo "</div>";
                        }
                    }
                }
            ?>
        </section>

        <div class="applyContainer hide">
            <div class="applyWindow">
                <img class="cancel" src="<?php echo base_url(); ?>/assets/images/cancel.svg" alt="">
                <div class="applyInfo"></div>
                <!-- <div class="top">
                    <h3>Send Your Application</h3>
                    <img src="<?php echo base_url(); ?>/assets/images/preview_resume.jpg" alt="">
                    <div class="operation"><span class="preview">Preview</span> | <span class="edit">Edit</span></div>
                </div>
                <div class="bottom">
                    <p>*Update notification will send through email</p>
                    <div class="send">CONFIRM & SEND</div>
                </div> -->
                <!-- <div class="top">
                    <h3>No Resume Yet</h3>
                    <div class="addIcon">
                        <svg class="icon" aria-hidden="true">
                            <use xlink:href="#icon-jiahao1"></use>
                        </svg>
                    </div>
                    <div class="operation"><span class="preview">Fill out Your Resume First</span></div>
                </div>
                <div class="bottom">
                    <p>*Update notification will send through email</p>
                    <div class="noSend">CONFIRM & SEND</div>
                </div> -->
            </div>
        </div>
    </main>

    <script src="//at.alicdn.com/t/font_2506080_2mev0uhqlhi.js"></script>
    <script src="https://cdn.tiny.cloud/1/gbtp6vl40eos66xyp6998by5z40su14dpxh8qkbk269e5jam/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        //Initialize global var
        let commentID = 0;
        //Add show applies to those who have
        $("#event .comment .commentReplies").each(function(index) {
            if ($(this).find(".data.reply").length > 0) {
                $(this).prev().find(".interact").append("<div class='showReplies'>Show replies</div>");
            }
        });

        $("#event .comment").on("click", ".data .interact .showReplies", function() {
            if ($(this).text() == "Show replies") {
                $(this).parent().parent().nextAll(".commentReplies").eq(0).removeClass("hide");
                $(this).text("Hide replies");
            } else {
                $(this).parent().parent().nextAll(".commentReplies").eq(0).addClass("hide");
                $(this).text("Show replies");
            }    
        });
        
        //Delelte top line of first comment
        $("#event .comment").find(".data:first").attr("style", "border: none;");
        //Get parameters from url
        function getQueryVariable(variable) {
            let query = window.location.search.substring(1);
            let vars = query.split("&");
            for (let i=0; i<vars.length; i++) {
                    var pair = vars[i].split("=");
                    if(pair[0] == variable) {return pair[1];}
            }
            return false;
        }

        $(".applyContainer .applyWindow").on("click", ".preview", function() {
            window.open(window.location.origin + "/static/resume");
        });

        $(".applyContainer .applyWindow").on("click", ".edit, .addIcon", function() {
            window.open(window.location.origin + "/static/editResume");
        });

        $(".applyContainer .applyWindow").on("click", ".cancel", function() {
            $(".applyContainer").addClass("hide");
        });

        $(".applyContainer .applyWindow").on("click", ".bottom .send", function() {
            $(".applyContainer .applyWindow .bottom .send").html("<div class='spiral'></div>");
            $.ajax({
                type: "post",
                url: window.location.origin + "/static/apply",
                data: {"eventID":getQueryVariable("eventID")},
                success: function(response){
                    if (response) {
                        let obj = JSON.parse(response);
                        if (obj.success) {
                            $(".applyContainer .applyWindow .applyInfo").html("<h4><strong class='success'>Congratulations!</strong> You have successfully sent the application.</h4>\
                            <p>Any further update notification will send to you through eamil.</p>");
                            setTimeout(() => {
                                window.location.reload();
                            }, 3000);
                        } else {
                            $(".applyContainer .applyWindow .applyInfo").html("<h4><strong class='warn'>Warning:</strong> You have already applied this event.</h4>\
                            <p>Please don't apply twice.</p>");
                        }
                    }
                },
                error: function (reason){
                    console.log(reason);
                }
            });
        });

        $("#event .interact, #event .location").on("click", ".apply", function() {

            $.ajax({
                type: "post",
                url: window.location.origin + "/static/apply/hasResume",
                data: "",
                success: function(response){
                    if (response) {
                        //Has resume
                        $(".applyContainer .applyWindow .applyInfo").html('\
                        <div class="top">\
                            <h3>Send Your Application</h3>\
                            <img src="<?php echo base_url(); ?>/assets/images/preview_resume.jpg" alt="">\
                            <div class="operation"><span class="preview">Preview</span> | <span class="edit">Edit</span></div>\
                        </div>\
                        <div class="bottom">\
                            <p>*Update notification will send through email</p>\
                            <div class="send">CONFIRM & SEND</div>\
                        </div>');
                    } else {
                        $(".applyContainer .applyWindow .applyInfo").html('\
                        <div class="top">\
                            <h3>No Resume Yet</h3>\
                            <div class="addIcon">\
                                <svg class="icon" aria-hidden="true">\
                                    <use xlink:href="#icon-jiahao1"></use>\
                                </svg>\
                            </div>\
                            <div class="operation"><span class="preview">Fill out Your Resume First</span></div>\
                        </div>\
                        <div class="bottom">\
                            <p>*Update notification will send through email</p>\
                            <div class="noSend">CONFIRM & SEND</div>\
                        </div>');
                    }

                    $(".applyContainer").removeClass("hide");
                },
                error: function (reason){
                    console.log(reason);
                }
            });
        });

        let coordinates = <?php echo json_encode($event->coordinates); ?>;
        let address = <?php echo json_encode($event->address); ?>;
        let latitude = coordinates.replace(/\s+/g, "").split(",")[0];
        let longitude = coordinates.replace(/\s+/g, "").split(",")[1];
        let myMap = L.map("map").setView([latitude, longitude], 13); 
        $.ajax({
            type: "get",
            async: true,
            url: "https://apis.map.qq.com/ws/location/v1/ip",
            data: {"key":"BM7BZ-EWU6U-7DAVV-2FH3H-A2ZH5-Q7BV6","output":"jsonp"},
            dataType: "jsonp",
            success: function(result){
                let location = result.result.location;
                let curLatitude = location.lat;
                let curLongitude = location.lng;
                let distance = Math.round(L.latLng(latitude,longitude).distanceTo(L.latLng(curLatitude,curLongitude)) / 1000);
                $(".location .distance span").html(distance);
            },
            error: function (XMLHttpRequest,textStatus,errorThrown){
                console.log(JSON.stringify(XMLHttpRequest));
            }
        });

        L.tileLayer("https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoieGlhb21vYXMiLCJhIjoiY2t0bDl6eTRrMXRxZDJycnQ0b2xrZ3RtMiJ9.GTO8e7RFE8rrkDE4490wIQ", 
        { 
            attribution: 'The location of this event', 
            maxZoom: 18, 
            id: 'mapbox/streets-v11', 
            tileSize: 512, 
            zoomOffset: -1, 
            accessToken: ''
        }).addTo(myMap); 

        let marker = L.marker([latitude, longitude]).addTo(myMap);   
        let popupText = "<h3>"  + address + "</h3><br>" + "Latitude: " + latitude + "<br>" + "Longtitude: " + longitude;
        marker.bindPopup(popupText).openPopup();

        /*Textarea*/
        tinymce.init({
            selector: '#commentTextArea',
            toolbar: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            toolbar_mode: 'floating',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            remove_linebreaks: true,
            width: '100%',
            height: 300,
            setup : function(ed) {
                ed.on('blur', checkCommentTextArea);
            },
        }); 

        function checkCommentTextArea() {
            let text = "";
            if (tinymce.get("commentTextArea")) {
                text = tinymce.get("commentTextArea").getContent();
            } else {
                text = $("#commentTextArea").html();
            }

            if (text) {
                $("#event .comment h2 .error").addClass("hide");
                text = text.replace(/\"/g, '\\\"').replace(/\n/g, '').replace(/\'/g, '\\\'');
                $("#commentContent").val(text);
                return true;
            } else {
                $("#event .comment h2 .error").removeClass("hide");
                return false;
            }
        }

        $("#commentContent").on("focus", function() {
            $("#commentContentForm").toggleClass("hide");
            $("#event .commentTextContainer").toggleClass("hide");
        });

        $("#event .commentTextContainer .cancel").on("click", function() {
            $("#commentContentForm").toggleClass("hide");
            $("#event .commentTextContainer").toggleClass("hide");
        });

        $("#event .commentTextContainer .comment").on("click", function() {
            if (checkCommentTextArea()) {
                let contentText = $("#commentContent").val();
                let eventID = getQueryVariable("eventID");
                let month = (new Date().getMonth() + 1 + '').padStart(2, '0');
                let day = (new Date().getDate() + '').padStart(2, '0');
                let commentDate = new Date().getFullYear() + "-" + month + "-" + day;
                
                $.ajax({
                    url: window.location.origin + "/static/comment/insert",
                    method: "POST",
                    data: {contentText, eventID, commentDate},
                    type: "jsonp",
                    success: function(response) {
                        window.location.reload();
                        let pos = $("#event section.comment").offset().top;
                        $("html,body").animate({scrollTop: pos}, 1000);
                    },
                    error: function(reason) {
                        console.log(reason);
                    }
                });
            }
        });

        //Get parameters from url
        function getQueryVariable(variable) {
            let query = window.location.search.substring(1);
            let vars = query.split("&");
            for (let i=0; i<vars.length; i++) {
                    var pair = vars[i].split("=");
                    if(pair[0] == variable) {return pair[1];}
            }
            return false;
        }

        let likeChanged = true;
        $("#event .comment").on("click", ".data .dianzan svg", function() {
            if (likeChanged === false) return;
            likeChanged = false;
            let amount = parseInt($(this).parent().find(".amount").html());
            let commentID = $(this).parent().parent().attr("commentid");
            let that = $(this);
            if (!$(this).hasClass("liked")) {
                //add 1 like
                $.ajax({
                    url: window.location.origin + "/static/comment/addLike",
                    method: "POST",
                    data: {commentID},
                    type: "jsonp",
                    success: function(response) {
                        that.toggleClass("liked");
                        amount++;
                        that.parent().find(".amount").html(amount);
                        likeChanged = true;
                    },
                    error: function(reason) {
                        console.log(reason);
                    }
                });
            } else {
                //remove 1 like
                $.ajax({
                    url: window.location.origin + "/static/comment/subtractLike",
                    method: "POST",
                    data: {commentID},
                    type: "jsonp",
                    success: function(response) {console.log(response);
                        that.toggleClass("liked");
                        amount--;
                        that.parent().find(".amount").html(amount);
                        likeChanged = true;
                    },
                    error: function(reason) {
                        console.log(reason);
                    }
                });
            }
        });

        /*Reply part*/

        tinymce.init({
            selector: '#replyTextArea',
            toolbar: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            toolbar_mode: 'floating',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            remove_linebreaks: true,
            width: '100%',
            height: 300,
            setup : function(ed) {
                ed.on('blur', checkReplyTextArea);
            },
        }); 

        function checkReplyTextArea() {
            let text = "";
            if (tinymce.get("replyTextArea")) {
                text = tinymce.get("replyTextArea").getContent();
            } else {
                text = $("#replyTextArea").html();
            }

            if (text) {
                $("#event .comment h2 .error").addClass("hide");
                text = text.replace(/\"/g, '\\\"').replace(/\n/g, '').replace(/\'/g, '\\\'');
                $("#replyContent").val(text);
                return true;
            } else {
                $("#event .comment h2 .error").removeClass("hide");
                return false;
            }
        }
            
        $("#event .comment").on("click", ".replyButton", function() {
            commentID = $(this).parent().parent().attr("commentid");
            $("#event .comment").find(".data[commentid='" + commentID + "']").after($(".replyTextAreaContainer"));
            tinymce.get("replyTextArea").remove();
            tinymce.execCommand("mceAddEditor", false, "replyTextArea");
            $("#replyTextArea").closest('.mce-tinymce.mce-container').show();
            $(".replyTextAreaContainer").removeClass("hide");
        });

        $("#event").on("click", ".replyTextAreaContainer div.cancelButton", function() {
            $(".replyTextAreaContainer").addClass("hide");
        });

        $("#event").on("click", ".replyTextAreaContainer div.commentButton", function() {
            if (checkReplyTextArea()) {
                let replyContent = $("#replyContent").val();
                let month = (new Date().getMonth() + 1 + '').padStart(2, '0');
                let day = (new Date().getDate() + '').padStart(2, '0');
                let replyDate = new Date().getFullYear() + "-" + month + "-" + day;


                $.ajax({
                    url: window.location.origin + "/static/comment/insertReply",
                    method: "POST",
                    data: {replyContent, commentID, replyDate},
                    type: "jsonp",
                    success: function(response) {
                        window.location.reload();
                        let pos = $("#event section.comment").offset().top;
                        $("html,body").animate({scrollTop: pos}, 1000);
                    },
                    error: function(reason) {
                        console.log(reason);
                    }
                });
            }
        });
    </script>
</body>
</html>