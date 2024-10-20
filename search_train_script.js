// script.js

// Function to pre-fill date and other information from the home page
document.addEventListener('DOMContentLoaded', function() {
    // Simulate getting date from localStorage (as if passed from the homepage)
    const storedDate = localStorage.getItem('travelDate') || 'Wed, 11 Dec 24';
    document.getElementById('travel-date').value = storedDate;
});

// Function to redirect to the booking page when a class is selected
function redirectToBooking(classType) {
    // Optionally, you can store classType in local storage or pass it to the next page
    // localStorage.setItem('selectedClass', classType);
    
    // Redirect to the booking page
    window.location.href = 'bookticket.html';
}

