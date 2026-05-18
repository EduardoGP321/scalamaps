<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParteObra extends Model
{
    protected $table = 'partes_obra';
    protected $fillable = ['nombre'];
    public function catalogoConceptos()
    {
        return $this->hasMany(CatalogoConcepto::class);
    }
}
