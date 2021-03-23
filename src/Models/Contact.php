<?php

declare(strict_types=1);

namespace Tipoff\Forms\Models;

use Assert\Assert;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Tipoff\Forms\Enums\ContactStatus;
use Tipoff\Statuses\Traits\HasStatuses;
use Tipoff\Support\Models\BaseModel;
use Tipoff\Support\Traits\HasPackageFactory;

class Contact extends BaseModel
{
    use HasPackageFactory;
    use SoftDeletes;
    use HasStatuses;

    protected $casts = [
        'emailed_at' => 'datetime',
        'requested_date' => 'date',
        'fields' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($contact) {
            Assert::lazy()
                ->that($contact->location_id)->notEmpty('A contact must be made to a location.')
                ->verifyNow();
            $contact->generateReferenceNumber();
        });
    }

    public function getRouteKeyName()
    {
        return 'reference_number';
    }

    public function generateReferenceNumber()
    {
        do {
            $token = Str::of(Carbon::now('America/New_York')->format('ymdB'))->substr(1, 7) . Str::upper(Str::random(3));
        } while (self::where('reference_number', $token)->first()); //check if the token already exists and if it does, try again

        $this->reference_number = $token;
    }

    public function scopeByContactStatus(Builder $query, ContactStatus $contactStatus): Builder
    {
        return $this->scopeByStatus($query, $contactStatus->toStatus());
    }

    public function setContactStatus(ContactStatus $contactStatus): self
    {
        $this->setStatus((string) $contactStatus->getValue(), ContactStatus::statusType());

        return $this;
    }

    public function getContactStatus(): ? ContactStatus
    {
        $status = $this->getStatus(ContactStatus::statusType());

        return $status ? ContactStatus::byValue((string) $status) : null;
    }

    public function getContactStatusHistory(): Collection
    {
        return $this->getStatusHistory(ContactStatus::statusType());
    }

    public function response()
    {
        return $this->hasOne(ContactResponse::class);
    }

    public function user()
    {
        return $this->belongsTo(app('user'));
    }

    public function location()
    {
        return $this->belongsTo(app('location'));
    }

    public function notes()
    {
        return $this->morphMany(app('note'), 'noteable');
    }
}
