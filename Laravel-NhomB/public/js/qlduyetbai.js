// Ví dụ thêm hiệu ứng khi hover vào nút
document.querySelectorAll('td').forEach(td => {
    td.addEventListener('mouseover', () => {
        td.style.backgroundColor = '#eee';
    });
    td.addEventListener('mouseout', () => {
        td.style.backgroundColor = '';
    });
});
