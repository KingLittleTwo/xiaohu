<?php

namespace App\Http\Controllers\Web;

use App\Model\Answer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $answers = new Answer();
        if (empty($request->input('question_id')))
            return ['status' => 0, 'msg' => '缺少问题ID'];

        $page      = !empty($request->input('page')) ? $request->input('page') : 1;
        $page_size = !empty($request->input('page_size')) ? $request->input('page_size') : 10;

        $data = $answers
            ->where('question_id', $request->input('question_id'))
            ->limit($page_size)
            ->offset($page)
            ->get(['id', 'user_id', 'question_id', 'content', 'created_at'])
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
        $answers = new Answer();
        $answers->user_id = $request->session()->get('userinfo.user_id');

        if (empty($request->input('question_id')))
            return ['status' => 0, 'msg' => '缺少问题ID'];
        if (empty($request->input('content')))
            return ['status' => 0, 'msg' => '缺少回答内容'];

        $answers->question_id = $request->input('question_id');
        $answers->content = $request->input('content');

        return $answers->save() ?
            ['status' => 1, 'msg' => '回答成功!'] :
            ['status' => 0, 'msg' => '回答失败!'];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $answers = new Answer();
        $answers->user_id = $request->session()->get('userinfo.user_id');

//        if (empty($request->input('id')))
        if (empty($id))
            return ['status' => 0, 'msg' => '缺少参数ID'];

        return [
            'status' => 1,
            'data'   => $answers
//            ->where('id', $request->input('id'))
            ->where('id', $id)
            ->first()
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
        $answers = new Answer();
        $answers->user_id = $request->session()->get('userinfo.user_id');

        if (empty($request->input('id')))
            return ['status' => 0, 'msg' => '缺少ID'];
        $user_id = $answers
            ->where('id', $request->input('id'))
            ->select
            ->first();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
