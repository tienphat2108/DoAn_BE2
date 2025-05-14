// Hiện/ẩn menu ba chấm
window.toggleProfileMenu = function(event) {
    event.stopPropagation();
    var menu = document.getElementById('profileMenu');
    menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
}

// Đổi mật khẩu
window.showChangePassword = function() {
    document.getElementById('changePasswordModal').style.display = 'flex';
    document.getElementById('profileMenu').style.display = 'none';
}
window.hideChangePassword = function() {
    document.getElementById('changePasswordModal').style.display = 'none';
}

// Đổi avatar
window.showChangeAvatar = function() {
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

function togglePostMenu(btn) {
    // Đóng tất cả menu khác
    document.querySelectorAll('.post-menu').forEach(menu => menu.classList.remove('active'));
    var wrapper = btn.closest('.post-menu-wrapper');
    var post = btn.closest('.post');
    var postOwnerId = post.getAttribute('data-user-id');
    var postId = post.id.replace('post-', '');
    var menu = wrapper.querySelector('.post-menu');
    menu.innerHTML = '';
    // Nếu là trang cá nhân của chính mình
    if (window.currentUserId && window.profileUserId && window.currentUserId === window.profileUserId) {
        menu.innerHTML += '<div onclick="editPost(' + postId + ')">Chỉnh sửa</div>';
        menu.innerHTML += '<div onclick="deletePost(' + postId + ')">Xóa</div>';
    } else {
        // Nếu là trang cá nhân người khác
        menu.innerHTML += '<div onclick="reportPost(' + postId + ')">Báo cáo</div>';
    }
    menu.classList.toggle('active');
}

document.addEventListener('click', function(e) {
    if (!e.target.classList.contains('post-menu-btn')) {
        document.querySelectorAll('.post-menu').forEach(menu => menu.classList.remove('active'));
    }
});

// Hàm xử lý các chức năng (có thể đã có ở trangchu.js, nếu dùng chung thì không cần viết lại)
// function editPost(postId) { ... }
// function deletePost(postId) { ... }
// function reportPost(postId) { ... }
