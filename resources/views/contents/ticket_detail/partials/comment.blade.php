<div class="col-lg-6">
    <div class="row">
        <div class="col-12">
            <div class="card info-card">
                <div class="card-body pb-2">
                    <h5 class="card-title">Komentar</h5>
                    <div class="col-md-12">
                        <form action="/ticket-comments" method="POST">
                            @csrf
                            <input type="text" name="user_id" value="{{ auth()->user()->id }}" hidden>
                            <input type="text" name="ticket_id" value="{{ $ticket->id }}" hidden>
                            <textarea name="komentar" class="form-control h-50" id="komentar" rows="4" placeholder="Tulis komentar anda..." required></textarea>
                            <!-- Showing notification error for input validation -->
                            @error('komentar')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                            <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                            <a href="#"><button type="submit" class="btn btn-sm btn-primary float-end mt-2 ms-1"><i class="bi bi-send me-1"></i> Kirim</button></a>
                        </form>
                        @if(session()->has('commentSuccess'))
                        <script>
                            swal("Terkirim!", "{{ session('commentSuccess') }}", "success", {
                                timer: 3000
                            });
                        </script>
                        @endif
                    </div>
                </div><!-- End Card Body -->
            </div><!-- End Info Card -->
        </div><!-- End col-12 -->
    </div> <!-- End row -->
</div> <!-- End col-lg-6 -->

<!-- Right side columns -->
<div class="col-lg-6 flex-nowrap">
    <!-- Recent Activity -->
    <div class="card py-4 h-100" style="max-height: 232px; overflow: hidden;">
        <div class="card-body overflow-auto">
            <div class="activity">
                @if($checkComment == 0)
                <p class="text-center">Belum ada komentar.</p>
                @else
                @foreach($comments as $comment)
                <div class="activity-item d-flex">
                    <div class="activite-label pe-3">{{ date('d-M-Y H:i', strtotime($comment->created_at)) }}</div>
                    <i class='bi bi-circle-fill activity-badge text-secondary align-self-start'></i>
                    <div class="activity-content">
                        @if(auth()->user()->nama == $comment->user->nama)
                        <a href="#" class="fw-bold text-dark pe-1">{{ ucwords($comment->user->nama) }}</a><span class="badge bg-info text-capitalize">saya</span></td> : {{ $comment->komentar }}
                        @else
                        <a href="#" class="fw-bold text-dark">{{ ucwords($comment->user->nama) }}</a></td> : {{ $comment->komentar }}
                        @endif
                    </div>
                </div><!-- End activity item-->
                @endforeach
                @endif
            </div>
        </div>
    </div><!-- End Recent Activity -->
</div><!-- End col-lg-6 -->