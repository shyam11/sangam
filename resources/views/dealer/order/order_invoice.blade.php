@extends('layouts.admin')
@section('content')
<style>
.top_rw{ background-color:#f4f4f4; }
	.td_w{ }
	button{ padding:5px 10px; font-size:14px;}
    .invoice-box {
        max-width: 890px;
        margin: auto;
        padding:10px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 14px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }
    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
		border-bottom: solid 1px #ccc;
    }
    .invoice-box table td {
        padding: 5px;
        vertical-align:middle;
    }
    .invoice-box table tr td:nth-child(2) {
        text-align: right;
    }
    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }
    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
    }
    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }
    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
		font-size:12px;
    }
    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }
    .invoice-box table tr.item td{
        border-bottom: 1px solid #eee;
    }
    .invoice-box table tr.item.last td {
        border-bottom: none;
    }
    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }
    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }
        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }
    /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }
    .rtl table {
        text-align: right;
    }
    .rtl table tr td:nth-child(2) {
        text-align: left;
    }
    @media print {
        #printPageButton {
            display: none;
        }
      }
</style>
    <div class="invoice-box">
    <div style="max-width: 700px; width: 100%; margin: 0 auto;">
        <table style="border-collapse: collapse;width: 100%;">
            <tr>
            <td colspan="2" align="center" style="font-size: 20px;">
                <strong>M/S SANGAM ALMIRAH PVT. LTD.</strong>
                <h5 style="font-size: 15px;margin-top: 7px;">PATAHI ROOP, MUZAFFARPUR</h5>
                <h5 style="font-size: 15px;margin-top: 7px;">BIHAR</h5>
                <h5 style="font-size: 15px;margin-top: 7px;">CIN: U36996BR2017PTC034229</h5>
                <h5 style="font-size: 15px;margin-top: 7px;">GISTIN:  10AAYCS4686B1Z5</h5>
                <h5 style="font-size: 15px;margin-top: 7px;">Tel: +919102407962 | Email: sangamalmirahpvtltd@gmail.com</h5>
            </td>
            </tr>
        </table>
    </div>
    <div class="table-responsive"> 
        <table cellpadding="0" cellspacing="0">
            <tr class="information">
                  <td colspan="3">
                    <table>
                        <tr>
                            <td colspan="2">
							<b> Billed To: {{$dds->store_name}} </b> <br>
                                {{$dds->phone}} <br>
                                {{$dds->gst_no}}<br>
								{{$dds->registered_address}}
                            </td>
                            <td> <b> Shipped To: {{$dds->store_name}} </b> <br>
                                {{$dds->phone}} <br>
                                {{$dds->gst_no}}<br>
								{{$dds->registered_address}}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <td colspan="3">
            <table cellspacing="0px" cellpadding="2px">
            <tr class="heading">
                <td style="width:25%;">
                    ITEM
                </td>
                <td style="width:10%; text-align:center;">
                    CATEGORY
                </td>
				<td style="width:10%; text-align:center;">
                    DD PRICE (INR)
                </td>
                <td style="width:10%; text-align:center;">
                    ADD-ON PRICE (INR)
                </td>
				 <td style="width:15%; text-align:right;">
                   QUANTITY
                </td>
				 <td style="width:15%; text-align:right;">
                   TOTAL AMOUNT (INR)
                </td>
            </tr>
            <?php $totalPartyPrice = 0;$addonsPrice = 0; $quantity = 0; $total_amount =0;?>
            @foreach($itemOrders as $order)
            <?php
                $totalPartyPrice += $order->dd_price;
                $addonsPrice += 800;
                $quantity += $order->quantity;
                $total_amount += $order->amount;
                $order_id = $order->order_id;
            ?>
			<tr class="item">
               <td style="width:25%;">
                    {{$order->product_name}}
                </td>
				<td style="width:10%; text-align:center;">
                    {{ucwords($order->category)}}
                </td>
                <td style="width:10%; text-align:right;">
                    {{$order->dd_price}}
                </td>
				 <td style="width:15%; text-align:right;">
                   {{$order->attr_price}}
                </td>
				 <td style="width:15%; text-align:right;">
                    {{$order->quantity}}
                </td>
				 <td style="width:15%; text-align:right;">
                    {{$order->amount}}
                </td>
            </tr>
            @endforeach
			<tr class="item">
               <td style="width:25%;"> <b>  </b> </td>
				<td style="width:10%; text-align:center;">

                </td>
                <td style="width:10%; text-align:right;">

                </td>
				 <td style="width:15%; text-align:right;">
                </td>
				 <td style="width:15%; text-align:right;">
                    {{$quantity}}
                </td>
				 <td style="width:15%; text-align:right;">
                    {{$total_amount}}
                </td>
            </tr>
            </td>
            <?php $cgst = ($total_amount * 9)/100; ?>
            <?php $sgst = 0; $sgst = ($total_amount * 9)/100; ?>
            <?php $igst = 0; $igst = ($total_amount * 18)/100; ?>
            @if($dds->state_id == 4)
            <tr class="item">
               <td style="width:25%;"> <b>  </b> </td>
				<td style="width:10%; text-align:center;">

                </td>
                <td style="width:10%; text-align:right;">

                </td>
				 <td style="width:15%; text-align:right;">Add : CGST
                </td>
				 <td style="width:15%; text-align:right;">
                    @ 9 %
                </td>
				 <td style="width:15%; text-align:right;">
                    {{($total_amount * 9)/100}}
                </td>
            </tr>
            <tr class="item">
               <td style="width:25%;"> <b>  </b> </td>
				<td style="width:10%; text-align:center;">

                </td>
                <td style="width:10%; text-align:right;">

                </td>
				 <td style="width:15%; text-align:right;">Add : SGST
                </td>
				 <td style="width:15%; text-align:right;">
                    @ 9 %
                </td>
				 <td style="width:15%; text-align:right;">
                    {{($total_amount * 9)/100}}
                </td>
            </tr>
            @else
            <tr class="item">
               <td style="width:25%;"> <b>  </b> </td>
				<td style="width:10%; text-align:center;">

                </td>
                <td style="width:10%; text-align:right;">

                </td>
				 <td style="width:15%; text-align:right;">Add : IGST
                </td>
				 <td style="width:15%; text-align:right;">
                    @ 18 %
                </td>
				 <td style="width:15%; text-align:right;">
                    {{($total_amount * 18)/100}}
                </td>
            </tr>
            @endif
            <tr class="item">
               <td style="width:25%;"> <b> GROSS TOTAL  </b> </td>
				<td style="width:10%; text-align:center;">

                </td>
                <td style="width:10%; text-align:right;">

                </td>
				 <td style="width:15%; text-align:right;">
                </td>
				 <td style="width:15%; text-align:right;">

                </td>
				 <td style="width:15%; text-align:right;">
                    @if($dds->state_id == 4) {{$total_amount + $igst}}  @else {{$total_amount + ($sgst + $cgst)}} @endif
                </td>
            </tr>
			</table>
            <tr class="total">
                  <td colspan="3" align="right">  Total Amount in Words :  <b> {{getIndianCurrency(round($total_amount + $igst))}} </b> </td>
            </tr><br><br><br><br>
            <div class="table-responsive">
            <table cellspacing="0px" cellpadding="2px">
            <tr class="item">
               <td style="width:10%;"> <b> Bank Details:  </b> </td>
                <td style="width:10%; text-align:center;">
                    CANARA BANK;
                </td>
                <td style="width:20%; text-align:right;">
                    A/C NO - 749114000001012;
                </td>
                 <td style="width:15%; text-align:right;">IFSC CODE-
                </td>
                 <td style="width:15%; text-align:right;">
                    CNRB0017491;
                </td>
                 <td style="width:20%; text-align:right;">
                   BRANCH- BHAGWANPUR
                </td>
            </tr>
        </table>
    </div>
			<tr>
			   <td colspan="3">
			     <table cellspacing="0px" cellpadding="2px">
				    <tr>
			            <td width="50%">
						<b> Terms & Conditions: </b> <br>
                        E.& O.E <br>
                        1. Goods once sold will not be able to be taken back.<br>
                        2. Interest @ 18% p.a will be charged if the payment<br>
                            is not made with in the stipulated time.<br>
                        3. Subject to 'Bihar' Jurisdiction only.
						</td>
						<td>
						 * This is a computer generated invoice and does not
						  require a physical signature
						</td>
			        </tr>
					 <tr>
			            <td width="50%">
						</td>
						<td>
						 	<b> Authorized Signature </b>
							<br>
							<br>
							...................................
							<br>
							<br>
							<br>
						</td>
			        </tr>
			     </table>
                <div class="row">
                    <div class="col-sm-2">
                        <input type="button" class="btn btn-danger" id="printPageButton" onClick="window.print()" value="Generate PDF">
                    </div>
                </div>
			   </td>
			</tr>
        </table>
    </div>
</td>
</table>
</div>


@endsection
