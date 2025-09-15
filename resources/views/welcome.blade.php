<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- // CSS FILES // -->

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{url('landing-page/bootstrap-4.1.1-dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <!-- OWl Carousel CSS Files -->
    <link rel="stylesheet" href="{{url('landing-page/js/plugins/owl-carousel/owl.carousel.css')}}">
    <link rel="stylesheet" href="{{url('landing-page/js/plugins/owl-carousel/owl.theme.css')}}">
    <link rel="stylesheet" href="{{url('landing-page/js/plugins/owl-carousel/owl.transitions.css')}}">
    <!-- ANIMATE CSS -->
    <link rel="stylesheet" href="{{url('landing-page/css/animate.css')}}">
    <!-- Video Pop Up Plugin -->
    <link rel="stylesheet" href="{{url('landing-page/js/plugins/YouTube_PopUp-master/YouTubePopUp.css')}}">
    <!-- PRELOADER -->
    <link rel="stylesheet" href="{{url('landing-page/css/preloader.css')}}">
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{url('landing-page/css/style.css')}}">
    <title>{{config('app.name')}}</title>
</head>

<body data-spy="scroll" data-target=".navbar">

    <!-- ==============================================
    PRELOADER
    =============================================== -->

    <div class="preloader-holder">
        <div class="loading">
            <div class="finger finger-1">
                <div class="finger-item">
                    <span></span><i></i>
                </div>
            </div>
            <div class="finger finger-2">
                <div class="finger-item">
                    <span></span><i></i>
                </div>
            </div>
            <div class="finger finger-3">
                <div class="finger-item">
                    <span></span><i></i>
                </div>
            </div>
            <div class="finger finger-4">
                <div class="finger-item">
                    <span></span><i></i>
                </div>
            </div>
            <div class="last-finger">
                <div class="last-finger-item"><i></i></div>
            </div>
        </div>
    </div>

    <!-- ==============================================
    HEADER
    =============================================== -->

    <header id="home">

        <!-- /// Navbar /// -->

        <nav class="navbar navbar-expand-lg fixed-top">
            <div class="container">
                <!-- // Brand // -->

                <a class="navbar-brand" href="#">My<span>Laundry</span>POS</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"><i class="material-icons">menu</i></button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <!-- / NavLinks / -->

                    <ul class="nav navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link page-scroll" href="#home">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link page-scroll" href="#features">Features</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link page-scroll" href="#prices">Pricing</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link page-scroll" href="#contact">Contact</a>
                        </li>
                        <li>
                            <a href="{{route('login')}}" class="btn btn-primary">Sign In</a>
                        </li>
                    </ul>

                </div>
            </div>
        </nav>

        <!-- /// BANNER /// -->
        <div class="banner">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <!-- // Caption // -->
                        <div class="caption">
                            <h1>Powerful Laundry POS Software for Smarter, Faster Business</h1>
                            <p class="sub">Manage orders, track payments, monitor staff, and grow your laundry business — all in one simple dashboard.</p>
                            <a class="btn btn-primary" href="{{route('register')}}">Start Free Trial</a>
                            <!-- / Macbook IMG / -->
                            <img class="img-fluid mx-auto wow fadeInUp" data-wow-duration="1s" data-wow-delay=".3s" src="{{url('landing-page/imgs/macbook.png')}}" alt="macbook">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- ==============================================
    ABOUT
    =============================================== -->

    <section id="about">
        <div class="container">
            <div class="row">
                <div class="col-md-4 wow bounceIn" data-wow-duration=".5s" data-wow-delay=".2s">
                    <!-- /// Icon /// -->
                    <i data-vi="layers" data-vi-size="70" data-vi-primary="#1992ec" data-vi-accent="#daeffd" data-vi-prop="#CEFAFF"></i>
                    <!-- // Title // -->
                    <h4>Point of Sale</h4>
                    <!-- / Caption / -->
                    <p>Quick order creation for walk-in and returning customers.</p>
                </div>
                <div class="col-md-4 wow bounceIn" data-wow-duration=".5s" data-wow-delay=".4s">
                    <!-- /// Icon /// -->
                    <i data-vi="website" data-vi-size="70" data-vi-primary="#1992ec" data-vi-accent="#daeffd" data-vi-prop="#CEFAFF"></i>
                    <!-- // Title // -->
                    <h4>Order Tracking</h4>
                    <!-- / Caption / -->
                    <p>Monitor every order from drop-off to pickup.</p>
                </div>
                <div class="col-md-4 wow bounceIn" data-wow-duration=".5s" data-wow-delay=".6s">
                    <!-- /// Icon /// -->
                    <i data-vi="chat" data-vi-size="70" data-vi-primary="#1992ec" data-vi-accent="#daeffd" data-vi-prop="#CEFAFF"></i>
                    <!-- // Title // -->
                    <h4>Customer Management</h4>
                    <!-- / Caption / -->
                    <p>Store customer profiles and purchase history.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==============================================
    SERVICES
    =============================================== -->




    <!-- ==============================================
    FEATURES
    =============================================== -->

    <section id="features">
        <div class="container">
            <!-- /// Title /// -->
            <div class="row">
                <div class="col-md-6 col-sm-offset-2 text-center mx-auto">
                    <h2 class="section-title">Features designed for you:</h2>
                    <p>We are passionate about helping laundry businesses succeed with technology.</p>
                </div>
            </div>
            <!-- /// Features /// -->
            <div class="row">
                <div class="col-md-4 feature wow bounceIn" data-wow-duration=".5s" data-wow-delay=".2s">
                    <div class="feature-icon">
                        <i data-vi="processor" data-vi-primary="#cfecff" data-vi-accent="#60b6f4" data-vi-prop="#CEFAFF"></i>
                    </div>
                    <h4>Cloud-Based Access</h4>
                    <p>Run your laundry from anywhere</p>
                </div>
                <div class="col-md-4 feature wow bounceIn" data-wow-duration=".5s" data-wow-delay=".4s">
                    <div class="feature-icon">
                        <i data-vi="newspaper" data-vi-primary="#60b6f4" data-vi-accent="#cfecff" data-vi-prop="#CEFAFF"></i>
                    </div>
                    <h4>Multi-Device Friendly</h4>
                    <p>Works on desktop, tablet, or smartphone.</p>
                </div>
                <div class="col-md-4 feature wow bounceIn" data-wow-duration=".5s" data-wow-delay=".6s">
                    <div class="feature-icon">
                        <i data-vi="controller" data-vi-primary="#60b6f4" data-vi-accent="#cfecff" data-vi-prop="#CEFAFF"></i>
                    </div>
                    <h4>Inventory Tracking</h4>
                    <p>Stay on top of detergents, chemicals, and supplies.</p>
                </div>
                <div class="col-md-4 feature wow bounceIn" data-wow-duration=".5s" data-wow-delay=".8s">
                    <div class="feature-icon">
                        <i data-vi="doc" data-vi-primary="#60b6f4" data-vi-accent="#cfecff" data-vi-prop="#CEFAFF"></i>
                    </div>
                    <h4>Reports & Analytics</h4>
                    <p>Daily sales, top services, outstanding balances.</p>
                </div>
                <div class="col-md-4 feature wow bounceIn" data-wow-duration=".5s" data-wow-delay="1s">
                    <div class="feature-icon">
                        <i data-vi="user" data-vi-primary="#cfecff" data-vi-accent="#60b6f4" data-vi-prop="#CEFAFF"></i>
                    </div>
                    <h4>Multi-Branch Support</h4>
                    <p>Manage multiple outlets from one account.</p>
                </div>
                <div class="col-md-4 feature wow bounceIn" data-wow-duration=".5s" data-wow-delay="1.2s">
                    <div class="feature-icon">
                        <i data-vi="cloud" data-vi-primary="#60b6f4" data-vi-accent="#60b6f4" data-vi-prop="#CEFAFF"></i>
                    </div>
                    <h4>Automated Notifications</h4>
                    <p>Send SMS/WhatsApp alerts for order status and reminders.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==============================================
    TESTIMONIALS
    =============================================== -->



    <!-- ==============================================
    PRICING
    =============================================== -->

    <section id="prices">
        <div class="container">
            <div class="row">
                <!-- /// Title /// -->
                <div class="col-md-6 col-sm-offset-2 col-md-offset-3 text-center mx-auto">
                    <h2 class="section-title">Our Plans And Pricing:</h2>
                    <p>We are passionate about helping laundry businesses in Nigeria succeed with technology.</p>
                </div>
            </div>
            <!-- /// Pricing Tables /// -->
            <div class="row">
                <!-- Starter Plan -->
                <div class="col-lg-4 wow fadeInLeft" data-wow-duration=".5s" data-wow-delay=".2s">
                    <div class="price-table">
                        <div class="header">
                            <h5 class="title">Starter</h5>
                            <div class="price">₦3,500</div>
                            <h4>Per Month</h4>
                        </div>
                        <ul>
                            <li>Single Branch</li>
                            <li>Unlimited Orders</li>
                            <li>Basic Reports & Analytics</li>
                            <li>Customer Management</li>
                            <li>Email Support</li>
                        </ul>
                        <button class="btn btn-transparent" type="button">Get Started</button>
                    </div>
                </div>

                <!-- Growth Plan -->
                <div class="col-lg-4 wow bounceIn" data-wow-duration=".5s" data-wow-delay=".4s">
                    <div class="price-table">
                        <div class="header">
                            <h5 class="title">Growth</h5>
                            <div class="price">₦14,500</div>
                            <h4>Per Month</h4>
                        </div>
                        <ul>
                            <li>Multi-Branch Support</li>
                            <li>Staff & Role Management</li>
                            <li>Inventory Tracking</li>
                            <li>Automated SMS/WhatsApp Alerts</li>
                            <li>Priority Support</li>
                        </ul>
                        <button class="btn btn-primary" type="button">Get Started</button>
                    </div>
                </div>

                <!-- Enterprise Plan -->
                <div class="col-lg-4 wow fadeInRight" data-wow-duration=".5s" data-wow-delay=".2s">
                    <div class="price-table">
                        <div class="header">
                            <h5 class="title">Enterprise</h5>
                            <div class="price">Custom</div>
                            <h4>Tailored Plan</h4>
                        </div>
                        <ul>
                            <li>Advanced Custom Features</li>
                            <li>API Integrations</li>
                            <li>Dedicated Account Manager</li>
                            <li>Custom Analytics Dashboards</li>
                            <li>24/7 Dedicated Support</li>
                        </ul>
                        <button class="btn btn-transparent" type="button">Contact Sales</button>
                    </div>
                </div>
            </div>

        </div>
    </section>



    <!-- ==============================================
    APP SCREENSHOTS
    =============================================== -->

    <section id="screenshots">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-offset-2 col-md-offset-3 text-center title-container">
                    <!-- /// Title /// -->
                    <h2 class="section-title">App Screenshots:</h2>
                    <p>Our features will wow you.</p>
                </div>
            </div>
            <div class="row">
                <div id="owl-screenshots">
                    <div class="item"><img src="{{url('landing-page/imgs/screenshots/1.png')}}" class="img-fluid" alt="screen-1"></div>
                    <div class="item"><img src="{{url('landing-page/imgs/screenshots/2.png')}}" class="img-fluid" alt="screen-2"></div>
                    <div class="item"><img src="{{url('landing-page/imgs/screenshots/3.png')}}" class="img-fluid" alt="screen-3"></div>
                    <div class="item"><img src="{{url('landing-page/imgs/screenshots/1.png')}}" class="img-fluid" alt="screen-1"></div>
                    <div class="item"><img src="{{url('landing-page/imgs/screenshots/2.png')}}" class="img-fluid" alt="screen-2"></div>
                    <div class="item"><img src="{{url('landing-page/imgs/screenshots/3.png')}}" class="img-fluid" alt="screen-3"></div>
                    <div class="item"><img src="{{url('landing-page/imgs/screenshots/1.png')}}" class="img-fluid" alt="screen-1"></div>
                    <div class="item"><img src="{{url('landing-page/imgs/screenshots/2.png')}}" class="img-fluid" alt="screen-2"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==============================================
    VIDEO POP UP
    =============================================== -->

    <div id="video-popup">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-sm-offset-2 video-box">
                    <img src="{{url('landing-page/imgs/video-bg.png')}}" class="img-fluid wow rotateIn" data-wow-duration=".5s" data-wow-delay=".2s" alt="popup">
                    <div class="play-button">
                        <a class="bla-2 wow flipInY" data-wow-duration=".5s" data-wow-delay=".4s" href="https://www.youtube.com/watch?v=3qyhgV0Zew0"><i class="material-icons">play_arrow</i></a>
                        <div class="waves-block">
                            <div class="waves wave-1"></div>
                            <div class="waves wave-2"></div>
                            <div class="waves wave-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==============================================
    TEAM
    =============================================== -->



    <!-- ==============================================
    BRAND
    =============================================== -->

    <section id="brands">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3 text-center title-container">
                    <!-- /// Title /// -->
                    <h2 class="section-title">We Are Trusted By:</h2>
                    <p>Our software is helping these business manage and grow their laundry business in Nigeria efficiently.</p>
                </div>
            </div>
            <div class="row">
                <div id="owl-brands">
                    <div class="item"><img src="{{url('landing-page/imgs/brands/brand-1.png')}}" class="img-fluid" alt="brand-1"></div>
                    <div class="item"><img src="{{url('landing-page/imgs/brands/brand-2.png')}}" class="img-fluid" alt="brand-1"></div>
                    <div class="item"><img src="{{url('landing-page/imgs/brands/brand-3.png')}}" class="img-fluid" alt="brand-3"></div>
                    <div class="item"><img src="{{url('landing-page/imgs/brands/brand-1.png')}}" class="img-fluid" alt="brand-1"></div>
                    <div class="item"><img src="{{url('landing-page/imgs/brands/brand-2.png')}}" class="img-fluid" alt="brand-2"></div>
                    <div class="item"><img src="{{url('landing-page/imgs/brands/brand-3.png')}}" class="img-fluid" alt="brand-3"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==============================================
    FOOTER
    =============================================== -->

    <section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3 text-center title-container">
                    <!-- /// Title /// -->
                    <h2 class="section-title">Don't Be Shy Send a Message:</h2>
                    <p>Contact us for a demo or any other enquiry</p>
                </div>
            </div>

            <!-- /// CONTACT FORMS /// -->

            <form>
                <div class="form-row">
                    <div class="form-group col-xs-12 col-sm-4">
                        <label>Name:</label>
                        <input type="text" class="form-control" id="inputName4">
                    </div>
                    <div class="form-group col-xs-12 col-sm-4">
                        <label for="inputPassword4">Email:</label>
                        <input type="email" class="form-control" id="inputPassword4">
                    </div>
                    <div class="form-group col-xs-12 col-sm-4">
                        <label>Phone:</label>
                        <input type="text" class="form-control" id="inputPhone">
                    </div>
                </div>
                <div class="form-group">
                    <label>Subject:</label>
                    <input type="text" class="form-control" id="inputSubject">
                </div>
                <div class="form-group">
                    <label>Message:</label>
                    <textarea id="form-message" cols="30" rows="5" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send Message</button>
            </form>
            <p>© <script>
                    document.write(new Date().getFullYear())
                </script> My laundry POS Software is a product of <a href="https://fortranhouse.com" target="_blank"> Fortran House Technologies</a></p>
        </div>
    </section>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Popper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="{{url('landing-page/bootstrap-4.1.1-dist/js/bootstrap.min.js')}}"></script>
    <!-- Icons -->
    <script src="https://cdn.jsdelivr.net/npm/vivid-icons"></script>
    <script src="https://unpkg.com/vivid-icons"></script>
    <!-- OWL Carousel -->
    <script src="{{url('landing-page/js/plugins/owl-carousel/owl.carousel.js')}}"></script>
    <!-- Video Pop Up Plugin -->
    <script src="{{url('landing-page/js/plugins/YouTube_PopUp-master/YouTubePopUp.jquery.js')}}"></script>
    <script src="{{url('landing-page/js/plugins/wow/wow.min.js')}}"></script>
    <!-- Easing -->
    <script src="{{url('landing-page/js/plugins/jquery.easing.min.js')}}"></script>
    <!-- Main JS -->
    <script src="{{url('landing-page/js/main.js')}}"></script>

</body>

</html>