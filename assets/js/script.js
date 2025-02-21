function bookEvent(eventId) {
    fetch('/user/book_event.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `event_id=${eventId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Event booked successfully!');
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function cancelBooking(eventId) {
    if (confirm('Are you sure you want to cancel this booking?')) {
        fetch('/user/cancel_booking.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `event_id=${eventId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Booking cancelled successfully!');
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}