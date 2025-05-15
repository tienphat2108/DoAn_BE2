<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ADMIN quản lý bài đăng</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    
<style>
    #menuPopup div {
    padding: 4px 0;
    font-size: 15px;
}
#menuPopup div:not(:last-child) {
    border-bottom: 1px solid #eee;
}
table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    th, td {
        border: 1px solid #222;
        padding: 8px 12px;
        text-align: center;
        font-size: 15px;
    }
    th {
        background: #fff;
        font-weight: bold;
    }
    .section-title {
        font-size: 18px;
        font-weight: bold;
        margin: 16px 0 8px 0;
        display: flex;
        align-items: center;
    }
    .section-title i {
        margin-right: 8px;
    }
    
.dot-btn {
    background: #fff;
    border: 1px solid #222;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    padding: 0;
    cursor: pointer;
    transition: background 0.2s;
}
.dot-btn:hover {
    background: #eee;
}
.popup-menu {
    position: absolute;
    background: #fff;
    border: 1px solid #222;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-top: 40px;
    z-index: 100;
    min-width: 200px;
}
.popup-menu div {
    padding: 8px 16px;
    cursor: pointer; 

}
.popup-menu div:hover {
    background: #f0f0f0;
}
.popup-menu {
    position: absolute;
    top: 40px;         /* Đặt menu ngay dưới nút 3 chấm (cao 32px + margin) */
    left: 0;           /* Thẳng hàng với nút 3 chấm */
    background: #fff;
    border: 1px solid #222;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    z-index: 100;
    min-width: 220px;
    padding: 0;
}
.popup-menu div {
    padding: 10px 16px;
    cursor: pointer;
    white-space: nowrap;
}
.popup-menu div:hover {
    background: #f0f0f0;
}
</style>


</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <div class="logo">
                <span>Fite</span>
            </div>
            <h1>Fite hệ thống ADMIN</h1>
            <div class="admin-info">
                <span class="admin-name">{{ Auth::user()->name }}</span>
            </div>
        </div>

        <div class="admin-content" style="display: flex; align-items: flex-start; width: 100%;">
            <div class="admin-sidebar" style="width: 240px; flex-shrink: 0;">
                <ul class="admin-menu">
                    <li><a href="{{ route('admin.quanlynguoidung') }}">QUẢN LÝ NGƯỜI DÙNG</a></li>
                    <li><a href="{{ route('admin.quanlybainguoidung') }}">QUẢN LÝ BÀI VIẾT CỦA NGƯỜI DÙNG</a></li>
                    <li><a href="{{ route('admin.baichoduyet') }}">BÀI CHỜ DUYỆT</a></li>
                    <li><a href="{{ route('admin.baiduyet') }}">BÀI ĐÃ DUYỆT</a></li>
                    <li><a href="{{ route('admin.lichdangbai') }}">LỊCH ĐĂNG BÀI</a></li>
                    <li><a href="{{ route('admin.quanlybinhluan') }}">PHÂN TÍCH TƯƠNG TÁC</a></li>
                    <li><a href="#" onclick="showLogoutModal()">ĐĂNG XUẤT</a></li>
                </ul>
            </div>

            <!-- Main content -->
            <div id="mainContentWrapper" style="flex: 1 1 0%; padding: 32px 24px 0 24px; min-width: 0;">
                <div style="display: flex; align-items: center; gap: 12px; position: relative;">
                    <button class="dot-btn" id="menuBtn">⋯</button>
                    <span style="font-weight: bold;" id="sectionTitle">Chọn chức năng</span>
                    <div id="popupMenu" class="popup-menu" style="display: none;">
                        <div onclick="showSection('section1', 'Danh sách bài viết đã kiểm duyệt')">Quản lý bài đã được duyệt</div>
                        <div onclick="showSection('section2', 'KIỂM TRA BÀI VIẾT')">Kiểm tra bài trước khi đưa ra</div>
                        <div onclick="showSection('section3', 'Đưa bài lên hệ thống')">Đưa bài đăng lên hệ thống</div>
                        <div onclick="showSection('section4', 'Cập nhật trạng thái bài viết')">Cập nhật trạng thái bài viết</div>
                    </div>
                </div>
                <div id="mainContent">
                    <div style="padding: 24px; color: #888;">Vui lòng chọn chức năng từ menu 3 chấm.</div>
                </div>
            </div>
        </div>
    </div>
    


    <script>
    //  ss2 Event delegation: luôn bắt sự kiện xóa trên bảng section2 kể cả khi load động
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('delete-btn')) {
            const row = e.target.closest('tr');
            if (row) row.remove();
        }
    });
</script>
<script>
    document.addEventListener('click', function(e) {
        // Xóa dòng
        if (e.target && e.target.classList.contains('delete-btn')) {
            // Nếu nút đã bị disabled thì không làm gì cả
            if (e.target.disabled) return;
            const row = e.target.closest('tr');
            if (row) row.remove();
        }
        // Duyệt dòng
        if (e.target && e.target.classList.contains('approve-btn')) {
            const row = e.target.closest('tr');
            if (row) {
                row.classList.add('approved-row');
                // Disable cả hai nút trong dòng này
                row.querySelectorAll('button').forEach(btn => {
                    btn.disabled = true;
                    btn.style.opacity = 0.6;
                    btn.style.cursor = "not-allowed";
                });
            }
        }
    });
</script>
    
<script>
    const btn = document.querySelector('.dot-btn');
    const popup = document.getElementById('menuPopup');
    document.addEventListener('click', function(e) {
        if (btn.contains(e.target)) {
            popup.style.display = popup.style.display === 'block' ? 'none' : 'block';
        } else if (!popup.contains(e.target)) {
            popup.style.display = 'none';
        }
    });

    // Hiện/ẩn popup menu
    document.getElementById('menuBtn').onclick = function(event) {
        event.stopPropagation();
        var menu = document.getElementById('popupMenu');
        menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
    };
    // Ẩn menu khi click ra ngoài
    document.addEventListener('click', function() {
        document.getElementById('popupMenu').style.display = 'none';
    });

    // Hiện section tương ứng
    function showSection(sectionId, title) {
        // Đổi tiêu đề
        document.getElementById('sectionTitle').innerText = title;
        // Đổi nội dung
        fetch('/admin/sections/' + sectionId) // route này bạn cần tạo ở backend
            .then(response => response.text())
            .then(html => {
                document.getElementById('mainContent').innerHTML = html;
            });
        // Ẩn menu
        document.getElementById('popupMenu').style.display = 'none';
    }
</script>
<script>
    function showSection(sectionId, title) {
        document.getElementById('sectionTitle').innerText = title;
        fetch('/admin/sections/' + sectionId)
            .then(response => {
                if (!response.ok) throw new Error('Không tìm thấy section!');
                return response.text();
            })
            .then(html => {
                document.getElementById('mainContent').innerHTML = html;
            })
            .catch(err => {
                document.getElementById('mainContent').innerHTML = '<div style="color:red;padding:24px;">Không tìm thấy nội dung!</div>';
            });
        document.getElementById('popupMenu').style.display = 'none';
    }

    document.getElementById('menuBtn').onclick = function(event) {
        event.stopPropagation();
        var menu = document.getElementById('popupMenu');
        menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
    };
    document.addEventListener('click', function() {
        document.getElementById('popupMenu').style.display = 'none';
    });
</script>
<script> //ss3
    document.addEventListener('click', function(e) {
        // Xử lý nút check
        if (e.target && e.target.classList.contains('check-btn')) {
            const row = e.target.closest('tr');
            if (row) {
                row.classList.add('published-row');
                row.classList.remove('rejected-row');
            }
        }
        // Xử lý nút X
        if (e.target && e.target.classList.contains('x-btn')) {
            const row = e.target.closest('tr');
            if (row) {
                row.classList.add('rejected-row');
                row.classList.remove('published-row');
            }
        }
    });
</script>
<script>
    document.addEventListener('click', function(e) {
        // Xử lý nút trạng thái
        if (e.target && e.target.classList.contains('status-btn')) {
            const td = e.target.parentNode;
            td.querySelectorAll('.status-btn').forEach(btn => btn.classList.remove('active'));
            e.target.classList.add('active');
        }
        // Xử lý nút cập nhật
        if (e.target && e.target.classList.contains('update-btn')) {
            const td = e.target.parentNode;
            td.querySelectorAll('.update-btn').forEach(btn => {
                btn.classList.remove('success', 'waiting');
            });
            if (e.target.textContent.trim() === 'Thành công') {
                e.target.classList.add('success');
            } else {
                e.target.classList.add('waiting');
            }
        }
    });
</script>
</body>
</html>
