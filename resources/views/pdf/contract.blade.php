<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>猫譲渡契約書</title>
    <style>
        body {
            font-family: 'Noto Sans JP', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 30px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 18px;
        }
        .box {
            border: 1px solid #666;
            padding: 10px;
        }
    </style>
</head>
<body>

<h2>猫譲渡契約書</h2>

<p>譲渡者（甲）と譲受者（乙）は以下内容に合意する。</p>

<div class="section box">
    <p>年齢：{{ $pair->post->age }}歳</p>
    <p>性別：{{ $pair->post->gender === 'male' ? 'オス' : 'メス' }}</p>
    <p>品種：{{ $pair->post->breed }}</p>
    <p>譲渡金額：{{ number_format($pair->post->cost) }}円</p>
</div>

<p>署名（乙）：{{ $transfer->buyer_signature ?? '' }}</p>
<p>署名日：{{ $transfer->signed_date ?? '' }}</p>

<br><br>
<p>署名（甲）：{{ $transfer->seller_signature ?? '' }}</p>
<p>署名日：{{ $transfer->seller_signed_date ?? '' }}</p>

</body>
</html>
