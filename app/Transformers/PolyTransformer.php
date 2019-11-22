<?php

namespace App\Transformers;


use App\Models\Poly;
use League\Fractal\TransformerAbstract;

class PolyTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Poly $poly
     * @return array
     */
    public function transform(Poly $poly): array
    {
        return [
            'id' => (int) $poly->id,
            'name' => (string) $poly->name,
            'level' => (integer) $poly->level,
            'aoguid' => (string) $poly->aoguid
        ];
    }
}
