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
