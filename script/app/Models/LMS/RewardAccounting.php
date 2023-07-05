<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class RewardAccounting extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        RewardAccounting::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_rewards_accounting';
    public $timestamps = false;
    protected $guarded = ['id'];

    const ADDICTION = 'addiction';
    const DEDUCTION = 'deduction';

    public function user()
    {
        return $this->belongsTo('App\Models\LMS\User', 'user_id', 'id');
    }

    public static function makeRewardAccounting($userId, $score, $type, $itemId = null, $checkDuplicate = false, $status = self::ADDICTION)
    {
        if ($score and $score > 0) {
            $create = true;

            if ($checkDuplicate) {
                $check = self::where('user_id', $userId)
                    ->where('item_id', $itemId)
                    ->where('type', $type)
                    ->where('status', $status)
                    ->first();

                $create = empty($check);
            }

            if ($create) {
                self::createAccounting($userId, $itemId, $type, $score, $status);
            }
        }
    }

    private static function createAccounting($userId, $itemId, $type, $score, $status): bool
    {
        self::create([
            'user_id' => $userId,
            'item_id' => $itemId ?? null,
            'type' => $type,
            'score' => $score,
            'status' => $status,
            'created_at' => time()
        ]);

        $notifyOptions = [
            '[points]' => $score,
            '[item_title]' => trans('update.reward_type_' . $type),
            '[time.date]' => dateTimeFormat(time(), 'j M Y H:i')
        ];
        sendNotification("user_get_new_point", $notifyOptions, $userId);

        return true;
    }

    public static function calculateScore($type, $extra = null)
    {
        $score = 0;
        $reward = Reward::where('type', $type)->first();

        if (!empty($reward)) {

            switch ($reward->type) {
                case Reward::BUY:
                case Reward::ACCOUNT_CHARGE:
                case Reward::BUY_STORE_PRODUCT:
                    if (!empty($extra)) { // for this type $extra is amount
                        $score = $reward->score * ($extra / $reward->condition);
                    }
                    break;

                case Reward::CHARGE_WALLET:
                    if (!empty($extra) and $extra > $reward->condition) { // for this type $extra is total_amount
                        $score = $reward->score;
                    }
                    break;

                case Reward::BADGE:
                    if (!empty($extra)) {
                        $score = $extra; // for this type $extra is $badge->score
                    }
                    break;

                default:
                    $score = $reward->score;
            }
        }

        return $score;
    }
}
