<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeadRequest;
use App\Http\Resources\LeadCollectionResource;
use App\Http\Resources\LeadCreatedResource;
use App\Repositories\Lead\LeadRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class LeadController extends Controller
{

    /**
     * @var LeadRepository
     */
    private LeadRepository $repository;


    /**
     * @param LeadRepository $repository
     */
    public function __construct(LeadRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $user = auth()->user();

            $cacheKey = $user->isManager() ? 'all_leads' : 'user_leads_' . $user->id;

            $leads = Cache::remember($cacheKey, 3600, function () use ($user) {
                if ($user->isManager()) {
                    return $this->repository->search([])->get();
                } else {
                    return $this->repository->search(['owner' => $user->id])->get();
                }
            });

            return response()->json(new LeadCollectionResource(collect($leads)), 200);

        } catch (Exception $e) {
            return response()->json([
                'meta' => [
                    'success' => false,
                    'errors' => [$e->getMessage()],
                ],
                'data' => [],
            ], 500);
        }
    }

    /**
     * @param StoreLeadRequest $request
     * @return JsonResponse
     */
    public function store(StoreLeadRequest $request): JsonResponse
    {
        try {
            $user = auth()->user();

            $lead = $this->repository->create([
                'name' => $request->name,
                'source' => $request->source,
                'owner' => $request->owner,
                'created_by' =>  $user->id,
            ]);

            return response()->json(new LeadCreatedResource(['lead' => $lead]), 201);
        } catch (Exception $e) {
            return response()->json([
                'meta' => [
                    'success' => false,
                    'errors' => [$e->getMessage()],
                ],
                'data' => [],
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $lead = $this->repository->getById($id);

            return response()->json(new LeadCreatedResource(['lead' => $lead]), 201);

        } catch (Exception $e) {
            return response()->json([
                'meta' => [
                    'success' => false,
                    'errors' => [$e->getMessage()],
                ],
                'data' => [],
            ], 500);
        }
    }
}
