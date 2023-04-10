<?php

declare(strict_types=1);

namespace Mstysk\RedisPhp;

final class Storage
{
    /** @var array<DataSet> */
    private array $dataList;

    public function set(DataSet $data): void
    {
        $this->dataList[] = $data;
    }

    public function get(string $key): ?string
    {
        foreach($this->dataList as $data) {
            if ($data->exists($key)) {
                return $data->get();
            }
        }
        return null;
    }
}
