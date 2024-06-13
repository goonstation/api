<!DOCTYPE html>
<html>

<head>
  <style>
    * {
      box-sizing: border-box;
    }

    html,
    body {
      margin: 0;
      padding: 0;
      height: 100%;
    }

    body {
      display: flex;
      flex-direction: column;
      padding: 80px;
      background: #0f0f0f;
      border-top: 10px solid #ffd125;
      font: 26px sans-serif;
      color: white;
    }

    .header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 30px;
      opacity: 0.8;
    }

    .title {
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      text-overflow: ellipsis;
      margin-bottom: 60px;
      font-size: 60px;
    }

    .title span {
      opacity: 0.8;
    }

    .content-wrap {
      display: flex;
      flex-grow: 1;
    }

    .content {
      display: flex;
      flex-direction: column;
    }

    .logo {
      display: flex;
      align-items: flex-end;
      margin-left: auto;
    }

    .details {
      display: flex;
      align-items: center;
      gap: 40px;
    }

    .details>div,
    .footer>div {
      display: flex;
      align-items: center;
    }

    .details svg,
    .footer svg {
      width: 50px;
      margin-right: 20px;
    }

    .footer {
      display: flex;
      align-items: flex-end;
      justify-content: space-between;
      margin-top: auto;
    }

    .dimmed {
      opacity: 0.8;
    }
  </style>
</head>

<body>
  {{ $slot }}
</body>

</html>
