@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Thống kê số lượng bài duyệt</h1>

    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Bộ lọc thời gian</h6>
                </div>
                <div class="card-body">
                    <form id="filterForm" class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date">Ngày</label>
                                <input type="date" class="form-control" id="date" name="date" value="{{ request('date', now()->format('Y-m-d')) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="start_date">Từ ngày</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="end_date">Đến ngày</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date', now()->format('Y-m-d')) }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Lọc</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Số lượng bài duyệt hôm nay</h6>
                </div>
                <div class="card-body">
                    <h2 id="dailyCount" class="text-center">0</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tổng số bài duyệt trong khoảng thời gian</h6>
                </div>
                <div class="card-body">
                    <h2 id="rangeCount" class="text-center">0</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Chi tiết theo ngày</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="statsTable">
                            <thead>
                                <tr>
                                    <th>Ngày</th>
                                    <th>Số lượng bài duyệt</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const dailyCount = document.getElementById('dailyCount');
    const rangeCount = document.getElementById('rangeCount');
    const statsTable = document.getElementById('statsTable').getElementsByTagName('tbody')[0];

    function loadStats() {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData);

        fetch(`/admin/api/post-approval-stats?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                dailyCount.textContent = data.daily_count;
                rangeCount.textContent = data.range_count;

                // Clear table
                statsTable.innerHTML = '';

                // Add rows
                data.detailed_stats.forEach(stat => {
                    const row = statsTable.insertRow();
                    const dateCell = row.insertCell(0);
                    const countCell = row.insertCell(1);

                    dateCell.textContent = stat.date;
                    countCell.textContent = stat.count;
                });
            })
            .catch(error => console.error('Error:', error));
    }

    // Load stats on page load
    loadStats();

    // Load stats when form is submitted
    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        loadStats();
    });
});
</script>
@endpush
@endsection 