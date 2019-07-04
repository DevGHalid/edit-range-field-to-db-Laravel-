use DB;

trait DBEnum
{
    //4774 - 5273
    public function fieldEnum($begin = 1, $finish, $name = "id")
    {
        // first argument or second arguments, may be only type int
        if (!is_int($begin) || !is_int($finish)) {
            return null;
        }

        $list_num = range($begin, $finish);
        $data = [];
        foreach ($list_num as $item) {
            $data[][$name] = $item;
        }
        return $data;
    }

    public function _deleteFields($begin, $finish, $table_name = null)
    {
        // first argument or second arguments, may be only type int
        if (!is_int($begin) || !is_int($finish)) {
            return null;
        }

        if (is_string($table_name)) {
            DB::table($table_name)->where([
                ['id', '>=', $begin],
                ['id', '<=', $finish]
            ])->delete();
        }
    }

    public function _insertFields($int, $table_name = null)
    {
        // first argument, may be only type int
        if (!is_int($int)) {
            return null;
        }
        if (is_string($table_name)) {
            $data = $this->fieldEnum(1, $int);
            DB::table($table_name)->insert($data);
            return true;
        }
    }

    public function updateId($to, $from, $table_name = null)
    {
        // only for type array
        if (!is_array($to) || !is_array($from)) {
            return null;
        }

        // not equal arrays
        if (count($to) != count($from)) {
            return null;
        }

        if (is_string($table_name)) {
            $toBegin = $to[0];
            $toFinish = $to[1];
            if ($toBegin > $toFinish) return null;
            $dataTo = $this->fieldEnum($toBegin, $toFinish);

            $fromBegin = $from[0];
            $fromFinish = $from[1];
            if ($fromBegin > $fromFinish) return null;
            $dataFrom = $this->fieldEnum($fromBegin, $fromFinish);

            foreach ($dataTo as $key => $itemTo) {
                $itemFrom = $dataFrom[$key];
                if ($itemFrom != $itemTo) {
                    DB::table($table_name)->where($itemTo)->update($itemFrom);
                };
            }
            return true;
        }
    }
}
