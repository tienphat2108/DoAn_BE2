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
    fetch(`/posts/${postId}/like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const likeButton = document.querySelector(`.like-btn[data-post-id="${postId}"]`);
            const likeCount = document.querySelector(`#like-count-${postId}`);
            const currentCount = parseInt(likeCount.textContent) || 0;
            
            // Toggle trạng thái like
            const isLiked = likeButton.classList.toggle('liked');
            
            // Cập nhật số lượt like
            if (isLiked) {
                // Nếu đang like (thêm like)
                likeCount.textContent = currentCount + 1;
            } else {
                // Nếu đang unlike (bớt like)
                likeCount.textContent = Math.max(0, currentCount - 1);
            }
        }
    })
    .catch(error => console.error('Error:', error));
}

function toggleComments(postId) {
    console.log('Toggling comments for post:', postId); // Log khi hàm được gọi
    const commentsSection = document.getElementById(`comments-${postId}`);
    if (commentsSection) {
        console.log('Comments section found.', commentsSection); // Log nếu tìm thấy phần comments
        const currentDisplay = commentsSection.style.display;
        commentsSection.style.display = currentDisplay === 'none' || currentDisplay === '' ? 'block' : 'none';
        console.log('Comments section display set to:', commentsSection.style.display); // Log trạng thái display mới
    } else {
        console.error('Comments section not found for post:', postId); // Log nếu không tìm thấy phần comments
    }
}

function addComment(postId, form) {
    console.log('Executing addComment for post:', postId, 'with form:', form); // Log khi hàm addComment bắt đầu
    const formData = new FormData(form);
    const content = formData.get('content');
    
    if (!content.trim()) {
        alert('Nội dung bình luận không được để trống!');
        console.log('Comment content is empty.'); // Log nếu nội dung trống
        return;
    }

    console.log('Sending comment to /comments with content:', content); // Log trước khi fetch
    fetch('/comments', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            post_id: formData.get('post_id'),
            content: content
        })
    })
    .then(response => {
         console.log('Received response for addComment. Status:', response.status); // Log status response
         return response.json();
     })
    .then(data => {
        console.log('addComment response data:', data); // Log dữ liệu phản hồi
        console.log('Comment ID from response:', data.comment_id); // Log cụ thể comment_id
        if (data.success) {
            console.log('Comment added successfully.'); // Log nếu thành công
            // Tìm phần tử comments của bài post
            const commentsSection = document.getElementById(`comments-${postId}`);
            const commentForm = form;
            
            // Tạo HTML cho comment mới
            const newComment = document.createElement('div');
            newComment.className = 'comment';
            // Sử dụng data.comment_id để lấy ID từ phản hồi server
            const newCommentId = data.comment_id || data.id; // Ưu tiên comment_id, fallback là id
            newComment.id = `comment-${newCommentId}`;
            
            // Lấy thông tin người dùng hiện tại từ data attribute trên body
            const userName = document.body.dataset.currentUserName || 'Ẩn danh';
            
            // Tạo nội dung comment
            newComment.innerHTML = `
                <div class="comment-header">
                    <strong>${userName}</strong>
                    <span style="color: #888; font-size: 12px; margin-left: 8px;">Vừa xong</span>
                </div>
                <div class="comment-body">
                    <p class="comment-content">${content}</p>
                    <button class="edit-comment-btn" data-comment-id="${newCommentId}">Sửa</button>
                </div>
                <form class="edit-comment-form" id="edit-comment-form-${newCommentId}" style="display:none; margin-top:5px;">
                    <input type="text" class="edit-comment-input" value="${content}" style="width:70%;padding:3px;">
                    <button type="button" class="save-comment-btn" data-comment-id="${newCommentId}">Lưu</button>
                    <button type="button" class="cancel-comment-btn" data-comment-id="${newCommentId}">Hủy</button>
                </form>
            `;
            
            // Thêm comment mới vào trước form comment
            // Kiểm tra xem commentsSection có tồn tại không trước khi append
            if (commentsSection) {
                 commentsSection.insertBefore(newComment, commentForm);
             }
            
            // Cập nhật số lượng comment
            const commentCount = document.querySelector(`#comment-count-${postId}`);
            const currentCount = parseInt(commentCount.textContent) || 0;
            commentCount.textContent = currentCount + 1;
            
            // Xóa nội dung trong form
            form.querySelector('textarea').value = '';
        } else {
            alert(data.message || 'Không thể gửi bình luận!');
            console.error('Failed to add comment:', data.message); // Log lỗi nếu server báo thất bại
        }
    })
    .catch(error => {
        console.error('Error submitting comment:', error); // Log lỗi khi fetch thất bại
        alert('Lỗi khi gửi bình luận!');
    });
}

function sharePost(postId) {
    // Tìm phần tử bài viết cha để lấy thông tin
    const postElement = document.getElementById(`post-${postId}`);
    if (!postElement) {
        console.error('Post element not found for sharing!');
        return;
    }

    const postTitleElement = postElement.querySelector('.post-body h4');
    const postContentElement = postElement.querySelector('.post-body p');

    const postTitle = postTitleElement ? postTitleElement.textContent : 'Bài viết';
    const postContent = postContentElement ? postContentElement.textContent : '';
    const postUrl = `${window.location.origin}/posts/${postId}`; // Giả định cấu trúc URL bài viết

    // Sử dụng Web Share API nếu có
    if (navigator.share) {
        navigator.share({
            title: postTitle,
            text: postContent.substring(0, 100) + '...', // Lấy 100 ký tự đầu làm text chia sẻ
            url: postUrl,
        })
        .then(() => console.log('Successful share'))
        .catch((error) => console.error('Error sharing:', error));
    } else {
        // Fallback: Sao chép link vào clipboard
        navigator.clipboard.writeText(postUrl)
            .then(() => {
                alert('Đã sao chép liên kết bài viết vào clipboard!');
            })
            .catch(err => {
                console.error('Could not copy text: ', err);
                alert('Không thể sao chép liên kết bài viết.');
            });
    }

    // Xóa bỏ phần cập nhật số lượt chia sẻ trên giao diện như yêu cầu
    // const shareCount = document.querySelector(`#share-count-${postId}`);
    // if (shareCount) { shareCount.textContent = (parseInt(shareCount.textContent) || 0) + 1; }
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

// Use event delegation for post actions (like, comment, share)
document.body.addEventListener('click', function(event) {
    const target = event.target;
    // Tìm phần tử nút like, comment, share hoặc view count được click
    // Sử dụng closest để đảm bảo bắt được cả khi click vào icon bên trong nút
    const clickedLikeButton = target.closest('.like-btn');
    const clickedCommentButton = target.closest('.toggle-comments-btn');
    const clickedShareButton = target.closest('.share-btn');
    // View count is usually not interactive, but include for completeness if needed later
    // const clickedViewCount = target.closest('.view-count');

    // Xử lý click cho nút Like
    if (clickedLikeButton) {
        event.preventDefault();
        const postElement = clickedLikeButton.closest('.post');
        if (postElement) {
            const postId = postElement.id.replace('post-', '');
            if (postId) {
                console.log('Delegated Like button clicked for post:', postId);
                likePost(postId);
            }
        }
    }

    // Xử lý click cho nút Comment
    if (clickedCommentButton) {
        event.preventDefault();
        const postElement = clickedCommentButton.closest('.post');
        if (postElement) {
            const postId = postElement.id.replace('post-', '');
            if (postId) {
                 console.log('Delegated Comment button clicked for post:', postId);
                toggleComments(postId);
            }
        }
    }

    // Xử lý click cho nút Share
    if (clickedShareButton) {
         event.preventDefault();
         const postElement = clickedShareButton.closest('.post');
         if (postElement) {
             const postId = postElement.id.replace('post-', '');
             if (postId) {
                  console.log('Delegated Share button clicked for post:', postId);
                 sharePost(postId);
             }
         }
    }
    
    // Xử lý các click khác không phải nút hành động bài viết ở đây nếu cần

}); // Dòng kết thúc bộ lắng nghe sự kiện click cho hành động bài viết

// Use event delegation for comment actions (edit, save, cancel)
document.body.addEventListener('click', function(event) {
    const target = event.target;
    
    // Handle Edit button click
    // Find the closest edit button to the clicked element
    const clickedEditButton = target.closest('.edit-comment-btn');
    if (clickedEditButton) {
        const commentId = clickedEditButton.dataset.commentId; // Lấy commentId từ data attribute
        if (commentId) {
            console.log('Delegated Edit comment button clicked for comment:', commentId);
            showEditCommentForm(commentId);
        } else {
             console.error('Edit comment button clicked but commentId not found on button.');
        }
    }

    // Handle Save button click
    // Find the closest save button to the clicked element
    const clickedSaveButton = target.closest('.save-comment-btn');
    if (clickedSaveButton) {
        const commentId = clickedSaveButton.dataset.commentId; // Lấy commentId từ data attribute
        if (commentId) {
            console.log('Delegated Save comment button clicked for comment:', commentId);
            submitEditComment(commentId, clickedSaveButton); 
        } else {
            console.error('Save comment button clicked but commentId not found on button.');
        }
    }

    // Handle Cancel button click
    // Find the closest cancel button to the clicked element
    const clickedCancelButton = target.closest('.cancel-comment-btn');
    if (clickedCancelButton) {
         const commentId = clickedCancelButton.dataset.commentId; // Lấy commentId từ data attribute
        if (commentId) {
            console.log('Delegated Cancel comment button clicked for comment:', commentId);
            cancelEditComment(commentId);
        } else {
             console.error('Cancel comment button clicked but commentId not found on button.');
        }
    }
});

// Re-add DOMContentLoaded listener for other initializations if needed
document.addEventListener('DOMContentLoaded', () => {
    // Existing initializations like location elements, user menu, menu items
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

    // Keep the event delegation for the delete action from the post menu (Already exists and working)
    document.body.addEventListener('click', function(event) {
        const target = event.target;
        if (target.matches('.post-menu div[data-action="delete"]')) {
            const postElement = target.closest('.post');
            if (postElement) {
                const postId = postElement.id.replace('post-', '');
                if (postId) {
                    deletePost(postId);
                } else {
                    console.error('Post ID not found for delete action.');
                }
            }
        }
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

// Bell notification logic
const bellIcon = document.getElementById('bell-icon');
console.log('Attempting to find bell icon. Element found:', bellIcon);
const bellBadge = document.getElementById('bell-badge');
const bellDropdown = document.getElementById('bell-dropdown');
const bellList = document.getElementById('bell-list');

console.log('Bell notification elements:', { bellIcon, bellBadge, bellDropdown, bellList });

// Function to fetch and display unread notifications count
window.fetchUnreadNotificationsCount = function(unreadNotificationsRoute, bellBadgeElement) {
    console.log('Fetching unread notifications count...');
    console.log('Fetching unread notifications from:', unreadNotificationsRoute);
    fetch(unreadNotificationsRoute)
        .then(response => {
             console.log('Unread notifications count response status:', response.status);
             if (!response.ok) {
                 throw new Error(`HTTP error! status: ${response.status}`);
             }
             return response.json();
         })
        .then(data => {
            console.log('Unread notifications count data:', data);
            if (data.length > 0) {
                bellBadgeElement.textContent = data.length;
                bellBadgeElement.style.display = 'block';
            } else {
                bellBadgeElement.style.display = 'none';
            }
        })
        .catch(error => console.error('Error fetching unread notifications:', error));
};

// Function to fetch and display notifications in the dropdown
window.fetchAndDisplayNotifications = function(unreadNotificationsRoute, bellListElement, markAsReadNotificationsRoute, bellBadgeElement) {
     console.log('Fetching and displaying notifications...');
     console.log('Fetching unread notifications from:', unreadNotificationsRoute);
     fetch(unreadNotificationsRoute) // Fetch UNREAD notifications for display
         .then(response => {
             console.log('Fetch and display notifications response status:', response.status);
             if (!response.ok) {
                 throw new Error(`HTTP error! status: ${response.status}`);
             }
             return response.json();
         })
        .then(data => {
            console.log('Fetch and display notifications data:', data);
            bellListElement.innerHTML = ''; // Clear previous notifications
            console.log('Number of unread notifications received:', data.length);
            if (data.length > 0) {
                data.forEach(notification => {
                    const notificationItem = document.createElement('div');
                    // Assuming the notification data has a 'data' object with a 'message'
                    const message = notification.data.message || 'New notification';
                    const sentTime = new Date(notification.created_at).toLocaleString('vi-VN');
                    notificationItem.innerHTML = `
                        <p style="margin:0;font-size:0.9em;">${message}</p>
                        <small style="color:#888;">${sentTime}</small>
                    `;
                    notificationItem.style.padding = '10px 15px';
                    notificationItem.style.borderBottom = '1px solid #eee';
                    bellListElement.appendChild(notificationItem);
                });
                // Mark all displayed UNREAD notifications as read after a short delay
                 setTimeout(() => markAllAsRead(markAsReadNotificationsRoute, bellBadgeElement), 1000);
            } else {
                console.log('No unread notifications to display.');
                bellListElement.innerHTML = '<div style="padding:10px 15px;text-align:center;color:#888;">Không có thông báo mới.</div>';
            }
        })
        .catch(error => {
            console.error('Error fetching notifications:', error);
            bellListElement.innerHTML = '<div style="padding:10px 15px;text-align:center;color:#888;">Không thể tải thông báo</div>';
        });
};

// Function to mark all unread notifications as read
window.markAllAsRead = function(markAsReadNotificationsRoute, bellBadgeElement) {
     console.log('Marking all notifications as read...');
     fetch(markAsReadNotificationsRoute, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
         console.log('Mark all as read response status:', response.status);
         if (!response.ok) {
             throw new Error(`HTTP error! status: ${response.status}`);
         }
         return response.json();
     })
    .then(data => {
         console.log('Mark all as read data:', data.message); // Log success message
         if (bellBadgeElement) bellBadgeElement.style.display = 'none'; // Hide badge after marking as read
    })
    .catch(error => console.error('Error marking notifications as read:', error));
};

// Setup function to be called from Blade template (Adjusted parameters and logic)
window.setupBellNotificationListener = function(bellIconElement, bellDropdownElement, bellListElement, unreadNotificationsRoute, markAsReadNotificationsRoute, bellBadgeElement) {
    console.log('Setting up bell notification listener (reverted logic).', { bellIconElement, bellDropdownElement, bellListElement, unreadNotificationsRoute, markAsReadNotificationsRoute, bellBadgeElement });
    if (!bellIconElement || !bellDropdownElement || !bellListElement) {
        console.error('Bell notification elements not found!');
        return;
    }

    bellIconElement.addEventListener('click', function() {
        console.log('Bell icon clicked!');
        console.log('Current bellDropdown display style:', bellDropdownElement.style.display);
        // Check if the dropdown is currently hidden
        if (bellDropdownElement.style.display === 'none' || bellDropdownElement.style.display === '') {
             // Fetch and display ONLY unread notifications when opening dropdown
             fetchAndDisplayNotifications(unreadNotificationsRoute, bellListElement, markAsReadNotificationsRoute, bellBadgeElement);
             bellDropdownElement.style.display = 'block';
         } else {
            bellDropdownElement.style.display = 'none';
         }
    });

    // Hide dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!bellIconElement.parentElement.contains(e.target) && !bellDropdownElement.contains(e.target)) {
             bellDropdownElement.style.display = 'none';
        }
    });
};

// Initial fetch for unread count (already exists, keeping it)
window.fetchInitialUnreadCount = function(unreadNotificationsRoute, bellBadgeElement) {
     console.log('Fetching initial unread count...');
     fetchUnreadNotificationsCount(unreadNotificationsRoute, bellBadgeElement);
};

// Add event listener for delete action using event delegation
document.addEventListener('click', function(event) {
    const target = event.target;
    if (target.matches('.post-menu div[data-action="delete"]')) {
        const postElement = target.closest('.post');
        if (postElement) {
            const postId = postElement.id.replace('post-', '');
            if (postId) {
                deletePost(postId);
            }
        }
    }
});

function showEditCommentForm(commentId) {
    // Ẩn nội dung comment
    const commentContent = document.querySelector(`#comment-${commentId} .comment-content`);
    const editButton = document.querySelector(`#comment-${commentId} .edit-comment-btn`);
    const editForm = document.querySelector(`#edit-comment-form-${commentId}`);
    
    if (commentContent && editButton && editForm) {
        commentContent.style.display = 'none';
        editButton.style.display = 'none';
        editForm.style.display = 'block';
    }
}

function cancelEditComment(commentId) {
    // Hiện lại nội dung comment
    const commentContent = document.querySelector(`#comment-${commentId} .comment-content`);
    const editButton = document.querySelector(`#comment-${commentId} .edit-comment-btn`);
    const editForm = document.querySelector(`#edit-comment-form-${commentId}`);
    
    if (commentContent && editButton && editForm) {
        commentContent.style.display = 'block';
        editButton.style.display = 'inline-block';
        editForm.style.display = 'none';
    }
}

function submitEditComment(commentId, button) {
    const editForm = document.querySelector(`#edit-comment-form-${commentId}`);
    const newContent = editForm.querySelector('.edit-comment-input').value;
    
    if (!newContent.trim()) {
        alert('Nội dung bình luận không được để trống!');
        return;
    }

    fetch(`/comments/${commentId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            content: newContent
        })
    })
    .then(response => {
         console.log('Received response for update comment. Status:', response.status); // Log status response
         return response.json();
     })
    .then(data => {
        console.log('Update comment response data:', data); // Log dữ liệu phản hồi
        if (data.success) {
            console.log('Comment updated successfully.'); // Log nếu thành công
            // Cập nhật nội dung comment
            const commentContent = document.querySelector(`#comment-${commentId} .comment-content`);
            if (commentContent) {
                commentContent.textContent = newContent;
            }
            
            // Hiện lại nội dung comment và nút sửa
            cancelEditComment(commentId);
        } else {
            alert(data.message || 'Không thể cập nhật bình luận!');
            console.error('Failed to update comment:', data.message); // Log lỗi nếu server báo thất bại
        }
    })
    .catch(error => {
        console.error('Error submitting update comment:', error); // Log lỗi khi fetch thất bại
        alert('Lỗi khi cập nhật bình luận!');
    });
}
// Use event delegation for comment form submission
document.body.addEventListener('submit', function(event) {
    const target = event.target;
     console.log('Submit event captured. Target:', target); // Log khi bắt sự kiện submit
    // Check if the submitted element is a comment form
    if (target.classList.contains('comment-form')) {
        console.log('Comment form submitted.', target); // Log nếu là form comment
        event.preventDefault(); // Prevent default form submission
        const postIdInput = target.querySelector('input[name="post_id"]');
        const postId = postIdInput ? postIdInput.value : null;
        
        if (postId) {
            console.log('Calling addComment for post:', postId, 'with form:', target); // Log trước khi gọi addComment
            addComment(postId, target); // Call addComment function with postId and the form element
        } else {
            console.error('Post ID not found for comment form submission.'); // Log nếu không tìm thấy postId
        }
    }
});

