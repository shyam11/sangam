<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style type="text/css">
        td{
            font-size: 12px;
        }
    </style>
</head>

<body>

<body>

<style>
    table.trade-details{
        width:100%;
        text-align: center;
    }
    table.trade-details tr:nth-child(n+2) td
    {
        font-size: 16px;
    }

</style>
    <table class="trade-details">
        <tr><td><h1>M/S SANGAM ALMIRAH PVT. LTD. </h1></td></tr>
        <tr><td>PATAHI ROOP, MUZAFFARPUR</td></tr>
        <tr><td>BIHAR </td></tr>
        <tr><td>CIN: U36996BR2017PTC034229</td></tr>
        <tr><td>GISTIN: 10AAYCS4686B1Z5 </td></tr>
        <tr><td>Tel: +919102407962 | Email: sangamalmirahpvtltd@gmail.com </td></tr>
    </table>
    <table width="40%"></table>
    <div style="overflow: auto; clear: both;"></div>
    <table width>
        <tr>
            <td>
                <h1>{{$invoice_title}}</h1>
            </td>
        </tr>
    </table>
    <div style="overflow: auto; clear: both;"></div>
    <style>
        table.billing-detail tr:nth-child(n+2) td
        {
            font-size: 16px;
        }
        table.billing-detail tr:nth-child(1) td
        {
            font-size: 18px;
            font-weight: bold;
        }
        table.billing-detail{
            width: 60%;
            float: left
        }
    </style>
    <table class="billing-detail">
        <?php 
          $states = getAllState(1);
          $cities = getAllCities(1);
        ?>
        <tr>
            <td width="50%"><strong>BILL TO</strong></td>
            <td width="50%"><strong>SHIP TO</strong></td>
        </tr>
        <tr>
            <td>{{$dds->name}}</td>
            <td>{{$dds->name}}</td>
        </tr>
        <tr>
            <td>{{$dds->phone}}</td>
            <td>{{$dds->phone}}</td>
        </tr>
        <tr>
            <td>{{$dds->registered_address}}</td>
            <td>{{$dds->registered_address}}</td>
        </tr>
        <tr>
            <td>{{$dds->store_name}}</td>
            <td>{{$dds->store_name}}</td>
        </tr>
        <tr>
            <td>{{$cities[$dds->city_id]}}</td>
            <td>{{$cities[$dds->city_id]}}</td>
        </tr>
        <tr>
            <td>{{$states[$dds->state_id]}}</td>
            <td>{{$states[$dds->state_id]}}</td>
        </tr>
    </table>
    <style>
        .invoice-detail{
            text-align:center;
            float:right;
            width:40%;
        }
        .invoice-detail p{
            font-size: 12px;
        }
    </style>
    <table class="invoice-detail">
        <tr>
            <td>
                <p><strong>Bill NO.</strong> {{$order->id}} </p>
                <p><strong>Bill Date</strong>{{date('Y-m-d',strtotime($order->created_at))}} </p>
                @if($invoice_title == "Invoice")
                    <p><strong>Delivered Date</strong> {{date('Y-m-d',strtotime($order->updated_at))}} </p>
                @else
                    <p><strong>Due Date</strong> {{date('Y-m-d',strtotime($order->order_expiry))}} </p>
                @endif
            </td>
        </tr>
    </table>
    <div style="overflow: auto; clear: both;"></div>
    <br/>
    <hr/>
    <div style="overflow: auto; clear: both;"></div>
    <style>
        table.products{
            border-collapse: collapse;
        }
        table.products tr{
            margin:3px 0;
        }
        table.products td, table.products th{
            border:2px solid white;
        }
         table.products td
         {
             font-size: 16px;
         }
        table.products th
        {
            font-size: 18px;
            margin:3px;
            background-color: #7486a1;
            text-align:left;
        }
        table.products tr:nth-child(n+2) td
        {
            font-size: 16px;
        }
        table.products td:nth-child(1), table.products td:nth-child(2), table.products td:nth-child(4), table.products td:nth-child(5), table.products td:nth-child(6), table.products td:nth-child(7)
        {
            text-align:right;
        }
        table.products td:nth-child(3){
            text-align:left;
        }

    </style>
    <table width="100%" class="products">
        <thead style="">
            <tr>
                <th width="5%">NO.</th>
                <th width="33%">Item</th>
                <th width="12%">Category</th>
                <th width="10%">DD Price</th>
                <th width="8%">Add-On Price</th>
                <th width="17%">Quantity</th>
                <th width="15%">Total Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php $count = 1; ?>
            @foreach($itemOrders as $item)
            <tr>
                <td>{{$count++}}</td>
                <td>{{$item->product_name}}</td>
                <td>{{ucwords($item->category)}}</td>
                <td>{{$item->dd_price}}</td>
                <td>{{$item->attr_price}}</td>
                <td>{{$item->quantity}}</td>
                <td>{{$item->amount}}</td>
            @endforeach
            </tr>
        </tbody>
    </table>
<div style="overflow: auto; clear: both;"></div>
<br/>
<hr/>
<br/>
<div style="overflow: auto; clear: both;"></div>
<style>
    .amount-detail{
        width:100%;
    }
    .amount-detail td{
        font-size: 16px;
    }

</style>
<table class="amount-detail">
    <!--thead>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </thead-->
    <tbody>
        <tr>
            <td width="10%"> </td>
            <td width="20%"> </td>
            <td width="20%"> </td>
            <td width="10%"> </td>
            <td width="15%"> </td>
            <td width="15%"> </td>
            <td width="10%"> </td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td colspan="2">SUBTOTAL</td>
            <td colspan="2" style="text-align: right;">{{$order->subtotal}}</td>
        </tr>
        @if($order->gst_type == "SGST/CGST")
        <tr>
            <td colspan="3"></td>
            <td colspan="2">CGST @ 9% </td>
            <td colspan="2" style="text-align: right;">{{($order->subtotal * 9)/100}}</td>
        </tr>
        
        <tr>
            <td colspan="3"></td>
            <td colspan="2">SGST @ 9% </td>
            <td colspan="2" style="text-align: right;">{{($order->subtotal * 9)/100}}</td>
        </tr>
        @else
        <tr>
            <td colspan="3"></td>
            <td colspan="2">IGST @ </td>
            <td colspan="2" style="text-align: right;">{{($order->subtotal * 18)/100}}</td>
        </tr>
        @endif
        <tr>
            <td colspan="3"></td>
            <td colspan="2">GROSS TOTAL</td>
            <td colspan="2" style="text-align: right;">{{$order->total_amount}}</td>
        </tr>
        <tr>
            <td colspan="3">Sangam Almirah Pvt. Ltd.</td>
            <td colspan="4" style="text-align: left;">{{ getIndianCurrency($order->total_amount) }}</td>
        </tr>
        @if($invoice_title != "Invoice")
        <tr style="height:30px;">
            <td>Bank Details:</td>
            <td>CANARA BANK</td>
            <td>A/C NO - 749114000001012 </td>
            <td>IFSC CODE</td>
            <td>CNRB0017491</td>
            <td>BRANCH- BHAGWANPUR</td>
        </tr>
        @endif
        <tr style="height:30px;">Terms & Conditions:</tr>
        <tr>Terms & Conditions:</tr>
        <tr>1. Goods once sold will not be able to be taken back.</tr>
        <tr>2. Interest @ 18% p.a will be charged if the payment
is not made with in the stipulated time.</tr>
        <tr>3. Subject to Bihar Jurisdiction only.</tr>
        <tr>
            <td colspan="7">This is System Generated Invoice, doesn’t required Signature.</td>
        </tr>
    </tbody>
</table>


        <!-- <tr style="height:30px;">
            <td colspan="3">Authorized Signatory</td>
            <td colspan="2">BALANCE DUE</td>
            <td colspan="2" style="text-align: right; font-size:30px; font-weight: bold"><span style="font-family: DejaVu Sans, sans-serif; font-size:20px;">&#8377;</span> </td>
        </tr>
        <tr>
            <td colspan="3">Sangam Almirah Pvt. Ltd.</td>
            <td colspan="4" style="text-align: left;">{{ getIndianCurrency($order->total_amount) }}</td>
        </tr>
        <tr>
            <td colspan="7">This is System Generated Invoice, doesn’t required Signature.</td>
        </tr> -->

</body>
</html>
