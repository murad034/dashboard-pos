<?php

namespace App\Http\Controllers\Product\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class CSVController extends Controller
{


    function object_to_array($data): array
    {
        if (is_array($data) || is_object($data)) {
            $result = [];
            foreach ($data as $key => $value) {
                $result[$key] = (is_array($data) || is_object($data)) ? $this->object_to_array($value) : $value;
            }
            return $result;
        }
        return $data;
    }

    public function uploadMainCSV(Request $request): JsonResponse
    {
        try {
            $data = Category::all()->toArray();
            if (count($data) > 0) {
                $h_arrays = $data[0];
                $headers = array_keys($h_arrays);
                array_shift($headers);
            } else {
                $headers = ["catagoryname", "catid", "status"];
            }

            session_start();

            $error = '';

            $html = '';

            if ($request->file->getClientOriginalName() != '') {
                $file_array = explode(".", $request->file->getClientOriginalName());

                $extension = end($file_array);

                if ($extension == 'csv') {
                    $file_data = fopen($_FILES['file']['tmp_name'], 'r');

                    $file_header = fgetcsv($file_data);

                    $html .= '<table class="table table-bordered" style="width:1400px;"><tr>';
                    $header_column = '<option value="">Set Column</option>';

                    foreach ($headers as $item => $values) {
                        $header_column .= '<option value="' . $values . '">' . $values . '</option>';
                    }

                    for ($count = 0; $count < count($file_header); $count++) {
                        $html .= '
                                   <th>
                                    <select name="set_column_data" class="form-control set_column_data" data-column_number="' . $count . '">
                                    ' . $header_column . '
                                    </select>
                                   </th>
                                   ';
                    }

                    $html .= '</tr>';

                    $limit = 0;

                    while (($row = fgetcsv($file_data)) !== FALSE) {
                        $limit++;

                        if ($limit < 6) {
                            $html .= '<tr>';

                            for ($count = 0; $count < count($row); $count++) {
                                $html .= '<td>' . $row[$count] . '</td>';
                            }

                            $html .= '</tr>';
                        }

                        $temp_data[] = $row;
                    }

                    $_SESSION['file_data'] = $temp_data;

                    $html .= '
                              </table>

                              ';
                } else {
                    $error = 'Only <b>.csv</b> file allowed';
                }
            } else {
                $error = 'Please Select CSV File';
            }

            $output = array(
                'error' => $error,
                'output' => $html
            );

            return response()->json($output);

        } catch (Exception $e) {
            return response()->json([]);
        }
    }

    public function importMainCSV(Request $request): JsonResponse
    {
        try {
            session_start();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                try {
                    $file_data = $_SESSION['file_data'];

                    $customer_data = $request->post();

                    foreach ($file_data as $row) {
                        $data = array();
                        $cat_id = "";

                        foreach ($customer_data as $index => $val) {
                            $data[$index] = $row[$val];
                            if ($index == "catid") {
                                $cat_id = strval($row[$val]);
                            }
                        }
                        $update_data = array('$set' => $data);
                        $condition = array('catid' => $cat_id);
                        Category::raw()->updateOne($condition, $update_data, array('upsert' => true));
                    }
                    return response()->json([
                        'status' => 'success'
                    ]);
                } catch (Exception $e) {
                    return response()->json([
                        'status' => 'fail'
                    ]);
                }

            }

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function uploadSubCSV(Request $request): JsonResponse
    {
        try {
            $data = SubCategory::all()->toArray();
            if (count($data) > 0) {
                $h_arrays = $data[0];
                $headers = array_keys($h_arrays);
                array_shift($headers);
            } else {
                $headers = ["subcatagoryname", "subcatid", "status"];
            }

            session_start();

            $error = '';

            $html = '';

            if ($request->file->getClientOriginalName() != '') {
                $file_array = explode(".", $request->file->getClientOriginalName());

                $extension = end($file_array);

                if ($extension == 'csv') {
                    $file_data = fopen($_FILES['file']['tmp_name'], 'r');

                    $file_header = fgetcsv($file_data);

                    $html .= '<table class="table table-bordered" style="width:1400px;"><tr>';
                    $header_column = '<option value="">Set Column</option>';

                    foreach ($headers as $item => $values) {
                        $header_column .= '<option value="' . $values . '">' . $values . '</option>';
                    }

                    for ($count = 0; $count < count($file_header); $count++) {
                        $html .= '
                                   <th>
                                    <select name="set_column_data" class="form-control set_column_data" data-column_number="' . $count . '">
                                    ' . $header_column . '
                                    </select>
                                   </th>
                                   ';
                    }

                    $html .= '</tr>';

                    $limit = 0;

                    while (($row = fgetcsv($file_data)) !== FALSE) {
                        $limit++;

                        if ($limit < 6) {
                            $html .= '<tr>';

                            for ($count = 0; $count < count($row); $count++) {
                                $html .= '<td>' . $row[$count] . '</td>';
                            }

                            $html .= '</tr>';
                        }

                        $temp_data[] = $row;
                    }

                    $_SESSION['file_data'] = $temp_data;

                    $html .= '
                              </table>

                              ';
                } else {
                    $error = 'Only <b>.csv</b> file allowed';
                }
            } else {
                $error = 'Please Select CSV File';
            }

            $output = array(
                'error' => $error,
                'output' => $html
            );

            return response()->json($output);

        } catch (Exception $e) {
            return response()->json([]);
        }
    }

    public function importSubCSV(Request $request): JsonResponse
    {
        try {
            session_start();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                try {
                    $file_data = $_SESSION['file_data'];

                    $customer_data = $request->post();

                    foreach ($file_data as $row) {
                        $data = array();
                        $cat_id = "";

                        foreach ($customer_data as $index => $val) {
                            $data[$index] = $row[$val];
                            if ($index == "subcatid") {
                                $cat_id = strval($row[$val]);
                            }
                        }
                        $update_data = array('$set' => $data);
                        $condition = array('subcatid' => $cat_id);
                        SubCategory::raw()->updateOne($condition, $update_data, array('upsert' => true));
                    }
                    return response()->json([
                        'status' => 'success'
                    ]);
                } catch (Exception $e) {
                    return response()->json([
                        'status' => 'fail'
                    ]);
                }

            }

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

}
