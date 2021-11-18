$(function() {
    /*Home click naviagtion to detail page*/
    $("#header .logo").on("click", () => {
        window.location.href = window.location.origin + "/static";
    });

    /*Search button on home page*/
    $("#searchForm img").on("click", function() {
        $("#searchForm").submit();
    });

    $("body").on("click", ".keyword:not(.no), #event .organization .info .container .area", function(event) {
        event.stopPropagation();
        let text = $(this).html()
        text = text.replace("&", "!");
        window.open(window.location.origin + "/static?areaVal=" + text);
    });

    /*Remain last search value*/
    let searchVal = getQueryVariable("searchVal");
    if (searchVal) {
        searchVal = searchVal.replace("!amp;", "&").replace(/\+/g, " ");
        $("#searchVal").val(searchVal);
    }

    let areaVal = getQueryVariable("areaVal");
    if (areaVal) {
        areaVal = areaVal.replace("!amp;", "&").replace("%26", "&");
        $("#searchForm .areas option").each(function(index) {
            if ($(this).text() == areaVal) {
                $(this).prop("selected", true);
            }
        });
    }

    /* When scroll below header, give a button to scroll top*/
    //When page refreshed, adjust the scroll_top.
    if ($(document).scrollTop() >= 200) {
        $(".scroll_top").css("visibility", "visible");
        $(".scroll_top").css("opacity", "1");
    }else {
        $(".scroll_top").css("visibility", "hidden");
        $(".scroll_top").css("opacity", "0");
    };
    //Check scroll_top in each scroll event.
    $(document).scroll(function() {
        if ($(document).scrollTop() >= 200){
            $(".scroll_top").css("visibility", "visible");
            $(".scroll_top").css("opacity", "1");
        }else {
            $(".scroll_top").css("visibility", "hidden");
            $(".scroll_top").css("opacity", "0");
        }
    });
    //When scrolling to top, adding an animation.
    $("#aside .scroll_top a").click(function(event) {
        $("html, body").animate({scrollTop: 0}, 1000);
        event.preventDefault();
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

    /*Login page*/
    //Show password
    $("#loginForm img").on("click", function() {
        let attr = $("#loginPassword").attr("type");
        if (attr == "password") {
            $("#loginPassword").attr("type", "text");
        } else {
            $("#loginPassword").attr("type", "password");
        }
    });

    $("#registerForm img").on("click", function() {
        let attr = $("#registerPassword").attr("type");
        if (attr == "password") {
            $("#registerPassword").attr("type", "text");
        } else {
            $("#registerPassword").attr("type", "password");
        }
    });

    //Cancel login page
    $("#login .container .cancel").on("click", function() {
        $("#login").addClass("hide");
    });
    $("#login").on("click", function(event) {
        let target = $(event.target).attr("id");
        if (target && target == "login") {
            $("#login").addClass("hide");
        }
    });

    //Scroll to next page in login pages
    $("#login .login_page strong").on("click", function() {
        $("#login .container .windows").css("top", "-550px");
    });

    $("#login .role_page strong, #login .register_page strong").on("click", function() {
        $("#login .container .windows").css("top", "0px");
    });

    $("#login .role_page .role").on("click", function() {
        $("#login .container .windows").css("top", "-1100px");
    });

    $("#login .register_page .back").on("click", function() {
        $("#login .container .windows").css("top", "-550px");
    });

    //Select user type
    $("#login .role_page .role").on("click", function() {
        let userType = 0;
        if($(this).html() == "ORGANIZATION") {
            userType = 1;
        }
        $("#userType").val(userType);
    });

    /*Login and register on top menu*/
    $("#header a.login").on("click", (event) => {
        event.preventDefault();
        $("#login").removeClass("hide");
        $("#login .container .windows").css("top", "0px");
    });

    $("#header a.register").on("click", (event) => {
        event.preventDefault();
        $("#login").removeClass("hide");
        $("#login .container .windows").css("top", "-550px");
    });

    /*Check whether all the input is valid in the Sign_in page*/
    function checkLoginUsername() {
        //Check the validation of username
        let username = $("#loginUsername").val();
        let reg_username = /^\w+/;
        let flag = reg_username.test(username);
        if (flag) {
            $("#loginUsername").css("border-bottom", "2px solid #5D5FEF");
            $("#loginUsername").prev().prev().removeClass("show");
        }else {
            $("#loginUsername").css("border-bottom", "2px solid red");
            $("#loginUsername").prev().prev().addClass("show");
        }
        return flag;
    }

    function checkLoginPassword() {
        //Check the validation of username
        let password = $("#loginPassword").val();
        let reg_password = /^\w+/;
        let flag = reg_password.test(password);
        if (flag) {
            $("#loginPassword").css("border-bottom", "2px solid #5D5FEF");
            $("#loginPassword").prev().prev().removeClass("show");
        }else {
            $("#loginPassword").css("border-bottom", "2px solid red");
            $("#loginPassword").prev().prev().addClass("show");
        }
        return flag;
    }

    $("#loginUsername").blur(checkLoginUsername);
    $("#loginPassword").blur(checkLoginPassword);

    $("#loginForm .submit").on("click", () => {
        $("#loginForm p.error").html("*Wrong username or password");
        $("#loginForm p.error").removeClass("show");
        if (checkLoginUsername() && checkLoginPassword()) {
            $.ajax({
                url: window.location.origin + "/static/login",
                method: "POST",
                data: $("#loginForm").serialize(),
                success: function(response) {
                    let obj = JSON.parse(response);
                    if (obj.success) {                      
                        window.location.reload();

                    } else {
                        if (obj.active === false) {
                            $("#loginForm p.error").addClass("show");
                        } else {
                            $("#loginForm p.error").html("Please activate your account from email");
                            $("#loginForm p.error").addClass("show");
                        }
                    }
                }
            });
        }
    });
    //register page
    function checkRegisterUsername() {
        //Check the validation of username
        let username = $("#registerName").val();
        let reg_username = /^\w{1,18}$/;
        let flag = reg_username.test(username);
        if (flag) {
            $("#registerName").css("border-bottom", "2px solid #5D5FEF");
            $("#registerName").prev().prev().removeClass("show");
        }else {
            $("#registerName").css("border-bottom", "2px solid red");
            $("#registerName").prev().prev().addClass("show");
        }
        return flag;
    }

    function checkRegisterPassword() {
        //Check the validation of password
        let password = $("#registerPassword").val();
        let reg_password = /^\w{5,18}$/;
        let flag = reg_password.test(password);
        if (flag) {
            $("#registerPassword").css("border-bottom", "2px solid #5D5FEF");
            $("#registerPassword").prev().prev().removeClass("show");
        }else {
            $("#registerPassword").css("border-bottom", "2px solid red");
            $("#registerPassword").prev().prev().addClass("show");
        }
        return flag;
    }

    function checkEmail() {
        //Check the validation of password
        let email = $("#registerEmail").val();
        let reg_email = /[^`~!@#$%\^&\*\(\)\+=\|\{\}\':;\',\\\[\]<>\/\?~！@#￥%……&\*（）——+\|\{\}【】‘；：”“’。，、？\s]{1,}@[^`~!@#$%\^&\*\(\)\+=\|\{\}\':;\',\\\[\]\.<>\/\?~！@#￥%……&\*（）——+\|\{\}【】‘；：”“’。，、？\s]{1,}\.[^`~!@#$%\^&\*\(\)\+=\|\{\}\':;\',\\\[\]<>\/\?~！@#￥%……&\*（）——+\|\{\}【】‘；：”“’。，、？\s]{1,}/i;
        let flag = reg_email.test(email);
        if (flag) {
            $("#registerEmail").css("border-bottom", "2px solid #5D5FEF");
            $("#registerEmail").prev().prev().removeClass("show");
        }else {
            $("#registerEmail").css("border-bottom", "2px solid red");
            $("#registerEmail").prev().prev().addClass("show");
        }
        return flag;
    }

    $("#registerName").blur(checkRegisterUsername);
    $("#registerPassword").blur(checkRegisterPassword);
    $("#registerEmail").blur(checkEmail);

    $("#registerForm .submit").on("click", () => {
        $("#registerForm p.error").removeClass("show");
        if (checkRegisterUsername() && checkRegisterPassword() && checkEmail()) {
            $.ajax({
                url: window.location.origin + "/static/login/register",
                method: "POST",
                data: $("#registerForm").serialize(),
                success: function(response) {
                    if (response == 1) {
                        window.location.reload();
                    } else {
                        $("#registerForm p.error").addClass("show");
                    }
                },
                error: function(reason) {
                    console.log(reason);
                }
            });
        }
    });

    /*Avatar menu*/
    $("nav section.login .logout").on("click", () => {
        window.location.href = window.location.origin + "/static/login/logout";
    });

    $("nav section.login .resume").on("click", () => {
        window.location.href = window.location.origin + "/static/resume";
    });

    $("nav section.login .events").on("click", () => {
        window.location.href = window.location.origin + "/static/organiserManage";
    });

    $("nav section.login .application").on("click", () => {
        window.location.href = window.location.origin + "/static/myApplication";
    });

    $("#resume .top .edit").on("click", () => {
        window.location.href = window.location.origin + "/static/editResume";
    });

    /*Refresh image */
    $("img").each(function(index) {
        if($(this).parents('.map').length < 1) {
            $(this).attr('src', $(this).attr('src') + "?time=" + (new Date()).getTime());
        }
    });

    /*Edit resume form*/
    $("#resume .save").on("click", () => {
        if (basicCheck1(["#firstName", "#lastName", "#phoneNo", "#gender", "#age"]) && checkOrgName() && checkChoice() && checkTextArea() && checkSkill()) {
            console.log(1);
            $("#resume .save").off("click");
            $("#resume .save").html("<div class='spiral'></div>");
            $.ajax({
                url: window.location.origin + "/static/editResume/update",
                method: "POST",
                data: $("#editResumeForm").serialize(),
                success: function(response) {
                    if (response) {
                        $("#resume .save").html("SAVE CHANGE");
                        window.location.href = window.location.origin + "/static/resume";
                    }                  
                },
                error: function(reason) {
                    console.log(reason);
                }
            });
        } else {
            console.log(0);
        }
    });

    function basicCheck() {
        //Check the validation 
        let value = $(this).val();
        let reg_value = /^\w+/;
        let flag = reg_value.test(value);
        if (flag) {
            $(this).css("border-bottom", "2px solid #5D5FEF");
            $(this).next().removeClass("show");
        }else {
            $(this).css("border-bottom", "2px solid red");
            $(this).next().addClass("show");
        }
        return flag;
    }

    function basicCheckNum() {
        //Check the validation 
        let value = $(this).val();
        let reg_value = /^\d+$/;
        let flag = reg_value.test(value);
        if (flag) {
            $(this).css("border-bottom", "2px solid #5D5FEF");
            $(this).next().removeClass("show");
        }else {
            $(this).css("border-bottom", "2px solid red");
            $(this).next().addClass("show");
        }
        return flag;
    }

    function checkSlot() {
        //Check the validation 
        if ($("#eventSlot").length > 0) {
            let value = $("#eventSlot").val();
            let reg_value = /^\d+$/;
            let flag = reg_value.test(value);
            if (flag) {
                $("#eventSlot").css("border-bottom", "2px solid #5D5FEF");
                $("#eventSlot").next().removeClass("show");
            }else {
                $("#eventSlot").css("border-bottom", "2px solid red");
                $("#eventSlot").next().addClass("show");
            }
            return flag;
        } else {
            return true;
        }   
    }

    function basicCheck1(array) {
        //Check the validation of an array
        let valid = true;
        $.each(array, function(key, id) {
            let value = $(id).val();
            let reg_value = /^\w+/;
            if (id == "#phoneNo" || id == "#age") {
                reg_value = /^\d+$/;
            }
            let flag = reg_value.test(value);
            if (flag) {
                $(id).css("border-bottom", "2px solid #5D5FEF");
                $(id).next().removeClass("show");
            }else {
                $(id).css("border-bottom", "2px solid red");
                $(id).next().addClass("show");
                valid = false;
            }
           
        });
        
        return valid;
    }

    // function checkResumeEmail() {
    //     //Check the validation 
    //     let value = $("#emailAddress").val();
    //     let reg_value = /[^`~!@#$%\^&\*\(\)\+=\|\{\}\':;\',\\\[\]<>\/\?~！@#￥%……&\*（）——+\|\{\}【】‘；：”“’。，、？\s]{1,}@[^`~!@#$%\^&\*\(\)\+=\|\{\}\':;\',\\\[\]\.<>\/\?~！@#￥%……&\*（）——+\|\{\}【】‘；：”“’。，、？\s]{1,}\.[^`~!@#$%\^&\*\(\)\+=\|\{\}\':;\',\\\[\]<>\/\?~！@#￥%……&\*（）——+\|\{\}【】‘；：”“’。，、？\s]{1,}/i
    //     let flag = reg_value.test(value);
    //     if (flag) {
    //         $("#emailAddress").css("border-bottom", "2px solid #5D5FEF");
    //         $("#emailAddress").next().removeClass("show");
    //     }else {
    //         $("#emailAddress").css("border-bottom", "2px solid red");
    //         $("#emailAddress").next().addClass("show");
    //     }
    //     return flag;
    // }

    function checkSkill() {
        let arr = [];
        if ($("#resume .bottom .requirements .skillOption").length > 0) {
            $("#resume .bottom .requirements .skillOption").each(function(index) {
                if ($(this).prop("checked")) {
                    arr.push(1);
                } else {
                    arr.push(0);
                }
            });
        }
        let result = "[" + arr.join(",") + "]";
        $("#skills").val(result);

        return result;
    }

    function checkEventSkill() {
        let arr = [];
        if ($("#event.edit .requirements .skillOption").length > 0) {
            $("#event.edit .requirements .skillOption").each(function(index) {
                if ($(this).prop("checked")) {
                    arr.push(1);
                } else {
                    arr.push(0);
                }
            });
        }
        let result = "[" + arr.join(",") + "]";
        $("#eventSkills").val(result);

        return result;
    }

    function checkChoice() {
        if ($("#resume .top .times .keyword").length > 0) {
            $("#resume .top .times .error").removeClass("show");
        } else {
            $("#resume .top .times .error").addClass("show");
            return false;
        }

        if ($("#resume .top .areas .keyword").length > 0) {
            $("#resume .top .areas .error").removeClass("show");
        } else {
            $("#resume .top .areas .error").addClass("show");
            return false;
        }

        return true;
    }

    function checkEventChoice() {
        if ($("#event.edit .interact .keywords .keyword").length > 0) {
            $("#event.edit .interact .keywords .error").removeClass("show");
        } else {
            $("#event.edit .interact .keywords .error").addClass("show");
            return false;
        }

        return true;
    }

    function checkOrgName() {
        if ($("#orgName").length > 0) {
            //Check the validation 
            let value = $("#orgName").val();
            let reg_value = /^\w+/;
            let flag = reg_value.test(value);
            if (flag) {
                $("#orgName").css("border-bottom", "2px solid #5D5FEF");
                $("#orgName").next().removeClass("show");
            }else {
                $("#orgName").css("border-bottom", "2px solid red");
                $("#orgName").next().addClass("show");
            }
            return flag;
        } else {
            return true;
        }
    }

    function checkDate() {
        if ($("#editEventDate").length > 0) {
            //Check the validation 
            let value = $("#editEventDate").val();
            let reg_value = /^\d{4}\-\d{2}\-\d{2}$/;
            let flag = reg_value.test(value);
            if (flag) {
                $("#editEventDate").css("border-bottom", "2px solid #5D5FEF");
                $("#editEventDate").next().removeClass("show");
            }else {
                $("#editEventDate").css("border-bottom", "2px solid red");
                $("#editEventDate").next().addClass("show");
            }
            return flag;
        } else {
            return true;
        }
    }

    function checkCoordinate() {
        if ($(this).length > 0) {
            //Check the validation 
            let value = $(this).val();
            let reg_value = /(^\-?\d+$)|(^\-?\d+\.?\d+$)/;
            let flag = reg_value.test(value);
            if (flag) {
                $(this).css("border-bottom", "2px solid #5D5FEF");
                $(this).next().removeClass("show");
            }else {
                $(this).css("border-bottom", "2px solid red");
                $(this).next().addClass("show");
            }
            return flag;
        } else {
            return true;
        }
    }

    function checkLatitude() {
        if ($("#eventLatitude").length > 0) {
            //Check the validation 
            let value = $("#eventLatitude").val();
            let reg_value = /(^\-?\d+$)|(^\-?\d+\.?\d+$)/;
            let flag = reg_value.test(value);
            if (flag) {
                $("#eventLatitude").css("border-bottom", "2px solid #5D5FEF");
                $("#eventLatitude").next().removeClass("show");
            }else {
                $("#eventLatitude").css("border-bottom", "2px solid red");
                $("#eventLatitude").next().addClass("show");
            }
            return flag;
        } else {
            return true;
        }
    }

    function checkLongtitude() {
        if ($("#eventLongtitude").length > 0) {
            //Check the validation 
            let value = $("#eventLongtitude").val();
            let reg_value = /(^\-?\d+$)|(^\-?\d+\.?\d+$)/;
            let flag = reg_value.test(value);
            if (flag) {
                $("#eventLongtitude").css("border-bottom", "2px solid #5D5FEF");
                $("#eventLongtitude").next().removeClass("show");
            }else {
                $("#eventLongtitude").css("border-bottom", "2px solid red");
                $("#eventLongtitude").next().addClass("show");
            }
            return flag;
        } else {
            return true;
        }
    }

    $("#firstName, #lastName, #gender, #editEventName, #editEventAddress, #editEventPersonas").blur(basicCheck);
    $("#phoneNo, #age").blur(basicCheckNum);
    $("#orgName").blur(checkOrgName);
    $("#editEventDate").blur(checkDate);
    $("#eventLatitude, #eventLongtitude").blur(checkCoordinate);
    $("#eventSlot").blur(checkSlot);

    /*Edit Event*/
    $("#event .intro .interact .edit, #event .location .edit").on("click", function() {
        window.location.href = window.location.origin + "/static/eventDetail/edit?eventID=" + getQueryVariable("eventID");
    });

    $("#editEventMode .save").on("click", () => {
        if (basicCheck1(["#editEventName", "#editEventAddress", "#editEventPersonas"]) && checkDate() && checkSlot() && checkEventChoice() && checkLatitude() && checkLongtitude() && checkTextArea() && checkEventSkill()) {
            console.log(1);
            $("#editEventMode .save").off("click");
            $("#editEventMode .save").html("<div class='spiral'></div>");
            $.ajax({
                url: window.location.origin + "/static/eventDetail/update",
                method: "POST",
                data: $("#editEventForm").serialize(),
                success: function(response) {
                    console.log(response);
                    if (response) {
                        window.location.href = window.location.origin + "/static/eventDetail?eventID=" + response;
                    }                  
                },
                error: function(reason) {
                    console.log(reason);
                }
            });
        } else {
            console.log(0);
        }
    });

    /*Organiser Management*/
    $("#organiserManage .personal .edit, #myApplication .personal .edit").on("click", function() {
        window.location.href = window.location.origin + "/static/resume";
    });

});