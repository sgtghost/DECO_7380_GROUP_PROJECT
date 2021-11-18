
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
                        <div class="category">
                            <p>Private</p>
                        </div>
                        <div class="category">
                            <p>Ongoing</p>
                        </div>
                        <div class="category">
                            <p>Ended</p>
                        </div>
                    </div>

                    <div class="eventDetail">
                        <h3> You don't have an event yet.</h3><br>
                        <h3> Please add one first.</h3>                      
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
    </main>

    <script src="//at.alicdn.com/t/font_2506080_2mev0uhqlhi.js"></script>
    <script>
        //Show list
        $(".category").on("click",".arrowContainer", function() {
            $(this).next().toggleClass("show");
        });

        $("#organiserManage .addEvent").on("click", function() {
            window.location.href = window.location.origin + "/static/eventDetail/edit";
        });
    </script>
</body>
</html>