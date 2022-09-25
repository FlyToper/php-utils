<?php
/**
 * Created by PhpStorm.
 * @purpose Export file
 * @Author: cbf
 * @Time: 2022/9/23 23:53
 */

namespace flytoper\phputils\files;

class Exporter
{
    /**
     *  Export csv file
     * @param array $data data to export
     * @param array $headers file header： [key => name]
     * @param $filename string filename
     * @param $rm_cols array excluded columns
     * @return void
     */
    public static function exportCsv(array $data, array $headers, $filename, $rm_cols = [])
    {
        // set http header
        header("Content-Disposition:attachment;filename={$filename}.csv");
        header('Content-Encoding: UTF-8');
        header("Content-type:application/vnd.ms-excel;charset=UTF-8");

        // open php standard stream
        $fp = fopen('php://output', 'a');

        // add Bom
        fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // check and split headers
        $output_cols = [];
        $output_headers = [];
        foreach ($headers as $k => $v) {
            if ($rm_cols && in_array($k, $rm_cols)) {
                continue;
            }
            $output_cols[] = $k;
            $output_headers = $v;
        }

        // output headers
        $output_headers && fputcsv($fp, $output_headers);

        // foreach and output rows
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

        // close resource
        fclose($fp);
        ob_flush();
        flush();
        exit;
    }


}
