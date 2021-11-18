<title>Resume</title>
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
        <div class="top">
            <h2><?php echo $resume->username; ?>'s Resume</h2>
            <?php if ($resume->username == $this->session->userdata('username')) echo "<div class='edit'>EDIT RESUME</div>"; ?>
            <div class="infoContainer">
                <div class="basic">
                    <div class="title">
                        <?php if ($resume->organiser) {
                            echo "<p>Organization Name:</p>";
                        } ?> 
                        <p>Name:</p>
                        <p>Phone Number:</p>
                        <p>Email Address:</p>
                        <p>Gender:</p>
                        <p>Age:</p>
                    </div>

                    <div class="data">
                        <?php if ($resume->organiser) {
                            echo "<p>" . json_decode($resume->descriptionText)->name . "</p>";
                        } ?>
                        <p><?php echo $resume->firstName." ".$resume->lastName; ?></p>
                        <p><?php echo $resume->phoneNo; ?></p>
                        <p><?php echo $resume->emailAddress; ?></p>
                        <p><?php echo json_decode($resume->descriptionText)->gender; ?></p>
                        <p><?php echo json_decode($resume->descriptionText)->age; ?></p>
                    </div>
                </div>

                <div class="avatar">
                    <img src="<?php echo base_url() . 'assets/images/' . json_decode($resume->descriptionImageLink)->avatar; ?>">
                    <h3>Available Time</h3>
                    <div class="time"> 
                        <?php
                            $time = json_decode($resume->descriptionText)->time;
                            foreach ($time as $key => $value) {
                                echo "<span class='keyword no'>$value</span>";
                            }
                        ?>
                    </div>
                    <h3>Interested Area</h3>
                    <div class="area">
                        <?php
                            $areas = json_decode($resume->tags)->tags;
                            foreach ($areas as $key => $value) {
                                echo "<span class='keyword'>$value</span>";
                            }
                        ?>
                    </div>
                </div>
            </div>

        </div>

        <div class="bottom">
            <div class="desc">
                <h3>Descriptions</h3>
                <p><?php echo json_decode($resume->descriptionText)->description; ?></p>
            </div>

            <div class="requirement">
                <h3>Skills and Requirements</h3>
                <div class="requirements">
                    <img src="<?php echo base_url(); ?>assets/images/<?php 
                        $skills = json_decode($resume->descriptionText)->skills;
                        if ($skills[0] == 0) {
                            echo 'checkbox.svg';
                        } else {
                            echo 'checkbox_checked.svg';
                        }
                    ?>"> <span>Teaching / Instruction</span><br>
                    <img src="<?php echo base_url(); ?>assets/images/<?php 
                        if ($skills[1] == 0) {
                            echo 'checkbox.svg';
                        } else {
                            echo 'checkbox_checked.svg';
                        }
                    ?>"> <span>English as a Secondary Language (ESL)</span><br>
                    <img src="<?php echo base_url(); ?>assets/images/<?php 
                        if ($skills[2] == 0) {
                            echo 'checkbox.svg';
                        } else {
                            echo 'checkbox_checked.svg';
                        }
                    ?>"> <span>Tutoring Experience</span><br>
                    <img src="<?php echo base_url(); ?>assets/images/<?php 
                        if ($skills[3] == 0) {
                            echo 'checkbox.svg';
                        } else {
                            echo 'checkbox_checked.svg';
                        }
                    ?>"> <span>Must be at least 18</span><br>
                    <img src="<?php echo base_url(); ?>assets/images/<?php 
                        if ($skills[4] == 0) {
                            echo 'checkbox.svg';
                        } else {
                            echo 'checkbox_checked.svg';
                        }
                    ?>"> <span>At least 1 month with the commitment</span><br>
                <div>
            </div>
        </div>
    </main>

    <script>

    </script>
</body>
</html>