@extends('layouts.app')

@section('content')
    <h2>Chi tiết bài viết: {{ $post->title }}</h2>
    <p><b>ID:</b> {{ $post->id }}</p>
    <p><b>Trạng thái:</b> {{ $post->status }}</p>
    <p><b>Nội dung:</b> {{ $post->content ?? '' }}</p>

    <h3>Đánh giá chất lượng nội dung</h3>
    @if($post->evaluations->count())
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Người đánh giá</th>
                    <th>Số sao</th>
                    <th>Nhận xét</th>
                    <th>Thời gian</th>
                </tr>
            </thead>
            <tbody>
                @foreach($post->evaluations as $eval)
                    <tr>
                        <td>{{ $eval->user->name ?? 'Admin' }}</td>
                        <td>
                            @for($i=1; $i<=$eval->rating; $i++)
                                <i class="fa fa-star" style="color: #ffd700"></i>
                            @endfor
                        </td>
                        <td>{{ $eval->comment }}</td>
                        <td>{{ $eval->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Chưa có đánh giá nào cho bài viết này.</p>
    @endif
@endsection
