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
            padding-bottom: 20px;
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
        <p style="margin: 0;" class="location content-text">{{ $grn_id }}</p>
    </div>
    <div class="header-subcontent">
        <p class="content-text"> <span style="font-weight: bold">Crop Owner:</span> {{ $grn->actor->name }}</p>
        <p class="content-text"> <span style="font-weight: bold">Phone No.:</span> {{ $grn->actor->contactPhone }}</p>
        <p class="content-text"> <span style="font-weight: bold">Region:</span> {{ $grn->actor->region }}</p>
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
                <td>{{ $grn->warehouse->registeredName }} </td>
                <td>{{ $grn->warehouse->lastUpdatedByName }}</td>
                <td>{{ $grn->order->transactionType }} ({{ $grn->maxStorageDuration }})</td>
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
                <td>{{ $grn->commodity->commodityName }}</td>
                <td>{{ date('Y', strtotime($grn->commodity->created_at)) }}</td>
                <td>{{ json_decode($grn->order->orderDetails)->package_size }}</td>
                <td>{{ json_decode($grn->order->orderDetails)->quantity }}</td>
            </tr>
        </table>
    </div>

    {{-- <div class="body-groups">
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
                <td>{{ $grn->warehouse->registeredName }}</td>
                <td> - </td>
                <td> - </td>
            </tr>
        </table>
    </div> --}}

    {{-- <div class="body-groups">
        <h2 class="titles">Crop History</h2>
        <table class="body-content">
            <tr>
                <td>Production Area</td>
                <td>Commodity</td>
            </tr>
        </table>
    </div> --}}

    <div class="body-groups">
        <h2 class="titles">Mandatory Fees</h2>
        <table class="body-content-service">
            <tr class="body-content-header">
                <td>Service</td>
                <td>Fee (GHC)</td>
                <td>Service</td>
                <td>Fee (GHC)</td>
                {{-- <td>Service</td>
                <td>Fee (GHC)</td> --}}
            </tr>

            <tr>
                <td>Weighing & Rebagging</td>
                <td>{{ $fees->rebaggingFee ?? "" }}</td>
                <td>Storage (Bag/Month)</td>
                <td>{{ $fees->storageFee ?? "" }}</td>
                {{-- <td>Receipt Fee</td>
                <td></td> --}}
            </tr>
            {{-- <tr>
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
            </tr> --}}
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
                <td>Loading</td>
                <td>{{ $fees->loadingFee ?? "" }}</td>
                <td>Cleaning (Per Bag)</td>
                <td>{{ $fees->cleaningFee ?? "" }}</td>
                <td>Unloading</td>
                <td>{{ $fees->unloadingFee ?? "" }}</td>
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
                {{-- <td>
                    <p style="font-weight: bold;">Staff Name</p>
                    <p style="border-bottom: 1px solid black; width: 50%; margin-top:10px;">
                        {{ $grn->lastUpdatedByName }}</p>
                </td> --}}
            </tr>
            <tr>
                <td>
                    <p style="margin-top: 10px; font-weight: bold;">Date</p>
                    <p style="border-bottom: 1px solid black; width: 50%; margin-top:10px;">
                        {{ date('jS F, Y', strtotime($grn->created_at)) }} </p>
                </td>
                {{-- <td>
                    <p style="margin-top: 10px; font-weight: bold;">Date</p>
                    <p style="border-bottom: 1px solid black; width: 50%; margin-top:10px;">
                        {{ date('jS F, Y', strtotime($grn->created_at)) }} </p>
                </td> --}}
            </tr>
        </table>
    </div>
    {{-- <div class="footer">
        <ul>
            <li style="text-align: center">This GRN is Non-Trading</li>
        </ul>
    </div> --}}
</body>

</html>
