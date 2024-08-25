<div>
  <!-- Well begun is half done. - Aristotle -->
</div>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Credential</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet" />
</head>

<body style="
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: #ffffff;
      font-size: 14px;
    ">
  <div style="
        max-width: 680px;
        margin: 0 auto;
        padding: 45px 30px 60px;
        background: #f4f7ff;
        background-image: url(https://archisketch-resources.s3.ap-northeast-2.amazonaws.com/vrstyler/1661497957196_595865/email-template-background-banner);
        background-repeat: no-repeat;
        background-size: 800px 452px;
        background-position: top center;
        font-size: 14px;
        color: #434343;
      ">
    <header>
      <table style="width: 100%;">
        <tbody>
          <tr style="height: 0;">
            <td style="text-align: right;">
              <span style="font-size: 16px; line-height: 30px; color: #ffffff;">{{ date('d, M Y') }}</span>
            </td>
          </tr>
        </tbody>
      </table>
    </header>

    <main>
      <div style="
            margin: 0;
            margin-top: 70px;
            padding: 92px 30px 115px;
            background: #ffffff;
            border-radius: 30px;
            text-align: center;
          ">
        <div style="width: 100%; max-width: 489px; margin: 0 auto;">
          <p style="
                margin: 0;
                margin-top: 17px;
                font-weight: 500;
                letter-spacing: 0.56px;
              ">
            Terima kasih telah memilih PT MITRA INTI BERSAMA. Gunakan data berikut
            untuk akses ke dashboard kami ❤️
          </p>

           <h2>Verifikasi OTP Anda</h2>
                  <form action="{{ route('otp.check') }}" method="POST">
                      @csrf
                         <label for="email">Email:</label>
                         <input type="email" id="email" name="email" required>
                         <br>
                         <label for="otp">OTP:</label>
                         <input type="text" id="otp" name="otp" required>
                         <br>
                         <button type="submit">Verify OTP</button>
                     </form>

                     @if(session('message'))
                         <p>{{ session('message') }}</p>
                     @endif

                     @if($errors->any())
                         <div>
                             <ul>
                                 @foreach($errors->all() as $error)
                                     <li>{{ $error }}</li>
                                 @endforeach
                             </ul>
                         </div>
                     @endif

                     <a href="{{route('password.request')}}">Not Working? Request New OTP</a>
        </div>
      </div>

      <p style="
            max-width: 400px;
            margin: 0 auto;
            margin-top: 90px;
            text-align: center;
            font-weight: 500;
            color: #8c8c8c;
          ">
        Butuh bantuan? tanya di
        <a href="mailto:archisketch@gmail.com" style="color: #499fb6; text-decoration: none;">admin@danaagung.com</a>
        atau kunjungi
        <a href="" target="_blank" style="color: #499fb6; text-decoration: none;">Help Center</a>
      </p>
    </main>

    <footer style="
          width: 100%;
          max-width: 490px;
          margin: 20px auto 0;
          text-align: center;
          border-top: 1px solid #e6ebf1;
        ">
      <p style="
            margin: 0;
            margin-top: 40px;
            font-size: 16px;
            font-weight: 600;
            color: #434343;
          ">
        PT MITRA INTI BERSAMA
      </p>
      <p style="margin: 0; margin-top: 8px; color: #434343;">
        Address.
      </p>
      <div style="margin: 0; margin-top: 16px;">
        <a href="" target="_blank" style="display: inline-block;">
          <img width="36px" alt="Facebook" src="https://archisketch-resources.s3.ap-northeast-2.amazonaws.com/vrstyler/1661502815169_682499/email-template-icon-facebook" />
        </a>
        <a href="" target="_blank" style="display: inline-block; margin-left: 8px;">
          <img width="36px" alt="Instagram" src="https://archisketch-resources.s3.ap-northeast-2.amazonaws.com/vrstyler/1661504218208_684135/email-template-icon-instagram" /></a>
        <a href="" target="_blank" style="display: inline-block; margin-left: 8px;">
          <img width="36px" alt="Twitter" src="https://archisketch-resources.s3.ap-northeast-2.amazonaws.com/vrstyler/1661503043040_372004/email-template-icon-twitter" />
        </a>
        <a href="" target="_blank" style="display: inline-block; margin-left: 8px;">
          <img width="36px" alt="Youtube" src="https://archisketch-resources.s3.ap-northeast-2.amazonaws.com/vrstyler/1661503195931_210869/email-template-icon-youtube" /></a>
      </div>
      <p style="margin: 0; margin-top: 16px; color: #434343;">
        Copyright © 2023. All rights reserved.
      </p>
    </footer>
  </div>
</body>

</html>