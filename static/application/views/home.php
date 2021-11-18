    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/leaflet.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css">
    <title>Home</title>
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

    <section id="topInfo" class="<?php
        if ($this->session->userdata('logged_in')) {
            echo "login";
        }
    ?>">
        <h2 class="title">Today's Top Pick<div class="header_line"></div></h2>
        <?php
            if ($this->session->userdata('logged_in')) {
                echo "<h2 class='title my_event'>My Events<div class='header_line'></div></h2>";
            }
        ?>
        
        <div class="info_container">
            <div class="first" style="background-image: url('<?php echo base_url() . 'assets/images/' . json_decode($topEvents[0]->descriptionImageLink)->avatar?>');">
                <div class="eventInfo">
                    <h3><?php echo json_decode($topEvents[0]->descriptionText)->name; ?></h3>
                    <p>Project Time: <?php echo $topEvents[0]->date; ?></p>
                    <p>Address: <?php echo $topEvents[0]->address; ?></p>
                </div>
                <div class="topEventID"><?php echo $topEvents[0]->eventID; ?></div>
            </div>

            <div class="second" style="background-image: url('<?php echo base_url() . 'assets/images/' . json_decode($topEvents[1]->descriptionImageLink)->avatar?>');">
                <div class="eventInfo">
                    <h3><?php echo json_decode($topEvents[1]->descriptionText)->name; ?></h3>
                    <p>Project Time: <?php echo $topEvents[1]->date; ?></p>
                    <p>Address: <?php echo $topEvents[1]->address; ?></p>
                </div>
                <div class="topEventID"><?php echo $topEvents[1]->eventID; ?></div>
            </div>

            <div class="third" style="background-image: url('<?php echo base_url() . 'assets/images/' . json_decode($topEvents[2]->descriptionImageLink)->avatar?>');">
                <div class="eventInfo">
                    <h3><?php echo json_decode($topEvents[2]->descriptionText)->name; ?></h3>
                    <p>Project Time: <?php echo $topEvents[2]->date; ?></p>
                    <p>Address: <?php echo $topEvents[2]->address; ?></p>
                </div>
                <div class="topEventID"><?php echo $topEvents[2]->eventID; ?></div>
            </div>

            <div class="fourth" style="background-image: url('<?php echo base_url() . 'assets/images/' . json_decode($topEvents[3]->descriptionImageLink)->avatar?>');">
                <div class="eventInfo">
                    <h3><?php echo json_decode($topEvents[3]->descriptionText)->name; ?></h3>
                    <p>Project Time: <?php echo $topEvents[3]->date; ?></p>
                    <p>Address: <?php echo $topEvents[3]->address; ?></p>
                </div>
                <div class="topEventID"><?php echo $topEvents[3]->eventID; ?></div>
            </div>
        </div>

        <?php
            if ($this->session->userdata('logged_in')) {
                echo "<div id='homeMyEvents' class='map'></div>";
            }
        ?>
    </section>

    <section id="search">
        <section class="bar">
            <form id="searchForm" action="">
                <input type="text" name="searchVal" id="searchVal" placeholder="Input here">
                
                <div class='areaContainer'>
                    <div class='fakeArrow'></div>
                    <select class="areas" name="areaVal">
                        <option value='' disabled selected style='display:none;'>Case Areas</option>
                        <option></option>
                        <option>Animals</option>
                        <option>Art&Culture</option>
                        <option>Children&Youth</option>
                        <option>Computer&Technology</option>
                        <option>Education</option>
                        <option>Environment</option>
                        <option>Health&Medicine</option>
                        <option>Seniors</option>
                        <option>Sports&Recreation</option>
                        <option>Community</option>
                        <option>Museum</option>
                    </select>
                </div>
                <img src="<?php echo base_url(); ?>assets/images/search.png" alt="">
            </form>

            <!-- <div class="keywords">
                <span class="keyword">keyword</span>
                <span class="keyword">keyword</span>
                <span class="keyword">keyword</span>
                <span class="keyword">keyword</span>
                <span class="keyword">keyword</span>
            </div> -->
        </section>

        <section class="result">
            <h2>RESULTS</h2>
            <hr>
            <ul class="results">
                <?php 
                $size = 0;
                if (!empty($events)) {
                    $size = count($events);

                    for ($i=0; $i < ($size<6 ? $size : 5); $i++) {
                        $value = $events[$i];

                        if ($value->eventID != "1" && $value->applied != $value->slot) {
                            // $type = preg_replace('/\"\s/', "' ", $value->descriptionText);
                            $type = json_decode($value->descriptionText)->name;
                            $imgUrl = json_decode($value->descriptionImageLink)->avatar;
                            $areas = json_decode($value->requirement)->tags;
                            $keywords = "";
                            foreach ($areas as $key => $area) {
                                $keywords .= "<span class='keyword'>$area</span>";
                            }
                            echo 
                            '<li class="detail"> 
                                <div class="intro">' . 
                                    '<h3 class="view">' . $type . '</h3><br>' . 
                                    '<p>Project time: <span class="time">' . $value->date . '</span></p>' . 
                                    '<p>' . $value->address . '</p>' .

                                    '<div class="keywords">' . $keywords . '</div>' . 
                                '</div>' . 

                                '<div class="remainInfo">
                                    <div class="info">' .
                                        '<h3>' . $value->organiserName . '</h3><br>' .
                                        '<p><strong>' . $value->slot . '</strong> Needed</p><br>' .
                                        '<p><strong>' . $value->applied . '</strong> Applied</p><br>' .
                                    '</div>' . 
                                    '<img class="view" src="' . base_url() . 'assets/images/' . $imgUrl . '" alt="" >' .
                                '</div>' . 
                                '<div class="eventID">' . $value->eventID . '</div>' .                         
                            '</li>';
                        }
                        
                    }
                }

                ?>
            </ul>
            <?php 
                if ($size < 6) {
                    echo '<div class="load_more hidden">Load more</div>';
                } else {
                    echo '<div class="load_more">Load more</div>';
                }
            ?>
            <div class="loading"></div>
        </section>
    </section>

    <section id="login" class="hide">
        <div class="container">
            <img class="cancel" src="<?php echo base_url(); ?>/assets/images/cancel.svg" alt="">
            <div class="windows">
                <div class="login_page">
                    <h3>Welcome Back to <span>VOLUNTEERING AND YOU</span></h3>
                    <div class="content">
                        <p>New to our site? <b>Login to see more</b></p>
                        <p>Take a Try and <strong>Create account </strong></p>
                        <form id="loginForm">
                            <p class="error">*Wrong username or password</p>
                            <label for="loginUsername">Username</label><span class="error"> *Not null!</span><br>
                            <input type="text" name="loginUsername" id="loginUsername">
                            <label for="loginPassword">Password<img src="<?php echo base_url(); ?>/assets/images/trailing.svg" alt=""></label><span class="error"> *Not null!</span><br>
                            <input type="password" name="loginPassword" id="loginPassword">
                            <input type="checkbox" name="remember" id="remember"><label for="remember">Remember me</label>
                            <div class="submit">LOG IN ></div>
                        </form>
                    </div>
                </div>
                <div class="role_page">
                    <h3>Welcome Back to <span>VOLUNTEERING AND YOU</span></h3>
                    <div class="content">
                        <p>Already have an account? <strong>Log In</strong></p>
                        <div class="role">PERSONAL</div>
                        <div class="role">ORGANIZATION</div>
                    </div>
                </div>
                <div class="register_page">
                    <div class="content">
                        <p>Already have an account? <strong>Log In</strong></p>
                        <form id="registerForm">
                            <p class="error">*Duplicate username or email</p>
                            <label for="registerName">Name</label><span class="error"> *Invalid username!</span><br>
                            <input type="text" name="registerName" id="registerName">
                            <label for="registerEmail">Email</label><span class="error"> *Invalid email!</span><br>
                            <input type="text" name="registerEmail" id="registerEmail">
                            <label for="registerPassword">Password<img src="<?php echo base_url(); ?>/assets/images/trailing.svg" alt=""></label><span class="error"> *Length between 5 and 18!</span><br>
                            <input type="password" name="registerPassword" id="registerPassword">
                            <input type="text" id="userType" name="userType" value="">
                            <div class="submit">GO VOLUNTEERING ></div>
                        </form>
                        <div class="back">< BACK</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <script src="<?php echo base_url(); ?>assets/js/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster-src.js"></script>
    <script>
        $("#searchForm .areas").hover(function() {
            $("#searchForm .fakeArrow").css("border-color", "black transparent transparent transparent");
        }, function() {
            $("#searchForm .fakeArrow").css("border-color", "#979797 transparent transparent transparent");
        });

        //Direct to detail
        $('#search ul.results').on('click', 'h3.view, img.view', function(event) {
            if ('<?php echo $this->session->userdata('logged_in');?>'  == "1") {
                let eventID = $(this).parent().siblings(".eventID").text();
                window.open(window.location.origin + "/static/eventDetail?eventID=" + eventID);
            } else {
                $("#login").removeClass("hide");
            }     
        });

        $("#topInfo .info_container .first, #topInfo .info_container .second, #topInfo .info_container .third, #topInfo .info_container .fourth").on("click", function() {
            if ('<?php echo $this->session->userdata('logged_in');?>' == "1") {
                let topEventID = $(this).children(".topEventID").text();
                window.open(window.location.origin + "/static/eventDetail?eventID=" + topEventID);
            } else {
                $("#login").removeClass("hide");
            }  
        });

        if ($("#homeMyEvents").length > 0) {
            let myMap = L.map("homeMyEvents").setView([0, 0], 1);
            L.tileLayer("https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoieGlhb21vYXMiLCJhIjoiY2t0bDl6eTRrMXRxZDJycnQ0b2xrZ3RtMiJ9.GTO8e7RFE8rrkDE4490wIQ", 
            { 
                attribution: 'My events\' positions', 
                maxZoom: 18, 
                minZoom: 1,
                id: 'mapbox/streets-v11', 
                tileSize: 512, 
                zoomOffset: -1, 
            }).addTo(myMap); 
            let bounds = L.latLngBounds([[-90, -180], [90, 180]]);
            myMap.setMaxBounds(bounds);
            myMap.on('drag', function() {
                myMap.panInsideBounds(bounds, { animate: false });
            });

            let hasEvents = "<?php 
                if (isset($myEvents) && $myEvents) {
                    echo 'true';
                } else {
                    echo 'false';
                }
            ?>";
            if (hasEvents == "true") {
                let markers = L.markerClusterGroup();
                let eventsArray = new Array(<?php if (isset($myEvents) && $myEvents) {echo json_encode($myEvents);}?>);
                for (let event of eventsArray[0]) {  
                    let popupText = "<h3>Event Name: " + JSON.parse(event.descriptionText).name + "</h3><br>" + 
                                    "<p>Project Time: " +  event.date + "</p>" + 
                                    "<p>Address: " + event.address + "</p>" + 
                                    "<p>Event Page Link: <a target='_blank' href='https://productlander.uqcloud.net/static/eventDetail?eventID=" + event.eventID + "'> Go \> </a></p>";
                    let icon = new L.Icon({
                        iconUrl: "https://productlander.uqcloud.net/static/assets/images/" + JSON.parse(event.descriptionImageLink).avatar,
                        iconSize: [40, 40],
                        iconAnchor:   [20, 20],
                    });
                    let latitude = event.coordinates.replace(/\s+/g, "").split(",")[0];
                    let longitude = event.coordinates.replace(/\s+/g, "").split(",")[1];
                    let marker = L.marker([latitude, longitude], {icon:icon}); 
                    marker.bindPopup(popupText); 
                    markers.addLayer(marker);
                }
                myMap.addLayer(markers);
            } else {
                let marker = L.marker([0, 0]).addTo(myMap);   
                let popupText = "<h3>You don't have an event yet!</h3>";
                marker.bindPopup(popupText).openPopup(); 
            }
        }

        /*Load more*/
        function getQueryVariable(variable) {
            let query = window.location.search.substring(1);
            let vars = query.split("&");
            for (let i=0; i<vars.length; i++) {
                    var pair = vars[i].split("=");
                    if(pair[0] == variable) {return pair[1];}
            }
            return false;
        }
        //Scroll down auto click load more
        $(document).on('scroll',function(){
            let homeHeight = $(document).height() - $(window).height();
            let	curHeight = $(window).scrollTop();
            if(homeHeight <= curHeight){
                $("#search .load_more").trigger("click");
            }
        });

        let loadCount = 0;
        let lastCall = 0;
        $("#search .load_more").on("click", function() {
            //Throttle
            const now = new Date().getTime()
            if (lastCall && now - lastCall < 1000) return;
            lastCall = now;

            $("#search .loading").addClass("spiral");
            $("#search .load_more").addClass("hidden");
            loadCount++; 
            let searchVal = getQueryVariable("searchVal");
            let areaVal = getQueryVariable("areaVal");
            if (!searchVal) {
                searchVal = "";
            } else {
                searchVal = searchVal.replace("+", " ");
            }

            if (!areaVal) {
                areaVal = "";
            } else {
                areaVal = areaVal.replace("!amp;", "&").replace("%26", "&");
            }

            $.ajax({
                url:window.location.origin + "/static/getEvent",
                method:"POST",
                data:{
                    count:loadCount,
                    searchVal,
                    areaVal
                },
                success:function(response) {
                    if (response != 0 && response !== "[]") {
                        let array = JSON.parse(response);
                        let totalNum = array.length;

                        if (totalNum === 6) {
                            totalNum = 5;
                            $("#search .load_more").removeClass("hidden");
                        } else if (totalNum < 6) {
                            $("section.result").append("<div style='color:lightgray; text-align: center;'>All results are loaded</div>");
                            $("#search .load_more").unbind("click");
                        }

                        for (let i=0; i < totalNum; i++) {
                            let value = array[i];
                            if (value.applied != value.slot) {
                                //Not full
                                let type = JSON.parse(value.descriptionText).name;
                                let imgUrl = JSON.parse(value.descriptionImageLink).avatar;
                                let areas = JSON.parse(value.requirement).tags;
                                let keywords = "";
                                $.each(areas, function(key, area) {
                                    keywords += "<span class='keyword'>" + area + "</span>";
                                });
                            
                                $("#search ul.results").append(
                                    '<li class="detail">' +
                                        '<div class="intro">' + 
                                            '<h3 class="view">' + type + '</h3><br>' + 
                                            '<p>Project time: <span class="time">' + value.date + '</span></p>' + 
                                            '<p>' + value.address + '</p>' +

                                            '<div class="keywords">' + keywords + '</div>' + 
                                        '</div>' + 

                                        '<div class="remainInfo"> \
                                            <div class="info">' +
                                                '<h3>' + value.organiserName + '</h3><br>' +
                                                '<p><strong>' + value.slot + '</strong> Needed</p><br>' +
                                                '<p><strong>' + value.applied + '</strong> Applied</p><br>' +
                                            '</div>' + 
                                            '<img class="view" src=\"' + window.location.origin + '/static/assets/images/' + imgUrl + '\" alt="" >' +
                                        '</div>' +     
                                        '<div class="eventID">' + value.eventID + '</div>' +                     
                                    '</li>'
                                );  
                            }
                        }

                    } else {
                        $("section.result").append("<div style='color:lightgray; text-align: center;'>All results are loaded</div>");
                        $("#search .load_more").unbind("click");
                    }
                    $("#search .loading").removeClass("spiral");
                },
                error: function(response) {
                    console.log(response);
                }
            });
        })
    </script>
</body>
</html>