document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('follow-btn');
    if (!btn) return;

    btn.addEventListener('click', () => {
        const isFollowing = btn.dataset.following === '1';

        const url = isFollowing
            ? btn.dataset.unfollowUrl
            : btn.dataset.followUrl;

        const method = isFollowing ? 'DELETE' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'followed') {
                btn.textContent = 'Unfollow';
                btn.classList.remove('button-follow');
                btn.classList.add('button-unfollow');
                btn.dataset.following = '1';
            } else if (data.status === 'unfollowed') {
                btn.textContent = 'Follow';
                btn.classList.remove('button-unfollow');
                btn.classList.add('button-follow');
                btn.dataset.following = '0';
            }
        })
        .catch(console.error);
    });
});
