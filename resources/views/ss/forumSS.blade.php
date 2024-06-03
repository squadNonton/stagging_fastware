@extends('layout')

@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Forum SS</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboardHandling') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Forum SS</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="mb-4 d-flex flex-column flex-md-row">
                <input type="text" id="searchInput" class="form-control mb-2 mb-md-0 me-md-2"
                    placeholder="Cari postingan..." value="{{ request()->input('search') }}">
                <div class="btn-group flex-md-grow-1">
                    <button class="btn btn-secondary me-2" id="filterLikesHigh" title="Like Terbanyak">
                        <i class="fas fa-crown"></i>
                    </button>
                    <button class="btn btn-secondary me-2" id="filterNewest" title="SS Terbaru">
                        <i class="fas fa-sort-amount-down"></i>
                    </button>
                    <button class="btn btn-secondary" id="filterOldest" title="SS Terlama">
                        <i class="fas fa-sort-amount-up"></i>
                    </button>
                </div>
            </div>
            <div class="row" id="postsContainer">
                <div class="container">
                    <div class="row">
                        <!-- First Place -->
                        @if ($data->count() > 0)
                            <div class="col-12 mb-4 post-item" data-likes="{{ $data[0]->suka }}"
                                data-date="{{ $data[0]->tgl_pengajuan }}" data-title="{{ strtolower($data[0]->judul) }}"
                                data-user="{{ strtolower($data[0]->user->name) }}">
                                <div class="card position-relative"
                                    style="background: linear-gradient(to right, #FFD700, #FFA500);">
                                    <div class="position-absolute top-0 end-0 m-2 badge rounded-pill text-warning"
                                        style="background-color: #333; font-size: 1.5rem; padding: 1rem;">
                                        🥇 1st Place
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3 d-flex">
                                            <img src="assets/img/user.png" alt="User Avatar" class="rounded-circle me-2"
                                                style="width: 50px; height: 50px;">
                                            <div>
                                                <h6 class="mb-0">{{ $data[0]->user->name }}</h6>
                                                <small
                                                    class="text-muted">{{ \Carbon\Carbon::parse($data[0]->tgl_pengajuan)->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        <p class="mb-4">{{ $data[0]->judul }}</p>
                                        <div class="">
                                            <button class="btn btn-primary btn-sm like-button"
                                                data-id="{{ $data[0]->id }}">
                                                <i class="fas fa-thumbs-up"></i> <span
                                                    class="like-count">{{ $data[0]->suka }}</span>
                                            </button>
                                            <span class="ms-2" id="likeEmote">{{ $data[0]->suka > 9 ? '🔥' : '' }}</span>
                                            <button class="btn btn-success btn-sm" id="fetchDataButton"
                                                onclick="viewFormSS({{ $data[0]->id }})" title="Lihat">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Second Place -->
                        @if ($data->count() > 1)
                            <div class="col-12 col-md-6 mb-4 post-item" data-likes="{{ $data[1]->suka }}"
                                data-date="{{ $data[1]->tgl_pengajuan }}" data-title="{{ strtolower($data[1]->judul) }}"
                                data-user="{{ strtolower($data[1]->user->name) }}">
                                <div class="card position-relative"
                                    style="background: linear-gradient(to right, #C0C0C0, #A9A9A9);">
                                    <div class="position-absolute top-0 end-0 m-2 badge rounded-pill text-secondary"
                                        style="background-color: #333; font-size: 1.5rem; padding: 1rem;">
                                        🥈 2nd Place
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3 d-flex">
                                            <img src="assets/img/user.png" alt="User Avatar" class="rounded-circle me-2"
                                                style="width: 50px; height: 50px;">
                                            <div>
                                                <h6 class="mb-0">{{ $data[1]->user->name }}</h6>
                                                <small
                                                    class="text-muted">{{ \Carbon\Carbon::parse($data[1]->tgl_pengajuan)->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        <p class="mb-4">{{ $data[1]->judul }}</p>
                                        <div class="">
                                            <button class="btn btn-primary btn-sm like-button"
                                                data-id="{{ $data[1]->id }}">
                                                <i class="fas fa-thumbs-up"></i> <span
                                                    class="like-count">{{ $data[1]->suka }}</span>
                                            </button>
                                            <span class="ms-2" id="likeEmote">{{ $data[1]->suka > 9 ? '🔥' : '' }}</span>
                                            <button class="btn btn-success btn-sm" id="fetchDataButton"
                                                onclick="viewFormSS({{ $data[1]->id }})" title="Lihat">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Third Place -->
                        @if ($data->count() > 2)
                            <div class="col-12 col-md-6 mb-4 post-item" data-likes="{{ $data[2]->suka }}"
                                data-date="{{ $data[2]->tgl_pengajuan }}" data-title="{{ strtolower($data[2]->judul) }}"
                                data-user="{{ strtolower($data[2]->user->name) }}">
                                <div class="card position-relative"
                                    style="background: linear-gradient(to right, #cd7f32, #dbaa3f);">
                                    <div class="position-absolute top-0 end-0 m-2 badge rounded-pill text-info"
                                        style="background-color: #333; font-size: 1.5rem; padding: 1rem;">
                                        🥉 3rd Place
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3 d-flex">
                                            <img src="assets/img/user.png" alt="User Avatar" class="rounded-circle me-2"
                                                style="width: 50px; height: 50px;">
                                            <div>
                                                <h6 class="mb-0">{{ $data[2]->user->name }}</h6>
                                                <small
                                                    class="text-muted">{{ \Carbon\Carbon::parse($data[2]->tgl_pengajuan)->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        <p class="mb-4">{{ $data[2]->judul }}</p>
                                        <div class="">
                                            <button class="btn btn-primary btn-sm like-button"
                                                data-id="{{ $data[2]->id }}">
                                                <i class="fas fa-thumbs-up"></i> <span
                                                    class="like-count">{{ $data[2]->suka }}</span>
                                            </button>
                                            <span class="ms-2"
                                                id="likeEmote">{{ $data[2]->suka > 9 ? '🔥' : '' }}</span>
                                            <button class="btn btn-success btn-sm" id="fetchDataButton"
                                                onclick="viewFormSS({{ $data[2]->id }})" title="Lihat">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Other Posts -->
                        @foreach ($data as $index => $post)
                            @if ($index > 2)
                                <div class="col-6 col-md-3 mb-4 post-item" data-likes="{{ $post->suka }}"
                                    data-date="{{ $post->tgl_pengajuan }}" data-title="{{ strtolower($post->judul) }}"
                                    data-user="{{ strtolower($post->user->name) }}">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="mb-3 d-flex">
                                                <img src="assets/img/user.png" alt="User Avatar"
                                                    class="rounded-circle me-2" style="width: 50px; height: 50px;">
                                                <div>
                                                    <h6 class="mb-0">{{ $post->user->name }}</h6>
                                                    <small
                                                        class="text-muted">{{ \Carbon\Carbon::parse($post->tgl_pengajuan)->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                            <p class="mb-4">{{ $post->judul }}</p>
                                            <div class="">
                                                <button class="btn btn-primary btn-sm like-button"
                                                    data-id="{{ $post->id }}">
                                                    <i class="fas fa-thumbs-up"></i> <span
                                                        class="like-count">{{ $post->suka }}</span>
                                                </button>
                                                <span class="ms-2"
                                                    id="likeEmote">{{ $post->suka > 9 ? '🔥' : '' }}</span>
                                                <button class="btn btn-success btn-sm" id="fetchDataButton"
                                                    onclick="viewFormSS({{ $post->id }})" title="Lihat">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <style>
            .btn-group.flex-md-grow-1 .btn {
                flex-grow: 1;
            }
            @media (max-width: 768px) {
                .btn-group.flex-md-grow-1 .btn {
                    margin-bottom: 5px;
                }
            }
        </style>

        <script>
            document.getElementById('searchInput').addEventListener('input', function() {
                let searchQuery = this.value.toLowerCase();
                let params = new URLSearchParams(window.location.search);
                params.set('search', searchQuery);
                window.history.replaceState({}, '', `${window.location.pathname}?${params.toString()}`);
                filterPosts();
            });

            document.getElementById('filterLikesHigh').addEventListener('click', function() {
                let params = new URLSearchParams(window.location.search);
                params.set('sort', 'likesHigh');
                window.location.search = params.toString();
            });

            document.getElementById('filterNewest').addEventListener('click', function() {
                let params = new URLSearchParams(window.location.search);
                params.set('sort', 'newest');
                window.location.search = params.toString();
            });

            document.getElementById('filterOldest').addEventListener('click', function() {
                let params = new URLSearchParams(window.location.search);
                params.set('sort', 'oldest');
                window.location.search = params.toString();
            });

            function filterPosts() {
                let searchQuery = new URLSearchParams(window.location.search).get('search') || '';
                let posts = document.querySelectorAll('.post-item');
                posts.forEach(function(post) {
                    let title = post.getAttribute('data-title');
                    let user = post.getAttribute('data-user');
                    if (title.includes(searchQuery) || user.includes(searchQuery)) {
                        post.style.display = '';
                    } else {
                        post.style.display = 'none';
                    }
                });
            }

            // Call filterPosts on page load to apply search filter if exists
            document.addEventListener('DOMContentLoaded', function() {
                filterPosts();
            });
        </script>


        <script>
            $(document).ready(function() {
                $('.like-button').on('click', function() {
                    var postId = $(this).data('id');
                    var likeButton = $(this);

                    // Check if the post is already liked or not
                    var isLiked = likeButton.hasClass('liked');

                    $.ajax({
                        url: isLiked ? '{{ url('/sumbangsaran/unlike') }}/' + postId :
                            '{{ url('/sumbangsaran/like') }}/' + postId,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (isLiked) {
                                likeButton.removeClass('liked');
                            } else {
                                likeButton.addClass('liked');
                            }
                            likeButton.find('.like-count').text(response.suka);
                        }
                    });
                });
            });

            //viewmodal
            function viewFormSS(id) {
                $.ajax({
                    url: '{{ route('sechead.show', ':id') }}'.replace(':id', id),
                    type: 'GET',
                    success: function(response) {
                        console.log(response); // Tambahkan ini untuk debug
                        if (response) {
                            $('#viewname').val(response.user.name);
                            $('#viewnpk').val(response.user.npk);
                            $('#viewTglPengajuan').val(response.tgl_pengajuan_ide);
                            $('#viewPlant').val(response.plant);
                            $('#viewLokasiIde').val(response.lokasi_ide);
                            $('#viewTglDiterapkan').val(response.tgl_diterapkan);
                            $('#viewJudulIde').val(response.judul);
                            $('#viewKeadaanSebelumnya').val(response.keadaan_sebelumnya);
                            $('#viewUsulanIde').val(response.usulan_ide);
                            $('#viewKeuntungan').val(response.keuntungan_ide);
                            $('#viewSumbangSaranId').val(response.id);

                            if (response.file_name && response.image) {
                                var fileExtension1 = response.file_name.split('.').pop().toLowerCase();
                                var fileLink1 = '{{ asset('assets/image/') }}/' + response.image;
                                if (['jpg', 'jpeg', 'png'].includes(fileExtension1)) {
                                    $('#view-image-preview').html('<img src="' + fileLink1 +
                                        '" class="img-fluid rounded clickable-view-image" style="max-width: 200px; height: auto;" data-bs-toggle="modal" data-bs-target="#viewImageModal" data-img-src="' +
                                        fileLink1 + '">');
                                } else {
                                    $('#view-image-preview').html('<a href="' + fileLink1 + '" download="' +
                                        response.file_name + '">' + response.file_name + '</a>');
                                }
                            } else {
                                $('#view-image-preview').html('');
                            }

                            if (response.file_name_2 && response.image_2) {
                                var fileExtension2 = response.file_name_2.split('.').pop().toLowerCase();
                                var fileLink2 = '{{ asset('assets/image/') }}/' + response.image_2;
                                if (['jpg', 'jpeg', 'png'].includes(fileExtension2)) {
                                    $('#view-image2-preview').html('<img src="' + fileLink2 +
                                        '" class="img-fluid rounded clickable-view-image" style="max-width: 200px; height: auto;" data-bs-toggle="modal" data-bs-target="#viewImageModal" data-img-src="' +
                                        fileLink2 + '">');
                                } else {
                                    $('#view-image2-preview').html('<a href="' + fileLink2 + '" download="' +
                                        response.file_name_2 + '">' + response.file_name_2 + '</a>');
                                }
                            } else {
                                $('#view-image2-preview').html('');
                            }

                            $('#viewSumbangSaranModal').modal('show');
                        } else {
                            console.error('No response data');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }
            // Event listener untuk gambar yang diklik
            $(document).on('click', '.clickable-view-image', function() {
                var imgSrc = $(this).data('img-src');
                $('#viewModalImage').attr('src', imgSrc);
                $('#viewImageModal').modal('show');
            });

            document.addEventListener('DOMContentLoaded', function() {
                var likeCounts = document.querySelectorAll('.like-count');

                likeCounts.forEach(function(count) {
                    var likes = parseInt(count.textContent);
                    if (likes > 10) {
                        var emoteSpan = count.parentElement.nextElementSibling.querySelector('#likeEmote');
                        emoteSpan.innerHTML = '🔥'; // Menampilkan emoji api jika suka > 10
                    }
                });
            });
        </script>
    </main>
@endsection
