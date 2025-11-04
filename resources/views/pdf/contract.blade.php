<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>猫譲渡契約書</title>
    <style>
        @font-face {
            font-family: 'NotoSansJP';
            src: url("{{ storage_path('fonts/NotoSansJP-Regular.ttf') }}") format('truetype');
            font-weight: normal;
        }

        @font-face {
            font-family: 'NotoSansJP';
            src: url("{{ storage_path('fonts/NotoSansJP-Bold.ttf') }}") format('truetype');
            font-weight: bold;
        }

        body {
            font-family: 'NotoSansJP';
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

        b,
        strong {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <h2>猫譲渡契約書</h2>

    <p>譲渡者（甲）と譲受者（乙）は以下内容に合意する。</p>

    <div class="section box">
        <p>年齢：{{ $pair->post->age }}歳</p>
        <p>性別：{{ $pair->post->gender === 1 ? 'オス' : 'メス' }}</p>
        <p>品種：{{ $pair->post->breed }}</p>
        <p>譲渡金額：{{ number_format($pair->post->cost) }}円</p>
    </div>

    <br><br>

    <table width="100%" style="border-collapse: collapse; margin-top: 40px;">
        <tr>
            <td width="50%" style="vertical-align: top; padding-right: 20px;">
                <strong>譲渡者（甲）</strong><br><br>
                住所：<br>
                _______________________________<br>
                _______________________________<br><br>
                氏名：__________________________　印<br><br>
                日付：__________________________
            </td>

            <td width="50%" style="vertical-align: top; padding-left: 20px;">
                <strong>譲受者（乙）</strong><br><br>
                住所：<br>
                _______________________________<br>
                _______________________________<br><br>
                氏名：{{ $transfer->buyer_signature ?? '________________________' }}　印<br><br>
                日付：{{ $transfer->signed_date ?? '________________________' }}
            </td>
        </tr>
    </table>

</body>

</html>
