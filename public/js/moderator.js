function toggleCheckmark(newsId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch('/news/' + newsId + '/checkmark', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Find the button 
            const checkmarkBtn = document.querySelector(`.checkmark-btn[onclick="toggleCheckmark(${newsId})"]`);
            
            if (checkmarkBtn) {
                if (data.hasCheckmark) {
                    checkmarkBtn.classList.add('active');
                } else {
                    checkmarkBtn.classList.remove('active');
                }
            }
        } else {
            alert('Error: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to toggle checkmark.');
    });
}

function submitTimeout(userId) {
    const duration = document.getElementById('timeout-duration').value;
    const reason = document.getElementById('timeout-reason').value.trim();
    
    // Validate inputs
    if (!duration || duration < 1 || duration > 168) {
        alert('Please enter a valid duration between 1 and 168 hours.');
        return;
    }
    
    if (!reason) {
        alert('Please enter a reason for the timeout.');
        return;
    }
    
    const durationInt = parseInt(duration);
    if (isNaN(durationInt)) {
        alert('Please enter a valid number for duration.');
        return;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch('/user/' + userId + '/timeout', {
        method: 'POST',
        headers:{
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            duration_hours: durationInt,
            reason: reason
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Timeout applied successfully!');
            window.location.href = '/user/' + userId;
        } else {
            alert('Error: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to apply timeout.');
    });
}