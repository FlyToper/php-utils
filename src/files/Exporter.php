<?php
/**
 * Created by PhpStorm.
 * @purpose 文件导出
 * @Author: cbf
 * @Time: 2022/9/23 23:53
 */

namespace flytoper\phputils\files;

class Exporter
{
    /**
     *  导出 CSV文件
     * @param array $data 二维数组数据
     * @param array $headers 表头,格式： [key => name]
     * @param $filename string 导出的文件名
     * @param $rm_cols array 排查不导出的列
     * @return void
     */
    public static function exportCsv(array $data, array $headers, $filename, $rm_cols = [])
    {
        // 设置 http header
        header("Content-Disposition:attachment;filename={$filename}.csv");
        header('Content-Encoding: UTF-8');
        header("Content-type:application/vnd.ms-excel;charset=UTF-8");

        // 打开php标准输出流
        $fp = fopen('php://output', 'a');

        // 添加BOM头，以UTF8编码导出CSV文件，如果文件头未添加BOM头，打开会出现乱码。
        fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // 查询需要输出的表头，以及对应列的key
        $output_cols = [];
        $output_headers = [];
        foreach ($headers as $k => $v) {
            if ($rm_cols && in_array($k, $rm_cols)) {
                continue;
            }
            $output_cols[] = $k;
            $output_headers = $v;
        }

        // 输出表头
        $output_headers && fputcsv($fp, $output_headers);

        // 遍历输出每行数据
        foreach ($data as $row) {
            $tmp = [];
            foreach ($output_cols as $k) {
                if (!isset($row[$k])) {
                    $tmp[] = '';
                    continue;
                }

                // 格式化部分值
                $v = $row[$k];
                if (is_array($v)) {
                    $v = implode('|', $v);
                } else {
                    $v = str_replace(',', '|', $v);
                    $v = str_replace(PHP_EOL, '', $v);
                    $v = str_replace('<br>', '', $v);
                }

                if (is_numeric($v) && strlen($v) >= 10) {
                    $v = "\t" . $v;
                }

                $tmp[] = $v;
            }
            fputcsv($fp, $tmp);
        }

        // 关闭输出流
        fclose($fp);
        ob_flush();
        flush();
        exit;
    }


}
