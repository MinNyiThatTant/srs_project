@extends('master')

@section('content')
{{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}

<section class="main" style="background-image: url(images/hero-bg.png);">
<!--main-content-->
        <div class="home-content">

            <!--text-->
            <div class="home-text">
                <div class="contain"></div>
                <!-- <h3 style="color: white; letter-spacing: 3px;">Welcome to WYTU</h3> -->
                <h1 style="color: white;"> Student Registration</h1>
                <p style="color: white;">The purpose of WYTU is to prepare students with promise
                    to enhance their intellectual, innovation, physical, social, emotional, spiritual,
                    and technological growth so that they may realize their power for good engineering
                    as citizens</p>
                <a href="newstu_form.php" class="main-login1" style="border-radius: 5px;"><b>For New Students - Apply
                    Now</b></a><br>
                <a href="oldstu_form.php" class="main-login2" style="border-radius: 5px;"><b>For Old Students - Apply
                    Now</b></a>
            </div>
            <!--img-->
            <div class="home-img" style="width: 500px;">
                <img src="images/hero1.png" width="500px" style="text-shadow: 20px 22px;" />
                <marquee width="100%" direction="left" onmouseover="this.stop();" onmouseout="this.start();">
                    <a href="#" style="color: white; font-size: large;">Addmission open of <em
                            style="color: #160402;"><strong>November</strong></em> in every year.</a>
                </marquee>
            </div>
        </div>

        <!-- arrow -->
        <div class="arrow"></div>
        <span class="scroll">Scroll-me</span>

    </section>



    <!--services----------------------->
    <section class="services">
        <!--heading----------->
        <div class="services-heading">
            <h2>OUR AVAILABLE SPECIALIZED COURSES</h2>
            <p>In WYTU, there are 11 specialized courses in Departments, in there, following are available...</p>
        </div>
        <!--box-container----------------->
        <div class="box-container">
            <!--box-civil-------->
            <div class="box">
                <img src="images/icon5.png">
                <font>Civil Engineering</font>
                <p>Civil Engineering Civil Engineering Civil Engineering
                </p>
                <!--btn--------->
                <a href="#">Details</a>
            </div>
            <!--box-archi-------->
            <div class="box">
                <img src="images/icon5.png">
                <font>Architectural Engineering</font>
                <p>Architectural Engineering Architectural Engineering Architectural Engineering
                </p>
                <!--btn--------->
                <a href="#">Details</a>
            </div>
            <!--box-electronics-------->
            <div class="box">
                <img src="images/icon5.png">
                <font>Electronics Engineering</font>
                <p>Electronics Engineering Electronics Engineering Electronics Engineering
                </p>
                <!--btn--------->
                <a href="#">Details</a>
            </div>
            <!--box-chemical-------->
            <div class="box">
                <img src="images/icon5.png">
                <font>Chemical Engineering</font>
                <p>Chemical Engineering Chemical Engineering Chemical Engineering
                </p>
                <!--btn--------->
                <a href="#">Details</a>
            </div>
            <!--box- ep-------->
            <div class="box">
                <img src="images/icon5.png">
                <font>Electrical Power Engineering</font>
                <p>Electrical Power Engineering Electrical Power Engineering Electrical Power Engineering
                </p>
                <!--btn--------->
                <a href="#">Details</a>
            </div>
        </div>

        <div class="box-container">
            <!--box-mechanical-------->
            <div class="box">
                <img src="images/icon5.png">
                <font>Mechanical Engineering</font>
                <p>Mechanical Engineering Mechanical Engineering Mechanical Engineering
                </p>
                <!--btn--------->
                <a href="#">Details</a>
            </div>
            <!--box-agri-------->
            <div class="box">
                <img src="images/icon5.png">
                <font>Agricultural Engineering</font>
                <p>Agricultural Engineering Agricultural Engineering Agricultural Engineering
                </p>
                <!--btn--------->
                <a href="#">Details</a>
            </div>
            <!--box-ceit-------->
            <div class="box">
                <img src="images/icon5.png">
                <font>Computer Science and Information Technology Engineering</font>
                <p>Computer Science and Information Technology Engineering Computer Science and Information Technology
                    Engineering Computer Science and Information Technology Engineering
                </p>
                <!--btn--------->
                <a href="#">Details</a>
            </div>
            <!--box-mc-------->
            <div class="box">
                <img src="images/icon5.png">
                <font>Mechatronics Engineering</font>
                <p>Mechatronics Engineering Mechatronics Engineering Mechatronics Engineering
                </p>
                <!--btn--------->
                <a href="#">Details</a>
            </div>
            <!--box-textile-------->
            <div class="box">
                <img src="images/icon5.png">
                <font>Textile Engineering</font>
                <p>Textile Engineering Textile Engineering Textile Engineering
                </p>
                <!--btn--------->
                <a href="#">Details</a>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            <div class="box-container col-3">
                <!--box-metrology-------->
                <div class="box">
                    <img src="images/icon5.png">
                    <font>Metrology Engineering</font>
                    <p>Metrology Engineering Metrology Engineering Metrology Engineering
                    </p>
                    <!--btn--------->
                    <a href="#">Details</a>
                </div>
            </div>
        </div>

    </section>

@endsection