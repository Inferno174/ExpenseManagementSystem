<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Insufficient Funds</title>
  <style>
    body {
      font-size: 16px;
      background: #f6f6f5;
      font-family: "HK Grotesk", sans-serif;
    }

    .wrapper {
      max-width: 567px;
      margin: 32px auto;
      background-color: #fff;
      border: 1px solid #ddd;
    }

    .header {
      padding: 24px;
      background-color: black;
      text-align: center;
    }

    .header img {
      width: 200px;
    }

    .content {
      padding: 32px;
    }

    .footer {
      padding: 20px 32px;
      background-color: #000000;
      color: #ffffff;
      font-size: 14px;
      line-height: 1.6;
      text-align: center;
    }
  </style>
</head>

<body>
  <div class="wrapper">
    <div class="header">
      <a href="https://wayjobs.in/">
        <img src="https://wayjobs.in/public/assets/imgs/theme/Wayjobslogo.png" alt="WayJobs Logo" />
      </a>
    </div>
    <div class="content">
      <p>Hello {{$details}},</p>
      <p>You are lacking funds.</p>
    </div>
    <div class="footer">
      <p>Expense Management System</p>
    </div>
  </div>
</body>

</html>
