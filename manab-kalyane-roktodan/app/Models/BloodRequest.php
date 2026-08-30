<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_name',
        'blood_group',
        'units_required',
        'needed_by_date',
        'hospital_name',
        'location',
        'contact_number',
        'status',
        'created_by',
        'notes',
        'fulfillment_notes',
        'fulfilled_by_donor',
    ];

    protected $casts = [
        'needed_by_date' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
