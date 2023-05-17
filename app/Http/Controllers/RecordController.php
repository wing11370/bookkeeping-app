<?php

namespace App\Http\Controllers;

use App\Services\RecordService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    public function __construct(protected RecordService $recordService)
    {
    }

    /**
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
