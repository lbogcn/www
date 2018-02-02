<?php

namespace App\Http\Controllers\Admin;

use App\Components\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use Illuminate\Http\Request;

class QuestionnairesController extends Controller
{

    const PERMISSION = array(
        'title' => '问卷管理',
        'nodes' => [
            ['action' => 'index', 'name' => '列表'],
            ['action' => 'store', 'name' => '保存'],
            ['action' => 'update', 'name' => '更新'],
            ['action' => 'destroy', 'name' => '删除'],
        ],
    );

    /**
     * 列表
     * @param Request $request
     * @param Questionnaire $questionnaire
     * @return ApiResponse
     */
    public function index(Request $request, Questionnaire $questionnaire)
    {
        $keys = ['title'];
        /** @var \Illuminate\Pagination\LengthAwarePaginator $paginate */
        $paginate = $this->buildSearch($questionnaire, $request, $keys)
            ->orderBy('id', 'desc')
            ->paginate();

        $data = array(
            'paginate' => $paginate->toArray()
        );

        return ApiResponse::success($data);
    }

    /**
     * 保存
     * @param Request $request
     * @return ApiResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, array(
            'title' => ['required', 'min:1', 'max:20'],
            'questions' => ['required', 'array', 'min:1'],
        ));

        $questions = $request->input('questions');
        $choosesTypes = [Questionnaire::QUESTION_TYPE_RADIO, Questionnaire::QUESTION_TYPE_CHECKBOX];
        foreach ($questions as &$question) {
            if (!in_array($question['type'], $choosesTypes) && isset($question['options'])) {
                unset($question['options']);
            }
        }

        $data = $request->only(['title']);
        $data['config'] = json_encode(array('questions' => $questions), JSON_UNESCAPED_UNICODE);
        $data['stat'] = '';
        Questionnaire::create($data);

        return ApiResponse::success(null);
    }

    /**
     * 更新
     * @param Request $request
     * @param Questionnaire $questionnaire
     * @return ApiResponse
     */
    public function update(Request $request, Questionnaire $questionnaire)
    {
        $this->validate($request, array(
            'title' => ['required', 'min:1', 'max:20'],
            'questions' => ['required', 'array', 'min:1'],
        ));

        $questions = $request->input('questions');
        $choosesTypes = [Questionnaire::QUESTION_TYPE_RADIO, Questionnaire::QUESTION_TYPE_CHECKBOX];
        foreach ($questions as &$question) {
            if (!in_array($question['type'], $choosesTypes) && isset($question['options'])) {
                unset($question['options']);
            }
        }

        $data = $request->only(['title']);
        $data['config'] = json_encode(array('questions' => $questions), JSON_UNESCAPED_UNICODE);
        $questionnaire->update($data);

        return ApiResponse::success(null);
    }

    /**
     * 删除
     * @param Questionnaire $questionnaire
     * @return ApiResponse
     * @throws \Exception
     */
    public function destroy(Questionnaire $questionnaire)
    {
        $questionnaire->delete();

        return ApiResponse::success(null);
    }

}