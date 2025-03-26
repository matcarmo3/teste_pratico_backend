<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gateway extends Model
{
    protected $table = 'gateways';

    protected $fillable = ['name', 'class_name', 'active', 'priority'];

    public function setPriority($newPriority)
    {
        $this->update(['priority' => -1]);
        self::where('priority', '>=', $newPriority)
            ->increment('priority');
        $this->update(['priority' => $newPriority]);
    }
}
