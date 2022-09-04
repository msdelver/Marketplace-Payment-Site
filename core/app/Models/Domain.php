<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model {
    use HasFactory;
    protected $guarded = [];

    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bids() {
        return $this->hasMany(DomainBid::class, 'domain_id');
    }

    public function comments() {
        return $this->hasMany(Comment::class, 'domain_id');
    }

    public function contactMessages() {
        return $this->hasMany(ContactMessage::class, 'domain_id');
    }

    public function scopeActive() {
        return $this->where('status', 1)->where('end_time', '>=', now()->toDateTimeString());
    }

    public function scopeFinished() {
        return $this->where('status', 1)->where('end_time', '<', now()->toDateTimeString());
    }

    public function scopeSold() {
        return $this->where('status', 2);
    }

    public function getStatusTextAttribute() {
        $class = "badge badge--";

        if ($this->status == 1 && Carbon::parse($this->end_time) >= now()) {
            $class .= 'success';
            $text = 'Approved';
        } elseif ($this->status == 1 && Carbon::parse($this->end_time) < now()) {
            $class .= 'dark';
            $text = 'Finished';
        } elseif ($this->status == 0) {
            $class .= 'warning';
            $text = 'Pending';
        } elseif ($this->status == 2) {
            $class .= 'primary';
            $text = 'Sold';
        }else {
            $class .= 'danger';
            $text = 'Rejected';
        }

        return "<span class='$class'>" . trans($text) . "</span>";
    }

}
