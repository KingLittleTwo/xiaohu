<?php

namespace App\Http\Controllers\Web;

use App\Model\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $question = new Question();

        $page      = !empty($request->input('page')) ? $request->input('page') : 1;
        $page_size = !empty($request->input('page_size')) ? $request->input('page_size') : 10;

        $data = $question
            ->where('status', '<>', 0)
            ->limit($page_size)
            ->offset($page)
            ->get(['id', 'title', 'desc', 'user_id', 'created_at'])
            ->keyBy('id');

        return [
            'status' => 1,
            'data'   => $data
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $question = new Question();

        if (empty($request->input('title')))
            return ['status' => 0, 'msg' => '请输入标题!'];
        $question->title = $request->input('title');

        if (!empty($request->input('desc')))
            $question->desc = $request->input('desc');

        $question->user_id = $request->session()->get('userinfo.user_id');

        return $question->save() ?
            ['status' => 1, 'msg' => '添加成功!'] :
            ['status' => 0, 'msg' => '添加失败!'];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $question = new Question();
        if (empty($request->input('id')))
            return ['status' => 0, 'msg' => '缺少参数 id'];

        $data = $question->find($request->input('id'));
        if (empty($data))
            return ['status' => 0, 'msg' => '没有找到此问题'];

        return [
            'status' => 1,
            'data'   => $data
            ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (empty($request->input('id')))
            return ['status' => 0, 'msg' => '缺少参数 id '];

        $question = new Question();
        $question = $question->where('id', $request->input('id'))->first();

        if (empty($question))
            return ['status' => 0, 'msg' => '此问题不存在'];

        if(session('userinfo.user_id') != $question->user_id)
            return ['status' => 0, 'msg' => '只有作者可以修改本问题'];

        if (!empty($request->input('title')))
            $question->title = $request->input('title');

        if (!empty($request->input('desc')))
            $question->desc = $request->input('desc');

        return $question->save() ?
            ['status' => 1, 'msg' => '修改成功!'] :
            ['status' => 0, 'msg' => '修改失败!'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (empty($request->input('id')))
            return ['status' => 0, 'msg' => '缺少参数 id '];

        $question = new Question();

        return $question
            ->where('id', $request->input('id'))
            ->delete() ?
            ['status' => 1, 'msg' => '删除成功!'] :
            ['status' => 0, 'msg' => '删除失败!'];

    }
}
