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
            border-bottom: 1px solid #625f5f;
            height: 100px;
            margin-bottom: 20px;
            padding-top: 20px;
        }

        .header>h2 {
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
            padding-bottom: 10px;
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

        .header .logo {
            font-size: 30px;
            font-weight: bolder;
            margin-bottom: 10px;
        }


        .header .logo-msr {
            background-color: #FCB756;
            color: white;
            padding: 5px;
        }
    </style>
</head>

<body>
    <div class="header">
        <p class="logo">
            <span style="color:blue;">US</span><span style="color: red;">AID</span> <span class="logo-msr">MSR</span>
        </p>
        <h2 style="margin: 0;" class="titles">GOODS RECEIVED NOTE</h2>
        <p style="margin: 0;" class="location content-text">{{ $gin->ginidno }}</p>
    </div>
    <div class="header-subcontent">
        <p class="content-text"> <span style="font-weight: bold">Crop Owner:</span> {{ $gin->actor->name }}</p>
        <p class="content-text"> <span style="font-weight: bold">Phone No.:</span> {{ $gin->actor->contactPhone }}</p>
        <p class="content-text"> <span style="font-weight: bold">Region:</span> {{ $gin->actor->region }}</p>
    </div>

  
    <div class="body-groups">
      <h2 class="titles">Warehouse Details</h2>
      <table class="body-content">
          <tr>
              <td>Warehouse</td>
              <td>Operator</td>
              <td>Transacton Type</td>
          </tr>
          <tr>
              <td>{{ $gin->warehouse->registeredName }}/td>
              <td>{{ $gin->warehouse->lastUpdatedByName }}</td>
              <td>{{ $gin->order->transactionType }} </td>
          </tr>
      </table>
  </div>

  <div class="body-groups">
    <h2 class="titles">Crop Detail</h2>
    <table class="body-content">
        <tr>
            <td>Commodity</td>
            <td>Harvest Year</td>
            <td>Package Size</td>
            <td>Quantity</td>
        </tr>
        <tr>
            <td>{{ $gin->commodity->commodityName }}</td>
            <td>{{ date('Y', strtotime($gin->commodity->created_at)) }}</td>
            <td>{{ json_decode($gin->order->orderDetails)->package_size }}</td>
            <td>{{ json_decode($gin->order->orderDetails)->quantity }}</td>
        </tr>
    </table>
</div>

    <div>
        <table class="sign-content">
            <tr>
                <td>
                    <p>Received By:</p>
                </td>
                <td>
                    <p>{{ $gin->actor->name }}</p>
                </td>
            </tr>
            
            <tr>
                <td>
                    <p>Date:</p>
                </td>
                <td>
                    <p style="border-bottom: 1px solid black; width: 50%; margin-top:10px;">
                        {{ date('jS F, Y', strtotime($gin->created_at)) }} </p>
                </td>
            </tr>

        </table>
    </div>

</body>

</html>
