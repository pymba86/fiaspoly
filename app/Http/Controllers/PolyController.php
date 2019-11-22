<?php

namespace App\Http\Controllers;

use App\Models\Poly;
use App\Transformers\PolyTransformer;
use Exception;
use Grimzy\LaravelMysqlSpatial\Eloquent\Builder;
use Grimzy\LaravelMysqlSpatial\Types\MultiPolygon;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Throwable;

class PolyController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function index(Request $request): JsonResponse
    {
        $attributes = $this->validate($request, [
            'lat' => 'required:regex:/^-?\d{1,2}\.\d{6,}$/',
            'long' => 'required:regex:/^-?\d{1,2}\.\d{6,}$/',
            'level' => 'integer'
        ]);

        $point = new Point($attributes['lat'], $attributes['long']);

        $poly = Poly::query()
            ->when(array_key_exists('level', $attributes),
                function (Builder $query) use ($attributes) {
                    $query->where('level', $attributes['level']);
                })
            ->contains('polygon', $point)
            ->orderBy('level')
            ->get();

        $transform = fractal($poly, new PolyTransformer())->toArray();
        return response()->json($transform, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     * @throws Throwable
     */
    public function store(Request $request): JsonResponse
    {
        $attributes = $this->validate($request, [
            'name' => 'required:string',
            'level' => 'required:integer',
            'polygon' => 'required:string',
            'aoguid' => 'required:uuid'
        ]);

        $polygon = MultiPolygon::fromString($attributes['polygon']);

        $poly = new Poly($attributes);
        $poly->name = $attributes['name'];
        $poly->level = $attributes['level'];
        $poly->aoguid = $attributes['aoguid'];
        $poly->polygon = $polygon;

        $poly->saveOrFail();

        $transform = fractal($poly, new PolyTransformer())->toArray();
        return response()->json($transform, Response::HTTP_CREATED);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $poly = Poly::query()->findOrFail($id);
        return response()->json($poly, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $attributes = $this->validate($request, [
            'name' => 'required:string',
            'level' => 'required:integer',
            'polygon' => 'required:string',
            'aoguid' => 'required:uuid'
        ]);

        $poly = Poly::query()->findOrFail($id);
        $poly->update($attributes);
        return response()->json($poly, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(int $id): JsonResponse
    {
        $poly = Poly::query()->findOrFail($id);
        $poly->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

}
