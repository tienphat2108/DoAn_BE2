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

// XÓA BÀI VIẾT
function deletePost(postId) {
    if (confirm('Bạn có chắc chắn muốn xóa bài viết này?')) {
        var form = document.getElementById('delete-form-' + postId);
        if (form) {
            form.submit();
        } else {
            alert('Không tìm thấy form xóa cho bài viết này!');
        }
    }
}

// CHỈNH SỬA BÀI VIẾT
function editPost(postId) {
    var modal = document.getElementById('editPostModal');
    var form = document.getElementById('edit-post-form');
    var titleInput = document.getElementById('edit-post-title');
    var post = document.getElementById('post-' + postId);
    
    if (modal && form && titleInput && post) {
        var title = post.querySelector('.post-body h4').innerText;
        titleInput.value = title;
        form.action = '/posts/' + postId;
        modal.style.display = 'flex';
    }
}

function hideEditModal() {
    var modal = document.getElementById('editPostModal');
    if (modal) modal.style.display = 'none';
}

// Đảm bảo chỉ gán onsubmit một lần
document.addEventListener('DOMContentLoaded', function() {
    const editForm = document.getElementById('edit-post-form');
    if (editForm) {
        editForm.onsubmit = function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('_method', 'PUT');
            const postId = this.action.split('/').pop();
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
            .then(async res => {
                let data;
                try {
                    data = await res.json();
                } catch (e) {
                    // Nếu không phải JSON (có thể là redirect HTML), coi như thành công
                    document.querySelector(`#post-${postId} .post-body h4`).innerText = document.getElementById('edit-post-title').value;
                    hideEditModal();
                    return;
                }
                if (data && data.success) {
                    document.querySelector(`#post-${postId} .post-body h4`).innerText = data.title || document.getElementById('edit-post-title').value;
                    hideEditModal();
                } else {
                    alert((data && data.message) || 'Chỉnh sửa thất bại!');
                }
            })
            .catch(() => {
                // Nếu fetch lỗi, vẫn thử cập nhật DOM (phòng trường hợp backend redirect)
                document.querySelector(`#post-${postId} .post-body h4`).innerText = document.getElementById('edit-post-title').value;
                hideEditModal();
            });
        };
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const locationElements = document.querySelectorAll('.location-name');
    locationElements.forEach(element => {
        const lat = parseFloat(element.getAttribute('data-lat'));
        const lng = parseFloat(element.getAttribute('data-lng'));
        fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
            .then(res => res.json())
            .then(data => {
                let shortAddr = '';
                if (data.address) {
                    if (data.address.city) shortAddr += data.address.city;
                    if (data.address.town) shortAddr += data.address.town;
                    if (data.address.village) shortAddr += data.address.village;
                    if (data.address.state) shortAddr += (shortAddr ? ', ' : '') + data.address.state;
                    if (!shortAddr && data.display_name) shortAddr = data.display_name.split(',').slice(0,2).join(', ');
                }
                element.innerText = shortAddr || 'Không xác định';
            })
            .catch(() => {
                element.innerText = 'Không xác định';
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
            } else if (actionType === 'delete') {
                deletePost(postId);
            } else if (actionType === 'edit') {
                editPost(postId);
            }
        });
    });
});

// Đảm bảo các hàm này là global để gọi được từ HTML
window.togglePostMenu = function(btn) {
    // Đóng tất cả menu khác
    document.querySelectorAll('.post-menu').forEach(menu => menu.classList.remove('active'));
    var menu = btn.nextElementSibling;
    menu.classList.toggle('active');
};
window.editPost = editPost;
window.deletePost = deletePost;
window.hideEditModal = hideEditModal;
window.showLogoutModal = function() {
    var modal = document.getElementById('logoutModal');
    if (modal) modal.style.display = 'flex';
};
window.hideLogoutModal = function() {
    var modal = document.getElementById('logoutModal');
    if (modal) modal.style.display = 'none';
};
window.confirmLogout = function() {
    var form = document.getElementById('logout-form');
    if (form) form.submit();
};

// Leaflet (OpenStreetMap) chọn vị trí
let leafletMap, leafletMarker, selectedLatLng = null, selectedAddress = '';
window.showLocationModal = function() {
    document.getElementById('locationModal').style.display = 'flex';
    setTimeout(() => {
        if (!leafletMap) {
            leafletMap = L.map('leaflet-map').setView([10.762622, 106.660172], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(leafletMap);
            leafletMarker = L.marker([10.762622, 106.660172], {draggable:true}).addTo(leafletMap);
            leafletMap.on('click', function(e) {
                setLeafletLocation(e.latlng.lat, e.latlng.lng);
            });
            leafletMarker.on('dragend', function(e) {
                const pos = e.target.getLatLng();
                setLeafletLocation(pos.lat, pos.lng);
            });
        }
    }, 100);
};
window.hideLocationModal = function() {
    document.getElementById('locationModal').style.display = 'none';
};
function setLeafletLocation(lat, lng) {
    selectedLatLng = { lat, lng };
    leafletMarker.setLatLng(selectedLatLng);
    leafletMap.panTo(selectedLatLng);
    // Gọi Nominatim để lấy địa chỉ ngắn gọn
    fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
        .then(res => res.json())
        .then(data => {
            let shortAddr = '';
            if (data.address) {
                if (data.address.city) shortAddr += data.address.city;
                if (data.address.town) shortAddr += data.address.town;
                if (data.address.village) shortAddr += data.address.village;
                if (data.address.state) shortAddr += (shortAddr ? ', ' : '') + data.address.state;
                if (!shortAddr && data.display_name) shortAddr = data.display_name.split(',').slice(0,2).join(', ');
            }
            selectedAddress = shortAddr || 'Không xác định địa chỉ';
            document.getElementById('modal-location-address').innerText = selectedAddress;
        })
        .catch(() => {
            selectedAddress = '';
            document.getElementById('modal-location-address').innerText = 'Không xác định địa chỉ';
        });
}
window.confirmLocation = function() {
    if (!selectedLatLng) return;
    document.getElementById('post-latitude').value = selectedLatLng.lat;
    document.getElementById('post-longitude').value = selectedLatLng.lng;
    document.getElementById('location-display').innerText = selectedAddress ? `Địa điểm: ${selectedAddress}` : 'Đã chọn vị trí';
    document.getElementById('location-display').style.display = 'block';
    hideLocationModal();
};
