<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}

                    <!-- 予約ページに移動するボタン -->
                    <a href="{{ route('reservation.index') }}" class="btn btn-primary mt-3">
                        Go to Reservation Page
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
