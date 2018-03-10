<?php

namespace App\ModelExtensions\HasForeignIds;

use App\Models\ForeignIds;

trait HasForeignIds
{
    public function foreignIds() {
        return $this->hasMany(ForeignIds::class, 'model_id')->where('model_type', self::class);
    }

    public function attachExId($service, $id) {
        return $this->foreignIds()->create([
            'model_type' => self::class,
            'service' => $service,
            'foreign_id' => $id,
        ]);
    }

    public function exIds() {
        return $this->foreignIds()->select('service', 'foreign_id')->get();
    }

    public function hasExId($service, $id) {
        return $this->foreignIds()->where([
            'service' => $service,
            'foreign_id' => $id,
        ])->exists();
    }

    public function removeExId($service) {
        return $this->foreignIds()->where('service', $service)->delete();
    }
}