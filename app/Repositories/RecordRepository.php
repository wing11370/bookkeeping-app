<?php

namespace App\Repositories;

use App\Models\Record;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class RecordRepository
{
    public function __construct(protected Record $record)
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
        try {
            $this->record->create([
                'user_id' => $userId,
                'item' => $item,
                'datetime' => $datetime,
                'in' => $in,
                'out' => $out,
            ]);
            return $this->record;

        } catch (Exception $e) {
            throw new Exception("新增失敗");
        }
    }

    /**
     * @param int $userId
     * @return Collection
     * @throws Exception
     */
    public function list(int $userId): Collection
    {
        try {
            return $this->record->where('user_id', $userId)->get();
        } catch (Exception $e) {
            throw new Exception("查詢列表失敗");
        }
    }

    /**
     * @param int $userId
     * @param int $id
     * @return Record
     * @throws Exception
     */
    public function find(int $userId, int $id): Record
    {
        try {
            return $this->record->where('user_id', $userId)->findOrFail($id);
        } catch (Exception $e) {
            throw new Exception("沒有這個資料，id:$id");
        }
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
        $record = $this->find($userId, $id);
        try {
            $record->update($data);
            return $record;
        } catch (Exception $e) {
            throw new Exception("修改失敗");
        }

    }

    /**
     * @param int $userId
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function delete(int $userId, int $id): bool
    {
        $record = $this->find($userId, $id);
        try {
            $record->delete();
            return true;
        } catch (Exception $e) {
            throw new Exception("刪除失敗");
        }
    }

}
