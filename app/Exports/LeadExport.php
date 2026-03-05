<?php

namespace App\Exports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class LeadExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $authUser = auth()->user();

        $leads = Lead::select('id', 'lead_number', 'first_name', 'last_name', 'mobile_number', 'email', 'service_id', 'is_allergies', 'allergies', 'price_range', 'additional_details','utm_source','utm_medium','utm_campaign','utm_id','utm_term','utm_content', 'status','created_at')
        ->with([
            'service' => function($query) use($filter){
                $query->select('id', 'name');
            }
        ])
        ->orderBy('id', 'DESC')->get();

        return $leads;
    }                                    

    public function headings(): array
    {
        return [
            'Lead Number',
            'First Name',
            'Last Name',
            'Email',
            'Mobile Number',
            'Service Name',
            'Is Allergies',
            'Allergies',
            'Budget',
            'Additional Details',
            'UTM Source',
            'UTM Medium',
            'UTM Campaign',
            'UTM Id',
            'UTM Term',
            'UTM Content',
            'Status',
            'Date'
        ];
    }

    /**
    * @var leads $leads
    */
    public function map($leads): array
    {
        $lead_number            = 'N/A';
        $first_name             = 'N/A';
        $last_name              = 'N/A';
        $email                  = 'N/A';
        $mobile_number          = 'N/A';
        $service_name           = 'N/A';
        $is_allergies           = 'N/A';
        $allergies              = 'N/A';
        $price_range            = 'N/A';
        $additional_details     = 'N/A';
        $utm_source             = 'N/A';
        $utm_medium             = 'N/A';
        $utm_campaign           = 'N/A';
        $utm_id                 = 'N/A';
        $utm_term               = 'N/A';
        $utm_content            = 'N/A';
        $status                 = 'N/A';
        $date                   = 'N/A';

        // Preparing Data
        if($leads->lead_number != ''){
            $lead_number = $leads->lead_number;
        }

        if($leads->first_name != ''){
            $first_name = $leads->first_name;
        }

        if($leads->last_name != ''){
            $last_name = $leads->last_name;
        }

        if($leads->email != ''){
            $email = $leads->email;
        }

        if($leads->mobile_number != ''){
            $mobile_number = $leads->mobile_number;
        }

        if($leads['service'] != ''){
            $service_name = $leads['service']->name;
        }

        if($leads->is_allergies == 1){
            $is_allergies = 'Yes';
        } else {
            $is_allergies = 'No';
        }

        if($leads->allergies != ''){
            $allergies = $leads->allergies;
        }

        if($leads->price_range != ''){
            $price_range = $leads->price_range;
        }

        if($leads->additional_details != ''){
            $additional_details = $leads->additional_details;
        }

        if($leads->utm_source != ''){
            $utm_source = $leads->utm_source;
        }

        if($leads->utm_medium != ''){
            $utm_medium = $leads->utm_medium;
        }

        if($leads->utm_campaign != ''){
            $utm_campaign = $leads->utm_campaign;
        }

        if($leads->utm_id != ''){
            $utm_id = $leads->utm_id;
        }

        if($leads->utm_term != ''){
            $utm_term = $leads->utm_term;
        }

        if($leads->utm_content != ''){
            $utm_content = $leads->utm_content;
        }

        if($leads->status == 1){
            $status = 'Pending';
        } else {
            $status = 'Completed';
        }

        $date = Carbon::parse($leads->created_at)->format('d M y, h:i A');

        return [
            $lead_number,
            $first_name,
            $last_name,
            $email,
            $mobile_number,
            $service_name,
            $is_allergies,
            $allergies,
            $price_range,
            $additional_details,
            $utm_source,
            $utm_medium,
            $utm_campaign,
            $utm_id,
            $utm_term,
            $utm_content,
            $status,
            $date
        ];
    }
}
