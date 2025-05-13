document.addEventListener('DOMContentLoaded', function() {
    // Xử lý nút thêm người dùng
    const btnAddUser = document.querySelector('.btn-add-user');
    if (btnAddUser) {
        btnAddUser.addEventListener('click', function() {
            alert('Chức năng thêm người dùng sẽ được triển khai sau!');
        });
    }

    // Xử lý nút tìm kiếm
    const searchBox = document.querySelector('.search-box input');
    const searchButton = document.querySelector('.search-box button');
    
    if (searchButton) {
        searchButton.addEventListener('click', function() {
            const searchTerm = searchBox.value.trim();
            if (searchTerm) {
                // Gửi request tìm kiếm
                fetch(`/admin/search?q=${encodeURIComponent(searchTerm)}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Cập nhật bảng người dùng
                    updateUsersTable(data.users);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi tìm kiếm!');
                });
            }
        });
    }

    // Xử lý nút sửa người dùng
    const editButtons = document.querySelectorAll('.btn-edit');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-id');
            alert(`Chức năng sửa người dùng ID: ${userId} sẽ được triển khai sau!`);
        });
    });

    // Xử lý nút xóa người dùng
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-id');
            if (confirm('Bạn có chắc chắn muốn xóa người dùng này?')) {
                // Gửi request xóa người dùng
                fetch(`/admin/users/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Xóa hàng khỏi bảng
                        const row = this.closest('tr');
                        row.remove();
                        alert('Xóa người dùng thành công!');
                    } else {
                        alert(data.message || 'Có lỗi xảy ra khi xóa người dùng!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi xóa người dùng!');
                });
            }
        });
    });

    // Hàm cập nhật bảng người dùng
    function updateUsersTable(users) {
        const tbody = document.querySelector('.users-table tbody');
        if (!tbody) return;

        // Xóa tất cả các hàng hiện tại
        tbody.innerHTML = '';

        // Thêm các hàng mới
        users.forEach(user => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${user.id}</td>
                <td>${user.first_name} ${user.last_name}</td>
                <td>${user.email}</td>
                <td>${user.phone}</td>
                <td>${user.created_at}</td>
                <td>
                    <button class="btn-edit" data-id="${user.id}"><i class="fas fa-edit"></i></button>
                    <button class="btn-delete" data-id="${user.id}"><i class="fas fa-trash"></i></button>
                </td>
            `;
            tbody.appendChild(row);
        });

        // Thêm lại event listeners cho các nút
        addEventListenersToButtons();
    }

    // Hàm thêm event listeners cho các nút
    function addEventListenersToButtons() {
        // Xử lý nút sửa người dùng
        const editButtons = document.querySelectorAll('.btn-edit');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-id');
                alert(`Chức năng sửa người dùng ID: ${userId} sẽ được triển khai sau!`);
            });
        });

        // Xử lý nút xóa người dùng
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-id');
                if (confirm('Bạn có chắc chắn muốn xóa người dùng này?')) {
                    // Gửi request xóa người dùng
                    fetch(`/admin/users/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Xóa hàng khỏi bảng
                            const row = this.closest('tr');
                            row.remove();
                            alert('Xóa người dùng thành công!');
                        } else {
                            alert(data.message || 'Có lỗi xảy ra khi xóa người dùng!');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra khi xóa người dùng!');
                    });
                }
            });
        });
    }
}); 