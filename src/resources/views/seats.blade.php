<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>座席予約システム</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .seat-map {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            margin-top: 20px;
        }
        button {
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
        }
        .btn-white { background-color: white; color: black; }
        .btn-blue { background-color: blue; color: white; }
        .btn-red { background-color: red; color: white; }
        #modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border: 1px solid #ccc;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }
        #modal-content {
            margin-bottom: 20px;
        }
        #close-modal {
            display: inline-block;
            padding: 10px;
            background-color: #ddd;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        #close-modal:hover {
            background-color: #bbb;
        }
        #overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
    </style>
</head>
<body>

<pre>{{ var_dump($reservations) }}</pre>

<div class="container">
    <h1>座席予約</h1>
    <form method="GET" action="{{ route('reservations.index') }}">
        <label for="date">日付を選択:</label>
        <input type="date" id="date" name="date" value="{{ $date }}">
        <button type="submit">表示</button>
    </form>

    <div class="seat-map">
        @for ($i = 1; $i <= 20; $i++) <!-- 座席数20 -->
        @php
            $reservation = $reservations->firstWhere('seat_number', $i);
            $class = 'btn-white';
            $action = null;
            if ($reservation) {
                if ($reservation->user_id === $userId) {
                    $class = 'btn-blue';
                    $action = 'cancel';
                } else {
                    $class = 'btn-red';
                    $action = 'details';
                }
            }
        @endphp

        <button class="{{ $class }}" data-seat="{{ $i }}" data-action="{{ $action }}">
            {{ $i }}
            @if ($action === 'cancel')
                キャンセル
            @elseif ($action === 'details')
                詳細
            @else
                予約
            @endif
        </button>
        @endfor
    </div>
</div>

<!-- モーダル -->
<div id="overlay"></div>
<div id="modal">
    <div id="modal-content"></div>
    <button id="close-modal">閉じる</button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('modal');
        const overlay = document.getElementById('overlay');
        const modalContent = document.getElementById('modal-content');
        const closeModalButton = document.getElementById('close-modal');

        document.querySelectorAll('.seat-map button').forEach(button => {
            button.addEventListener('click', function () {
                const seat = this.getAttribute('data-seat');
                const action = this.getAttribute('data-action');

                if (action === 'cancel') {
                    if (confirm('予約をキャンセルしますか？')) {
                        fetch(`/reservations/${seat}`, { method: 'DELETE' })
                            .then(() => location.reload());
                    }
                } else if (action === 'details') {
                    fetch(`/reservations/${seat}`)
                        .then(response => response.json())
                        .then(data => {
                            modalContent.textContent = `座席番号: ${data.seat_number}\n予約者: ${data.user_id}\nメモ: ${data.notes}`;
                            modal.style.display = 'block';
                            overlay.style.display = 'block';
                        });
                } else {
                    modalContent.innerHTML = `
        <h2>新しい予約</h2>
        <label for="notes">備考</label>
        <textarea id="notes"></textarea>
        <button id="submit-reservation">予約する</button>
    `;
                    modal.style.display = 'block';
                    overlay.style.display = 'block';

                    document.getElementById('submit-reservation').addEventListener('click', function () {
                        const notes = document.getElementById('notes').value;
                        fetch('/reservations', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ seat_number: seat, date, notes })
                        })
                            .then(() => {
                                modal.style.display = 'none';
                                overlay.style.display = 'none';
                                location.reload();
                            })
                            .catch(error => alert('予約に失敗しました: ' + error.message));
                    });
                }
            });
        });

        closeModalButton.addEventListener('click', () => {
            modal.style.display = 'none';
            overlay.style.display = 'none';
        });

        overlay.addEventListener('click', () => {
            modal.style.display = 'none';
            overlay.style.display = 'none';
        });
    });
</script>
</body>
</html>
