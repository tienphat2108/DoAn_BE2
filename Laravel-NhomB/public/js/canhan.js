// Hiện/ẩn menu ba chấm
window.toggleProfileMenu = function() {
    var menu = document.getElementById('profileMenu');
    if (menu.style.display === 'block') {
        menu.style.display = 'none';
    } else {
        menu.style.display = 'block';
    }
}

// Đổi mật khẩu
window.showChangePassword = function(event) {
    if (event) event.preventDefault();
    document.getElementById('changePasswordModal').style.display = 'flex';
    document.getElementById('profileMenu').style.display = 'none';
}
window.hideChangePassword = function() {
    document.getElementById('changePasswordModal').style.display = 'none';
}

// Đổi avatar
window.showChangeAvatar = function(event) {
    if (event) event.preventDefault();
    document.getElementById('changeAvatarModal').style.display = 'flex';
    document.getElementById('profileMenu').style.display = 'none';
}
window.hideChangeAvatar = function() {
    document.getElementById('changeAvatarModal').style.display = 'none';
}

// Đóng menu khi click ra ngoài
document.addEventListener('click', function(event) {
    var menu = document.getElementById('profileMenu');
    if (menu && menu.style.display === 'block') {
        // Nếu click ngoài menu và ngoài nút ba chấm thì ẩn menu
        if (!menu.contains(event.target) && !event.target.classList.contains('profile-menu-btn') && !event.target.closest('.profile-menu-btn')) {
            menu.style.display = 'none';
        }
    }
    var pwModal = document.getElementById('changePasswordModal');
    if (pwModal && event.target == pwModal) {
        pwModal.style.display = 'none';
    }
    var avModal = document.getElementById('changeAvatarModal');
    if (avModal && event.target == avModal) {
        avModal.style.display = 'none';
    }
});

// Xử lý menu 3 chấm cho từng bài viết trên trang cá nhân
// Cần biến currentUserId và userProfileId (id của chủ trang cá nhân)

// Lấy id user hiện tại từ backend (nên render vào blade, ví dụ: window.currentUserId = ...)
if (typeof window.currentUserId === 'undefined') {
    window.currentUserId = document.body.getAttribute('data-current-user-id')
        ? parseInt(document.body.getAttribute('data-current-user-id'))
        : null;
}
// Lấy id user của trang cá nhân (nên render vào blade, ví dụ: window.profileUserId = ...)
if (typeof window.profileUserId === 'undefined') {
    window.profileUserId = document.body.getAttribute('data-profile-user-id')
        ? parseInt(document.body.getAttribute('data-profile-user-id'))
        : null;
}

// Đổi function thường thành global để menu ba chấm hoạt động
window.togglePostMenu = function(btn) {
    document.querySelectorAll('.post-menu').forEach(menu => menu.classList.remove('active'));
    var wrapper = btn.closest('.post-menu-wrapper');
    var post = btn.closest('.post');
    var postOwnerId = post.getAttribute('data-user-id');
    var postId = post.id.replace('post-', '');
    var menu = wrapper.querySelector('.post-menu');
    menu.innerHTML = '';
    // Log debug
    console.log('currentUserId:', window.currentUserId, typeof window.currentUserId);
    console.log('profileUserId:', window.profileUserId, typeof window.profileUserId);
    console.log('postOwnerId:', postOwnerId, typeof postOwnerId);
    if (
        window.currentUserId !== null &&
        window.profileUserId !== null &&
        Number(window.currentUserId) === Number(window.profileUserId)
    ) {
        menu.innerHTML += '<div onclick="editPost(' + postId + ')">Chỉnh sửa</div>';
        menu.innerHTML += '<div onclick="deletePost(' + postId + ')">Xóa</div>';
    } else {
        menu.innerHTML += '<div onclick="reportPost(' + postId + ')">Báo cáo</div>';
    }
    menu.classList.toggle('active');
};

document.addEventListener('click', function(e) {
    if (!e.target.classList.contains('post-menu-btn')) {
        document.querySelectorAll('.post-menu').forEach(menu => menu.classList.remove('active'));
    }
});

// Hàm xử lý các chức năng (có thể đã có ở trangchu.js, nếu dùng chung thì không cần viết lại)
// function editPost(postId) { ... }
// function deletePost(postId) { ... }
// function reportPost(postId) { ... }

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
window.editPost = function(postId) {
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
};
window.hideEditModal = function() {
    var modal = document.getElementById('editPostModal');
    if (modal) modal.style.display = 'none';
};
window.deletePost = function(postId) {
    if (confirm('Bạn có chắc chắn muốn xóa bài viết này?')) {
        var form = document.getElementById('delete-form-' + postId);
        if (form) {
            form.submit();
        } else {
            alert('Không tìm thấy form xóa cho bài viết này!');
        }
    }
};
