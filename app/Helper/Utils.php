<?php

declare(strict_types=1);
namespace App\Helper;

/**
 * 辅助工具
 * Class Tools
 * @package App\Help
 */
class Utils
{
    /**
     * 判断是否在某种环境下
     * @param $currEnv
     * @return bool
     */
    public static function envIs($currEnv)
    {
        return config('app.env') == $currEnv;
    }

    /**
     * 获取对应的对象并执行回调
     * @param array $rows
     * @param string $id
     * @param string $pid
     * @param string $sonKey
     * @return array|mixed
     */
    public static function toTree(array $rows, string $id='id', string $pid='pid', string $sonKey='children')
    {
        $tree = [];
        foreach ($rows as $row) {
            $tree[$row[$id]] = $row;
        };
        foreach ($tree as $item) {
            $tree[$item[$pid]][$sonKey][$item[$id]] = &$tree[$item[$id]];
        };

        return isset($tree[0][$sonKey]) ? $tree[0][$sonKey] : [];
    }

    /**
     * 格式化树形结构，从对象格式化成列表
     * @param array $tree
     * @param string $sonKey
     * @return array
     */
    public static function formatTree(array $tree, string $sonKey='children')
    {
        $data = array_values($tree);
        foreach ($data as &$sTree) {
            if (isset($sTree[$sonKey])) {
                $sTree[$sonKey] = self::formatTree($sTree[$sonKey]);
            }
        }
        return $data;
    }
}
