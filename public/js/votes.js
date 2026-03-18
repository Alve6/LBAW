function vote(newsId, value) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch('/news/' + newsId + '/vote', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ value: value })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('upvote-count-' + newsId).textContent = data.upvotes;
            document.getElementById('downvote-count-' + newsId).textContent = data.downvotes;
            
            const upvoteBtn = document.querySelector(`.upvote[onclick="vote(${newsId}, 1)"]`);
            const downvoteBtn = document.querySelector(`.downvote[onclick="vote(${newsId}, -1)"]`);
            
            const upvoteImg = upvoteBtn.querySelector('.vote-icon');
            const downvoteImg = downvoteBtn.querySelector('.vote-icon');
            
            upvoteBtn.classList.remove('active');
            downvoteBtn.classList.remove('active');
            
            if (data.userVote === 1) {
                upvoteBtn.classList.add('active');
                upvoteImg.src = '/images/icones/gostar1.png';
                downvoteImg.src = '/images/icones/nao-gosto.png';
            } else if (data.userVote === -1) {
                downvoteBtn.classList.add('active');
                upvoteImg.src = '/images/icones/gostar.png';
                downvoteImg.src = '/images/icones/nao-gosto1.png';
            } else {
                upvoteImg.src = '/images/icones/gostar.png';
                downvoteImg.src = '/images/icones/nao-gosto.png';
            }
        } else {
            alert('Error: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to vote.');
    });
}

function voteComment(commentId, value) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch('/comment/' + commentId + '/vote', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ value: value })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('comment-upvote-count-' + commentId).textContent = data.upvotes;
            document.getElementById('comment-downvote-count-' + commentId).textContent = data.downvotes;
            
            const upvoteBtn = document.querySelector(`.vote-btn.upvote[onclick="voteComment(${commentId}, 1)"]`);
            const downvoteBtn = document.querySelector(`.vote-btn.downvote[onclick="voteComment(${commentId}, -1)"]`);
            
            if (upvoteBtn && downvoteBtn) {
                const upvoteImg = upvoteBtn.querySelector('.vote-icon');
                const downvoteImg = downvoteBtn.querySelector('.vote-icon');
                
                upvoteBtn.classList.remove('active');
                downvoteBtn.classList.remove('active');
                
                if (data.userVote === 1) {
                    upvoteBtn.classList.add('active');
                    upvoteImg.src = '/images/icones/gostar1.png';
                    downvoteImg.src = '/images/icones/nao-gosto.png';
                } else if (data.userVote === -1) {
                    downvoteBtn.classList.add('active');
                    upvoteImg.src = '/images/icones/gostar.png';
                    downvoteImg.src = '/images/icones/nao-gosto1.png';
                } else {
                    upvoteImg.src = '/images/icones/gostar.png';
                    downvoteImg.src = '/images/icones/nao-gosto.png';
                }
            }
        } else {
            alert('Error: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to vote on comment.');
    });
}
