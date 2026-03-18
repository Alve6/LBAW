function submitComment(newsId) {
    const input = document.getElementById('comment-input-' + newsId);
    const content = input.value.trim();
    
    if (!content) {
        alert('Please write a comment');
        return;
    }
    
    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch('/news/' + newsId + '/comment', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ content: content })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            input.value = '';
            location.reload();
        } else {
            alert('Error: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to post comment. Check console for details.');
    });
}

function editComment(commentId) {
    const commentText = document.getElementById('comment-text-' + commentId);
    const currentContent = commentText.textContent;
    
    if (document.getElementById('edit-input-' + commentId)) {
        return;
    }
    
    // Create input field
    const input = document.createElement('input');
    input.type = 'text';
    input.id = 'edit-input-' + commentId;
    input.className = 'comment-edit-input';
    input.value = currentContent;
    
    // Create save button
    const saveBtn = document.createElement('button');
    saveBtn.textContent = 'Save';
    saveBtn.className = 'comment-action-btn';
    saveBtn.style.marginLeft = '5px';
    
    // Create cancel button
    const cancelBtn = document.createElement('button');
    cancelBtn.textContent = 'Cancel';
    cancelBtn.className = 'comment-action-btn';
    cancelBtn.style.marginLeft = '5px';
    
    commentText.style.display = 'none';
    
    // Insert input and buttons after the text
    commentText.parentNode.insertBefore(input, commentText.nextSibling);
    input.parentNode.insertBefore(saveBtn, input.nextSibling);
    input.parentNode.insertBefore(cancelBtn, saveBtn.nextSibling);
    
    input.focus();
    
    const cancelEdit = () => {
        commentText.style.display = 'inline';
        input.remove();
        saveBtn.remove();
        cancelBtn.remove();
    };
    
    // Save function
    const saveEdit = () => {
        const newContent = input.value.trim();
        
        if (!newContent) {
            alert('Comment cannot be empty');
            return;
        }
        
        if (newContent === currentContent) {
            cancelEdit();
            return;
        }
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch('/comment/' + commentId + '/edit', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ content: newContent })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                commentText.textContent = newContent;
                cancelEdit();
            } else {
                alert('Error: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to edit comment.');
        });
    };
    
    saveBtn.onclick = saveEdit;
    cancelBtn.onclick = cancelEdit;
    
    input.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            saveEdit();
        }
    });
    
    input.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            cancelEdit();
        }
    });
}

function deleteComment(commentId) {
    if (!confirm('Are you sure you want to delete this comment?')) {
        return;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch('/comment/' + commentId + '/delete', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to delete comment.');
    });
}



