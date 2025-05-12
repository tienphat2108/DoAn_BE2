function expandPostInput() {
    const expandedSection = document.getElementById('create-post-expanded');
    expandedSection.style.display = 'block';
    document.getElementById('post-content').style.display = 'none';
}

function previewMedia() {
    const mediaInput = document.getElementById('post-media');
    const previewArea = document.getElementById('media-preview');
    previewArea.innerHTML = '';

    if (mediaInput.files && mediaInput.files[0]) {
        const file = mediaInput.files[0];
        const fileType = file.type;

        if (fileType.startsWith('image/')) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.style.maxWidth = '100%';
            img.style.borderRadius = '8px';
            previewArea.appendChild(img);
        } else if (fileType.startsWith('video/')) {
            const video = document.createElement('video');
            video.src = URL.createObjectURL(file);
            video.controls = true;
            video.style.maxWidth = '100%';
            video.style.borderRadius = '8px';
            previewArea.appendChild(video);
        }
    }
}

function getLocation() {
    if (!navigator.geolocation) {
        alert('Trình duyệt không hỗ trợ định vị!');
        return;
    }

    navigator.geolocation.getCurrentPosition(
        (position) => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            document.getElementById('post-latitude').value = lat;
            document.getElementById('post-longitude').value = lng;

            const geocoder = new google.maps.Geocoder();
            const latlng = { lat, lng };
            geocoder.geocode({ location: latlng }, (results, status) => {
                const locationDisplay = document.getElementById('location-display');
                if (status === 'OK' && results[0]) {
                    locationDisplay.innerText = `Đang ở gần ${results[0].formatted_address}`;
                } else {
                    locationDisplay.innerText = 'Không thể xác định vị trí';
                }
                locationDisplay.style.display = 'block';
            });
        },
        (error) => {
            alert('Không thể lấy vị trí: ' + error.message);
        }
    );
}

function createPost() {
    const title = document.getElementById('post-title').value.trim();
    const content = document.getElementById('post-content-expanded').value.trim() || document.getElementById('post-content').value.trim();
    const media = document.getElementById('post-media').files[0];
    const latitude = document.getElementById('post-latitude').value;
    const longitude = document.getElementById('post-longitude').value;

    if (!content) {
        alert('Vui lòng nhập nội dung bài viết!');
        return;
    }

    const formData = new FormData();
    formData.append('title', title || 'Bài viết không tiêu đề');
    formData.append('content', content);
    if (media) formData.append('media', media);
    if (latitude && longitude) {
        formData.append('latitude', latitude);
        formData.append('longitude', longitude);
    }

    fetch('/posts', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            resetPostForm();
            location.reload(); // Hoặc thêm bài viết vào DOM nếu muốn tránh reload
        } else {
            alert(data.message || 'Đăng bài thất bại!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Đã có lỗi xảy ra khi đăng bài!');
    });
}

function resetPostForm() {
    document.getElementById('post-title').value = '';
    document.getElementById('post-content-expanded').value = '';
    document.getElementById('post-media').value = '';
    document.getElementById('media-preview').innerHTML = '';
    document.getElementById('post-latitude').value = '';
    document.getElementById('post-longitude').value = '';
    document.getElementById('location-display').style.display = 'none';
    document.getElementById('create-post-expanded').style.display = 'none';
    document.getElementById('post-content').style.display = 'block';
}

function likePost(postId) {
    fetch(`/api/posts/${postId}/like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const likeButton = document.querySelector(`.post[data-post-id="${postId}"] .action[data-action="like"]`);
            likeButton.classList.toggle('active');
            const likeCount = likeButton.querySelector('span');
            likeCount.textContent = data.likes;
        }
    })
    .catch(error => console.error('Error:', error));
}

function showComments(postId) {
    const commentsSection = document.querySelector(`.post[data-post-id="${postId}"] .comments-section`);
    if (commentsSection) {
        commentsSection.classList.toggle('show');
    }
}

function addComment(postId) {
    const input = document.getElementById(`comment-input-${postId}`);
    const content = input.value.trim();

    if (!content) {
        alert('Vui lòng nhập nội dung bình luận!');
        return;
    }

    fetch(`/posts/${postId}/comments`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ content })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Không thể gửi bình luận!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Lỗi khi gửi bình luận!');
    });
}

function sharePost(postId) {
    const shareModal = document.createElement('div');
    shareModal.className = 'share-modal';
    shareModal.innerHTML = `
        <div class="share-modal-content">
            <h3>Chia sẻ bài viết</h3>
            <div class="share-options">
                <button onclick="shareToFacebook(${postId})">Facebook</button>
                <button onclick="shareToTwitter(${postId})">Twitter</button>
                <button onclick="shareToEmail(${postId})">Email</button>
            </div>
            <button class="close-modal" onclick="closeShareModal()">Đóng</button>
        </div>
    `;
    document.body.appendChild(shareModal);
}

function closeShareModal() {
    const modal = document.querySelector('.share-modal');
    if (modal) {
        modal.remove();
    }
}

function shareToFacebook(postId) {
    console.log('Sharing to Facebook:', postId);
}

function shareToTwitter(postId) {
    console.log('Sharing to Twitter:', postId);
}

function shareToEmail(postId) {
    console.log('Sharing via Email:', postId);
}

document.addEventListener('DOMContentLoaded', () => {
    const locationElements = document.querySelectorAll('.location-name');
    const geocoder = new google.maps.Geocoder();

    locationElements.forEach(element => {
        const lat = parseFloat(element.getAttribute('data-lat'));
        const lng = parseFloat(element.getAttribute('data-lng'));
        const latlng = { lat, lng };

        geocoder.geocode({ location: latlng }, (results, status) => {
            if (status === 'OK' && results[0]) {
                element.innerText = results[0].formatted_address;
            } else {
                element.innerText = 'Không xác định';
            }
        });
    });

    const userMenu = document.querySelector('.user-menu');
    if (userMenu) {
        userMenu.addEventListener('click', function() {
            const dropdownMenu = this.querySelector('.dropdown-menu');
            dropdownMenu.classList.toggle('show');
        });
    }

    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            menuItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });

    const postActions = document.querySelectorAll('.post-actions .action');
    postActions.forEach(action => {
        action.addEventListener('click', function() {
            const postId = this.closest('.post').dataset.postId;
            const actionType = this.dataset.action;
            
            if (actionType === 'like') {
                likePost(postId);
            } else if (actionType === 'comment') {
                showComments(postId);
            } else if (actionType === 'share') {
                sharePost(postId);
            }
        });
    });
});
