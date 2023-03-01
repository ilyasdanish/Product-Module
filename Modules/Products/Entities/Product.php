<?php

namespace Modules\Products\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'status',
        'type',
        'added_by'
    ];

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }


    public function logs()
    {
        return $this->hasMany(ProductLog::class);
    }

    public function logChanges(array $changes, User $user)
    {
        foreach ($changes as $field => $change) {
            $oldValue = $change['old'];
            $newValue = $change['new'];

            if ($oldValue !== $newValue) {
                $this->logs()->create([
                    'field_name' => $field,
                    'old_value' => $oldValue,
                    'new_value' => $newValue,
                    'updated_by' => $user->id,
                ]);
            }
        }
    }
}
