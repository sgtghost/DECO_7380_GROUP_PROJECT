    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/leaflet.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/cropper.css">
    <title>Edit Event</title>
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

    <div class="mode" id="editEventMode">Edit Mode <div class="save">SAVE CHANGE</div></div>
    <main id="event" class="edit">
        <form id="editEventForm">
            <div class="topContainer">
                <section class="detail">
                    <section class="intro">
                        <h2>Event Name: <input type="text" name="editEventName" id="editEventName" value="<?php 
                            if (isset($event)) {
                                echo json_decode($event->descriptionText)->name; 
                            }
                        ?>"><span class="error"> *Not null!</span></h2><br>

                        <section class="info">
                            <section class="title">
                                <p>WHEN</p>
                                <P>WHERE</P>
                                <P>GOOD FOR</P>
                                <?php 
                                    if (!isset($event)) {
                                        echo "<P>Maximum Apply</P>
                                              <P>Publish at once?</P>";
                                    }
                                ?>
                            </section>

                            <section class="data">
                                <input type="text" name="editEventDate" id="editEventDate" placeholder="2021-xx-xx" value="<?php 
                                    if (isset($event)) {
                                        echo $event->date; 
                                    }
                                ?>"><span class="error"> *Not Valid!(Must be like xxxx-xx-xx)</span><br>
                                <input type="text" name="editEventAddress" id="editEventAddress" value="<?php 
                                    if (isset($event)) {
                                        echo $event->address; 
                                    }
                                ?>"><span class="error"> *Not null!</span><br>
                                <input type="text" name="editEventPersonas" id="editEventPersonas" value="<?php 
                                    if (isset($event)) {
                                        echo json_decode($event->requirement)->personas;
                                    }
                                ?>"><span class="error"> *Not null!</span><br>
                                <?php 
                                    if (!isset($event)) {
                                        echo "<input type='text' name='eventSlot' id='eventSlot'><span class='error'> *Must be a number!</span><br>
                                              <select name='eventStatus' id='eventStatus'>
                                              <option selected>Private</option>
                                              <option>Public</option>
                                              </select><br>";
                                    }
                                ?>
                            </section>
                        </section>

                        <section class="interact">
                            <div class="keywords">
                                <span class="chooseAreaButton">CHOOSE AREA</span><span class='error'> *Must choose</span>
                                <?php
                                    $areasValue = "[";
                                    if (isset($event)) {
                                        $areas = json_decode($event->requirement)->tags;
                                        foreach ($areas as $key => $value) {
                                            if ($key == count($areas) - 1) {
                                                $areasValue = $areasValue . "\"" . $value. "\"";
                                            } else {
                                                $areasValue = $areasValue . "\"" . $value . "\", ";
                                            }
                                            echo "<span class='keyword no'>$value</span>";
                                        }
                                    }
                                    $areasValue .= "]";
                                    if ($areasValue == "[]") {
                                        $areasValue = "";
                                    }
                                ?>
                            </div>
                            

                            <div class="chooseArea hide">
                                <div class="areaChosenWindow">
                                    <img class="cancel" src="<?php echo base_url(); ?>/assets/images/cancel.svg" alt="">
                                    <h3>Choose Areas</h3>
                                    <p class='intro'>Select 1-5 areas that you’re interested in </p>
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
                                    <div class="confirm">CONFIRM</div>
                                </div>
                            </div>
                        </section>
                    </section>

                    <div class="eventAvatarContainer">
                        <img id="eventAvatar" src="<?php 
                            if (isset($event)) {
                                echo base_url() . 'assets/images/' . json_decode($event->descriptionImageLink)->avatar; 
                            } else {
                                echo base_url() . 'assets/images/default_volunteer.jpg'; 
                            }
                        ?>" alt="">   
                    </div> 
                </section>
                
                <div class="previewContainer">
                    <div>
                        <div id="chooseEventAvatarButton">Choose Image</div>
                        <input type="file" style="display:none;" name="chooseEventAvatar" id="chooseEventAvatar"/>   
                    </div>
                    
                    <div class="preview">
                        <span>Preview: </span>
                        <img class="previewImage" src="<?php 
                            if (isset($event)) {
                                echo base_url() . 'assets/images/' . json_decode($event->descriptionImageLink)->avatar; 
                            } else {
                                echo base_url() . 'assets/images/test_photo_1_1024x1024.jpg'; 
                            }
                        ?>" alt="A cropped avatar">
                    </div>
                </div> 
            </div>
            

            <section class="desc">
                <section class="text edit">
                    <h3>Descriptions<span class="error"> *Not null<span></h3>
                    <p><textarea id="editEventDesc"><?php 
                        if (isset($event)) {
                            echo json_decode($event->descriptionText)->description;
                        }
                    ?></textarea></p>
                    <h3>Skills and Requirements</h3>
                    <div class="requirements">
                        <input type="checkbox" name="skillOption" class="skillOption" value="Teaching / Instruction" <?php
                            if (isset($event) && json_decode($event->requirement)->skills[0] == "1") {
                                echo "checked";
                            }
                        ?>>Teaching / Instruction<br>
                        <input type="checkbox" name="skillOption" class="skillOption" value="English as a Secondary Language (ESL)" <?php
                            if (isset($event) && json_decode($event->requirement)->skills[1] == "1") {
                                echo "checked";
                            }
                        ?>>English as a Secondary Language (ESL)<br>
                        <input type="checkbox" name="skillOption" class="skillOption" value="Tutoring Experience" <?php
                            if (isset($event) && json_decode($event->requirement)->skills[2] == "1") {
                                echo "checked";
                            }
                        ?>>Tutoring Experience<br>
                        <input type="checkbox" name="skillOption" class="skillOption" value="Must be at least 18" <?php
                            if (isset($event) && json_decode($event->requirement)->skills[3] == "1") {
                                echo "checked";
                            }
                        ?>>Must be at least 18<br>
                        <input type="checkbox" name="skillOption" class="skillOption" value="At least 1 month with the commitment" <?php
                            if (isset($event) && json_decode($event->requirement)->skills[4] == "1") {
                                echo "checked";
                            }
                        ?>>At least 1 month with the commitment<br>                     
                    </div>
                </section>

            </section>

                            
            <section class="location edit">
                <h2><img src="<?php echo base_url(); ?>assets/images/location.svg" alt="">Location: <span> *click on the map to choose coordinates</span></h2>
                <p>Latitude: <input type="text" name="eventLatitude" id="eventLatitude" value="<?php 
                    if (isset($event)) {
                        $latitude = explode(", ",$event->coordinates)[0]; 
                        echo $latitude;
                    }
                ?>"><span class="error"> *Must be a number!</span></p>
                <p>Longtitude: <input type="text" name="eventLongtitude" id="eventLongtitude" value="<?php 
                    if (isset($event)) {
                        $longtitude = explode(", ",$event->coordinates)[1]; 
                        echo $longtitude;
                    }
                ?>"><span class="error"> *Must be a number!</span></p>
                <div id="editEventMap" class="map"></div>
            </section>
            <input type="hidden" name="eventAreas" id="eventAreas" value='<?php echo $areasValue; ?>'>
            <input type="hidden" name="editEventAvatar" id="editEventAvatar">
            <input type="hidden" name="editEventDescVal" id="editEventDescVal">
            <input type="hidden" name="eventSkills" id="eventSkills">
            <input type="hidden" name="eventID" id="eventID" value="<?php
                if (isset($event)) {
                    echo $event->eventID;
                }
            ?>">
        </form>

    </main>

    <script src="<?php echo base_url(); ?>assets/js/leaflet.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/cropper.js"></script>
    <script src="https://cdn.tiny.cloud/1/gbtp6vl40eos66xyp6998by5z40su14dpxh8qkbk269e5jam/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        let latitude = '<?php 
            if (isset($latitude)) {
                echo $latitude;
            } else {
                echo "";
            }
        ?>'
        let longtitude = '<?php 
            if (isset($longtitude)) {
                echo $longtitude;
            } else {
                echo "";
            }
        ?>'

        /*Click interactions*/
        $("#event.edit").on("click", ".chooseAreaButton", function() {
            if ($("#event.edit .interact .keywords .keyword").length > 0) {
                $("#event.edit .interact .keywords .keyword").each(function(index) {
                    let value = $(this).html();
                    $("#event.edit .areaChosenWindow .areaOption").each(function(index1) {
                        if ($(this).html() == value) {
                            $(this).addClass("chosen");
                        }
                    });
                });
            }
            $("#event.edit .chooseArea").removeClass("hide");
        });

        $("#event.edit .chooseArea .cancel").on("click", () => {
            $("#event.edit .chooseArea").addClass("hide");
        });

        $("#event.edit .areaChosenWindow .areaOption").on("click", function() {
            $(this).toggleClass("chosen");
        });

        function getImgSize(str) {
            let strLength = str.length;
            let fileLength = parseInt(strLength - (strLength / 8) * 2);
            let size = (fileLength / 1024).toFixed(2);

            return parseInt(size);
        }

        $("#event.edit .chooseArea .confirm").on("click", function() {
            //Choose at least one
            if ($("#event.edit .areaChosenWindow .areaOption.chosen").length < 1 || $("#event.edit .areaChosenWindow .areaOption.chosen").length > 5) {
                alert("You must choose 1 to 5 options!");
                return;
            }

            let html = "<span class='chooseAreaButton'>CHOOSE AREA</span>";
            let arr = [];
            $("#event.edit .areaChosenWindow .areaOption").each(function(index) {
                if ($(this).hasClass('chosen')) {
                    html = html + "<span class='keyword no'>" + $(this).html() + "</span>";
                    arr.push('\"' + $(this).html() + '\"');
                }
            });
            arr = "[" + arr.join(",") + "]";
            $("#eventAreas").val(arr);
            $("#event.edit .interact .keywords").html(html);
            $("#event.edit .chooseArea").addClass("hide");
        });

        //Initialize the cropper
        $("#eventAvatar").cropper({
            aspectRatio: 1/1,
            viewMode:2,
            autoCropArea: 1,
            zoomable : false, 
            mouseWheelZoom : false, 
            touchDragZoom : false, 
            crop: function (e) {
                previewImage();
            }
        });

        //Choose local file
        function previewFile() {
            let file = document.getElementById('chooseEventAvatar').files[0];
            let reader = new FileReader();

            reader.addEventListener("load", function () {
                $("#eventAvatar").cropper('destroy').attr('src', reader.result).cropper({
                    aspectRatio: 1/1,
                    viewMode:2,
                    autoCropArea: 1,
                    zoomable : false, 
                    mouseWheelZoom : false,   
                    touchDragZoom : false,
                    crop: function (e) {
                        previewImage();
                    }
                });
                //preview the cropped image immediately
            }, false);

            //Justify the filename
            if (file) {
                reader.readAsDataURL(file);
            }
        }
        //Listen the choosing file event
        $("#chooseEventAvatar").change(previewFile);

        let cas;
        let base64url;
        //preview image function
        function previewImage() {
            cas = $('#eventAvatar').cropper('getCroppedCanvas');
            base64url = cas.toDataURL('image/jpeg');
            if (getImgSize(base64url) > 300) {
                //Size > 300kb, resize image
                compressImage(base64url);
            } else {
                $("#event.edit .preview .previewImage").attr("src", base64url);
                $("#editEventAvatar").val(base64url);
            }
        }

        function compressImage(url, MAX_WIDTH=450, MAX_HEIGHT=450) {
            let img = new Image();
            img.src = url;

            img.onload = function() {
                let canvas = document.createElement('canvas');
                let width = img.width;
                let height = img.height;

                if (width > height) {
                    if (width > MAX_WIDTH) {
                        height *= MAX_WIDTH / width;
                        width = MAX_WIDTH;
                    }
                } else {
                    if (height > MAX_HEIGHT) {
                        width *= MAX_HEIGHT / height;
                        height = MAX_HEIGHT;
                    }
                }
                canvas.width = width;
                canvas.height = height;
                let ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);

                let resizeUrl = canvas.toDataURL('image/jpeg');
                $("#event.edit .preview .previewImage").attr("src", resizeUrl);
                $("#editEventAvatar").val(resizeUrl);
            }
        }

        /*Textarea*/
        tinymce.init({
            selector: '#editEventDesc',           
            toolbar: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            toolbar_mode: 'floating',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            remove_linebreaks: true,
            width: '60%',
            height: 300,
            setup : function(ed) {
                ed.on('blur', checkTextArea);
            },
        });

        function checkTextArea() {
            let text = "";
            if (tinymce.get("editEventDesc")) {
                text = tinymce.get("editEventDesc").getContent();
            } else {
                text = $("#editEventDesc").html();
            }

            if (text) {
                $("#event.edit .desc .error").removeClass("show");
                text = text.replace(/\"/g, '\\"').replace(/\n/g, '');
                $("#editEventDescVal").val(text);
                return true;
            } else {
                $("#event.edit .desc .error").addClass("show");

                return false;
            }
        }
        let myMap = L.map("editEventMap").setView([0, 0], 1);
        L.tileLayer("https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoieGlhb21vYXMiLCJhIjoiY2t0bDl6eTRrMXRxZDJycnQ0b2xrZ3RtMiJ9.GTO8e7RFE8rrkDE4490wIQ", 
        { 
            attribution: 'Choose the location of this event', 
            maxZoom: 18, 
            minZoom: 1,
            id: 'mapbox/streets-v11', 
            tileSize: 512, 
            zoomOffset: -1, 
            accessToken: ''
        }).addTo(myMap);
        //Can't drag when zoom be smallest
        let bounds = L.latLngBounds([[-90, -180], [90, 180]]);
            myMap.setMaxBounds(bounds);
            myMap.on('drag', function() {
            myMap.panInsideBounds(bounds, { animate: false });
        });
        //Add marker when click
        let newMarker;
        if (longtitude) {
            newMarker = L.marker([latitude, longtitude]).addTo(myMap);
            newMarker.bindPopup("<p>Latitude: " + latitude + "</p><p>Longtitude: " + longtitude + "</p>").openPopup();
            myMap.setView([latitude, longtitude], 18);
        }
        myMap.on('click', function(e){
            $("#eventLatitude").prop("value", e.latlng.lat);
            $("#eventLongtitude").prop("value", e.latlng.lng);
            if (newMarker) {
                myMap.removeLayer(newMarker)
            }
            newMarker = new L.marker(e.latlng).addTo(myMap);
            newMarker.bindPopup("<p>Coordinates: " + e.latlng + "</p><br/>").openPopup();
        });

        //Fix save button 
        let fixHeight = $('#event').offset().top;

	    $(document).on('scroll',function(){
            let	curHeight = $(document).scrollTop();
		    if(fixHeight-10 <= curHeight){
			    $("#editEventMode").css({'position':'fixed','top':'0px', 'left': '0px'});
			}else{
				$("#editEventMode").css({'position':'relative'});
			}
		});

        //Choose Image
        $("#chooseEventAvatarButton").on("click", function() {
            $("#chooseEventAvatar").click();
        })
    </script>
</body>
</html>