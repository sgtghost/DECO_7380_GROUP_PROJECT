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
    
    <main id="noResume">
        <h2>You don't have a resume yet, please apply one.</h2>
        <h2>(If you are an organiser who want to publish an Event. </h2>
        <h2>You still need to have a resume first.)</h2>
        <div class='apply'>APPLY</div>
    </main>

    <script>
        $("#noResume .apply").on("click", () => {
            window.location.href = window.location.origin + "/static/editResume";
        });
    </script>
</body>
</html>