$(document).ready(function() {
    // Load courses from database
    loadCourses();
    
    // Load instructors from database
    loadInstructors();
    
    // Load events from database
    loadEvents();
    
    // Form submission for contact form
    $('#contact-form').submit(function(e) {
        e.preventDefault();
        submitContactForm();
    });
    
    // Form submission for question form
    $('#question-form').submit(function(e) {
        e.preventDefault();
        submitQuestion();
    });
    
    // Smooth scrolling for anchor links
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        
        var target = this.hash;
        var $target = $(target);
        
        $('html, body').animate({
            'scrollTop': $target.offset().top - 70
        }, 800, 'swing');
    });
});

// Function to load courses from database
function loadCourses() {
    $.ajax({
        url: 'includes/get_courses.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            let coursesHTML = '';
            
            if (data.length > 0) {
                data.forEach(function(course) {
                    coursesHTML += `
                        <div class="col-md-4">
                            <div class="course-card">
                                <div class="course-image">
                                    <img src="${course.image}" alt="${course.title}">
                                </div>
                                <div class="course-content">
                                    <span class="course-tag">${course.category}</span>
                                    <h3 class="course-title">${course.title}</h3>
                                    <p>${course.description.substring(0, 100)}...</p>
                                    <div class="course-info">
                                        <span><i class="fas fa-user"></i> ${course.instructor}</span>
                                        <span><i class="fas fa-star"></i> ${course.rating}</span>
                                    </div>
                                    <a href="course-details.php?id=${course.id}" class="btn btn-primary mt-3">View Details</a>
                                </div>
                            </div>
                        </div>
                    `;
                });
            } else {
                coursesHTML = '<div class="col-12"><p class="text-center">No courses available at the moment.</p></div>';
            }
            
            $('#courses-container').html(coursesHTML);
        },
        error: function(xhr, status, error) {
            console.error('Error loading courses:', error);
            $('#courses-container').html('<div class="col-12"><p class="text-center">Failed to load courses. Please try again later.</p></div>');
        }
    });
}

// Function to load instructors from database
function loadInstructors() {
    $.ajax({
        url: 'includes/get_instructors.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            let instructorsHTML = '';
            
            if (data.length > 0) {
                data.forEach(function(instructor) {
                    instructorsHTML += `
                        <div class="col-md-3">
                            <div class="instructor-card">
                                <div class="instructor-image">
                                    <img src="${instructor.image}" alt="${instructor.name}">
                                </div>
                                <div class="instructor-info">
                                    <h3>${instructor.name}</h3>
                                    <p>${instructor.specialty}</p>
                                    <div class="social-links">
                                        <a href="${instructor.facebook}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                        <a href="${instructor.twitter}" target="_blank"><i class="fab fa-twitter"></i></a>
                                        <a href="${instructor.linkedin}" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
            } else {
                instructorsHTML = '<div class="col-12"><p class="text-center">No instructors available at the moment.</p></div>';
            }
            
            $('#instructors-container').html(instructorsHTML);
        },
        error: function(xhr, status, error) {
            console.error('Error loading instructors:', error);
            $('#instructors-container').html('<div class="col-12"><p class="text-center">Failed to load instructors. Please try again later.</p></div>');
        }
    });
}

// Function to load events from database
function loadEvents() {
    $.ajax({
        url: 'includes/get_events.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            let eventsHTML = '';
            
            if (data.length > 0) {
                data.forEach(function(event) {
                    eventsHTML += `
                        <div class="col-md-4">
                            <div class="event-card">
                                <div class="event-image">
                                    <img src="${event.image}" alt="${event.title}">
                                </div>
                                <div class="event-content">
                                    <div class="event-date">
                                        <i class="far fa-calendar-alt"></i> ${event.date} | ${event.time}
                                    </div>
                                    <h3 class="event-title">${event.title}</h3>
                                    <div class="event-location">
                                        <i class="fas fa-map-marker-alt"></i> ${event.location}
                                    </div>
                                    <a href="event-details.php?id=${event.id}" class="btn btn-primary mt-3">Join Event</a>
                                </div>
                            </div>
                        </div>
                    `;
                });
            } else {
                eventsHTML = '<div class="col-12"><p class="text-center">No upcoming events at the moment.</p></div>';
            }
            
            $('#events-container').html(eventsHTML);
        },
        error: function(xhr, status, error) {
            console.error('Error loading events:', error);
            $('#events-container').html('<div class="col-12"><p class="text-center">Failed to load events. Please try again later.</p></div>');
        }
    });
}

// Function to submit contact form
function submitContactForm() {
    const formData = {
        name: $('#contact-form input[placeholder="Your Name"]').val(),
        email: $('#contact-form input[placeholder="Your Email"]').val(),
        message: $('#contact-form textarea').val()
    };
    
    $.ajax({
        url: 'includes/submit_contact.php',
        type: 'POST',
        data: formData,
        success: function(response) {
            alert('Thank you for your message! We will get back to you soon.');
            $('#contact-form')[0].reset();
        },
        error: function(xhr, status, error) {
            console.error('Error submitting contact form:', error);
            alert('Failed to submit your message. Please try again later.');
        }
    });
}

// Function to submit question
function submitQuestion() {
    const formData = {
        name: $('#question-form input[placeholder="Your Name"]').val(),
        email: $('#question-form input[placeholder="Your Email"]').val(),
        question: $('#question-form textarea').val()
    };
    
    $.ajax({
        url: 'includes/submit_question.php',
        type: 'POST',
        data: formData,
        success: function(response) {
            alert('Thank you for your question! We will get back to you soon.');
            $('#question-form')[0].reset();
        },
        error: function(xhr, status, error) {
            console.error('Error submitting question:', error);
            alert('Failed to submit your question. Please try again later.');
        }
    });
}

// Real-time user count display
function updateUserCount() {
    $.ajax({
        url: 'includes/get_active_users.php',
        type: 'GET',
        success: function(count) {
            $('.active-users-count').text(count);
        }
    });
}

// Update active users count every 30 seconds
setInterval(updateUserCount, 30000);
updateUserCount(); // Initial call