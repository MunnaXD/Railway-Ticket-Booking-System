// script.js

// Function to pre-fill date and other information from the home page
document.addEventListener('DOMContentLoaded', function() {
    // Simulate getting date from localStorage (as if passed from the homepage)
    const storedDate = localStorage.getItem('travelDate') || 'Wed, 11 Dec 24';
    document.getElementById('travel-date').value = storedDate;
});

// Function to redirect to the booking page when a class is selected
function redirectToBooking(trainClass) {
    // Here you can pass the selected class to the booking page
    alert('Redirecting to booking page for class: ' + trainClass);
    // window.location.href = `booking.html?class=${trainClass}`;
}
