<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>GRN Document</title>
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
      width: 100%;
    }

    .footer {
       padding: 50px;
    }

  </style>
</head>
<body>
  <div class="header">
    <h2 class="titles">GOODS RECEIVED NOTE</h2>
    <p class="location content-text">{{$grn->warehouse->region}}</p>
    <p class="warehouse-phone">Phone No: {{$grn->warehouse->mainContactTel}}</p>
  </div>
  <div class="header-subcontent">
    <h2 class="titles">Crop Owner</h2>
    <p style="font-weight: bold;" class="content-text">Member/Client</p>
    <p class="content-text">{{$grn->actor->name}}</p>
  </div>

  <div class="body-groups">
    <h2 class="titles">Crop Detail</h2>
    <table class="body-content">
      <tr>
        <td>Production Area</td>
        <td>Commodity</td>
        <td>Commodity Class</td>
        <td>Origin</td>
        <td>Harvest Year</td>
      </tr> 
      <tr>
        <td>N/A</td>
        <td>{{$grn->commodity->commodityName}}</td>
        <td></td>
        <td>{{$grn->actor->region}}</td>
        <td>{{date('Y', strtotime($grn->commodity->created_at))}}</td>
      </tr>
    </table>
  </div>

  <div class="body-groups">
    <h2 class="titles">Crop Location</h2>
    <table class="body-content">
      <tr>
        <td>Tracking Code</td>
        <td>Warehouse</td>
        <td>Shed</td>
        <td>Stack</td>
      </tr> 
      <tr>
        <td>{{ $grn->grnidno }}</td>
        <td>{{$grn->warehouse->registeredName}}</td>
        <td> - </td>
        <td> - </td>
      </tr>
    </table>
  </div>

  <div class="body-groups">
    <h2 class="titles">Crop History</h2>
    <table class="body-content">
      <tr>
        <td>Production Area</td>
        <td>Commodity</td>
      </tr> 
    </table>
  </div>

  <div class="body-groups">
    <h2 class="titles">Mandatory Fees</h2>
    <table class="body-content-service">
      <tr class="body-content-header">
        <td>Service</td>
        <td>Fee (GHC)</td>
        <td>Service</td>
        <td>Fee (GHC)</td>
        <td>Service</td>
        <td>Fee (GHC)</td>
      </tr> 
      <tr>
        <td>Grading, Weighing & Rebagging (Per Bag)</td>
        <td></td>
        <td>Moisture Loss (% Volume)</td>
        <td></td>
        <td>Handling (Per Bag)</td>
        <td></td>
      </tr>
      <tr>
        <td>Weighing (Per Bag)</td>
        <td></td>
        <td>Receipting Fee (Per MT)</td>
        <td></td>
        <td>Trading (% Value)</td>
        <td></td>
      </tr>
      <tr>
        <td>Central Depository (% Value)</td>
        <td></td>
        <td>Storage (Bag/Month)</td>
        <td></td>
        <td>Regulatory Fee (% Value)</td>
        <td></td>
      </tr>
    </table>
  </div>

  <div class="body-groups">
    <h2 class="titles">Optional Service Fees</h2>
    <table class="body-content-service">
      <tr class="body-content-header">
        <td>Service</td>
        <td>Fee (GHC)</td>
        <td>Service</td>
        <td>Fee (GHC)</td>
        <td>Service</td>
        <td>Fee (GHC)</td>
      </tr> 
      <tr>
        <td>Drying (Per Bag)</td>
        <td></td>
        <td>Re-bagging (Per Bag)</td>
        <td></td>
        <td>Fumigation (Per MT)</td>
        <td></td>
      </tr>
      <tr>
        <td>Cleaning (Per Bag)</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    </table>
  </div>

  <div>
    <table class="sign-content">
      <tr>
        <td>
          <p style="font-weight: bold;">Received By</p>
          <p style="border-bottom: 1px solid black; width: 50%; margin-top:10px;">{{ $grn->actor->name }}</p>
        </td>
        <td>
          <p style="font-weight: bold;">Staff Name</p>
          <p style="border-bottom: 1px solid black; width: 50%; margin-top:10px;">{{ $grn->lastUpdatedByName }}</p>
        </td>
      </tr>
      <tr>
        <td>
          <p style="margin-top: 10px; font-weight: bold;">Date</p>
          <p style="border-bottom: 1px solid black; width: 50%; margin-top:10px;"> {{ date('jS F, Y', strtotime($grn->created_at)) }} </p>
        </td>
        <td>
          <p style="margin-top: 10px; font-weight: bold;">Date</p>
          <p style="border-bottom: 1px solid black; width: 50%; margin-top:10px;"> {{ date('jS F, Y', strtotime($grn->created_at)) }} </p>
        </td>
      </tr>
    </table>
  </div>
  <div class="footer">
    <ul>
      <li style="text-align: center">This GRN is Non-Trading</li>
    </ul>
  </div>
</body>
</html>