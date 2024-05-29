@extends('layout')

@section('content')
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }

        .section {
            width: 100%;
            max-width: 1200px; /* Increase the max-width to allow more cards in a row */
            margin: 0 auto;
            padding: 20px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px; /* Add some gap between cards */
            justify-content: flex-start; /* Align cards to the start of the row */
        }

        .card-title {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        .card {
            flex: 0 0 calc(33.333% - 20px); /* 3 cards per row with 20px total gap */
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-body {
            padding: 20px;
        }

        .post-header {
            display: flex;
            align-items: center;
        }

        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .username {
            margin: 0;
            font-size: 1.1em;
            font-weight: bold;
        }

        .timestamp {
            margin: 0;
            color: #777;
            font-size: 0.9em;
        }

        .post-content {
            margin-top: 10px;
        }

        .post-content p {
            margin: 0 0 10px 0;
        }

        .post-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .like-button {
            display: inline-block;
            vertical-align: middle;
        }

        .like-count {
            display: inline-block;
            margin-left: 5px;
            vertical-align: middle;
            font-weight: bold;
        }

        .btn {
            vertical-align: middle;
        }

        .like-button:hover,
        .comment-button:hover,
        .share-button:hover {
            text-decoration: underline;
        }
    </style>
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
            <div class="row">
                <h5 class="card-title">Forum SS<span></span></h5>
                <div class="d-flex flex-row flex-wrap">
                    @foreach($data as $post)
                    <div class="card m-2" style="width: 18rem;">
                        <div class="card-body">
                            <div class="post-header">
                                <img src="assets/img/user.png" alt="User Avatar" class="avatar">
                                <div class="user-info">
                                    <h6 class="user">{{ $post->name }}</h6>
                                    <p class="timestamp">{{ $post->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="post-content">
                                <p>{{ $post->judul }}</p>
                            </div>
                            <div class="post-footer">
                                <button class="like-button"><i class="fas fa-thumbs-up"></i><span class="like-count">{{ $post->suka }}</span></button>
                                <a href="#" class="btn btn-success" title="Lihat"><i class="fa-solid fa-eye fa-1x"></i></a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>        
        
    </main><!-- End #main -->
@endsection
