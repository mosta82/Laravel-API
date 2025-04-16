<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    /**
     * Get the appropriate icon for the service
     *
     * @return string
     */
    public function getIconAttribute()
    {
        $icons = [
            'Bill Payment' => '💰',
            'Mobile Recharge' => '📱',
            'Merchant Payment' => '💳',
            'bKash to Bank' => '🏦',
            'bKash to Card' => '💳',
            'Send Money' => '↗️',
            'Agent Cashout' => '🏧',
            'Savings Registration' => '📈',
            'Cancel Transaction' => '❌',
            'Counter' => '🏷️',
            'Comment' => '💬',
            'STR/SAR Form' => '📝'
        ];
        
        return $icons[$this->name] ?? '🛠️'; // Default icon if not found
    }

    /**
     * Append the icon attribute when serializing
     *
     * @var array
     */
    protected $appends = ['icon'];
}