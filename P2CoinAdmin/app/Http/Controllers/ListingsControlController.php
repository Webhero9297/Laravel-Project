<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Common;

class ListingsControlController extends Controller
{
    //
    public function index() {
        return view("listingscontrol.index")->with(['totalusers'=>$this->totalusers,'volume'=>$this->volume]);
    }
    public function viewalllistings() {
        header("Content-type:application/html");
        $retHTML = '<thead>
                                                <tr role="row">
                                                    <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="" style="width: 47px;">Seller</th>
                                                    <th class="sorting_desc" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-sort="descending" aria-label=" Username : activate to sort column ascending" style="width: 93px;">Currency</th>
                                                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label=" Email : activate to sort column ascending" style="width: 148px;">Terms Of Trade</th>
                                                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label=" Joined : activate to sort column ascending" style="width: 67px;">Payment_Details</th>
                                                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label=" Actions : activate to sort column ascending" style="width: 70px;">Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
        $model = new Common();
        $listing_data = $model->getAllListingsData();
// dd($listing_data);  
        if ( $listing_data )    {
            foreach( $listing_data as $listing ) {
                $retHTML .= "<tr class='gradeX odd' role='row'>
                        <td>".$listing->name."</td>
                        <td class='sorting_1'>".(strtoupper($listing->coin_type))."</td>
                        <td>
                            ".$listing->terms_of_trade."
                        </td>
                        <td class='center'>".$listing->payment_method."</td>
                        <td align=center>
                            <a class='delete_listing btn red btn-outline' data-toggle='modal' href='#confirm_dialog' listing_id='".$listing->id."' onclick='doOnDelete(this)'>Delete
                            </a>
                        </td>
                    </tr>";
            }
        }           
        else {
            $retHTML .= "<tr><td colspan='8'><div style='font-weight: bolder;font-size: 24px;'>No Data</div></td></tr>";
        }
        
        $retHTML .= '</tbody>';
        echo $retHTML;
        exit;
    }
    public function viewreportedlistings() {
        header("Content-type:application/html");
        $retHTML = '<thead>
                        <tr role="row">
                            <th class="sorting">Report User</th>
                            <th class="sorting" >Listing ID</th>
                            <th class="sorting">Username</th>
                            <th class="sorting">Listing Amount</th>
                            <th class="sorting">Payment Method</th>
                            <th class="sorting">payment Details</th>
                            <th class="sorting">Report Reason</th>
                            <th class="sorting" >Action</th>
                        </tr>
                    </thead>
                    <tbody>';
        $model = new Common();
        $listing_data = $model->getAllReportedListingsData();
        if ($listing_data)  {
            foreach( $listing_data as $listing ) {
                $listUser = $model->getUserinfoById($listing->user_id);
                $retHTML .= "<tr class='gradeX odd' role='row'>
                        <td>".$listing->name."</td>
                        <td class='sorting_1'>".$listing->listing_id."</td>
                        <td class='sorting_1'>".$listUser['name']."</td>
                        <td class='sorting_1'>".$listing->coin_amount.strtoupper($listing->coin_type)."</td>
                        <td class='sorting_1'>".$listing->payment_method."</td>
                        <td class='sorting_1'>".$listing->payment_details."</td>
                        <td>".$listing->report_reason."</td>
                        <td align=center>
                            <a class='delete_listing btn red btn-outline' data-toggle='modal' href='#confirm_dialog' listing_id='".$listing->id."' onclick='doOnDelete(this)'>Delete
                            </a>
                            <!--<a class='delete_listing btn red btn-outline' data-toggle='modal' href='#confirm_dialog' listing_id='".$listing->id."' onclick='doOnDelete(this)'>Release
                            </a>-->
                        </td>
                    </tr>";
            }
        }   
        else {
            $retHTML .= "<tr><td colspan='8'><div style='font-weight: bolder;font-size: 24px;'>No Data</div></td></tr>";
        }
        $retHTML .= "<tbody>";
        echo $retHTML;
        exit;
    }
    public function deletelisting($listing_id) {

        $model = new Common();
        echo $model->deleteListing($listing_id);
        exit;
    }
}
