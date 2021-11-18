<title>My Resume</title>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/cropper.css">
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
    
    <main id="resume">
        <div class="mode">Edit Mode <div class="save">SAVE CHANGE</div></div>

        <form id="editResumeForm">
            <div class="top">
                <h2>My Resume</h2>
                <input type="hidden" name="ownerID" value="<?php echo $resume->id; ?>">
                <input type="hidden" name="isNew" value="<?php 
                    if (isset($resume->ownerID)) {
                        echo "false"; 
                    } else {
                        echo "true";
                    }
                ?>">

                <div class="infoContainer">
                    <div class="basic">
                        <div class="title">
                            <?php if ($resume->organiser) {
                                echo "<p>Organization Name:</p>";
                            } ?> 
                            <p>First Name:</p>
                            <p>Last Name:</p>
                            <p>Phone Number:</p>
                            <!-- <p>Email Address:</p> -->
                            <p>Gender:</p>
                            <p>Age:</p>
                        </div>

                        <div class="data">
                            <?php 
                                if ($resume->organiser) {
                                    //Is an organiser
                                    if (isset($resume->ownerID)) {
                                        //Have resume
                                        $orgNameVal = json_decode($resume->descriptionText)->name;
                                        
                                    } else {
                                        $orgNameVal = "";
                                    }
                                    echo "<label><input type='text' id='orgName' name='orgName' value='$orgNameVal'><span class='error'>*Not Null!<span></label>";   
                                }
                            ?> 
                            <label><input type="text" id="firstName" name="firstName" value="<?php 
                                if (isset($resume->ownerID)) {
                                    echo $resume->firstName; 
                                } else {
                                    echo "";
                                }
                            ?>"><span class='error'>*Not Null!<span></label>
                            <label><input type='text' id='lastName' name='lastName' value="<?php 
                                if (isset($resume->ownerID)) {
                                    echo $resume->lastName; 
                                } else {
                                    echo "";
                                }
                            ?>"><span class='error'>*Not Null!<span></label>                           
                            <label><input type="text" id="phoneNo" name="phoneNo" value="<?php 
                                if (isset($resume->ownerID)) {
                                    echo $resume->phoneNo; 
                                } else {
                                    echo "";
                                }
                            ?>"><span class='error'>*Must be a number!<span></label>
                            <!-- <label><input type="text" id="emailAddress" name="emailAddress" value="<?php echo $resume->emailAddress; ?>"><span class='error'>*Not Valid!<span></label> -->
                            <label><select id="gender" name="gender" value="<?php
                                if (isset($resume->ownerID)) {
                                    echo json_decode($resume->descriptionText)->gender; 
                                } else {
                                    echo "Male";
                                }
                             ?>">
                                <option value="Male" <?php 
                                    if (isset($resume->ownerID) && json_decode($resume->descriptionText)->gender == "Male") {
                                        echo "selected"; 
                                    } else {
                                        echo "";
                                    }
                                ?>>Male</option>
                                <option value="Female"<?php 
                                    if (isset($resume->ownerID) && json_decode($resume->descriptionText)->gender == "Female") {
                                        echo "selected"; 
                                    } else {
                                        echo "";
                                    }
                                ?>>Female</option>
                            </select><span class='error'>*Not Null!<span></label>
                            <label><input type="text" id="age" name="age" value="<?php
                                if (isset($resume->ownerID)) {
                                    echo json_decode($resume->descriptionText)->age; 
                                } else {
                                    echo "";
                                }
                             ?>"><span class='error'>*Must be a number!<span></label>
                        </div>
                    </div>

                    <div class="avatar">
                        <div class="cropper_container">
                            <img id="image" class="crop" src="<?php echo base_url().'assets/images/'.$this->session->userdata('avatar'); ?>" alt="avatar">
                        </div>

                        <div class="chooseFileContainer">
                            <input type="file" style="display:none" name="chooseFile" id="chooseFile"/>
                            <div id="chooseFileButton">Choose Image</div>
                        </div>

                        <div class="preview_container">
                            <div class="get_url">Preview: </div>
                            <img class="preview" src="<?php echo base_url().'assets/images/'.$this->session->userdata('avatar'); ?>" alt="A cropped avatar">
                            <input type="text" class="editAvatar hide" name="editAvatar" id="editAvatar">
                        </div>
                    </div>
                </div>

                <div class="times">
                    <h3>AVAILABLE TIME</h3><span class='error'> *Must choose</span>
                    <?php 
                        $timesValue = "[";
                        if (isset($resume->ownerID)) {
                            $time = json_decode($resume->descriptionText)->time;
                            foreach ($time as $key => $value) {
                                if ($key == count($time) - 1) {
                                    $timesValue =  $timesValue . "\"" . $value . "\""; 
                                } else {
                                    $timesValue = $timesValue . "\"" . $value . "\",";
                                }
                                echo "<span class='keyword no'>$value</span>";
                            }
                        }
                        $timesValue .= "]";
                        if ($timesValue == "[]") {
                            $timesValue = "";
                        }
                    ?>
                </div>
                <div class="areas">
                    <h3>INTERESTED AREA</h3><span class='error'> *Must choose</span>
                    <?php
                        $areasValue = "[";
                        if (isset($resume->ownerID)) {
                            $areas = json_decode($resume->tags)->tags;
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
                <input type="text" name="times" id="times" class="hide" value='<?php echo $timesValue; ?>'>
                <input type="text" name="areas" id="areas" class="hide" value='<?php echo $areasValue; ?>'>
            </div>

            <div class="bottom">
                <div class="desc">
                    <h3>Descriptions<span class="error"> *Not null<span></h3>
                    <div class="descContainer">
                        <textarea id="userDescription" name="userDescription"><?php 
                            if (isset($resume->ownerID)) {
                                echo json_decode($resume->descriptionText)->description; 
                            } else {
                                echo "";
                            }
                        ?></textarea>
                        <input type="hidden" name="userDesc" id="userDesc">
                    </div>
                </div>

                <div class="requirement">
                    <h3>Skills and Requirements</h3>
                    <div class="requirements">
                        <input type="checkbox" name="skillOption" class="skillOption" value="Teaching / Instruction" <?php
                            if (isset($resume->ownerID) && json_decode($resume->descriptionText)->skills[0] == "1") {
                                echo "checked";
                            }
                        ?>>Teaching / Instruction<br>
                        <input type="checkbox" name="skillOption" class="skillOption" value="English as a Secondary Language (ESL)" <?php
                            if (isset($resume->ownerID) && json_decode($resume->descriptionText)->skills[1] == "1") {
                                echo "checked";
                            }
                        ?>>English as a Secondary Language (ESL)<br>
                        <input type="checkbox" name="skillOption" class="skillOption" value="Tutoring Experience" <?php
                            if (isset($resume->ownerID) && json_decode($resume->descriptionText)->skills[2] == "1") {
                                echo "checked";
                            }
                        ?>>Tutoring Experience<br>
                        <input type="checkbox" name="skillOption" class="skillOption" value="Must be at least 18" <?php
                            if (isset($resume->ownerID) && json_decode($resume->descriptionText)->skills[3] == "1") {
                                echo "checked";
                            }
                        ?>>Must be at least 18<br>
                        <input type="checkbox" name="skillOption" class="skillOption" value="At least 1 month with the commitment" <?php
                            if (isset($resume->ownerID) && json_decode($resume->descriptionText)->skills[4] == "1") {
                                echo "checked";
                            }
                        ?>>At least 1 month with the commitment<br>                     
                    </div>
                    <input type="hidden" name="skills" id="skills">
                </div>
            </div>
        </form>

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
                <div class="confirm">CONFIRM</div>
            </div>
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
    </main>

    <script src="<?php echo base_url(); ?>assets/js/cropper.js"></script>
    <script src="https://cdn.tiny.cloud/1/gbtp6vl40eos66xyp6998by5z40su14dpxh8qkbk269e5jam/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        //Choose Image
        $("#chooseFileButton").on("click", function() {
            $("#chooseFile").click();
        });
        //Hide choose window
        $("#resume .chooseTime .cancel").on("click", () => {
            $("#resume .chooseTime").addClass("hide");
        });
        $("#resume .chooseArea .cancel").on("click", () => {
            $("#resume .chooseArea").addClass("hide");
        });

        //Open the chosen options window
        $("#resume .times").on("click", "h3", () => {
            if ($("#resume .times .keyword").length > 0) {
                $("#resume .times .keyword").each(function(index) {
                    let value = $(this).html();
                    $("#resume .timeChosenWindow .timeOption").each(function(index1) {
                        if ($(this).html() == value) {
                            $(this).addClass("chosen");
                        }
                    });
                });
            }

            $("#resume .chooseTime").removeClass("hide");
        });

        $("#resume .areas").on("click", "h3", () => {
            if ($("#resume .areas .keyword").length > 0) {
                $("#resume .areas .keyword").each(function(index) {
                    let value = $(this).html();
                    $("#resume .areaChosenWindow .areaOption").each(function(index1) {
                        if ($(this).html() == value) {
                            $(this).addClass("chosen");
                        }
                    });
                });
            }

            $("#resume .chooseArea").removeClass("hide");
        });

        //Update the chosen options
        $("#resume .chooseTime .confirm").on("click", function() {
            //Choose at least one
            if ($("#resume .timeChosenWindow .timeOption.chosen").length < 1) {
                alert("You must choose at least one option!");
                return;
            }

            let html = "<h3>AVAILABLE TIME</h3>";
            let arr = [];
            $("#resume .timeChosenWindow .timeOption").each(function(index) {
                if ($(this).hasClass('chosen')) {
                    html = html + "<span class='keyword no'>" + $(this).html() + "</span>";
                    arr.push('\"' + $(this).html() + '\"');
                }
            });
            arr = "[" + arr.join(",") + "]";
            $("#times").val(arr);
            $("#resume .times").html(html);
            $("#resume .chooseTime").addClass("hide");
        }); 

        $("#resume .chooseArea .confirm").on("click", function() {
            //Choose at least one
            if ($("#resume .areaChosenWindow .areaOption.chosen").length < 1 || $("#resume .areaChosenWindow .areaOption.chosen").length > 5) {
                alert("You must choose 1 to 5 options!");
                return;
            }

            let html = "<h3>INTERESTED AREA</h3>";
            let arr = [];
            $("#resume .areaChosenWindow .areaOption").each(function(index) {
                if ($(this).hasClass('chosen')) {
                    html = html + "<span class='keyword no'>" + $(this).html() + "</span>";
                    arr.push('\"' + $(this).html() + '\"');
                }
            });
            arr = "[" + arr.join(",") + "]";
            $("#areas").val(arr);
            console.log($("#areas").val());
            $("#resume .areas").html(html);
            $("#resume .chooseArea").addClass("hide");
        });

        $("#resume .chooseTime .timeOption, #resume .areaChosenWindow .areaOption").on("click", function() {
            $(this).toggleClass("chosen");
        }); 

        //Choose local file
        function previewFile() {
            let file = document.getElementById('chooseFile').files[0];
            let reader = new FileReader();

            reader.addEventListener("load", function () {
                // preview.src = reader.result;
                $("#image").cropper('destroy').attr('src', reader.result).cropper({
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
        $("#chooseFile").change(previewFile);

        //Initialize the cropper
        $("#image").cropper({
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

        let cas;
        let base64url;
        //preview image function
        function previewImage() {
            cas = $('#image').cropper('getCroppedCanvas');
            base64url = cas.toDataURL('image/png');
            if (getImgSize(base64url) > 300) {
                compressImage(base64url);
            } else {
                $(".preview_container img.preview").attr("src", base64url);
                $("#editAvatar").val(base64url);
            }           
        }

        function getImgSize(str) {
            let strLength = str.length;
            let fileLength = parseInt(strLength - (strLength / 8) * 2);
            let size = (fileLength / 1024).toFixed(2);

            return parseInt(size);
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

                let resizeUrl = canvas.toDataURL('image/png');
                $(".preview_container img.preview").attr("src", resizeUrl);
                $("#editAvatar").val(resizeUrl);
            }
        }
        /*Textarea*/
        tinymce.init({
            selector: '#userDescription',
            toolbar: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            toolbar_mode: 'floating',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            width: '100%',
            height: 300,
            setup : function(ed) {
                ed.on('blur', checkTextArea);
            },
        });

        function checkTextArea() {
            let text = "";
            if (tinymce.get("userDescription")) {
                text = tinymce.get("userDescription").getContent({format: "text"});
            } else {
                text = $("#userDescription").html();
            }

            if (text) {
                $("#resume .bottom h3 .error").removeClass("show");
                text = text.replace(/\"/g, '\\"').replace(/\n/g, '');
                $("#userDesc").val(text);

                return true;
            } else {
                $("#resume .bottom h3 .error").addClass("show");

                return false;
            }
        }
        
        //Fix save button 
        let fixHeight = $('#editResumeForm').offset().top;

        $(document).on('scroll',function(){
            let	curHeight = $(document).scrollTop();
            if(fixHeight-10 <= curHeight){
                $("#resume .mode").css({'position':'fixed','top':'0px', 'left': '0px'});
            }else{
                $("#resume .mode").css({'position':'relative'});
            }
        });
    </script>
</body>
</html>