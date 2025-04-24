<form action="{{ isset($post) ? route('posts.update', $post) : route('posts.store') }}" method="POST">
    @csrf
    @if(isset($post))
        @method('PUT')
    @endif

    <label>Tiêu đề:</label>
    <input type="text" name="title" value="{{ old('title', $post->title ?? '') }}">

    <label>Nội dung:</label>
    <textarea name="content">{{ old('content', $post->content ?? '') }}</textarea>

    <button type="submit">Lưu</button>
</form>
