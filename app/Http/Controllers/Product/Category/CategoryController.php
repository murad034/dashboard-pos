<?php

namespace App\Http\Controllers\Product\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return Factory|Application|View
     */
    public function index()
    {
        $categoryList = Category::all();
        $subCategoryList = SubCategory::all();
        return view('products.category.index', array('categories' => $categoryList, 'subCategories' => $subCategoryList));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function saveMainCategory(Request $request): JsonResponse
    {
        try {
            $cat_data = $request->post('data');
            $ops = array(

                array(
                    '$group' => array('_id' => null, 'maxid' => array('$max' => array(
                        '$toDouble' => '$catid'
                    )))
                )

            );
            $data = Category::raw()->aggregate($ops)->toArray();
            if (count($data) === 0) {
                $cat_data["catid"] = "1";
            } else {
                $max_id = $data[0]["maxid"];
                $cat_data["catid"] = strval(++$max_id);
            }
            $cat_data["status"] = 'active';
            Category::raw()->insertOne($cat_data);
            $this->saveLog("saved data : ".json_encode($cat_data), Category::tableName());
            return response()->json([
                'status' => 'success',
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getMainCategory(Request $request): JsonResponse
    {
        try {
            if ($request->has('id')) {
                $id = $request->get('id');
                $ops = array(

                    array(
                        '$match' => array('catid' => $id)
                    )

                );
                $data = Category::raw()->aggregate($ops)->toArray();
            } else if ($request->has('status')) {
                $show_status = $request->get('status');
                if (strcmp($show_status, "all") == 0) {
                    $ops = array();
                } else {
                    $ops = array(

                        array(
                            '$match' => array('status' => $show_status)
                        )

                    );
                }

                $data = Category::raw()->aggregate($ops)->toArray();
            } else {

                $data = Category::all()->toArray();
            }
            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function editMainCategory(Request $request, $id): JsonResponse
    {
        try {
            $update_data = array('$set' => $request->all());
            $condition = array('catid' => strval($id));
            Category::raw()->updateOne($condition, $update_data, ['upsert' => true]);
            $this->saveLog("reference id: " . $id . " updated data : " . json_encode($update_data), Category::tableName());
            return response()->json([
                'status' => 'success'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function deleteMainCategory($id): JsonResponse
    {
        try {
            $update_data = array('$set' => array('status' => 'inactive'));
            $condition = array('catid' => strval($id));
            Category::raw()->updateOne($condition, $update_data, ['upsert' => true]);
            $this->saveLog("deleted data (reference id): " . $id, Category::tableName());
            return response()->json([
                'status' => 'success'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
     */
    public function saveSubCategory(Request $request): JsonResponse
    {
        try {
            $cat_data = $request->post('data');
            $ops = array(

                array(
                    '$group' => array('_id' => null, 'maxid' => array('$max' => array(
                        '$toDouble' => '$subcatid'
                    )))
                )

            );
            $data = SubCategory::raw()->aggregate($ops)->toArray();
            if (count($data) === 0) {
                $cat_data["subcatid"] = "1";
            } else {
                $max_id = $data[0]["maxid"];
                $cat_data["subcatid"] = strval(++$max_id);
            }
            $cat_data["status"] = 'active';

            SubCategory::raw()->insertOne($cat_data);

            $this->saveLog("saved data : ".json_encode($cat_data), SubCategory::tableName());

            return response()->json([
                'status' => 'success',
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getSubCategory(Request $request): JsonResponse
    {
        try {
            if ($request->has('id')) {
                $id = $request->get('id');
                $ops = array(

                    array(
                        '$match' => array('subcatid' => $id)
                    )

                );
                $data = SubCategory::raw()->aggregate($ops)->toArray();
            } else if ($request->has('status')) {
                $show_status = $request->get('status');
                if (strcmp($show_status, "all") == 0) {
                    $ops = array();
                } else {
                    $ops = array(

                        array(
                            '$match' => array('status' => $show_status)
                        )

                    );
                }

                $data = SubCategory::raw()->aggregate($ops)->toArray();
            } else {

                $data = SubCategory::all()->toArray();
            }
            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function editSubCategory(Request $request, $id): JsonResponse
    {
        try {
            $update_data = array('$set' => $request->all());
            $condition = array('subcatid' => strval($id));
            SubCategory::raw()->updateOne($condition, $update_data, ['upsert' => true]);
            $this->saveLog("reference id: " . $id . " updated data : " . json_encode($update_data), SubCategory::tableName());
            return response()->json([
                'status' => 'success'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function deleteSubCategory($id): JsonResponse
    {
        try {
            $update_data = array('$set' => array('status' => 'inactive'));
            $condition = array('subcatid' => strval($id));
            SubCategory::raw()->updateOne($condition, $update_data, ['upsert' => true]);
            $this->saveLog("deleted data (reference id): " . $id, SubCategory::tableName());
            return response()->json([
                'status' => 'success'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }


}
