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
            <div class="mb-4 d-flex">
                <input type="text" id="searchInput" class="form-control me-2" placeholder="Cari postingan..."
                    value="{{ request()->input('search') }}">
                <button class="btn btn-secondary me-2" id="filterLikesHigh" title="Like Terbanyak">
                    <i class="fas fa-thumbs-up"></i>
                </button>
                <button class="btn btn-secondary me-2" id="filterLikesLow" title="Like Terendah">
                    <i class="fas fa-thumbs-down"></i>
                </button>
                <button class="btn btn-secondary me-2" id="filterNewest" title="SS Terbaru">
                    <i class="fas fa-sort-amount-down"></i>
                </button>
                <button class="btn btn-secondary" id="filterOldest" title=" SS Terlama">
                    <i class="fas fa-sort-amount-up"></i>
                </button>
            </div>
            <div class="row" id="postsContainer">
                @foreach ($data as $post)
                    <div class="col-3 mb-4 post-item" data-likes="{{ $post->suka }}"
                        data-date="{{ $post->tgl_pengajuan }}" data-title="{{ strtolower($post->judul) }}"
                        data-user="{{ strtolower($post->user->name) }}">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <img src="assets/img/user.png" alt="User Avatar" class="rounded-circle me-2"
                                        style="width: 50px; height: 50px;">
                                    <div>
                                        <h6 class="mb-0">{{ $post->user->name }}</h6>
                                        <small
                                            class="text-muted">{{ \Carbon\Carbon::parse($post->tgl_pengajuan)->diffForHumans() }}</small>
                                    </div>
                                </div>
                                <p class="mb-4">{{ $post->judul }}</p>
                                <div class="">
                                    <button class="btn btn-primary btn-sm like-button" data-id="{{ $post->id }}">
                                        <i class="fas fa-thumbs-up"></i> <span
                                            class="like-count">{{ $post->suka }}</span>
                                    </button>
                                    <span class="ms-2" id="likeEmote">{{ $post->suka > 9 ? '🔥' : '' }}</span>
                                    <!-- Display fire emoji if likes > 10 -->
                                    <button class="btn btn-success btn-sm" id="fetchDataButton"
                                        onclick="openViewSS({{ $post->id }})" title="Lihat">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Readonly Modal Form View Sumbang Saran -->
            <div class="modal fade" id="viewSumbangSaranModal" tabindex="-1" aria-labelledby="viewSumbangSaranModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 90%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewSumbangSaranModalLabel">Form View SS</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Form View Sumbang Saran -->
                            <form id="viewSumbangSaranForm" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-3">
                                    <label for="editLokasiIde" class="col-sm-2 col-form-label">Nama<span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="viewname" name="nama" disabled>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="editLokasiIde" class="col-sm-2 col-form-label">Npk<span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="viewnpk" name="npk" disabled>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="viewTglPengajuan" class="col-sm-2 col-form-label">Tgl. pengajuan Ide <span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control" id="viewTglPengajuan"
                                            name="tgl_pengajuan_ide" disabled>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="viewLokasiIde" class="col-sm-2 col-form-label">Lokasi Ide <span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="viewLokasiIde" name="lokasi_ide"
                                            disabled>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="viewTglDiterapkan" class="col-sm-2 col-form-label">Tgl. Diterapkan<span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control" id="viewTglDiterapkan"
                                            name="tgl_diterapkan" disabled>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="viewJudulIde" class="col-sm-2 col-form-label">Judul Ide <span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="viewJudulIde" name="judul"
                                            disabled>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="viewKeadaanSebelumnya" class="col-sm-2 col-form-label">Keadaan Sebelumnya
                                        (Permasalahan) <span style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" style="height: 100px" id="viewKeadaanSebelumnya" name="keadaan_sebelumnya" disabled></textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="viewImage" class="col-sm-2 col-form-label">File Upload
                                        (Sebelumnya)</label>
                                    <div class="col-sm-10">
                                        <div id="view-image-preview" style="margin-top: 10px;"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="viewUsulanIde" class="col-sm-2 col-form-label">Usulan Ide <span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" style="height: 100px" id="viewUsulanIde" name="usulan_ide" disabled></textarea>
                                    </div>
                                </div>
                                <!-- Input File Upload 2 -->
                                <div class="row mb-3">
                                    <label for="viewImage2" class="col-sm-2 col-form-label">File Upload (Sesudah)</label>
                                    <div class="col-sm-10">
                                        <div id="view-image2-preview" style="margin-top: 10px;"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="viewKeuntungan" class="col-sm-2 col-form-label">Keuntungan Dari Penerapan
                                        Ide</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" style="height: 100px" id="viewKeuntungan" name="keuntungan_ide" disabled></textarea>
                                    </div>
                                </div>
                                <input type="hidden" id="viewSumbangSaranId" name="id">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Gambar -->
            <div class="modal fade" id="viewImageModal" tabindex="-1" aria-labelledby="viewImageModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewImageModalLabel">Gambar</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img id="viewModalImage" src="" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script>
            document.getElementById('searchInput').addEventListener('input', function() {
                let searchQuery = this.value.toLowerCase();
                let params = new URLSearchParams(window.location.search);
                params.set('search', searchQuery);
                window.history.replaceState({}, '', `${window.location.pathname}?${params.toString()}`);
                filterPosts();
            });

            document.getElementById('filterLikesLow').addEventListener('click', function() {
                let params = new URLSearchParams(window.location.search);
                params.set('sort', 'likesLow');
                window.location.search = params.toString();
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

            function openViewSS(id) {
                $.ajax({
                    url: '{{ route('sumbangsaran.show', ':id') }}'.replace(':id', id),
                    type: 'GET',
                    success: function(response) {
                        // Mengisi data ke dalam form modal
                        $('#viewname').val(response.user.name);
                        $('#viewnpk').val(response.user.npk);
                        $('#viewTglPengajuan').val(response.tgl_pengajuan_ide);
                        $('#viewLokasiIde').val(response.lokasi_ide);
                        $('#viewTglDiterapkan').val(response.tgl_diterapkan);
                        $('#viewJudulIde').val(response.judul);
                        $('#viewKeadaanSebelumnya').val(response.keadaan_sebelumnya);
                        $('#viewUsulanIde').val(response.usulan_ide);
                        $('#viewKeuntungan').val(response.keuntungan_ide);
                        $('#viewSumbangSaranId').val(response.id);

                        // Menampilkan file pertama
                        if (response.file_name && response.image) {
                            var fileExtension1 = response.file_name.split('.').pop().toLowerCase();
                            var fileLink1 = '{{ asset('assets/image/') }}/' + response.image;

                            if (['jpg', 'jpeg', 'png'].includes(fileExtension1)) {
                                $('#view-image-preview').html('<img src="' + fileLink1 +
                                    '" class="img-fluid rounded clickable-view-image" style="max-width: 200px; height: auto;" data-bs-toggle="modal" data-bs-target="#viewImageModal" data-img-src="' +
                                    fileLink1 + '">');
                            } else {
                                $('#view-image-preview').html('<a href="' + fileLink1 + '" download="' + response
                                    .file_name + '">' + response.file_name + '</a>');
                            }
                        } else {
                            $('#view-image-preview').html('<p>No file uploaded</p>');
                        }

                        // Menampilkan file kedua
                        if (response.file_name_2 && response.image_2) {
                            var fileExtension2 = response.file_name_2.split('.').pop().toLowerCase();
                            var fileLink2 = '{{ asset('assets/image/') }}/' + response.image_2;

                            if (['jpg', 'jpeg', 'png'].includes(fileExtension2)) {
                                $('#view-image2-preview').html('<img src="' + fileLink2 +
                                    '" class="img-fluid rounded clickable-view-image" style="max-width: 200px; height: auto;" data-bs-toggle="modal" data-bs-target="#viewImageModal" data-img-src="' +
                                    fileLink2 + '">');
                            } else {
                                $('#view-image2-preview').html('<a href="' + fileLink2 + '" download="' + response
                                    .file_name_2 + '">' + response.file_name_2 + '</a>');
                            }
                        } else {
                            $('#view-image2-preview').html('<p>No file uploaded</p>');
                        }

                        // Menampilkan modal
                        $('#viewSumbangSaranModal').modal('show');
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
