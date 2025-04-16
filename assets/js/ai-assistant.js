$(document).ready(function() {
    // AI Assistant functionality
    $('#ask-ai').click(function() {
        askAI();
    });
    
    // Allow pressing Enter to submit question
    $('#ai-question').keypress(function(e) {
        if (e.which === 13) {
            askAI();
            return false;
        }
    });
});

// Function to ask AI assistant
function askAI() {
    const question = $('#ai-question').val().trim();
    
    if (question === '') {
        return;
    }
    
    // Show loading indicator
    $('#ai-response').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Processing your question...</div>');
    
    $.ajax({
        url: 'includes/ai_assistant.php',
        type: 'POST',
        data: { question: question },
        success: function(response) {
            $('#ai-response').html(`
                <div class="ai-message">
                    <p><strong>You asked:</strong> ${question}</p>
                    <p><strong>AI Assistant:</strong> ${response}</p>
                </div>
            `);
            $('#ai-question').val('');
        },
        error: function(xhr, status, error) {
            console.error('Error with AI assistant:', error);
            $('#ai-response').html('<div class="text-danger">Sorry, I couldn\'t process your question. Please try again later.</div>');
        }
    });
}

// Function to suggest related courses based on user behavior
function suggestCourses() {
    $.ajax({
        url: 'includes/suggest_courses.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data.length > 0) {
                let suggestionsHTML = '<h4>Recommended For You</h4><div class="row">';
                
                data.forEach(function(course) {
                    suggestionsHTML += `
                        <div class="col-md-4">
                            <div class="course-card">
                                <div class="course-image">
                                    <img src="${course.image}" alt="${course.title}">
                                </div>
                                <div class="course-content">
                                    <span class="course-tag">${course.category}</span>
                                    <h3 class="course-title">${course.title}</h3>
                                    <a href="course-details.php?id=${course.id}" class="btn btn-sm btn-primary mt-2">View Course</a>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                suggestionsHTML += '</div>';
                
                // Add suggestions to the page
                if ($('#course-suggestions').length) {
                    $('#course-suggestions').html(suggestionsHTML);
                } else {
                    $('#courses-container').after('<div id="course-suggestions" class="mt-5">' + suggestionsHTML + '</div>');
                }
            }
        }
    });
}

// Load course suggestions when page is loaded
$(window).on('load', function() {
    setTimeout(suggestCourses, 2000); // Delay to allow main content to load first
});