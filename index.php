<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholar - Education Platform</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header Section -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <a class="navbar-brand" href="#">SCHOLAR</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="#courses">Courses</a></li>
                        <li class="nav-item"><a class="nav-link" href="#instructors">Instructors</a></li>
                        <li class="nav-item"><a class="nav-link" href="#events">Events</a></li>
                        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                        <li class="nav-item"><a class="nav-link btn-login" href="login.php">Login</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1>With Scholar Teachers, Everything is Easier</h1>
                    <p>Find the best courses and achieve your professional goals with Scholar</p>
                    <a href="#courses" class="btn btn-primary">Get Started</a>
                </div>
                <div class="col-md-6">
                    <img src="assets/images/hero-image.jpg" alt="Students learning" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="feature-card">
                        <div class="icon"><i class="fas fa-graduation-cap"></i></div>
                        <h3>100+ Programs</h3>
                        <p>Explore our diverse range of educational programs</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-card">
                        <div class="icon"><i class="fas fa-book"></i></div>
                        <h3>Expert Tutors</h3>
                        <p>Learn from industry professionals and academics</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-card">
                        <div class="icon"><i class="fas fa-certificate"></i></div>
                        <h3>Certifications</h3>
                        <p>Earn recognized certificates upon completion</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-card">
                        <div class="icon"><i class="fas fa-users"></i></div>
                        <h3>Active Community</h3>
                        <p>Join our vibrant learning community</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="faq-form">
                        <h3>Have Questions?</h3>
                        <form id="question-form">
                            <div class="mb-3">
                                <input type="text" class="form-control" placeholder="Your Name">
                            </div>
                            <div class="mb-3">
                                <input type="email" class="form-control" placeholder="Your Email">
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" rows="3" placeholder="Your Question"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Question</button>
                        </form>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="faq-content">
                        <h3>What Makes Us The Best Education Platform?</h3>
                        <div class="ai-assistant">
                            <p>Ask our AI assistant any questions about courses, schedules, or learning paths!</p>
                            <div class="ai-chat">
                                <input type="text" id="ai-question" class="form-control" placeholder="Ask AI Assistant...">
                                <button id="ask-ai" class="btn btn-primary"><i class="fas fa-paper-plane"></i></button>
                            </div>
                            <div id="ai-response" class="mt-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Courses Section -->
    <section id="courses" class="courses-section">
        <div class="container">
            <h2 class="section-title">Latest Courses</h2>
            <div class="row" id="courses-container">
                <!-- Courses will be loaded dynamically from the database -->
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-item">
                        <h3>150+</h3>
                        <p>Courses Available</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <h3>650+</h3>
                        <p>Students Enrolled</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <h3>50+</h3>
                        <p>Expert Instructors</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <h3>18+</h3>
                        <p>Years Experience</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Instructors Section -->
    <section id="instructors" class="instructors-section">
        <div class="container">
            <h2 class="section-title">Our Expert Instructors</h2>
            <div class="row" id="instructors-container">
                <!-- Instructors will be loaded dynamically from the database -->
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <h2 class="section-title">What They Say About Us</h2>
            <div class="row">
                <div class="col-md-8">
                    <div class="testimonial-card">
                        <p>"Scholar has transformed my learning experience. The instructors are knowledgeable and the platform is user-friendly."</p>
                        <div class="testimonial-author">
                            <img src="assets/images/testimonial-1.jpg" alt="Testimonial Author">
                            <div>
                                <h4>Sarah Johnson</h4>
                                <p>Web Development Student</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-info">
                        <h3>What They Say About Us</h3>
                        <p>Our students love the learning experience we provide. Here's what they have to say about Scholar.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Events Section -->
    <section id="events" class="events-section">
        <div class="container">
            <h2 class="section-title">Upcoming Events</h2>
            <div class="row" id="events-container">
                <!-- Events will be loaded dynamically from the database -->
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2>Feel Free To Contact Us Anytime</h2>
                    <p>Have questions or need assistance? Reach out to our team.</p>
                </div>
                <div class="col-md-6">
                    <div class="contact-form">
                        <form id="contact-form">
                            <div class="mb-3">
                                <input type="text" class="form-control" placeholder="Your Name" required>
                            </div>
                            <div class="mb-3">
                                <input type="email" class="form-control" placeholder="Your Email" required>
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" rows="4" placeholder="Your Message" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h3>SCHOLAR</h3>
                    <p>Empowering education through technology and innovation.</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="#">Home</a></li>
                        <li><a href="#courses">Courses</a></li>
                        <li><a href="#instructors">Instructors</a></li>
                        <li><a href="#events">Events</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h3>Contact Info</h3>
                    <ul class="contact-info">
                        <li><i class="fas fa-map-marker-alt"></i> 123 Education St, Learning City</li>
                        <li><i class="fas fa-phone"></i> +1 (555) 123-4567</li>
                        <li><i class="fas fa-envelope"></i> info@scholar.com</li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2023 Scholar. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/ai-assistant.js"></script>
</body>
</html>