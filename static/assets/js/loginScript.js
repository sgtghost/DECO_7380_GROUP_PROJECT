$(function() {
    
    /*Responsively change html's font-size*/
    function setRootFontSize() {
        let rem, rootWidth;
        let rootHtml = document.documentElement;
        rootWidth = rootHtml.clientWidth < 1366 ? 1366 : rootHtml.clientWidth;
        rem = rootWidth / 19.2;
        rootHtml.style.fontSize = `${rem}px`;
    }

    setRootFontSize();
    window.addEventListener("resize", setRootFontSize);


    /*Typing effect on login page*/
    let rawWord = $("#welcome_content .welcome_desc p.word_hidden").text();
    let rawlength = rawWord.length;
    let wordLength = 0;
    typingWord();

    function typingWord() {
        let newWord = rawWord.slice(0, wordLength++);
        $("#welcome_content .welcome_desc h2").html(newWord+"<span>|</span>");

        //All the words have been printed
        if (rawlength === wordLength-1) {
            $("#welcome_content .welcome_desc h2 span").css("animation-play-state", "paused");
            $("#welcome_content .welcome_desc h2").stop().animate({"font-size": "0.4rem"}, 1000);
            return;
        }

        //Pause effect
        if (wordLength === 12) {
            setTimeout(typingWord, 2000);
            
        }else {
            setTimeout(typingWord, 200);
        }    
    }

    /*Check login form's validation*/
    //Check usernmae
    function checkLogUsername() {
        let username = $("#signIn_username").val();

        if (username) {
            $("#signIn_page_form label.signIn_username span").text("");
            $("#signIn_username").css("border", "1px solid gray");
        }else {
            $("#signIn_page_form label.signIn_username span").text(" *can't be null");
            $("#signIn_username").css("border", "1px solid red");
        }
    }

    //Check password
    function checkLogPassword() {
        let password = $("#signIn_password").val();

        if (password) {
            $("#signIn_page_form label.signIn_password span").text("");
            $("#signIn_password").css("border", "1px solid gray");
        }else {
            $("#signIn_page_form label.signIn_password span").text(" *can't be null");
            $("#signIn_password").css("border", "1px solid red");
        }
    }

    $("#signIn_username").keyup(checkLogUsername);
    $("#signIn_password").keyup(checkLogPassword);

    //Redirect to register
    $("#signIn_page_form button.signUp").click(function(event) {
        window.location.href = window.location.origin + "/web/register";
        return false;
    });


});