<?php

namespace App\Services;

use App\Models\Record;
use App\Repositories\RecordRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class RecordService
{
    public function __construct(protected RecordRepository $recordRepository)
    {
    }

    /**
     * @param int $userId
     * @param string $item
     * @param Carbon $datetime
     * @param int $in
     * @param int $out
     * @return Record
     * @throws Exception
     */
    public function create(int $userId, string $item, Carbon $datetime, int $in, int $out): Record
    {
        if ($in < 0 || $out < 0) {
            throw new Exception("收入或支出不得為負數");
        }
        if ($in > 0 && $out > 0) {
            throw new Exception("收入與支出不得同時有值");
        }
        if ($in === 0 && $out === 0) {
            throw new Exception("收入與支出不得同時為0");
        }
        return $this->recordRepository->create($userId, $item, $datetime, $in, $out);
    }

    /**
     * @param int $userId
     * @return Collection
     * @throws Exception
     */
    public function list(int $userId): Collection
    {
        return $this->recordRepository->list($userId);
    }

    /**
     * @param int $userId
     * @param int $id
     * @return Record
     * @throws Exception
     */
    public function find(int $userId, int $id): Record
    {
        return $this->recordRepository->find($userId, $id);
    }

    /**
     * @param int $userId
     * @param int $id
     * @param array $data
     * @return Record
     * @throws Exception
     */
    public function edit(int $userId, int $id, array $data): Record
    {
        if (array_key_exists('in', $data) && array_key_exists('out', $data)) {
            $in = $data['in'];
            $out = $data['out'];
            if ($in < 0 || $out < 0) {
                throw new Exception("收入或支出不得為負數");
            }
            if ($in > 0 && $out > 0) {
                throw new Exception("收入與支出不得同時有值");
            }
            if ($in === 0 && $out === 0) {
                throw new Exception("收入與支出不得同時為0");
            }
        }
        return $this->recordRepository->edit($userId, $id, $data);
    }

    /**
     * @param int $userId
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public
    function delete(int $userId, int $id): bool
    {
        return $this->recordRepository->delete($userId, $id);

    }
}
