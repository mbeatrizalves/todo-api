<?php

namespace App\Models;

use Database\Factories\TarefaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tarefa extends Model
{
    /** @use HasFactory<TarefaFactory> */
    use HasFactory;

    protected $fillable = ['titulo', 'descricao', 'status', 'prazo', 'categoria_id', 'user_id'];

    protected function casts(): array
    {
        return [
            'prazo' => 'date',
        ];
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
