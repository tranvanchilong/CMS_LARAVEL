<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReferralUsersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $referrals;
    protected $currency;

    public function __construct($referrals)
    {
        $this->referrals = $referrals;
        $this->currency = currencySign();
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->referrals;
    }

    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return [
            trans('lms/admin/main.user'),
            trans('lms/admin/main.role'),
            trans('lms/admin/main.user_group'),
            trans('lms/admin/main.referral_code'),
            trans('lms/admin/main.amount'),
            trans('lms/admin/main.commission'),
            trans('lms/admin/main.status'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function map($referral): array
    {
        $currency = $this->currency;

        $userType = '';
        if ($referral->affiliateUser->isUser()) {
            $userType = 'Student';
        } elseif ($referral->affiliateUser->isTeacher()) {
            $userType = 'Teacher';
        } elseif ($referral->affiliateUser->isOrganization()) {
            $userType = 'Organization';
        }


        return [
            $referral->affiliateUser->full_name,
            $userType,
            !empty($referral->affiliateUser->getUserGroup()) ? $referral->affiliateUser->getUserGroup()->name : '-',
            !empty($referral->affiliateUser->affiliateCode) ? $referral->affiliateUser->affiliateCode->code : '',
            $currency . $referral->getTotalAffiliateRegistrationAmounts(),
            $currency . $referral->getTotalAffiliateCommissions(),
            $referral->affiliateUser->affiliate ? trans('lms/admin/main.yes') : trans('lms/admin/main.no'),
        ];
    }
}
