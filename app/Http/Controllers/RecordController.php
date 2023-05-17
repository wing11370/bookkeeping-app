<?php

namespace App\Http\Controllers;

use App\Services\RecordService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class RecordController extends BaseController
{
    public function __construct(protected RecordService $recordService)
    {
    }

    /**
     * @OA\Post(
     *     path="/api/records",
     *     operationId="create",
     *     tags={"交易紀錄"},
     *     summary="新增紀錄",
     *     description="新增紀錄",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *          description="新增紀錄",
     *          required=true,
     *          @OA\JsonContent(
     *              required={"item","datetime","in","out"},
     *              @OA\Property(property="item", type="string", example="test"),
     *              @OA\Property(property="datetime", type="string", example="2021-01-01 00:00:00"),
     *              @OA\Property(property="in", type="integer", example=100),
     *              @OA\Property(property="out", type="integer", example=0),
     *              ),
     *     ),
     *     @OA\Response(
     *     response=200,
     *     description="新增成功",
     *     @OA\JsonContent(
     *          @OA\Property(property="status", type="string", example="success"),
     *          @OA\Property(property="message", type="string", example="新增成功"),
     *
     *     )
     *     ),
     *     @OA\Response(
     *     response=400,
     *     description="新增失敗",
     *     @OA\JsonContent(
     *          @OA\Property(property="status", type="string", example="error"),
     *          @OA\Property(property="message", type="string", example="新增失敗"),
     *     )
     *     ),
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'item' => 'required|string',
                'datetime' => 'required|date|date_format:Y-m-d H:i:s',
                'in' => 'required|integer|min:0',
                'out' => 'required|integer|min:0',
            ]);

            $userid = $request->user()->id;
            $item = $request->input('item');
            $datetimeString = $request->input('datetime');
            $datetime = Carbon::createFromFormat('Y-m-d H:i:s', $datetimeString);
            $in = $request->input('in');
            $out = $request->input('out');

            $this->recordService->create($userid, $item, $datetime, $in, $out);

            return response()->json(
                [
                    'status' => 'success',
                    'message' => '新增成功'
                ]
            );

        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ],
                400);
        }

    }

    /**
     * @OA\Get(
     *     path="/api/records",
     *     operationId="list",
     *     tags={"交易紀錄"},
     *     summary="取得紀錄列表",
     *     description="取得紀錄列表",
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *     response=200,
     *     description="取得成功",
     *     @OA\JsonContent(
     *          @OA\Property(property="status", type="string", example="success"),
     *          @OA\Property(
     *              property="data",
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="user_id", type="integer", example=1),
     *                  @OA\Property(property="item", type="string", example="test"),
     *                  @OA\Property(property="datetime", type="string", example="2021-01-01 00:00:00"),
     *                  @OA\Property(property="in", type="integer", example=100),
     *                  @OA\Property(property="out", type="integer", example=100),
     *              )
     *          ),
     *     )
     *     ),
     *     @OA\Response(
     *     response=400,
     *     description="取得失敗",
     *     @OA\JsonContent(
     *          @OA\Property(property="status", type="string", example="error"),
     *          @OA\Property(property="message", type="string", example="取得失敗"),
     *     )
     *     ),
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        try {
            $userid = $request->user()->id;
            return response()->json(
                [
                    'status' => 'success',
                    'data' => $this->recordService->list($userid)
                ]
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ],
                400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/records/{id}",
     *     operationId="find",
     *     tags={"交易紀錄"},
     *     summary="取得紀錄",
     *     description="取得紀錄",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="紀錄ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *              example=1
     *          )
     *     ),
     *     @OA\Response(
     *     response=200,
     *     description="取得成功",
     *     @OA\JsonContent(
     *          @OA\Property(property="status", type="string", example="success"),
     *          @OA\Property(
     *              property="data",
     *              type="object",
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="user_id", type="integer", example=1),
     *              @OA\Property(property="item", type="string", example="test"),
     *              @OA\Property(property="datetime", type="string", example="2021-01-01 00:00:00"),
     *              @OA\Property(property="in", type="integer", example=100),
     *              @OA\Property(property="out", type="integer", example=100),
     *          ),
     *     )
     *     ),
     *     @OA\Response(
     *     response=400,
     *     description="取得失敗",
     *     @OA\JsonContent(
     *          @OA\Property(property="status", type="string", example="error"),
     *          @OA\Property(property="message", type="string", example="取得失敗"),
     *     )
     *     ),
     * )
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function find(Request $request, int $id): JsonResponse
    {
        try {
            $userid = $request->user()->id;
            return response()->json(
                [
                    'status' => 'success',
                    'data' => $this->recordService->find($userid, $id)
                ]
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ],
                400);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/records/{id}",
     *     operationId="update",
     *     tags={"交易紀錄"},
     *     summary="更新紀錄",
     *     description="更新紀錄",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="紀錄ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *              example=1
     *          )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *              required={"item", "datetime", "in", "out"},
     *              @OA\Property(property="item", type="string", example="test"),
     *              @OA\Property(property="datetime", type="string", example="2021-01-01 00:00:00"),
     *              @OA\Property(property="in", type="integer", example=100),
     *              @OA\Property(property="out", type="integer", example=100),
     *         )
     *     ),
     *     @OA\Response(
     *     response=200,
     *     description="更新成功",
     *     @OA\JsonContent(
     *          @OA\Property(property="status", type="string", example="success"),
     *          @OA\Property(
     *              property="data",
     *              type="object",
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="user_id", type="integer", example=1),
     *              @OA\Property(property="item", type="string", example="test"),
     *              @OA\Property(property="datetime", type="string", example="2021-01-01 00:00:00"),
     *              @OA\Property(property="in", type="integer", example=100),
     *              @OA\Property(property="out", type="integer", example=0),
     *          ),
     *     )
     *     ),
     *     @OA\Response(
     *     response=400,
     *     description="更新失敗",
     *     @OA\JsonContent(
     *          @OA\Property(property="status", type="string", example="error"),
     *     @OA\Property(property="message", type="string", example="更新失敗"),
     *     )
     *     ),
     * )
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {

        try {
            $request->validate([
                'item' => 'string',
                'datetime' => 'date|date_format:Y-m-d H:i:s',
                'in' => 'integer|min:0',
                'out' => 'integer|min:0',
            ]);

            $userid = $request->user()->id;

            $data = [];
            if ($request->has('item')) {
                $data['item'] = $request->input('item');
            }
            if ($request->has('datetime')) {
                $data['datetime'] = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('datetime'));
            }
            if ($request->has('in')) {
                $data['in'] = $request->input('in');
            }
            if ($request->has('out')) {
                $data['out'] = $request->input('out');
            }
            return response()->json(
                [
                    'status' => 'success',
                    'data' => $this->recordService->edit($userid, $id, $data)
                ]
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ],
                400);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/records/{id}",
     *     operationId="delete",
     *     tags={"交易紀錄"},
     *     summary="刪除紀錄",
     *     description="刪除紀錄",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="紀錄ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *              example=1
     *          )
     *     ),
     *     @OA\Response(
     *     response=200,
     *     description="刪除成功",
     *     @OA\JsonContent(
     *          @OA\Property(property="status", type="string", example="success"),
     *          @OA\Property(property="data", type="bool", example="true"),
     *     )
     *     ),
     *     @OA\Response(
     *     response=400,
     *     description="刪除失敗",
     *     @OA\JsonContent(
     *          @OA\Property(property="status", type="string", example="error"),
     *     @OA\Property(property="message", type="string", example="刪除失敗"),
     *     )
     *     ),
     * )
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function delete(Request $request, int $id): JsonResponse
    {
        try {
            $userid = $request->user()->id;

            return response()->json(
                [
                    'status' => 'success',
                    'data' => $this->recordService->delete($userid, $id)
                ]
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ],
                400);
        }

    }
}
