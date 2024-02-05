<?php
    require_once __DIR__ . '/../setupdatabase.php';
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Spendwise</title>
    <link rel="icon" href="./assets/images/icon-1.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="./assets/css/style.css">
    
</head>
<body data-bs-spy="scroll" data-bs-target=".navbar">


    <!-- 
        
        NAVBAR
    
    -->

    <nav class="navbar navbar-expand-lg bg-white sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#hero">
                <span class="navbar-text">SpendWise</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#hero">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#team">Team</a>
                    </li>
                </ul>
                <a href="../pages/public/signin.php" class="btn btn-brand ms-lg-3">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- 
        
        HERO
    
    -->

    <section id="hero" class="min-vh-100 d-flex align-items-center text-center">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 data-aos="fade-left" class="text-uppercase text-white fw-semibold display-1">Welcome to SpendWise</h1>
                    <h5 class="text-white mt-3 mb-4" data-aos="fade-right">Effortlessly Manage Your Expenses with SpendWise - Your Digital Financial Solution</h5>
                    <div data-aos="fade-up" data-aos-delay="50">
                        <a href="../pages/public/signin.php" data-aos="fade-right" class="btn btn-brand me-2">Get Started</a>
                        <a href="#services" data-aos="fade-left" class="btn btn-light ms-2">Our Services</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 
        
        ABOUT
    
    -->

    <section id="about" class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center" data-aos="fade-down" data-aos-delay="50">
                    <div class="section-title">
                        <h1 class="display-4 fw-semibold">About us</h1>
                        <div class="line"></div>
                        <p>Crafting seamless financial experiences for you.</p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-between align-items-center">
                <div class="col-lg-6" data-aos="fade-down" data-aos-delay="50">
                    <img src="./assets/images/about.jpg">
                </div>
                <div data-aos="fade-down" data-aos-delay="150" class="col-lg-5">
                    <h1>About SpendWise</h1>
                    <p class="mt-3 mb-4">SpendWise simplifies expense management and empowers your financial journey.</p>
                    <div class="d-flex pt-4 mb-3">
                        <div class="iconbox me-4">
                            <i class="ri-mail-send-fill"></i>
                        </div>
                        <div>
                            <h5>User-Focused Design</h5>
                            <p>Enjoy an intuitive and accessible financial management experience.</p>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="iconbox me-4">
                            <i class="ri-user-5-fill"></i>
                        </div>
                        <div>
                            <h5>Effortless Tracking</h5>
                            <p>Seamlessly track and categorize expenses for effortless control.</p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="iconbox me-4">
                            <i class="ri-rocket-2-fill"></i>
                        </div>
                        <div>
                            <h5>informed Savings</h5>
                            <p>Get insights for informed decisions, boosting your savings effectively.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 
        
        SERVICES
    
    -->

    <section id="services" class="section-padding border-top">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center" data-aos="fade-down" data-aos-delay="150">
                    <div class="section-title">
                        <h1 class="display-4 fw-semibold">Awesome Services</h1>
                        <div class="line"></div>
                        <p>Empowering your financial journey with cutting-edge services.</p>
                    </div>
                </div>
            </div>
            <div class="row g-4 text-center">
                <div class="col-lg-4 col-sm-6" data-aos="fade-down" data-aos-delay="150">
                    <div class="service theme-shadow p-lg-5 p-4">
                        <div class="iconbox">
                            <i class="ri-pen-nib-fill"></i>
                        </div>
                        <h5 class="mt-4 mb-3">Expendse Tracking</h5>
                        <p>Keep a meticulous record of your spending habits.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6" data-aos="fade-down" data-aos-delay="250">
                    <div class="service theme-shadow p-lg-5 p-4">
                        <div class="iconbox">
                            <i class="ri-stack-fill"></i>
                        </div>
                        <h5 class="mt-4 mb-3">Receipt Scanning</h5>
                        <p>Effortlessly digitize and organize your receipts for easy expense management.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6" data-aos="fade-down" data-aos-delay="350">
                    <div class="service theme-shadow p-lg-5 p-4">
                        <div class="iconbox">
                            <i class="ri-ruler-2-fill"></i>
                        </div>
                        <h5 class="mt-4 mb-3">Financial Reporting</h5>
                        <p>Access comprehensive financial reports to analyze your spending trends.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6" data-aos="fade-down" data-aos-delay="450">
                    <div class="service theme-shadow p-lg-5 p-4">
                        <div class="iconbox">
                            <i class="ri-pie-chart-2-fill"></i>
                        </div>
                        <h5 class="mt-4 mb-3">Budget Management</h5>
                        <p>Set and manage budgets for various categories to control your spending.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6" data-aos="fade-down" data-aos-delay="550">
                    <div class="service theme-shadow p-lg-5 p-4">
                        <div class="iconbox">
                            <i class="ri-code-box-line"></i>
                        </div>
                        <h5 class="mt-4 mb-3">Automatic Categorization</h5>
                        <p>Save time and reduce manual effort with automatic categorization of expenses.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6" data-aos="fade-down" data-aos-delay="650">
                    <div class="service theme-shadow p-lg-5 p-4">
                        <div class="iconbox">
                            <i class="ri-user-2-fill"></i>
                        </div>
                        <h5 class="mt-4 mb-3">Collaborative Budgeting</h5>
                        <p>Stay connected and aligned on financial goals with real-time updates.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 
        
        COUNTER
    
    -->

    <section id="counter" class="section-padding">
        <div class="container text-center">
            <div class="row g-4">
                <div class="col-lg-3 col-sm-6" data-aos="fade-down" data-aos-delay="150">
                    <h1 class="text-white display-4">600K+</h1>
                    <h6 class="text-uppercase mb-0 text-white mt-3">Total Downloads</h6>
                </div>
                <div class="col-lg-3 col-sm-6" data-aos="fade-down" data-aos-delay="250">
                    <h1 class="text-white display-4">100K+</h1>
                    <h6 class="text-uppercase mb-0 text-white mt-3">Trusted Clients</h6>
                </div>
                <div class="col-lg-3 col-sm-6" data-aos="fade-down" data-aos-delay="350">
                    <h1 class="text-white display-4">1M+</h1>
                    <h6 class="text-uppercase mb-0 text-white mt-3">Dollars Saved</h6>
                </div>
                <div class="col-lg-3 col-sm-6" data-aos="fade-down" data-aos-delay="450">
                    <h1 class="text-white display-4">2</h1>
                    <h6 class="text-uppercase mb-0 text-white mt-3">Team Members</h6>
                </div>
            </div>
        </div>
    </section>

    <!-- 
        
        TEAM
    
    -->

    <section id="team" class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center" data-aos="fade-down" data-aos-delay="150">
                    <div class="section-title">
                        <h1 class="display-4 fw-semibold">Team Members</h1>
                        <div class="line"></div>
                        <p>Meet the passionate individuals shaping SpendWise for a seamless financial experience.</p>
                    </div>
                </div>
            </div>
            <div class="row g-4 text-center ">
                <div class="col-md-6" data-aos="fade-down" data-aos-delay="150">
                    <div class="team-member image-zoom">
                        <div class="image-zoom-wrapper">
                            <img src="./assets/images/rosto-1.jpg">
                        </div>
                        <div class="team-member-content">
                            <h4 class="text-white">Pedro Freitas</h4>
                            <p class="mb-0 text-white">Software Enginner</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" data-aos="fade-down" data-aos-delay="250">
                    <div class="team-member image-zoom">
                        <div class="image-zoom-wrapper">
                            <img src="./assets/images/rosto.jpg">
                        </div>
                        <div class="team-member-content">
                            <h4 class="text-white">Vitor Mina</h4>
                            <p class="mb-0 text-white">UI Designer</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 
        
        FOOTER
    
    -->

    <footer class="bg-dark">
        <div class="footer-top">
            <div class="container">
                <div class="row gy-5">
                    <div class="col-lg-4 col-sm-6">
                        <span class="footer-text">SpendWise</span>
                        <div class="social-icons">
                            <a href="#"><i class="ri-twitter-fill"></i></a>
                            <a href="#"><i class="ri-instagram-fill"></i></a>
                            <a href="#"><i class="ri-github-fill"></i></a>
                            <a href="#"><i class="ri-dribbble-fill"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <h5 class="mb-0 text-white">SERVICES</h5>
                        <div class="line"></div>
                        <ul>
                            <li><a href="#services">Expense Tracking</a></li>
                            <li><a href="#services">Budget Management</a></li>
                            <li><a href="#services">Financial Reporting</a></li>
                            <li><a href="#services">Receipt Management</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <h5 class="mb-0 text-white">ABOUT</h5>
                        <div class="line"></div>
                        <ul>
                            <li><a href="#hero">Home</a></li>
                            <li><a href="#about">About</a></li>
                            <li><a href="#services">Services</a></li>
                            <li><a href="#team">Team</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="row g-4 justify-content-between">
                    <div class="col-auto">
                        <p class="mb-0">© Copyright SpendWise. All Rights Reserved</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script src="./assets/js/main.js"></script>
</body>
</html>