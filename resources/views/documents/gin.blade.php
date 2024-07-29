<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>GIN Document</title>
  <style>



    * {
      margin: 0;
      padding: 0;
    }

    body {
      margin: 30px;
      font-family: Arial, Helvetica, sans-serif;
      font-size: 9px;
    }

    .header {
      border: 1px dashed black;
      height: 100px;
      margin-bottom: 20px;

    }

    .header > h2 {
       text-align: center;
       margin: 20px;
    }

    .titles {
      font-size: 18px;
    }

    .content-text {
      font-size: 14px;
    }

    .location {
      text-align: center;
    }

    .warehouse-phone {
      text-align: right;
      padding-right: 5px;
    }

    .header-subcontent {
      border-bottom: 1px solid #625f5f;
      padding-bottom:10px;
      margin-bottom: 20px;
    }

    .body-groups {
      margin-bottom: 20px;
    }

    .body-content {
      margin-top: 10px;
      width: 100%;
      border-collapse: collapse;
    }

    .body-content-service {
      border: 1px solid black;
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    .body-content-header td {
      border: 1px solid black;
      padding: 5px;
    }

    .body-content-service tr td {
      border-right: 1px solid black;
      padding: 5px;
    }


    .body-content tr td {
      border: 1px solid black;
      padding: 5px;
    }


    .sign-content {
      width: 40%;
      margin-top: 200px;
    }

    .footer {
       padding: 50px;
    }

    .header-content {
      width: 100%;
    }

  </style>
</head>
<body>
  <div class="header">
    <h2 class="titles">GOODS ISSUE NOTE</h2>
    <p class="location content-text">{{$gin->warehouse->region}}</p>
    <p class="warehouse-phone">Phone No: {{$gin->warehouse->mainContactTel}}</p>
  </div>
  <div class="header-subcontent">
    <table class="header-content">
      <tr>
        <td>
          <p style="font-weight: bold;">Member</p>
          <p>N/A</p>
        </td>
        <td>
          <p style="font-weight: bold;">Member ID</p>
          <p>N/A</p>
        </td>
      </tr>
      <tr>
        <td>
          <p style="font-weight: bold;">Client</p>
          <p>{{$gin->actor->name}}</p>
        </td>
        <td>
          <p style="font-weight: bold;">Client ID</p>
          <p>N/A</p>
        </td>
      </tr>
    </table>
  </div>

  <div class="body-groups">
    <h2 class="titles">Crop Detail</h2>
    <table class="body-content">
      <tr>
        <td>Commodity</td>
        <td>Harvest Year</td>
        <td>Grade</td>
        <td>Symbol</td>
        <td>No. Of Bags</td>
        <td>Bag Type</td>
        <td>Weight</td>
      </tr> 
      <tr>
        <td>{{$gin->commodity->commodityName}}</td>
        <td>{{date('Y', strtotime($gin->commodity->created_at))}}</td>
        <td>-</td>
        <td>-</td>
        <td>{{ $gin->noBagsIssued }}</td>
        <td>-</td>
        <td>{{ round(floatval($gin->noBagsIssued) * floatval($gin->weightPerBag), 4) }}</td>
      </tr>
    </table>
  </div>

  <div>
    <table class="sign-content">
      <tr>
        <td>
          <p >Received By:</p>
        </td>
        <td>
          <p></p>
        </td>
      </tr>
      <tr>
        <td>
          <p >Signature:</p>
        </td>
        <td>
          <p></p>
        </td>
      </tr>
      <tr>
        <td>
          <p >IC:</p>
        </td>
        <td>
          <p> {{ $gin->actor->name }} </p>
        </td>
      </tr>
      <tr>
        <td>
          <p >Date:</p>
        </td>
        <td>
          <p style="border-bottom: 1px solid black; width: 50%; margin-top:10px;"> {{ date('jS F, Y', strtotime($gin->created_at)) }} </p>
        </td>
      </tr>

    </table>
  </div>

</body>
</html>